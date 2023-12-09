<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('head', '', 'delete');

$custom_fields = get_custom_fields('head', [
    'show_on_table' => 1,
]);
$aColumns = [
    'id',
    'head_name',
    'type',
    'created_at',
];

$where  = [];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'head';
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where);

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

        if ($aColumns[$i] == "head_name") {
            $_data = ' <a href="' . admin_url('head/create/' . $aRow['id']) . '">' . $aRow['head_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('head/create/' . $aRow['id']) . '">' . _l('view') . '</a>';
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'id') {
            $_data = $key + 1;
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('head_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
