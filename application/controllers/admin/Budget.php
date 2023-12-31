<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Budget extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('budget_model');
        $this->load->model('financial_year_model');
        $this->load->model('head_model');
    }

    public function index()
    {
        $this->budget_model->get();
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('budget');
        }

        $data['title']          = _l('Budgets');
        $this->load->view('admin/budget/manage', $data);
    }

    public function create($id = '')
    {
        if ($this->input->post()) {
            $data = $this->input->post();

            if ($id == '') {
                $id = $this->budget_model->add($data);
                set_alert('success', _l('added_successfully', _l('Budget')));
                redirect(admin_url('budget/'));
            } else {
                $this->budget_model->update($data, $id);
                set_alert('success', _l('updated_successfully', _l('Budget')));
                redirect(admin_url('budget/'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('budget'));
        } else {
            $member = $this->budget_model->get($id);

            if (!$member) {
                blank_page('budget Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = 'Update';
        }

        $data['financial_years'] = $this->financial_year_model->get();
        $data['heads'] = $this->head_model->get();
        $data['title']         = $title;
        $data['id']  = $id;
        $this->load->view('admin/budget/create', $data);
    }

    public function delete($id)
    {
        $this->budget_model->delete($id);
        set_alert('success', _l('deleted', _l('Budget')));
        redirect(admin_url('budget/'));
    }

    public function change_status($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->budget_model->change_status($id, $status);
        }
    }

    public function report()
    {
        $data['year'] = isset($_GET['year']) ? $_GET['year'] : NULL;
        if ($this->input->is_ajax_request()) {
            if (isset($_GET['year'])) {
                $this->app->get_table_data('budget_report', ['year' => $_GET['year']]);
            } else {
                $this->app->get_table_data('budget_report');
            }
        }
        $data['title']         = 'Budget Report';
        $data['financial_years'] = $this->financial_year_model->get();
        $data['income_head'] = $this->budget_model->incomeHead($data['year']);
        $data['expense_head'] = $this->budget_model->expenseHead($data['year']);

        $this->load->view('admin/budget/report', $data);
    }
}
