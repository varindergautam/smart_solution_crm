<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Financial_year extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('financial_year_model');
    }

    public function index()
    {
        $this->financial_year_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('financial_year');
        }

        $data['title']          = _l('Financial Years');
        $this->load->view('admin/financial_year/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->financial_year_model->add($data);
                set_alert('success', _l('added_successfully', _l('Financial Year')));
                redirect(admin_url('financial_year/'));
            } else {
                $this->financial_year_model->update($data, $id);
                set_alert('success', _l('updated_successfully', _l('Financial Year')));
                redirect(admin_url('financial_year/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('financial_year'));
        } else {
            $member = $this->financial_year_model->get($id);

            if (!$member) {
                blank_page('financial_year Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }

        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/financial_year/create', $data);
    }

    public function delete($id)
    {
        $this->financial_year_model->delete($id);
        set_alert('success', _l('deleted', _l('Financial Year')));
        redirect(admin_url('financial_year/'));
    }

    public function change_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->financial_year_model->change_status($id, $status);
        }
    }
}
