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
        $this->db->join('pdc', 'pdc.receivable_id = receivable.id', 'left');
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
        $receivable['pdc'] = isset($data['pdc']) ? $data['pdc'] : NULL;

        $month = date('m', strtotime($data['invoice_due_date']));
        $receivable[$monthName[$month]] = $data['invoice_amount'];

        if (isset($data['pdc']) && $data['pdc']) {
            $pdc['cheque_number'] = $data['cheque_number'];
            $pdc['cheque_date'] = $data['cheque_date'];
            $pdc['amount'] = $data['amount'];
            $pdc['bank_number'] = $data['bank_number'];
            $pdc['type'] = 'receivable';
        }

        if (isset($id) && !empty($id)) {
            if (isset($data['pdc']) && $data['pdc']) {
                $pdc['receivable_id'] = $id;
                $this->pdc_model->add($pdc, $data['pdcID']);
            }
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

    public function change_paid_status($id, $status)
    {
        $status = hooks()->apply_filters('before_staff_status_change', $status, $id);

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'receivable', [
            'paid_status' => $status,
        ]);

        $this->db->where('receivable_id', $id);
        $this->db->update(db_prefix() . 'pdc', [
            'paid_status' => $status,
        ]);

        log_activity('Receivable Paid Status Changed [SupplierID: ' . $id . ' - Paid Status(Active/Inactive): ' . $status . ']');
    }

    // public function summarize_report($year)
    // {
    //     if (isset($year)) {
    //         $this->db->select('receivable.*, pdc.id as pdcID, pdc.cheque_number, pdc.cheque_date, pdc.amount, pdc.bank_number ');
    //         $this->db->join('pdc', 'pdc.receivable_id = receivable.id', 'left');
    //         $this->db->where('YEAR(invoice_due_date)', $year);
    //         $this->db->order_by('id', 'desc');

    //         return $this->db->get(db_prefix() . 'receivable')->result();
    //     }
    // }

    public function summarize_report($year)
    {
        if (isset($year)) {
            $explode = explode('-',$year);
            $year = $explode[0];
            $month = end($explode);
            $this->db->select('receivable.*,
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
            $this->db->join('pdc', 'pdc.receivable_id = receivable.id', 'left');
            $this->db->where('YEAR(invoice_due_date)', $year);
            $this->db->where('MONTH(invoice_due_date)', $month);
            $this->db->group_by('customer_id'); // Group by customer_id

            return $this->db->get(db_prefix() . 'receivable')->result();
        }
    }

    public function customers() {
        $this->db->select('r.*, c.userid, c.company');
        $this->db->from(db_prefix() . 'receivable as r');
        $this->db->join(db_prefix() . 'clients as c', 'c.userid = r.customer_id', 'left');
        $this->db->group_by('r.customer_id');

        $supplierQuery = $this->db->get();
        return $supplierQuery->result_array();
    }
}
