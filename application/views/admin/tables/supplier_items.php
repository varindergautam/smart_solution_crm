<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('supplier_items', '', 'delete');

$custom_fields = get_custom_fields('supplier_items', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'supplier_items.id',
    'company',
    'name',
    'name_2',
    'name_2',
    'email',
    'email_1',
    'email_2',
    'phone_number',
    'phone_number_1',
    'phone_number_2',
    db_prefix() . 'items.description',
    db_prefix() . 'supplier_items.rate',
    db_prefix() . 'supplier_items.date',
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

foreach ($rResult as $key => $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {

        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == db_prefix() . 'supplier_items.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == 'date') {
            $_data = date_format_change($aRow['date']);
        } elseif ($aColumns[$i] == "vat_number") {
            $_data = ' <a href="' . admin_url('supplier_item/create/' . $aRow[db_prefix() . 'supplier_items.id']) . '">' . $aRow["vat_number"] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('supplier_item/create/' . $aRow[db_prefix() . 'supplier_items.id']) . '">' . _l('view') . '</a>';
            $_data .= ' | ';
            $_data .= '<a href="' . admin_url('supplier_item/delete/' . $aRow[db_prefix() . 'supplier_items.id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'phone_number') {
            $_data = ($aRow['phone_number'] ? '<a href="tel:' . $aRow['phone_number'] . '">' . $aRow['phone_number'] . '</a>' : '');
        } elseif ($aColumns[$i] == 'phone_number_1') {
            $_data = ($aRow['phone_number_1'] ? '<a href="tel:' . $aRow['phone_number_1'] . '">' . $aRow['phone_number_1'] . '</a>' : '');
        } elseif ($aColumns[$i] == 'phone_number_2') {
            $_data = ($aRow['phone_number_2'] ? '<a href="tel:' . $aRow['phone_number_2'] . '">' . $aRow['phone_number_2'] . '</a>' : '');
        } elseif ($aColumns[$i] == 'email') {
            $_data = $aRow['email'] ? '<a href="mailto:' . $aRow['email'] . '">' . $aRow['email'] . '</a>' : '';
        } elseif ($aColumns[$i] == 'email_1') {
            $_data = $aRow['email_1'] ? '<a href="mailto:' . $aRow['email_1'] . '">' . $aRow['email_1'] . '</a>' : '';
        } elseif ($aColumns[$i] == 'email_2') {
            $_data = $aRow['email_2'] ? '<a href="mailto:' . $aRow['email_2'] . '">' . $aRow['email_2'] . '</a>' : '';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('supplier_items_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
