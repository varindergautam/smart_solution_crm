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
        $this->db->join('pdc', 'pdc.payable_id = payable.id', 'left');
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
        $monthName = array(
            "01" => "january",
            "02" => "february",
            "03" => "march",
            "04" => "april",
            "05" => "may",
            "06" => "june",
            "07" => "july",
            "08" => "august",
            "09" => "september",
            "10" => "october",
            "11" => "november",
            "12" => "december"
        );

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
        $payable['pdc'] = isset($data['pdc']) ? $data['pdc'] : NULL;

        $month = date('m', strtotime($data['invoice_due_date']));
        $payable[$monthName[$month]] = $data['invoice_amount'];

        if (isset($data['pdc']) && $data['pdc']) {
            $pdc['cheque_number'] = $data['cheque_number'];
            $pdc['cheque_date'] = $data['cheque_date'];
            $pdc['amount'] = $data['amount'];
            $pdc['bank_number'] = $data['bank_number'];
            $pdc['type'] = 'payable';
        }

        if (isset($id) && !empty($id)) {
            if (isset($data['pdc']) && $data['pdc']) {
                $pdc['payable_id'] = $id;
                $this->pdc_model->add($pdc, $data['pdcID']);
            }
            $this->db->where("id", $id);
            return $this->db->update(db_prefix() . "payable", $payable);
        }

        $this->db->insert(db_prefix() . 'payable', $payable);
        $lastID = $this->db->insert_id();

        if ($data['pdc']) {
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

    public function change_paid_status($id, $status)
    {
        $status = hooks()->apply_filters('before_staff_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'payable', [
            'paid_status' => $status,
        ]);

        $this->db->where('payable_id', $id);
        $this->db->update(db_prefix() . 'pdc', [
            'paid_status' => $status,
        ]);

        log_activity('payable Paid Status Changed [SupplierID: ' . $id . ' - Paid Status(Active/Inactive): ' . $status . ']');
    }

    // public function summarize_report($year)
    // {
    //     if (isset($year)) {
    //         $this->db->select('payable.*, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number ');
    //         $this->db->join('pdc', 'pdc.payable_id = payable.id', 'left');
    //         $this->db->where('YEAR(invoice_due_date)', $year);
    //         $this->db->order_by('id', 'desc');

    //         return $this->db->get(db_prefix() . 'payable')->result();
    //     }
    // }

    public function summarize_report($year)
    {
        if (isset($year)) {
            $this->db->select('payable.*,
        SUM(invoice_amount) as invoice_amount,
        SUM(january) as january,
        SUM(february) as february,
        SUM(march) as march,
        SUM(april) as april,
        SUM(may) as may,
        SUM(june) as june,
        SUM(july) as july,
        SUM(august) as august,
        SUM(september) as september,
        SUM(october) as october,
        SUM(november) as november,
        SUM(december) as december, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number');
            $this->db->join('pdc', 'pdc.payable_id = payable.id', 'left');
            $this->db->where('YEAR(invoice_due_date)', $year);
            $this->db->group_by('supplier_id');

            return $this->db->get(db_prefix() . 'payable')->result();
        }
    }
}
