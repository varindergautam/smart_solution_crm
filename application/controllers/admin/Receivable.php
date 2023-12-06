<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Receivable extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('receivable_model');
        $this->load->model('clients_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('receivable');
        }

        $data['title']          = _l('Receivables');
        $this->load->view('admin/receivable/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->receivable_model->add($data);
                set_alert('success', _l('added_successfully', _l('Receivable')));
                redirect(admin_url('receivable/'));
            } else {
                $this->receivable_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('Receivable')));
                redirect(admin_url('receivable/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('Receivable'));
        } else {
            $member = $this->receivable_model->get($id);

            if (!$member) {
                blank_page('Receivable Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }
        $data['customers'] = $this->clients_model->get();

        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/receivable/create', $data);
    }

    public function delete($id)
    {
        $this->receivable_model->delete($id);
        set_alert('success', _l('deleted', _l('Receivable')));
        redirect(admin_url('supplier_item/'));
    }

    public function report()
    {
        $data['month'] = isset($_GET['month']) ? $_GET['month'] : NULL;
        if ($this->input->is_ajax_request()) {
            if (isset($_GET['month'])) {
                $this->app->get_table_data('receivable_report', ['month' => $_GET['month']]);
            } else {
                $this->app->get_table_data('receivable_report');
            }
        }
        $data['title']         = 'Receivable Report';

        $this->load->view('admin/receivable/report', $data);
    }

    public function change_paid_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->receivable_model->change_paid_status($id, $status);
        }
    }
}
