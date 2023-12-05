<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Head_model extends App_Model
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
            $head = $this->db->get(db_prefix() . 'head')->row();
            return $head;
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'head')->result_array();
    }

    public function add($data)
    {
        $data = hooks()->apply_filters('before_create_head', $data);

        $this->db->where('name', $data['name']);
        $name = $this->db->get(db_prefix() . 'head')->row();

        // if ($name) {
        //     die('head already exists');
        // }

        $data['datecreated'] = date('Y-m-d H:i:s');

        $this->db->insert(db_prefix() . 'head', $data);
        $id = $this->db->insert_id();
        if ($id) {

            $this->db->where('id', $id);

            log_activity('New head Added [ID: ' . $id . ', ' . $data['name'] . ']');

            hooks()->do_action('head_created', $id);

            return $id;
        }

        return false;
    }

    public function update($data, $id)
    {
        $data = hooks()->apply_filters('before_head', $data, $id);

        $affectedRows = 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'head', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('head_updated', $id);
            log_activity('Head Updated [ID: ' . $id . ', ' . $data['name'] . ']');
            return true;
        }

        return false;
    }

    public function change_status($id, $status)
    {
        $status = hooks()->apply_filters('before_head_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'head', [
            'active' => $status,
        ]);

        log_activity('head Status Changed [id: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }
}
