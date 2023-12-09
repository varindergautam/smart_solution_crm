<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('supplier_group', '', 'delete');

$custom_fields = get_custom_fields('supplier_group', [
    'show_on_table' => 1,
]);


$aColumns = [
    db_prefix() . 'supplier_group.id',
    db_prefix() . 'items_groups.name',
    db_prefix() . 'suppliers.company',
    db_prefix() . 'suppliers.name',
    db_prefix() . 'suppliers.email',
    db_prefix() . 'suppliers.phone_number',
];

$where = [];

if (isset($group) && $group != '') {
    array_push($where,  'AND ', "group_id='$group'");
}

$join   = [
    'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'supplier_group.group_id',
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'supplier_group.supplier_id',
];


$sIndexColumn = 'id';
$sTable = db_prefix() . 'supplier_group';

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

        if ($aColumns[$i] == db_prefix() . 'supplier_group.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == db_prefix() . 'items_groups.name') {
            $_data = $aRow[db_prefix() . 'items_groups.name'];
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

    $row = hooks()->apply_filters('supplier_group_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
