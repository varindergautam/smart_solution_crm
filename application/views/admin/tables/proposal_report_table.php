<?php

defined('BASEPATH') or exit('No direct script access allowed');

$custom_fields = get_custom_fields('itemable', [
    'show_on_table' => 1,
]);

$aColumns = [
    db_prefix() . 'itemable.id',
    db_prefix() . 'proposals.id',
    db_prefix() . 'proposals.date',
    db_prefix() . 'items.description',
    db_prefix() . 'items_groups.name',
    'supplier_item_data',
    'supplier_id',
    db_prefix() . 'suppliers.name',
];

$join   = [
    'LEFT JOIN ' . db_prefix() . 'proposals ON ' . db_prefix() . 'proposals.id = ' . db_prefix() . 'itemable.rel_id',
    'LEFT JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.description = ' . db_prefix() . 'itemable.description',
    'LEFT JOIN ' . db_prefix() . 'items_groups ON ' . db_prefix() . 'items_groups.id = ' . db_prefix() . 'items.group_id',
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'itemable.supplier_id',
];

// $join = [];

$where  = [];
if (isset($supplier) && $supplier != '') {
    array_push($where,  'AND ', 'supplier_id=' . $supplier);
}

if (isset($date) && $date != '') {
    array_push($where, 'AND ' . db_prefix() . 'proposals.date = \'' . $date . '\'');
}


$relType = 'proposal';
array_push($where, 'AND ' . db_prefix() . 'itemable.rel_type = \'' . $relType . '\'');

$sIndexColumn = 'id';
$sTable = db_prefix() . 'itemable';
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

        if ($aColumns[$i] == db_prefix() . 'itemable.id') {
            $_data = $key + 1;
        }

        if ($aColumns[$i] == db_prefix() . 'proposals.id') {
            $_data = format_proposal_number($aRow[db_prefix() . 'proposals.id']);
        }

        if ($aColumns[$i] == 'supplier_item_data') {
            $_data = json_decode(json_decode($aRow['supplier_item_data']), true)['rate'];
        }

        if ($aColumns[$i] == 'supplier_id') {
            $_data = json_decode(json_decode($aRow['supplier_item_data']), true)['date'];
        }
        
        if ($aColumns[$i] == db_prefix() . 'suppliers.name') {
            $_data = $aRow[db_prefix() . 'suppliers.name'];
        }
        if ($aColumns[$i] == db_prefix() . 'proposals.date') {
            $_data = date_format_change($aRow[db_prefix() . 'proposals.date']);
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('itemable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
