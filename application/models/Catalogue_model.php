<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Catalogue_model extends App_Model
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
            $this->db->where('supplier_id', $id);
            $suppliers = $this->db->get(db_prefix() . 'catalog')->row();


            return $suppliers;
        }
        $this->db->order_by('supplier_id', 'desc');

        return $this->db->get(db_prefix() . 'catalog')->result_array();
    }

    public function insertFile($data)
    {
        $this->db->insert(db_prefix() . 'catalog', $data);
        return $this->db->insert_id();
    }

    public function delete($id){
		return $this->db->where('id', $id)
        ->delete(db_prefix() . 'catalog');
	}
}
