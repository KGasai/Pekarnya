<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Получение отчета о расходе сырья
    public function get_ingredient_consumption($start_date, $end_date) {
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->select('IngredientStockLog.ingredient_id, Ingredients.name, Ingredients.unit_of_measure, SUM(outgoing) as total_outgoing');
        $this->db->group_by('IngredientStockLog.ingredient_id');
        $this->db->join('Ingredients', 'Ingredients.ingredient_id = IngredientStockLog.ingredient_id');
        $query = $this->db->get('IngredientStockLog');
        
        $result = $query->result_array();
        
        // Добавляем стоимость
        foreach ($result as &$item) {
            $ingredient = $this->get_ingredient($item['ingredient_id']);
            $item['cost_per_unit'] = $ingredient['cost_per_unit'];
            $item['total_cost'] = $item['total_outgoing'] * $ingredient['cost_per_unit'];
        }
        
        return $result;
    }
    // Получение информации об ингредиенте
    private function get_ingredient($ingredient_id) {
        $this->db->where('ingredient_id', $ingredient_id);
        $query = $this->db->get('Ingredients');
        return $query->row_array();
    }

    // Получение отчета о производстве
    public function get_production_report($start_date, $end_date) {
        $this->db->where('task_date >=', $start_date);
        $this->db->where('task_date <=', $end_date);
        $this->db->where('status', 'completed');
        $this->db->join('ProductionTaskItems', 'ProductionTaskItems.task_id = ProductionTasks.task_id');
        $this->db->join('Products', 'Products.product_id = ProductionTaskItems.product_id');
        $this->db->select('Products.product_id, Products.name as product_name, Products.unit_of_measure, SUM(ProductionTaskItems.quantity) as total_quantity');
        $this->db->group_by('Products.product_id');
        $query = $this->db->get('ProductionTasks');
        
        $result = $query->result_array();
        
        // Добавляем стоимость
        foreach ($result as &$item) {
            $product = $this->get_product($item['product_id']);
            $item['price'] = $product['price'];
            $item['total'] = $item['total_quantity'] * $product['price'];
        }
        
        return $result;
    }

    // Получение информации о продукте
    private function get_product($product_id) {
        $this->db->where('product_id', $product_id);
        $query = $this->db->get('Products');
        return $query->row_array();
    }

    // Анализ спроса на продукцию
    public function get_demand_analysis($start_date, $end_date) {
        $this->db->where('order_date >=', $start_date);
        $this->db->where('order_date <=', $end_date);
        $this->db->where('Orders.status !=', 'canceled');
        $this->db->join('OrderItems', 'OrderItems.order_id = Orders.order_id');
        $this->db->join('Products', 'Products.product_id = OrderItems.product_id');
        $this->db->select('Products.product_id, Products.name as product_name, Products.unit_of_measure, SUM(OrderItems.quantity) as total_quantity, AVG(OrderItems.price) as avg_price');
        $this->db->group_by('Products.product_id');
        $this->db->order_by('total_quantity', 'DESC');
        $query = $this->db->get('Orders');
        
        $result = $query->result_array();
        
        // Добавляем общую стоимость
        foreach ($result as &$item) {
            $item['total'] = $item['total_quantity'] * $item['avg_price'];
        }
        
        return $result;
    }
}
?>