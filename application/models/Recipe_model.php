<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recipe_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Получение рецептуры продукта
    public function get_recipe_by_product($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->join('Ingredients', 'Ingredients.ingredient_id = Recipes.ingredient_id');
        $query = $this->db->get('Recipes');
        return $query->result_array();
    }

    // Расчет ингредиентов для конкретного продукта и количества
    public function calculate_ingredients($product_id, $quantity) {
        $recipe = $this->get_recipe_by_product($product_id);
        
        foreach ($recipe as &$item) {
            $item['required_quantity'] = $item['quantity'] * $quantity;
        }
        
        return $recipe;
    }

    // Расчет общего количества ингредиентов на смену
    public function calculate_daily_ingredients($orders) {
        $ingredients = array();
        
        foreach ($orders as $order) {
            $recipe = $this->get_recipe_by_product($order['product_id']);
            
            foreach ($recipe as $item) {
                $ingredient_id = $item['ingredient_id'];
                
                if (!isset($ingredients[$ingredient_id])) {
                    $ingredients[$ingredient_id] = array(
                        'name' => $item['name'],
                        'unit_of_measure' => $item['unit_of_measure'],
                        'quantity' => 0
                    );
                }
                
                $ingredients[$ingredient_id]['quantity'] += $item['quantity'] * $order['quantity'];
            }
        }
        
        return $ingredients;
    }

    // Добавление ингредиента в рецептуру
    public function add_ingredient_to_recipe($product_id, $ingredient_id, $quantity) {
        $data = array(
            'product_id' => $product_id,
            'ingredient_id' => $ingredient_id,
            'quantity' => $quantity
        );
        
        return $this->db->insert('Recipes', $data);
    }

    // Удаление ингредиента из рецептуры
    public function remove_ingredient_from_recipe($recipe_id) {
        $this->db->where('recipe_id', $recipe_id);
        return $this->db->delete('Recipes');
    }

    // Обновление количества ингредиента в рецептуре
    public function update_recipe_quantity($recipe_id, $quantity) {
        $this->db->where('recipe_id', $recipe_id);
        return $this->db->update('Recipes', array('quantity' => $quantity));
    }
}
?>