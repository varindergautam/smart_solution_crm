<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_brand_model extends App_Model
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
            $this->db->where('brand_id', $id);
            $supplier_brand = $this->db->get(db_prefix() . 'supplier_brand')->row();

            return $supplier_brand;
        }
        $this->db->order_by('id', 'desc');

        return $this->db->get(db_prefix() . 'supplier_brand')->result_array();
    }

    public function add($data, $supplierId)
    {
        // Get the currently associated brands for the supplier
        $currentBrands = $this->db->get_where(db_prefix() . 'supplier_brand', ['supplier_id' => $supplierId])->result_array();
        $currentBrandIds = array_column($currentBrands, 'brand_id');

        // Check if a brand was unselected and remove the association
        if (isset($data['brand_id'])) {
            $removedBrands = array_diff($currentBrandIds, $data['brand_id']);
            if (!empty($removedBrands)) {
                foreach ($removedBrands as $removedBrandId) {
                    $this->db->where('brand_id', $removedBrandId);
                    $this->db->where('supplier_id', $supplierId);
                    $this->db->delete(db_prefix() . 'supplier_brand');
                    log_activity('Supplier brand removed [Brand: ' . $removedBrandId . ', Supplier: ' . $supplierId . ']');
                    hooks()->do_action('supplier_brand_removed', $removedBrandId);
                }
            }

            // Add the selected brands
            if (count($data['brand_id']) > 0) {
                foreach ($data['brand_id'] as $key => $brand_id) {
                    // Check if the brand is not already associated with the supplier
                    if (!in_array($brand_id, $currentBrandIds)) {
                        $insert['brand_id'] = $brand_id;
                        $insert['supplier_id'] = $supplierId;

                        $this->db->insert(db_prefix() . 'supplier_brand', $insert);
                        log_activity('New supplier brand added [Brand: ' . $brand_id . ', Supplier: ' . $supplierId . ']');
                        hooks()->do_action('supplier_brand_created', $brand_id);
                    }
                }
                return true;
            }
        }

        return false;
    }
}
