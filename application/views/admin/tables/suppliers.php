<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('suppliers', '', 'delete');

$custom_fields = get_custom_fields('suppliers', [
    'show_on_table' => 1,
]);
$aColumns = [
    'supplierid',
    'website',
    'company',
    'name',
    'name_1',
    'name_2',
    'email',
    'email_1',
    'email_2',
    'vat_number',
    'phone_number',
    'phone_number_1',
    'phone_number_2',
    'currency',
    'default_language',
    'datecreated',
];
$sIndexColumn = 'supplierid';
$sTable       = db_prefix() . 'suppliers';

// Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$where = hooks()->apply_filters('suppliers_table_sql_where', []);

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
    'supplierid',
]);

$output  = $result['output'];
$rResult = $result['rResult'];
$CI = &get_instance();
$CI->load->database();
foreach ($rResult as $key => $aRow) {
    $brandNames = $CI->db->select('b.name')
        ->from('tblsupplier_brand sb')
        ->join('brand b', 'b.id = sb.brand_id')
        ->where('sb.supplier_id', $aRow['supplierid'])
        ->get()
        ->result_array();
    $brandNames = array_column($brandNames, 'name');
    $brandNamesImploded = (!empty($brandNames)) ? implode(', ', $brandNames) : '';

    $groupNames = $CI->db->select('b.name')
        ->from('tblsupplier_brand sb')
        ->join('items_groups b', 'b.id = sb.brand_id')
        ->where('sb.supplier_id', $aRow['supplierid'])
        ->get()
        ->result_array();
    $groupNames = array_column($groupNames, 'name');
    $groupNamesImploded = (!empty($groupNames)) ? implode(', ', $groupNames) : '';

    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'supplierid') {
            $_data = $key + 1;
        } elseif ($aColumns[$i] == 'datecreated') {
            $_data = date_format_change($aRow['datecreated']);
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
        } elseif ($aColumns[$i] == 'name') {
            $_data = ' <a href="' . admin_url('suppliers/create/' . $aRow['supplierid']) . '">' . $aRow['name'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('suppliers/create/' . $aRow['supplierid']) . '">' . _l('edit') . '</a>';
            $_data .= '<a href="' . admin_url('suppliers/delete/' . $aRow['supplierid']) . '" class="text-danger">' . _l('delete') . '</a>';

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'company') {
            $_data = $aRow['company'];
        } elseif ($aColumns[$i] == 'supplierid') {
            $_data = $aRow['supplierid'];
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
        } elseif ($aColumns[$i] == 'currency') {
            $_data = $brandNamesImploded;
        } elseif ($aColumns[$i] == 'default_language') {
            $_data = $groupNamesImploded;
        } elseif ($aColumns[$i] == 'website') {
            $_data = '<a href="' . admin_url('suppliers/upload_catalouge/') . $aRow['supplierid'] . '" class="btn btn-primary" style="padding: 2px 5px;
            font-size: 12px;
            margin-right: 5px;">Upload</a>';

            $_data .= '<a href="' . admin_url('suppliers/catalogues?supplier=' . $aRow['supplierid']) . '">View</a>';
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
