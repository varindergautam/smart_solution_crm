<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('receivable', '', 'delete');

$custom_fields = get_custom_fields('receivable', [
    'show_on_table' => 1,
]);

$aColumns = [
    db_prefix() . 'receivable.id',
    'company_name',
    'invoice_number',
    'invoice_date',
    db_prefix() . 'receivable.invoice_due_date',
    db_prefix() . 'receivable.invoice_amount',
    db_prefix() . 'pdc.date',
    db_prefix() . 'pdc.cheque_number',
    db_prefix() . 'pdc.bank_number',
    db_prefix() . 'pdc.amount',
    db_prefix() . 'receivable.remarks',
    db_prefix() . 'receivable.paid_status'
];

$join   = [
    'LEFT JOIN ' . db_prefix() . 'pdc ON ' . db_prefix() . 'pdc.receivable_id = ' . db_prefix() . 'receivable.id',
];

$where  = [];
if (isset($month) && $month != '') {
    $month = $this->ci->db->escape_str($month);
    $month = explode('-', $month);
    $month = end($month);
    array_push($where,  'AND ', 'MONTH(invoice_due_date)=' . $month);
}


$sIndexColumn ='id';
$sTable = db_prefix() . 'receivable';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        // if ($aColumns[$i] == "customer_name") {

        //     $_data = ' <a href="' . admin_url('receivable/create/' . $aRow['id']) . '">' . $aRow['customer_name'] . '</a>';

        //     $_data .= '<div class="row-options">';
        //     $_data .= '<a href="' . admin_url('receivable/create/' . $aRow['id']) . '">' . _l('view') . '</a>';

        //     $_data .= '</div>';
        // } else
        
        if ($aColumns[$i] == db_prefix() . 'receivable.paid_status') {
            $checked = '';
            if ($aRow[db_prefix() . 'receivable.paid_status'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox"  data-switch-url="' . admin_url() . 'receivable/change_paid_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow[db_prefix() . 'receivable.id'] . '" data-id="' . $aRow[db_prefix() . 'receivable.id'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow[db_prefix() . 'receivable.id'] . '"></label>
            </div>';

            $_data .= '<span class="">' . ($checked == 'checked' ? 'Paid' : 'Un-Paid') . '</span>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('receivable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
