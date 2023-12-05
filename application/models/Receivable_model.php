<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Receivable_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pdc_model');
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('receivable.*, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number ');
        $this->db->join('pdc', 'pdc.receivable_id = receivable.id');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('receivable.id', $id);
            $receivable = $this->db->get(db_prefix() . 'receivable')->row();
            return $receivable;
        }

        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'receivable')->result_array();
    }

    public function add($data, $id = NULL)
    {
        $receivable['customer_id'] = $data['customer_id'];
        $receivable['customer_name'] = $data['customer_name'];
        $receivable['company_name'] = $data['company_name'];
        $receivable['customer_mobile'] = $data['customer_mobile'];
        $receivable['customer_email'] = $data['customer_email'];
        $receivable['customer_city'] = $data['customer_city'];
        $receivable['entry_date'] = $data['entry_date'];
        $receivable['invoice_date'] = $data['invoice_date'];
        $receivable['invoice_number'] = $data['invoice_number'];
        $receivable['invoice_amount'] = $data['invoice_amount'];
        $receivable['invoice_due_date'] = $data['invoice_due_date'];
        $receivable['remarks'] = $data['remarks'];
        $receivable['pdc'] = $data['pdc'];

        if ($data['pdc']) {
            $pdc['cheque_number'] = $data['cheque_number'];
            $pdc['cheque_date'] = $data['cheque_date'];
            $pdc['amount'] = $data['amount'];
            $pdc['bank_number'] = $data['bank_number'];
        }

        if (isset($id) && !empty($id)) {
            if ($data['pdc']) {
                $pdc['receivable_id'] = $id;
            }
            $this->pdc_model->add($pdc, $data['pdcID']);
            $this->db->where("id", $id);
            return $this->db->update(db_prefix() . "receivable", $receivable);
        }

        $this->db->insert(db_prefix() . 'receivable', $receivable);
        $lastID = $this->db->insert_id();

        if ($data['pdc']) {
            $pdc['receivable_id'] = $lastID;
            $pdcId = $this->pdc_model->add($pdc);

            $arr = array("pdc_id" => $pdcId);
            $this->db->where("id", $lastID);
            $this->db->update("receivable", $arr);
        }
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->delete(db_prefix() . 'receivable');
    }

    public function get_due_dates_by_month($data)
    {
        if (isset($data['month'])) {
            $month = explode('-', $data['month']);
            $month = end($month);

            $this->db->select('receivable.*, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number ');
            $this->db->from('receivable');
            $this->db->join('pdc', 'pdc.receivable_id = receivable.id', 'left');
            if (isset($month)) {
                $this->db->where('MONTH(invoice_due_date)', $month);
            }

            $query = $this->db->get();

            return $query->result();
        }
    }
}
