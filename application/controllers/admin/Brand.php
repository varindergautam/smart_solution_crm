<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Brand extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('brand_model');
    }

    public function index()
    {
        $this->brand_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('brand');
        }

        $data['title']          = _l('brands');
        $this->load->view('admin/brand/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->brand_model->add($data);
                set_alert('success', _l('added_successfully', _l('brand')));
                redirect(admin_url('brand/'));
            } else {
                $this->brand_model->add($data, $id);
                set_alert('success', _l('updated_successfully', _l('brand')));
                redirect(admin_url('brand/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('brand'));
        } else {
            $member = $this->brand_model->get($id);

            if (!$member) {
                blank_page('brand Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }

        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/brand/create', $data);
    }

    public function delete($id)
    {
        $this->brand_model->delete($id);
        set_alert('success', _l('deleted', _l('brand')));
        redirect(admin_url('brand/'));
    }

    public function change_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->brand_model->change_status($id, $status);
        }
    }
}
