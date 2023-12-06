<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('budget', '', 'delete');

$aColumns = [
    'id',
    'financial_year',
];

$where  = [];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'budget';
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

        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('budget_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
