<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('pdc', '', 'delete');

$custom_fields = get_custom_fields('pdc', [
    'show_on_table' => 1,
]);
$aColumns = [
    db_prefix() . 'pdc.id',
    db_prefix() . 'receivable.company_name',
    'particular',
    'type',
    'date',
    'cheque_number',
    'bank_number',
    'cheque_date',
    'amount',
    'remark',
    db_prefix() . 'pdc.paid_status'
];

$where  = [];
// $where['type IS NOT NULL'] = NULL;
if (isset($month) && $month != '') {
    $month = $this->ci->db->escape_str($month);
    $month = explode('-', $month);
    $month = end($month);
    array_push($where,  'AND ', 'MONTH(cheque_date)=' . $month);
}

if (isset($type) && $type != '') {
    array_push($where,  'AND ', "type='$type'");
}

$join   = [
    'LEFT JOIN ' . db_prefix() . 'receivable ON ' . db_prefix() . 'receivable.id = ' . db_prefix() . 'pdc.receivable_id',
    'LEFT JOIN ' . db_prefix() . 'payable ON ' . db_prefix() . 'payable.id = ' . db_prefix() . 'pdc.payable_id',
];


$sIndexColumn = 'id';
$sTable = db_prefix() . 'pdc';
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

        // if ($aColumns[$i] == "cheque_number") {

        //     $_data = ' <a href="' . admin_url('pdc/create/' . $aRow['id']) . '">' . $aRow['cheque_number'] . '</a>';

        //     $_data .= '<div class="row-options">';
        //     $_data .= '<a href="' . admin_url('pdc/create/' . $aRow['id']) . '">' . _l('view') . '</a>';

        //     $_data .= '</div>';
        // } else
        
        if ($aColumns[$i] == db_prefix() . 'pdc.id') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == db_prefix() . 'pdc.paid_status') {
            $checked = '';
            if ($aRow[db_prefix() . 'pdc.paid_status'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox"  data-switch-url="' . admin_url() . 'pdc/change_paid_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow[db_prefix() . 'pdc.id'] . '" data-id="' . $aRow[db_prefix() . 'pdc.id'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow[db_prefix() . 'pdc.id'] . '"></label>
            </div>';

            $_data .= '<span class="">' . ($checked == 'checked' ? 'Paid' : 'Un-Paid') . '</span>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('pdc_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
