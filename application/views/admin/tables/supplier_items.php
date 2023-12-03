<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('supplier_items', '', 'delete');

$custom_fields = get_custom_fields('supplier_items', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'supplier_items.id',
    "CONCAT(" . db_prefix() . "suppliers.firstname, ' ', " . db_prefix() . "suppliers.lastname) as full_name",
    db_prefix() . 'items.description',
    db_prefix() . 'supplier_items.rate',
    db_prefix() . 'supplier_items.created_at',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'supplier_items';
$join   = [
    'LEFT JOIN ' . db_prefix() . 'suppliers ON ' . db_prefix() . 'suppliers.supplierid = ' . db_prefix() . 'supplier_items.supplier_id',
    'LEFT JOIN ' . db_prefix() . 'items ON ' . db_prefix() . 'items.id = ' . db_prefix() . 'supplier_items.item_id',
];
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
        if ($aColumns[$i] == 'created_at') {
            $_data = $aRow['created_at'];
        } elseif ($aColumns[$i] == "CONCAT(" . db_prefix() . "suppliers.firstname, ' ', " . db_prefix() . "suppliers.lastname) as full_name") {
            $_data = ' <a href="' . admin_url('supplier_item/create/' . $aRow[db_prefix() . 'supplier_items.id']) . '">' . $aRow["full_name"] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('supplier_item/create/' . $aRow[db_prefix() . 'supplier_items.id']) . '">' . _l('view') . '</a>';
            $_data .= ' | ';
            $_data .= '<a href="' . admin_url('supplier_item/delete/' . $aRow[db_prefix() . 'supplier_items.id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

            $_data .= '</div>';
        } 
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('supplier_items_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
