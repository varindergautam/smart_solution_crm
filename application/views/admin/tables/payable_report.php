<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('payable', '', 'delete');

$custom_fields = get_custom_fields('payable', [
    'show_on_table' => 1,
]);

$aColumns = [
    'id',
    db_prefix() . 'payable.company_name',
    db_prefix() . 'payable.supplier_mobile',
    db_prefix() . 'payable.invoice_due_date',
    db_prefix() . 'payable.created_at',
    'paid_status'
];

$where  = [];
if (isset($month) && $month != '') {
    $month = $this->ci->db->escape_str($month);
    $month = explode('-', $month);
    $month = end($month);
    array_push($where,  'AND ', 'MONTH(invoice_due_date)=' . $month);
}


$sIndexColumn = 'id';
$sTable = db_prefix() . 'payable';
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

        if ($aColumns[$i] == "company_name") {

            $_data = ' <a href="' . admin_url('payable/create/' . $aRow['id']) . '">' . $aRow['company_name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('payable/create/' . $aRow['id']) . '">' . _l('view') . '</a>';

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'paid_status') {
            $checked = '';
            if ($aRow['paid_status'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox"  data-switch-url="' . admin_url() . 'payable/change_paid_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['id'] . '" data-id="' . $aRow['id'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow['id'] . '"></label>
            </div>';

            $_data .= '<span class="">' . ($checked == 'checked' ? 'Paid' : 'Un-Paid') . '</span>';
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('payable_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
