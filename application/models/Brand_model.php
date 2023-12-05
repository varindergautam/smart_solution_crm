<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Brand_model extends App_Model
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
            $brand = $this->db->get(db_prefix() . 'brand')->row();
            return $brand;
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'brand')->result_array();
    }

    public function add($data)
    {
        $data = hooks()->apply_filters('before_create_brand', $data);

        $this->db->where('name', $data['name']);
        $name = $this->db->get(db_prefix() . 'brand')->row();

        // if ($name) {
        //     die('Brand already exists');
        // }

        $data['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'brand', $data);
        $id = $this->db->insert_id();
        if ($id) {

            $this->db->where('id', $id);

            log_activity('New Brand Added [ID: ' . $id . ', ' . $data['name'] . ']');

            hooks()->do_action('brand_created', $id);

            return $id;
        }

        return false;
    }

    public function update($data, $id)
    {
        $data = hooks()->apply_filters('before_brand', $data, $id);

        $affectedRows = 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'brand', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('brand_updated', $id);
            log_activity('brand Member Updated [ID: ' . $id . ', ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    public function change_status($id, $status)
    {
        $status = hooks()->apply_filters('before_brand_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'brand', [
            'active' => $status,
        ]);

        log_activity('Brand Status Changed [id: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }
}
