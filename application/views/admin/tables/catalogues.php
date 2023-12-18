<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('catalog', '', 'delete');

$custom_fields = get_custom_fields('catalog', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'catalog.id',
    'catalogue',
    db_prefix() . 'catalog.created_at',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'catalog';
// $join   = [
//     'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'catalog.supplier_id',
// ];
$join = [];

$where = [];

if (isset($supplier) && $supplier != '') {
    array_push($where,  'AND ', "supplier_id='$supplier'");
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);


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
        if ($aColumns[$i] == db_prefix() . 'catalog.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == 'catalogue') {
            $_data = '<a href="' . admin_url('suppliers/view_catalogue/') . $aRow['catalogue'] . '">View</a>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('catalog_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
