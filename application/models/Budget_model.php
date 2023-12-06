<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Budget_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $budget = $this->db->get(db_prefix() . 'budget')->row();
            return $budget;
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'budget')->result_array();
    }

    public function add($data)
    {
        $data = hooks()->apply_filters('before_create_budget', $data);

        $total = $data['amount'] / $data['into_month'];

        $monthNames = array(
            1 => "january",
            2 => "february",
            3 => "march",
            4 => "april",
            5 => "may",
            6 => "june",
            7 => "july",
            8 => "august",
            9 => "september",
            10 => "october",
            11 => "november",
            12 => "december"
        );

        $months = array();
        for ($i = 1; $i <= $data['into_month']; $i++) {
            $months[$monthNames[$i]] = $total;
        }

        for ($i = $data['into_month'] + 1; $i <= 12; $i++) {
            $months[$monthNames[$i]] = null;
        }

        $data = array_merge($data, $months);

        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'budget', $data);
        $id = $this->db->insert_id();
        if ($id) {
            $this->db->where('id', $id);

            log_activity('New budget Added [ID: ' . $id . ', ' . $data['name'] . ']');

            hooks()->do_action('budget_created', $id);

            return $id;
        }

        return false;
    }


    public function update($data, $id)
    {
        $data = hooks()->apply_filters('before_budget', $data, $id);

        $affectedRows = 0;

        $total = $data['amount'] / $data['into_month'];

        $monthNames = array(
            1 => "january",
            2 => "february",
            3 => "march",
            4 => "april",
            5 => "may",
            6 => "june",
            7 => "july",
            8 => "august",
            9 => "september",
            10 => "october",
            11 => "november",
            12 => "december"
        );

        $months = array();
        for ($i = 1; $i <= $data['into_month']; $i++) {
            $months[$monthNames[$i]] = $total;
        }

        for ($i = $data['into_month'] + 1; $i <= 12; $i++) {
            $months[$monthNames[$i]] = null;
        }

        $data = array_merge($data, $months);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'budget', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('budget_updated', $id);
            log_activity('budget Member Updated [ID: ' . $id . ', ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    public function change_status($id, $status)
    {
        $status = hooks()->apply_filters('before_budget_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'budget', [
            'active' => $status,
        ]);

        log_activity('budget Status Changed [id: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }

    public function incomeHead($year)
    {
        $this->db->select('budget.*, financial_year.year_name');
        $this->db->join('financial_year', 'financial_year.id = budget.financial_year');
        $this->db->where('financial_year', $year);
        $this->db->where('head_type', 'income');
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'budget')->result();
    }


    public function expenseHead($year)
    {
        $this->db->select('budget.*, financial_year.year_name');
        $this->db->join('financial_year', 'financial_year.id = budget.financial_year');
        $this->db->where('financial_year', $year);
        $this->db->where('head_type', 'expense');
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'budget')->result();
    }
}
