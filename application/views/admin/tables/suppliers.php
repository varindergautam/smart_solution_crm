<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('suppliers', '', 'delete');

$custom_fields = get_custom_fields('suppliers', [
    'show_on_table' => 1,
]);
$aColumns = [
    'firstname',
    'email',
    'phonenumber',
    'datecreated',
    'active',
];
$sIndexColumn = 'supplierid';
$sTable       = db_prefix() . 'suppliers';
$join         = ['LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = ' . db_prefix() . 'suppliers.role'];

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$where = hooks()->apply_filters('suppliers_table_sql_where', []);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'profile_image',
    'lastname',
    'supplierid',
]);

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
        if ($aColumns[$i] == 'datecreated') {

            $_data = $aRow['datecreated'];
        } elseif ($aColumns[$i] == 'active') {
            $checked = '';
            if ($aRow['active'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox" ' . ((/*$aRow['supplierid'] == get_suppliers_user_id() ||*/(is_admin($aRow['supplierid']) || !has_permission('suppliers', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'suppliers/change_suppliers_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['supplierid'] . '" data-id="' . $aRow['supplierid'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow['supplierid'] . '"></label>
            </div>';

            // For exporting
            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
        } elseif ($aColumns[$i] == 'firstname') {
            $_data = '<a href="' . admin_url('suppliers/profile/' . $aRow['supplierid']) . '">'
            . supplier_profile_image($aRow['supplierid'], [
                'staff-profile-image-small',
                ]) .
            '</a>';
            $_data .= ' <a href="' . admin_url('suppliers/create/' . $aRow['supplierid']) . '">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('suppliers/create/' . $aRow['supplierid']) . '">' . _l('view') . '</a>';

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'email') {
            $_data = '<a href="mailto:' . $_data . '">' . $_data . '</a>';
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('suppliers_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
