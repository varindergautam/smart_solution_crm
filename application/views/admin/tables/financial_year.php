<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('financial_year', '', 'delete');

$custom_fields = get_custom_fields('financial_year', [
    'show_on_table' => 1,
]);
$aColumns = [
    'id',
    'year_name',
    db_prefix() . 'financial_year.created_at',
    // 'status'
];

$where  = [];

$sIndexColumn = 'id';
$sTable = db_prefix() . 'financial_year';
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

        if ($aColumns[$i] == "year_name") {
            $_data = ' <a href="' . admin_url('financial_year/create/' . $aRow['id']) . '">' . $aRow['year_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('financial_year/create/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'id') {
            $_data = $key + 1;
        }
        // elseif ($aColumns[$i] == 'status') {
        //     $checked = '';
        //     if ($aRow['status'] == 1) {
        //         $checked = 'checked';
        //     }

        //     $_data = '<div class="onoffswitch">
        //         <input type="checkbox"  data-switch-url="' . admin_url() . 'financial_year/change_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
        //         <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
        //     </div>';

        //     $_data .= '<span class="">' . ($checked == 'checked' ? 'Paid' : 'Un-Paid') . '</span>';
        // }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('financial_year_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
