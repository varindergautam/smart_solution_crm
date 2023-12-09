<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('supplier_brand', '', 'delete');

$custom_fields = get_custom_fields('supplier_brand', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'supplier_brand.id',
    db_prefix() . 'brand.name',
    db_prefix() . 'suppliers.company',
    db_prefix() . 'suppliers.name',
    db_prefix() . 'suppliers.email',
    db_prefix() . 'suppliers.phone_number',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'supplier_brand';

$join   = [
    'LEFT JOIN ' . db_prefix() . 'brand ON ' . db_prefix() . 'brand.id = ' . db_prefix() . 'supplier_brand.brand_id',
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'supplier_brand.supplier_id',
];

$where = [];
if (isset($brand) && $brand != '') {
    array_push($where,  'AND ', "brand_id='$brand'");
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
        
        if ($aColumns[$i] == db_prefix() . 'supplier_brand.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == db_prefix() . 'brand.name') {
            $_data = $aRow[db_prefix() . 'brand.name'];
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.company') {
            $_data = $aRow[db_prefix() . 'suppliers.company'];
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.phone_number') {
            $_data = ($aRow[db_prefix() . 'suppliers.phone_number'] ? '<a href="tel:' . $aRow[db_prefix() . 'suppliers.phone_number'] . '">' . $aRow[db_prefix() . 'suppliers.phone_number'] . '</a>' : '');
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.email') {
            $_data = $aRow[db_prefix() . 'suppliers.email'] ? '<a href="mailto:' . $aRow[db_prefix() . 'suppliers.email'] . '">' . $aRow[db_prefix() . 'suppliers.email'] . '</a>' : '';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('supplier_brand_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
