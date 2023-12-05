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
}
