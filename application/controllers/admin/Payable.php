<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payable extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payable_model');
        $this->load->model('suppliers_model');
    }

    public function index()
    {
        $this->payable_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('payable');
        }

        $data['title']          = _l('Payables');
        $this->load->view('admin/payable/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->payable_model->add($data);
                set_alert('success', _l('added_successfully', _l('Payable')));
                redirect(admin_url('payable/'));
            } else {
                $this->payable_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('Payable')));
                redirect(admin_url('payable/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('payable'));
        } else {
            $member = $this->payable_model->get($id);

            if (!$member) {
                blank_page('payable Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }
        $data['suppliers'] = $this->suppliers_model->get('', ['active' => 1]);
        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/payable/create', $data);
    }

    public function delete($id)
    {
        $this->payable_model->delete($id);
        set_alert('success', _l('deleted', _l('Payable')));
        redirect(admin_url('supplier_item/'));
    }

    public function report()
    {
        $data['month'] = isset($_GET['month']) ? $_GET['month'] : NULL;
        if ($this->input->is_ajax_request()) {
            if (isset($_GET['month'])) {
                $this->app->get_table_data('payable_report', ['month' => $_GET['month']]);
            } else {
                $this->app->get_table_data('payable_report');
            }
        }
        $data['title']         = 'Payable Report';

        $this->load->view('admin/payable/report', $data);
    }

    public function change_paid_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->payable_model->change_paid_status($id, $status);
        }
    }
}
