<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('receivable', '', 'delete');

$custom_fields = get_custom_fields('receivable', [
    'show_on_table' => 1,
]);

$aColumns = [
    'id',
    'customer_name',
    db_prefix() . 'receivable.company_name',
    db_prefix() . 'receivable.customer_mobile',
    db_prefix() . 'receivable.invoice_due_date',
    db_prefix() . 'receivable.created_at',
];

$where  = [];
if (isset($month) && $month != '') {
    $month = $this->ci->db->escape_str($month);
    $month = explode('-', $month);
    $month = end($month);
    array_push($where,  'AND ', 'MONTH(invoice_due_date)=' . $month);
}


$sIndexColumn = 'id';
$sTable = db_prefix() . 'receivable';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);

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

        if ($aColumns[$i] == "customer_name") {

            $_data = ' <a href="' . admin_url('receivable/create/' . $aRow['id']) . '">' . $aRow['customer_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('receivable/create/' . $aRow['id']) . '">' . _l('view') . '</a>';

            $_data .= '</div>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('receivable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
