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

    public function supplierJson($id) {
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
}
