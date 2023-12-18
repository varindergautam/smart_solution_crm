<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('suppliers_model');
        $this->load->model('currencies_model');
        $this->load->model('brand_model');
        $this->load->model('invoice_items_model');
        $this->load->model('supplier_brand_model');
        $this->load->model('supplier_group_model');
        $this->load->model('catalogue_model');
    }

    public function index()
    {
        $this->suppliers_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('suppliers');
        }
        $data['title']          = _l('Suppliers');

        $this->load->view('admin/suppliers/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                $id = $this->suppliers_model->add($data);
                $this->supplier_brand_model->add($data, $id);
                $this->supplier_group_model->add($data, $id);

                handle_supplier_profile_image_upload($id);
                set_alert('success', _l('added_successfully', _l('Supplier')));
                // redirect(admin_url('staff/member/' . $id));
                redirect(admin_url('suppliers/'));
            } else {

                handle_supplier_profile_image_upload($id);
                $this->suppliers_model->update($data, $id);
                $this->supplier_brand_model->add($data, $id);
                $this->supplier_group_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('Supplier')));
                redirect(admin_url('suppliers/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('supplier'));
        } else {
            $member = $this->suppliers_model->get($id);
            if (!$member) {
                blank_page('Supplier Not Found', 'danger');
            }
            $data['member']            = $member;
            $data['already_brands'] = $this->db->get_where(db_prefix() . 'supplier_brand', ['supplier_id' => $id])->result_array();
            $data['already_groups'] = $this->db->get_where(db_prefix() . 'supplier_group', ['supplier_id' => $id])->result_array();
            $title                     = 'Update';
        }
        $data['title']         = $title;
        $data['currencies'] = $this->currencies_model->get();
        $data['brands'] = $this->brand_model->get();
        $data['countries'] = get_all_countries();

        $data['groups'] = $this->invoice_items_model->get_groups();
        $this->load->view('admin/suppliers/create', $data);
    }

    public function change_suppliers_status($id, $status)
    {
        if (has_permission('staff', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->suppliers_model->change_supplier_status($id, $status);
            }
        }
    }

    public function remove_supplier_profile_image($id = '')
    {
        if (is_numeric($id) && (has_permission('supplier', '', 'create') || has_permission('supplier', '', 'edit'))) {
            $supplier_id = $id;
        }
        hooks()->do_action('before_remove_supplier_profile_image');
        $member = $this->suppliers_model->get($supplier_id);
        if (file_exists(get_upload_path_by_type('supplier') . $supplier_id)) {
            delete_dir(get_upload_path_by_type('supplier') . $supplier_id);
        }
        $this->db->where('supplierid', $supplier_id);
        $this->db->update(db_prefix() . 'suppliers', [
            'profile_image' => null,
        ]);

        if (!is_numeric($id)) {
            redirect(admin_url('suppliers/create/' . $supplier_id));
        } else {
            redirect(admin_url('suppliers/create/' . $supplier_id));
        }
    }

    public function supplierJson($id)
    {
        $supplier = $this->suppliers_model->get($id);
        echo json_encode($supplier);
    }

    public function group_report()
    {
        $data['group'] = isset($_GET['group']) ? $_GET['group'] : NULL;
        if ($this->input->is_ajax_request()) {
            if (isset($_GET['group'])) {
                $this->app->get_table_data('group_report_table', ['group' => $_GET['group']]);
            } else {
                $this->app->get_table_data('group_report_table');
            }
        }
        $data['title']         = 'Group Report';
        $data['groups'] = $this->invoice_items_model->get_groups();
        $this->load->view('admin/suppliers/group_report', $data);
    }

    public function delete($id)
    {
        $delete = $this->suppliers_model->delete($id);
        if ($delete) {
            set_alert('success', _l('Deleted successfully', _l('Supplier')));
            redirect(admin_url('suppliers/'));
        }
    }

    public function upload_catalouge($supplierId)
    {
        $data['supplier_id'] = $supplierId;
        $this->load->view('admin/suppliers/catalogue', $data);
    }

    public function upload_files()
    {
        // Check if the form is submitted
        // Configuring upload library
        $config['upload_path']   = './uploads/catalogue/'; // Set your upload path
        $config['allowed_types'] = 'pdf'; // Add allowed file types
        // $config['max_size']      = 2048; // Set max file size in kilobytes

        $this->load->library('upload', $config);
        // Process each uploaded file
        $uploaded_files = [];
        $files = $_FILES['catalogue'];
        $count = count($files['name']);


        for ($i = 0; $i < $count; $i++) {
            $_FILES['catalogue'] = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            // Upload each file
            if ($this->upload->do_upload('catalogue')) {
                $uniqueIdentifier = uniqid();
                // Get the original file name
                $originalFileName = $this->upload->data('file_name');
                // Append the unique identifier to the original file name
                $newFileName = $uniqueIdentifier . '_' . $originalFileName;

                $uploadPath = './uploads/catalogue/';
                $newFilePath = $uploadPath . $newFileName;
                $oldFilePath = $this->upload->data('full_path');
                rename($oldFilePath, $newFilePath);

                // Add the new file name to the uploaded files array
                $uploaded_files[] = $newFileName;
            } else {
                // Handle upload errors if needed
                $upload_error = $this->upload->display_errors();
                set_alert('danger', _l($upload_error));
                redirect(admin_url('suppliers/'));
                // echo $upload_error;
            }
        }

        $supplier_id = $this->input->post('supplier_id');

        if (!empty($uploaded_files)) {
            $this->saveFileNamesToDatabase($uploaded_files, $supplier_id);

            set_alert('success', _l('Uploaded successfully', _l('Catalogue')));
            redirect(admin_url('suppliers/'));
        }
    }

    private function saveFileNamesToDatabase($fileNames, $supplier_id)
    {
        foreach ($fileNames as $fileName) {
            $data = [
                'catalogue' => $fileName,
                'supplier_id' => $supplier_id,
            ];

            $this->catalogue_model->insertFile($data);
        }
    }

    public function catalogues()
    {
        $data['suppliers'] = $this->suppliers_model->get();
        $data['supplier'] = isset($_GET['supplier']) ? $_GET['supplier'] : NULL;
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('catalogues', ['supplier' => $_GET['supplier']]);
        }
        $data['title']          = _l('Catalogue');
        $this->load->view('admin/suppliers/view_catalogue', $data);
    }

    public function view_catalogue($filename)
    {
        $pdfFilePath = base_url('uploads/catalogue/' . $filename);
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        readfile($pdfFilePath);
    }
}
