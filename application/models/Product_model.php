<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
    // Получение всех активных продуктов
    public function get_active_products() {
        $this->db->where('is_active', 1);
        $query = $this->db->get('Products');
        return $query->result_array();
    }

    // Получение продукта по ID
    public function get_product($product_id) {
        $this->db->where('product_id', $product_id);
        $this->db->join('ProductCategories', 'ProductCategories.category_id = Products.category_id');
        $query = $this->db->get('Products');
        return $query->result_array();
    }

    // Получение продуктов по категории
    public function get_products_by_category($category_id) {
        $this->db->where('Products.category_id', $category_id);
        $this->db->where('is_active', 1);
        $this->db->join('ProductCategories', 'ProductCategories.category_id = Products.category_id');
        $query = $this->db->get('Products');
        return $query->result_array();
    }

    // Добавление нового продукта
    public function add_product($data) {
        return $this->db->insert('Products', $data);
    }

    // Обновление продукта
    public function update_product($product_id, $data) {
        $this->db->where('product_id', $product_id);
        return $this->db->update('Products', $data);
    }

    // Удаление (деактивация) продукта
    public function delete_product($product_id) {
        $this->db->where('product_id', $product_id);
        return $this->db->update('Products', array('is_active' => 0));
    }

    // Получение всех категорий продуктов
    public function get_categories() {
        $query = $this->db->get('ProductCategories');
        return $query->result_array();
    }

    // Получение категории по ID
    public function get_category($category_id) {
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('ProductCategories');
        return $query->row_array();
    }
}
?>