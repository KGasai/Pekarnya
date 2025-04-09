<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consumption_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function create_consumption($data) {
        return $this->db->insert('IngredientConsumptions', $data) ? $this->db->insert_id() : false;
    }

    public function add_consumption_item($consumption_id, $ingredient_id, $quantity) {
        $data = array(
            'consumption_id' => $consumption_id,
            'ingredient_id' => $ingredient_id,
            'quantity' => $quantity
        );
        
        return $this->db->insert('IngredientConsumptionItems', $data);
    }

    public function get_consumptions() {
        $this->db->select('ic.*, u.full_name as issued_by_name');
        $this->db->from('IngredientConsumptions ic');
        $this->db->join('Users u', 'u.user_id = ic.issued_by');
        $this->db->order_by('ic.date', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_consumption($consumption_id) {
        $this->db->select('ic.*, u.full_name as issued_by_name');
        $this->db->from('IngredientConsumptions ic');
        $this->db->join('Users u', 'u.user_id = ic.issued_by');
        $this->db->where('ic.consumption_id', $consumption_id);
        return $this->db->get()->row_array();
    }

    public function get_consumption_items($consumption_id) {
        $this->db->select('ici.*, i.name as ingredient_name, i.unit_of_measure');
        $this->db->from('IngredientConsumptionItems ici');
        $this->db->join('Ingredients i', 'i.ingredient_id = ici.ingredient_id');
        $this->db->where('ici.consumption_id', $consumption_id);
        return $this->db->get()->result_array();
    }

    public function get_next_document_number() {
        $this->db->select_max('document_number');
        $query = $this->db->get('IngredientConsumptions');
        $result = $query->row_array();
        
        if ($result['document_number']) {
            $last_num = (int) substr($result['document_number'], 3);
            return 'РН-' . str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
        }
        
        return 'РН-000001';
    }

    public function get_next_document_number_prihod() {
        $this->db->select_max('document_number');
        $query = $this->db->get('IngredientConsumptions');
        $result = $query->row_array();
        
        if ($result['document_number']) {
            $last_num = (int) substr($result['document_number'], 3);
            return 'ПН-' . str_pad($last_num + 1, 6, '0', STR_PAD_LEFT);
        }
        
        return 'ПН-000001';
    }
}