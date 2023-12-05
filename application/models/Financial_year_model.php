<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Financial_year_model extends App_Model
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
            $financial_year = $this->db->get(db_prefix() . 'financial_year')->row();
            return $financial_year;
        }

        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'financial_year')->result_array();
    }

    public function add($data)
    {
        $data = hooks()->apply_filters('before_create_financial_year', $data);

        $this->db->where('name', $data['name']);
        $name = $this->db->get(db_prefix() . 'financial_year')->row();

        // if ($name) {
        //     die('financial_year already exists');
        // }

        $data['datecreated'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'financial_year', $data);
        $id = $this->db->insert_id();
        if ($id) {

            $this->db->where('id', $id);

            log_activity('New financial year Added [ID: ' . $id . ', ' . $data['name'] . ']');

            hooks()->do_action('financial_year_created', $id);

            return $id;
        }

        return false;
    }

    public function update($data, $id)
    {
        $data = hooks()->apply_filters('before_financial_year', $data, $id);

        $affectedRows = 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'financial_year', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('financial_year_updated', $id);
            log_activity('financial year Member Updated [ID: ' . $id . ', ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    public function change_status($id, $status)
    {
        $status = hooks()->apply_filters('before_financial_year_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'financial_year', [
            'active' => $status,
        ]);

        log_activity('financial year Status Changed [id: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }
}
