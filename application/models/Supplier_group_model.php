<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_group_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('group_id', $id);
            $supplier_group = $this->db->get(db_prefix() . 'supplier_group')->row();

            return $supplier_group;
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'supplier_group')->result_array();
    }

    public function add($data, $supplierId)
    {
        // Get the currently associated brands for the supplier
        $currentBrands = $this->db->get_where(db_prefix() . 'supplier_group', ['supplier_id' => $supplierId])->result_array();
        $currentBrandIds = array_column($currentBrands, 'group_id');

        // Check if a brand was unselected and remove the association
        if (isset($data['group_id'])) {
            $removedBrands = array_diff($currentBrandIds, $data['group_id']);
            if (!empty($removedBrands)) {
                foreach ($removedBrands as $removedBrandId) {
                    $this->db->where('group_id', $removedBrandId);
                    $this->db->where('supplier_id', $supplierId);
                    $this->db->delete(db_prefix() . 'supplier_group');
                    log_activity('Supplier brand removed [Brand: ' . $removedBrandId . ', Supplier: ' . $supplierId . ']');
                    hooks()->do_action('supplier_group_removed', $removedBrandId);
                }
            }

            // Add the selected brands
            if (count($data['group_id']) > 0) {
                foreach ($data['group_id'] as $key => $group_id) {
                    // Check if the brand is not already associated with the supplier
                    if (!in_array($group_id, $currentBrandIds)) {
                        $insert['group_id'] = $group_id;
                        $insert['supplier_id'] = $supplierId;

                        $this->db->insert(db_prefix() . 'supplier_group', $insert);
                        log_activity('New supplier brand added [Brand: ' . $group_id . ', Supplier: ' . $supplierId . ']');
                        hooks()->do_action('supplier_group_created', $group_id);
                    }
                }
                return true;
            }
        }

        return false;
    }
}
