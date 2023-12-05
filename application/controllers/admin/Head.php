<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Head extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('head_model');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('head');
        }

        $data['title']          = _l('Heads');
        $this->load->view('admin/head/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->head_model->add($data);
                set_alert('success', _l('added_successfully', _l('Head')));
                redirect(admin_url('head/'));
            } else {
                $this->head_model->update($data, $id);
                set_alert('success', _l('updated_successfully', _l('Head')));
                redirect(admin_url('head/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('head'));
        } else {
            $member = $this->head_model->get($id);

            if (!$member) {
                blank_page('head Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }

        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/head/create', $data);
    }

    public function delete($id)
    {
        $this->head_model->delete($id);
        set_alert('success', _l('deleted', _l('Head')));
        redirect(admin_url('head/'));
    }

    public function change_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->head_model->change_status($id, $status);
        }
    }

    public function headJson($id) {
        $clients = $this->head_model->get($id);
        echo json_encode($clients);
    }
}
