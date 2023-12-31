<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('payable', '', 'delete');

$custom_fields = get_custom_fields('payable', [
    'show_on_table' => 1,
]);

$aColumns = [
    db_prefix() . 'payable.id',
    db_prefix() . 'payable.company_name',
    'invoice_number',
    'invoice_date',
    db_prefix() . 'payable.invoice_due_date',
    db_prefix() . 'payable.invoice_amount',
    db_prefix() . 'pdc.date',
    db_prefix() . 'pdc.cheque_number',
    db_prefix() . 'pdc.bank_number',
    db_prefix() . 'pdc.amount',
    db_prefix() . 'payable.remarks',
    db_prefix() . 'payable.paid_status'
];

$join   = [
    'LEFT JOIN ' . db_prefix() . 'pdc ON ' . db_prefix() . 'pdc.payable_id = ' . db_prefix() . 'payable.id',
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'payable.supplier_id',
];

$where  = [];
if (isset($month) && $month != '') {
    $month = $this->ci->db->escape_str($month);
    $month = explode('-', $month);
    $month = end($month);
    array_push($where,  'AND ', 'MONTH(invoice_due_date)=' . $month);
}

if (isset($supplier) && $supplier != '') {
    array_push($where,  'AND ', 'supplier_id=' . $supplier);
}

$sIndexColumn ='id';
$sTable = db_prefix() . 'payable';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join , $where);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $key => $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == db_prefix() . 'payable.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == "company_name") {

            $_data = ' <a href="' . admin_url('payable/create/' . $aRow[db_prefix() . 'payable.id']) . '">' . $aRow['company_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('payable/create/' . $aRow[db_prefix() . 'payable.id']) . '">' . _l('view') . '</a>';

            $_data .= '</div>';
        } elseif ($aColumns[$i] == db_prefix() . 'payable.paid_status') {
            $checked = '';
            if ($aRow[db_prefix() . 'payable.paid_status'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox"  data-switch-url="' . admin_url() . 'payable/change_paid_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow[db_prefix() . 'payable.id'] . '" data-id="' . $aRow[db_prefix() . 'payable.id'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow[db_prefix() . 'payable.id'] . '"></label>
            </div>';

            $_data .= '<span class="">' . ($checked == 'checked' ? 'Paid' : 'Un-Paid') . '</span>';
        } elseif ($aColumns[$i] == db_prefix() . 'payable.invoice_due_date') {
            $_data = date_format_change($aRow[db_prefix() . 'payable.invoice_due_date']);
        } elseif ($aColumns[$i] == 'invoice_date') {
            $_data = date_format_change($aRow['invoice_date']);
        } elseif ($aColumns[$i] == db_prefix() . 'pdc.cheque_date') {
            $_data = date_format_change($aRow[db_prefix() . 'pdc.cheque_date']);
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('payable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
