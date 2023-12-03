<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Suppliers_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $where = [])
    {
        $select_str = '*,CONCAT(firstname,\' \',lastname) as full_name';

        $this->db->select($select_str);
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('supplierid', $id);
            $suppliers = $this->db->get(db_prefix() . 'suppliers')->row();


            return $suppliers;
        }
        $this->db->order_by('firstname', 'desc');

        return $this->db->get(db_prefix() . 'suppliers')->result_array();
    }

    public function add($data)
    {
        if (isset($data['fakeusernameremembered'])) {
            unset($data['fakeusernameremembered']);
        }
        if (isset($data['fakepasswordremembered'])) {
            unset($data['fakepasswordremembered']);
        }

        // First check for all cases if the email exists.
        $data = hooks()->apply_filters('before_create_supplier_member', $data);

        $this->db->where('email', $data['email']);
        $email = $this->db->get(db_prefix() . 'suppliers')->row();

        if ($email) {
            die('Email already exists');
        }

        $data['admin'] = 0;

        if (is_admin()) {
            if (isset($data['administrator'])) {
                $data['admin'] = 1;
                unset($data['administrator']);
            }
        }

        $send_welcome_email = true;
        if (!isset($data['send_welcome_email'])) {
            $send_welcome_email = false;
        } else {
            unset($data['send_welcome_email']);
        }

        $data['datecreated'] = date('Y-m-d H:i:s');

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        if ($data['admin'] == 1) {
            $data['is_not_suppliers'] = 0;
        }

        $this->db->insert(db_prefix() . 'suppliers', $data);
        $supplierid = $this->db->insert_id();
        if ($supplierid) {
            $slug = $data['firstname'] . ' ' . $data['lastname'];

            if ($slug == ' ') {
                $slug = 'unknown-' . $supplierid;
            }

            if ($send_welcome_email == true) {
                send_mail_template('supplier_created', $data['email'], $supplierid);
            }

            $this->db->where('supplierid', $supplierid);
            $this->db->update(db_prefix() . 'suppliers', [
                'media_path_slug' => slug_it($slug),
            ]);

            if (isset($custom_fields)) {
                handle_custom_fields_post($supplierid, $custom_fields);
            }

            log_activity('New supplier Member Added [ID: ' . $supplierid . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');

            // Get all announcements and set it to read.
            // $this->db->select('announcementid');
            // $this->db->from(db_prefix() . 'announcements');
            // $this->db->where('showtosupplier', 1);
            // $announcements = $this->db->get()->result_array();
            // foreach ($announcements as $announcement) {
            //     $this->db->insert(db_prefix() . 'dismissed_announcements', [
            //         'announcementid' => $announcement['announcementid'],
            //         'supplier'          => 1,
            //         'userid'         => $supplierid,
            //     ]);
            // }
            hooks()->do_action('supplier_member_created', $supplierid);

            return $supplierid;
        }

        return false;
    }

    public function update($data, $id)
    {
        if (isset($data['fakeusernameremembered'])) {
            unset($data['fakeusernameremembered']);
        }
        if (isset($data['fakepasswordremembered'])) {
            unset($data['fakepasswordremembered']);
        }

        $data = hooks()->apply_filters('before_update_suppliers_member', $data, $id);

        $affectedRows = 0;

        $this->db->where('supplierid', $id);
        $this->db->update(db_prefix() . 'suppliers', $data);

        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }

        if ($affectedRows > 0) {
            hooks()->do_action('suppliers_member_updated', $id);
            log_activity('suppliers Member Updated [ID: ' . $id . ', ' . $data['firstname'] . ' ' . $data['lastname'] . ']');

            return true;
        }

        return false;
    }

    public function change_supplier_status($id, $status)
    {
        $status = hooks()->apply_filters('before_staff_status_change', $status, $id);

        $this->db->where('supplierid', $id);
        $this->db->update(db_prefix() . 'suppliers', [
            'active' => $status,
        ]);

        log_activity('Supplier Status Changed [SupplierID: ' . $id . ' - Status(Active/Inactive): ' . $status . ']');
    }
}
