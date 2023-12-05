<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Payable_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pdc_model');
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('payable.*, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number ');
        $this->db->join('pdc', 'pdc.payable_id = payable.id');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('payable.id', $id);
            $payable = $this->db->get(db_prefix() . 'payable')->row();
            return $payable;
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'payable')->result_array();
    }

    public function add($data, $id = NULL)
    {
        $payable['supplier_id'] = $data['supplier_id'];
        $payable['supplier_name'] = $data['supplier_name'];
        $payable['company_name'] = $data['company_name'];
        $payable['supplier_mobile'] = $data['supplier_mobile'];
        $payable['supplier_email'] = $data['supplier_email'];
        $payable['supplier_city'] = $data['supplier_city'];
        $payable['entry_date'] = $data['entry_date'];
        $payable['invoice_date'] = $data['invoice_date'];
        $payable['invoice_number'] = $data['invoice_number'];
        $payable['invoice_amount'] = $data['invoice_amount'];
        $payable['invoice_due_date'] = $data['invoice_due_date'];
        $payable['remarks'] = $data['remarks'];
        $payable['pdc'] = $data['pdc'];

        if($data['pdc']) {
            $pdc['cheque_number'] = $data['cheque_number'];
            $pdc['cheque_date'] = $data['cheque_date'];
            $pdc['amount'] = $data['amount'];
            $pdc['bank_number'] = $data['bank_number'];
        }

        if (isset($id) && !empty($id)) {
            if($data['pdc']) {
                $pdc['payable_id'] = $id;
            }
            $this->pdc_model->add($pdc, $data['pdcID']);
            $this->db->where("id", $id);
            return $this->db->update(db_prefix() . "payable", $payable);
        }
       
        $this->db->insert(db_prefix() . 'payable', $payable);
        $lastID = $this->db->insert_id();

        if($data['pdc']) {
            $pdc['payable_id'] = $lastID;
            $pdcId = $this->pdc_model->add($pdc);

            $arr = array("pdc_id" => $pdcId);
            $this->db->where("id", $lastID);
            $this->db->update("payable", $arr);
        }
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->delete(db_prefix() . 'payable');
    }
}
