<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredient_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Получение всех активных ингредиентов
    public function get_active_ingredients() {
        $this->db->where('is_active', 1);
        $query = $this->db->get('Ingredients');
        return $query->result_array();
    }

    // Получение ингредиентов с текущими остатками
    public function get_ingredients_with_stock() {
        $this->db->where('is_active', 1);
        $this->db->where('current_stock >', 0);
        $query = $this->db->get('Ingredients');
        return $query->result_array();
    }

    // Получение ингредиентов с остатком ниже минимального
    public function get_ingredients_below_min_stock() {
        $this->db->where('is_active', 1);
        $this->db->where('current_stock < min_stock');
        $query = $this->db->get('Ingredients');
        return $query->result_array();
    }

    // Получение ингредиента по ID
    public function get_ingredient($ingredient_id) {
        $this->db->where('ingredient_id', $ingredient_id);
        $query = $this->db->get('Ingredients');
        return $query->row_array();
    }

    // Создание приходной накладной
    public function create_receipt($data) {
        return $this->db->insert('IngredientReceipts', $data) ? $this->db->insert_id() : false;
    }

    // Добавление позиции в приходную накладную
    public function add_receipt_item($receipt_id, $ingredient_id, $quantity, $cost_per_unit, $total_cost) {
        $data = array(
            'receipt_id' => $receipt_id,
            'ingredient_id' => $ingredient_id,
            'quantity' => $quantity,
            'cost_per_unit' => $cost_per_unit,
            'total_cost' => $total_cost
        );
        
        return $this->db->insert('IngredientReceiptItems', $data);
    }

    // Обновление общей стоимости приходной накладной
    public function update_receipt_total($receipt_id, $total_cost) {
        $this->db->where('receipt_id', $receipt_id);
        return $this->db->update('IngredientReceipts', array('total_cost' => $total_cost));
    }

    // Создание расходной накладной
    public function create_consumption($data) {
        return $this->db->insert('IngredientConsumptions', $data) ? $this->db->insert_id() : false;
    }

    // Добавление позиции в расходную накладную
    public function add_consumption_item($consumption_id, $ingredient_id, $quantity) {
        $data = array(
            'consumption_id' => $consumption_id,
            'ingredient_id' => $ingredient_id,
            'quantity' => $quantity
        );
        
        return $this->db->insert('IngredientConsumptionItems', $data);
    }

    // Получение журнала учета сырья за период
    public function get_stock_log($start_date, $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->join('Ingredients', 'Ingredients.ingredient_id = IngredientStockLog.ingredient_id');
        $this->db->order_by('date', 'DESC');
        $this->db->order_by('Ingredients.name', 'ASC');
        $query = $this->db->get('IngredientStockLog');
        
        return $query->result_array();
    }

    // Получение текущих остатков сырья
    public function get_current_stock() {
        $this->db->select('ingredient_id, name, unit_of_measure, current_stock, min_stock');
        $this->db->where('is_active', 1);
        $query = $this->db->get('Ingredients');
        return $query->result_array();
    }

    // Создание запроса на закупку сырья
    public function create_purchase_request($ingredient_id, $required_quantity, $requested_by) {
        $data = array(
            'date' => date('Y-m-d'),
            'ingredient_id' => $ingredient_id,
            'required_quantity' => $required_quantity,
            'requested_by' => $requested_by,
            'status' => 'pending'
        );
        
        return $this->db->insert('IngredientPurchaseRequests', $data);
    }
}
?>