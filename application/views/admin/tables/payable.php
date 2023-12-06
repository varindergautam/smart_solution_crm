<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('payable', '', 'delete');

$custom_fields = get_custom_fields('payable', [
    'show_on_table' => 1,
]);
$aColumns = [
    'id',
    'supplier_name',
    db_prefix() . 'payable.company_name',
    db_prefix() . 'payable.supplier_mobile',
    db_prefix() . 'payable.created_at',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'payable';
// $join   = [
//     'LEFT JOIN ' . db_prefix() . 'payable ON ' . db_prefix() . 'payable.supplierid = ' . db_prefix() . 'payable.supplier_id',
// ];
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], []);


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

        if ($aColumns[$i] == "supplier_name") {

            $_data = ' <a href="' . admin_url('payable/create/' . $aRow['id']) . '">' . $aRow['supplier_name']. '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('payable/create/' . $aRow['id']) . '">' . _l('edit') . '</a>';

            $_data .= '</div>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('payable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
