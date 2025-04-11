<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production_model extends CI_Model {

    // Создание задания на производство
    public function create_task($date, $created_by, $products) {
        $this->db->trans_start();
        
        $task_data = array(
            'task_date' => $date,
            'shift' => 'morning', // По умолчанию утренняя смена
            'status' => 'planned',
            'created_by' => $created_by
        );
        
        $this->db->insert('ProductionTasks', $task_data);
        $task_id = $this->db->insert_id();
        
        // Добавляем позиции задания
        foreach ($products as $product_id => $quantity) {
            if ($quantity > 0) {
                $this->db->insert('ProductionTaskItems', array(
                    'task_id' => $task_id,
                    'product_id' => $product_id,
                    'quantity' => $quantity
                ));
            }
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $task_id : false;
    }

    // Получение задания по ID
    public function get_task($task_id) {
        $this->db->where('task_id', $task_id);
        $this->db->join('Users', 'Users.user_id = ProductionTasks.created_by');
        $query = $this->db->get('ProductionTasks');
        return $query->row_array();
    }

    // Получение позиций задания
    public function get_task_items($task_id) {
        $this->db->where('task_id', $task_id);
        $this->db->join('Products', 'Products.product_id = ProductionTaskItems.product_id');
        $query = $this->db->get('ProductionTaskItems');
        return $query->result_array();
    }

    // Получение заданий для расхода сырья
    public function get_tasks_for_consumption() {
        $this->db->where('status', 'planned');
        $this->db->order_by('task_date', 'ASC');
        $query = $this->db->get('ProductionTasks');
        return $query->result_array();
    }

    // Обновление статуса задания
    public function update_task_status($task_id, $status) {
        $this->db->where('task_id', $task_id);
        return $this->db->update('ProductionTasks', array('status' => $status));
    }

    // Получение заданий на дату
    public function get_tasks_by_date($date) {
        $this->db->where('task_date', $date);
        $query = $this->db->get('ProductionTasks');
        return $query->result_array();
    }

    // Получение всех продуктов
    public function get_products() {
        $query = $this->db->get('Products');
        return $query->result_array();
    }
}
?>