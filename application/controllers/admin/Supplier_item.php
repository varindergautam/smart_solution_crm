<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_item extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_items_model');
        $this->load->model('suppliers_model');
        $this->load->model('invoice_items_model');
    }

    public function index()
    {
        $this->supplier_items_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('supplier_items');
        }

        $data['title']          = _l('Supplier Items');
        $this->load->view('admin/supplier_items/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if ($id == '') {
                $id = $this->supplier_items_model->add($data);
                set_alert('success', _l('added_successfully', _l('Supplier Item')));
                redirect(admin_url('supplier_item/'));
            } else {
                $this->supplier_items_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('Supplier Item')));
                redirect(admin_url('supplier_item/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('supplier item'));
        } else {
            $member = $this->supplier_items_model->get($id);
            if (!$member) {
                blank_page('Supplier Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }
        $data['suppliers_members'] = $this->suppliers_model->get('', ['active' => 1]);
        $data['item_members'] = $this->invoice_items_model->get('', ['active' => 1]);
        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/supplier_items/create', $data);
    }

    public function delete($id)
    {
        $this->supplier_items_model->delete($id);
        set_alert('success', _l('deleted', _l('Supplier Item')));
        redirect(admin_url('supplier_item/'));
    }
}
