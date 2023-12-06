<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('budget', '', 'delete');

$custom_fields = get_custom_fields('budget', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'budget.id',
    'amount',
    db_prefix() . 'financial_year.year_name',
    db_prefix() . 'head.head_name',
    db_prefix() . 'budget.head_type',
    db_prefix() . 'budget.into_month',
    db_prefix() . 'budget.created_at',
];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'budget';
$join   = [
    'LEFT JOIN ' . db_prefix() . 'financial_year ON ' . db_prefix() . 'financial_year.id = ' . db_prefix() . 'budget.financial_year',
    'LEFT JOIN ' . db_prefix() . 'head ON ' . db_prefix() . 'head.id = ' . db_prefix() . 'budget.head',
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
        } elseif ($aColumns[$i] == "amount") {
            $_data = ' <a href="' . admin_url('budget/create/' . $aRow[db_prefix() . 'budget.id']) . '">' . $aRow["amount"] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('budget/create/' . $aRow[db_prefix() . 'budget.id']) . '">' . _l('edit') . '</a>';
            // $_data .= ' | ';
            // $_data .= '<a href="' . admin_url('budget/delete/' . $aRow[db_prefix() . 'budget.id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';

            $_data .= '</div>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('budget_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
