<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_model extends App_Model
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
            $this->db->where('supplierid', $id);
            $suppliers = $this->db->get(db_prefix() . 'suppliers')->row();


            return $suppliers;
        }
        $this->db->order_by('supplierid', 'desc');

        return $this->db->get(db_prefix() . 'suppliers')->result_array();
    }

    public function add($data)
    {
        $data['brand_id'] = implode(',',$data['brand_id']);
        $data['group_id'] = implode(',',$data['group_id']);
        // First check for all cases if the email exists.
        $data = hooks()->apply_filters('before_create_supplier_member', $data);

        $this->db->where('phone_number', $data['phone_number']);
        $phone_number = $this->db->get(db_prefix() . 'suppliers')->row();

        if ($phone_number) {
            die('Phone already exists');
        }

        $data['datecreated'] = date('Y-m-d H:i:s');
       
        $this->db->insert(db_prefix() . 'suppliers', $data);
        $supplierid = $this->db->insert_id();
        if ($supplierid) {

            $this->db->where('supplierid', $supplierid);

            log_activity('New supplier Member Added [ID: ' . $supplierid . ', ' . $data['company'] . ' ' . $data['phone_number'] . ']');

            hooks()->do_action('supplier_member_created', $supplierid);

            return $supplierid;
        }

        return false;
    }

    public function update($data, $id)
    {
        $data['brand_id'] = implode(',',$data['brand_id']);
        $data['group_id'] = implode(',',$data['group_id']);
        $data = hooks()->apply_filters('before_update_suppliers_member', $data, $id);

        $affectedRows = 0;

        $this->db->where('supplierid', $id);
        $this->db->update(db_prefix() . 'suppliers', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('suppliers_member_updated', $id);
            log_activity('suppliers Member Updated [ID: ' . $id . ', ' . $data['company'] . ' ' . $data['phone_number'] . ']');

            return true;
        }

        return false;
    }

    public function change_supplier_status($id, $status)
    {
        $status = hooks()->apply_filters('before_staff_status_change', $status, $id);

        $this->db->where('supplierid', $id);
        $this->db->update(db_prefix() . 'suppliers', [
            'active' => $status,
        ]);

        log_activity('Supplier Status Changed [SupplierID: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }
}
