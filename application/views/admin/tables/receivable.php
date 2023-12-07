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
    db_prefix() . 'pdc.cheque_date',
    db_prefix() . 'pdc.cheque_number',
    db_prefix() . 'pdc.bank_number',
    db_prefix() . 'pdc.amount',
    db_prefix() . 'receivable.remarks',
];

$join   = [
    'LEFT JOIN ' . db_prefix() . 'pdc ON ' . db_prefix() . 'pdc.receivable_id = ' . db_prefix() . 'receivable.id',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'receivable';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, []);

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

        if ($aColumns[$i] == "company_name") {

            $_data = ' <a href="' . admin_url('receivable/create/' . $aRow[db_prefix() . 'receivable.id']) . '">' . $aRow['company_name']. '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('receivable/create/' . $aRow[db_prefix() . 'receivable.id']) . '">' . _l('edit') . '</a>';

            $_data .= '</div>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('receivable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
