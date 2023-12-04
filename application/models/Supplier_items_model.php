<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_items_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('supplier_items.*, suppliers.vat_number, suppliers.company');
        $this->db->join('suppliers', 'suppliers.supplierid = supplier_items.supplier_id');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('supplier_items.id', $id);
            $supplier_items = $this->db->get(db_prefix() . 'supplier_items')->row();


            return $supplier_items;
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'supplier_items')->result_array();
    }

    public function add($data, $id = NULL)
    {
        if (isset($id) && !empty($id)) {
            $this->db->where("id", $id);
            return $this->db->update(db_prefix() . "supplier_items", $data);
        }

        $this->db->where('supplier_id', $data['supplier_id']);
        $this->db->where('item_id', $data['item_id']);
        $supplier_item = $this->db->get(db_prefix() . "supplier_items")->row();

        if (isset($supplier_item) && !empty($supplier_item)) {
            $this->db->where("id", $supplier_item->id);
            return $this->db->update(db_prefix() . "supplier_items", $data);
        } else {
            $this->db->insert(db_prefix() . 'supplier_items', $data);
            return $this->db->insert_id();
        }
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->delete(db_prefix() . 'supplier_items');
    }

    public function byItemID($id = '')
    {
        $this->db->select('supplier_items.*, suppliers.vat_number, suppliers.company, items.description');
        $this->db->join('suppliers', 'suppliers.supplierid = supplier_items.supplier_id');
        $this->db->join('items', 'items.id = supplier_items.item_id');
        $this->db->where('item_id', $id);

        return $this->db->get(db_prefix() . 'supplier_items')->result();
    }
}
