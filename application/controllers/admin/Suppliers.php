<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('suppliers_model');
        $this->load->model('currencies_model');
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
                handle_supplier_profile_image_upload($id);
                set_alert('success', _l('added_successfully', _l('Supplier')));
                // redirect(admin_url('staff/member/' . $id));
                redirect(admin_url('suppliers/'));
            } else {
                handle_supplier_profile_image_upload($id);
                $this->suppliers_model->update($data, $id);
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
            $title                     = 'Update';
        }
        $data['title']         = $title;
        $data['currencies'] = $this->currencies_model->get();
        $data['countries'] = get_all_countries();
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
}
