<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {
    // Создание заказа
    public function create_order($client_id, $contract_id, $order_date, $items, $notes = null) {
        $this->db->trans_start();
        
        // Создаем заказ
        $order_data = array(
            'client_id' => $client_id,
            'contract_id' => $contract_id,
            'order_date' => $order_date,
            'status' => 'new',
            'notes' => '',
        );
        
        $this->db->insert('Orders', $order);
        $order_id = $this->db->insert_id();
        
        // Добавляем позиции заказа
        foreach ($items as $item) {
            $order_item = array(
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            );
            
            $this->db->insert('OrderItems', $order_item);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() ? $order_id : false;
    }

    // Получение заказов на дату
    public function get_orders_by_date($date) {
        $this->db->where('order_date', $date);
        $this->db->where('Orders.status !=', 'canceled');
        $this->db->join('OrderItems', 'OrderItems.order_id = Orders.order_id');
        $this->db->join('Products', 'Products.product_id = OrderItems.product_id');
        $this->db->join('Users', 'Users.user_id = Orders.client_id');
        $this->db->select('Orders.*, OrderItems.*, Products.name as product_name, Products.unit_of_measure, Users.full_name as client_name');
        $query = $this->db->get('Orders');
        
        return $query->result_array();
    }

    // Получение заказов для доставки на дату
    public function get_orders_for_delivery($date) {
        $this->db->where('order_date', $date);
        $this->db->where('Orders.status', 'completed');
        $this->db->join('OrderItems', 'OrderItems.order_id = Orders.order_id');
        $this->db->join('Products', 'Products.product_id = OrderItems.product_id');
        $this->db->join('Users', 'Users.user_id = Orders.client_id');
        $this->db->select('Orders.*, OrderItems.*, Products.name as product_name, Products.unit_of_measure, Users.full_name as client_name, Users.address, Users.inn');
        $query = $this->db->get('Orders');
        
        return $query->result_array();
    } 

    // Получение заказов клиента за период
    public function get_client_orders($client_id, $start_date, $end_date) {
        $this->db->where('client_id', $client_id);
        $this->db->where('order_date >=', $start_date);
        $this->db->where('order_date <=', $end_date);
        $this->db->where('Orders.status !=', 'canceled');
        $this->db->join('OrderItems', 'OrderItems.order_id = Orders.order_id');
        $this->db->join('Products', 'Products.product_id = OrderItems.product_id');
        $this->db->select('Orders.*, OrderItems.*, Products.name as product_name, Products.unit_of_measure, (OrderItems.quantity * OrderItems.price) as total');
        $this->db->order_by('order_date', 'ASC');
        $query = $this->db->get('Orders');
        
        return $query->result_array();
    }

    // Обновление статуса заказа
    public function update_order_status($order_id, $status) {
        $this->db->where('order_id', $order_id);
        return $this->db->update('Orders', array('status' => $status));
    }

    // Получение заказа по ID
    public function get_order($order_id) {
        $this->db->where('order_id', $order_id);
        $this->db->join('Users', 'Users.user_id = Orders.client_id');
        $this->db->join('Contracts', 'Contracts.contract_id = Orders.contract_id');
        $query = $this->db->get('Orders');
        return $query->row_array();
    }

    // Получение позиций заказа
    public function get_order_items($order_id) {
        $this->db->where('order_id', $order_id);
        $this->db->join('Products', 'Products.product_id = OrderItems.product_id');
        $query = $this->db->get('OrderItems');
        return $query->result_array();
    }

    // Получение заказа клиента для личного кабинета
    public function get_order_Client($id_user) {
        $this->db->select('
            Orders.order_date, 
            Contracts.contract_number, 
            Orders.status, 
            Products.name, 
            OrderItems.price
        ');
        
        $this->db->from('Orders');
        $this->db->join('OrderItems', 'Orders.order_id = OrderItems.order_id');
        $this->db->join('Products', 'OrderItems.product_id = Products.product_id');
        $this->db->join('Contracts', 'Orders.contract_id = Contracts.contract_id'); // Предполагаем, что есть связь
        
        $this->db->where('Contracts.client_id', $id_user);
        
        return $this->db->get()->result_array();
    }

}
?>