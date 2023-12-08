<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('supplier_items', '', 'delete');

$custom_fields = get_custom_fields('supplier_items', [
    'show_on_table' => 1,
]);


$aColumns = [
    db_prefix() . 'supplier_items.id',
    db_prefix() . 'items.description',
    db_prefix() . 'supplier_items.rate',
    // db_prefix() . 'items_groups.name',
    db_prefix() . 'suppliers.supplierid',
    db_prefix() . 'suppliers.default_language',
    db_prefix() . 'suppliers.company',
    db_prefix() . 'suppliers.name',
    db_prefix() . 'suppliers.email',
    db_prefix() . 'suppliers.phone_number',
    db_prefix() . 'supplier_items.date',
];

$where = [];

if (isset($item) && $item != '') {
    array_push($where,  'AND ', "item_id='$item'");
}

$join   = [
    'LEFT JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'supplier_items.item_id',
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'supplier_items.supplier_id',
    // 'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
];


$sIndexColumn = 'id';
$sTable = db_prefix() . 'supplier_items';

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

$output  = $result['output'];
$rResult = $result['rResult'];

$CI = &get_instance();
$CI->load->database();

foreach ($rResult as $aRow) {
    $brandNames = $CI->db->select('b.name')
        ->from('tblsupplier_brand sb')
        ->join('brand b', 'b.id = sb.brand_id')
        ->where('sb.supplier_id', $aRow[db_prefix() . 'suppliers.supplierid'])
        ->get()
        ->result_array();
    $brandNames = array_column($brandNames, 'name');
    $brandNamesImploded = (!empty($brandNames)) ? implode(', ', $brandNames) : '';

    $groupNames = $CI->db->select('b.name')
        ->from('tblsupplier_brand sb')
        ->join('items_groups b', 'b.id = sb.brand_id')
        ->where('sb.supplier_id', $aRow[db_prefix() . 'suppliers.supplierid'])
        ->get()
        ->result_array();
    $groupNames = array_column($groupNames, 'name');
    $groupNamesImploded = (!empty($groupNames)) ? implode(', ', $groupNames) : '';
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == db_prefix() . 'supplier_items.id') {
            $_data = $aRow[db_prefix() . 'supplier_items.id'];
        } elseif ($aColumns[$i] == db_prefix() . 'items.name') {
            $_data = $aRow[db_prefix() . 'items.name'];
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.company') {
            $_data = $aRow[db_prefix() . 'suppliers.company'];
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.supplierid') {
            $_data = $brandNamesImploded;
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.default_language') {
            $_data = $groupNamesImploded;
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.phone_number') {
            $_data = ($aRow[db_prefix() . 'suppliers.phone_number'] ? '<a href="tel:' . $aRow[db_prefix() . 'suppliers.phone_number'] . '">' . $aRow[db_prefix() . 'suppliers.phone_number'] . '</a>' : '');
        } elseif ($aColumns[$i] == db_prefix() . 'suppliers.email') {
            $_data = $aRow[db_prefix() . 'suppliers.email'] ? '<a href="mailto:' . $aRow[db_prefix() . 'suppliers.email'] . '">' . $aRow[db_prefix() . 'suppliers.email'] . '</a>' : '';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('supplier_items_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
