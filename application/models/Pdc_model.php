<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Pdc_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('pdc.*');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('pdc.id', $id);
            $pdc = $this->db->get(db_prefix() . 'pdc')->row();


            return $pdc;
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'pdc')->result_array();
    }

    public function add($data, $id = NULL)
    {
        if (isset($id) && !empty($id)) {
            $this->db->where("id", $id);
            return $this->db->update(db_prefix() . "pdc", $data);
        }

        $this->db->insert(db_prefix() . 'pdc', $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->delete(db_prefix() . 'pdc');
    }

    public function change_paid_status($id, $status)
    {
        $status = hooks()->apply_filters('before_staff_status_change', $status, $id);

        $this->db->where('receivable_id', $id);
        $this->db->update(db_prefix() . 'pdc', [
            'paid_status' => $status,
        ]);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'receivable', [
            'pdc_id' => $status,
        ]);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'payable', [
            'pdc_id' => $status,
        ]);

        log_activity('Pdc Paid Status Changed [SupplierID: ' . $id . ' - Paid Status(Active/Inactive): ' . $status . ']');
    }
}
