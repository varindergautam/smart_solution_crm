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
    db_prefix() . 'pdc.cheque_date',
    db_prefix() . 'pdc.cheque_number',
    db_prefix() . 'pdc.bank_number',
    db_prefix() . 'pdc.amount',
    db_prefix() . 'payable.remarks',
];

$join   = [
    'LEFT JOIN ' . db_prefix() . 'pdc ON ' . db_prefix() . 'pdc.payable_id = ' . db_prefix() . 'payable.id',
];


$sIndexColumn = 'id';
$sTable = db_prefix() . 'payable';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, []);


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
        } elseif ($aColumns[$i] == db_prefix() . 'payable.company_name') {

            $_data = ' <a href="' . admin_url('payable/create/' . $aRow[db_prefix() . 'payable.id']) . '">' . $aRow[db_prefix() . 'payable.company_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('payable/create/' . $aRow[db_prefix() . 'payable.id']) . '">' . _l('edit') . '</a>';

            $_data .= '</div>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('payable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
