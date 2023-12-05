<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pdc extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pdc_model');
        $this->load->model('clients_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('pdc');
        }

        $data['title']          = _l('PDC');
        $this->load->view('admin/pdc/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->pdc_model->add($data);
                set_alert('success', _l('added_successfully', _l('PDC')));
                redirect(admin_url('pdc/'));
            } else {
                $this->pdc_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('PDC')));
                redirect(admin_url('pdc/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('PDC'));
        } else {
            $member = $this->pdc_model->get($id);

            if (!$member) {
                blank_page('pdc Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }

        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/pdc/create', $data);
    }

    public function delete($id)
    {
        $this->pdc_model->delete($id);
        set_alert('success', _l('deleted', _l('PDC')));
        redirect(admin_url('supplier_item/'));
    }

    public function report()
    {
        $data['month'] = isset($_GET['month']) ? $_GET['month'] : NULL;
        $data['type'] = isset($_GET['type']) ? $_GET['type'] : NULL;
        if ($this->input->is_ajax_request()) {
            if (isset($_GET['month']) || isset($_GET['type'])) {
                $this->app->get_table_data('pdc_report', ['month' => $_GET['month'], 'type' => $_GET['type']]);
            } else {
                $this->app->get_table_data('pdc_report');
            }
        }
        $data['title']         = 'PDC Report';

        $this->load->view('admin/pdc/report', $data);
    }

    public function change_paid_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->pdc_model->change_paid_status($id, $status);
        }
    }
}
