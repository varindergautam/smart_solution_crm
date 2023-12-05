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

        $data['title']          = _l('pdc');
        $this->load->view('admin/pdc/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->pdc_model->add($data);
                set_alert('success', _l('added_successfully', _l('pdc')));
                redirect(admin_url('pdc/'));
            } else {
                $this->pdc_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('pdc')));
                redirect(admin_url('pdc/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('pdc'));
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
        set_alert('success', _l('deleted', _l('pdc')));
        redirect(admin_url('supplier_item/'));
    }

    public function report()
    {
        if ($this->input->post()) {
            $post = $this->input->post();
            if ($this->input->is_ajax_request()) {
                if(isset($_GET['month'])){
                    $this->app->get_table_data('pdc_report', ['month' => $_GET['month']]);
                } else {
                    $this->app->get_table_data('pdc_report');
                }
            }
        }
        $data['title']         = 'pdc Report';
        
        $this->load->view('admin/pdc/report', $data);
    }
}
