<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Создание путевого листа
    public function create_waybill($data) {
        return $this->db->insert('Waybills', $data) ? $this->db->insert_id() : false;
    }

    // Добавление позиции в путевой лист
    public function add_waybill_item($waybill_id, $order_id, $quantity) {
        $data = array(
            'waybill_id' => $waybill_id,
            'order_id' => $order_id,
            'product_id' => $this->get_order_product($order_id),
            'quantity' => $quantity
        );
        
        return $this->db->insert('WaybillItems', $data);
    }

    // Получение продукта из заказа
    private function get_order_product($order_id) {
        $this->db->where('order_id', $order_id);
        $query = $this->db->get('OrderItems');
        $result = $query->row_array();
        return $result ? $result['product_id'] : null;
    }

    // Получение путевого листа по ID
    public function get_waybill($waybill_id) {
        $this->db->where('waybill_id', $waybill_id);
        $this->db->join('DeliveryVehicles', 'DeliveryVehicles.vehicle_id = Waybills.vehicle_id');
        $this->db->join('Users', 'Users.user_id = Waybills.driver_id');
        $query = $this->db->get('Waybills');
        return $query->row_array();
    }

    // Получение позиций путевого листа
    public function get_waybill_items($waybill_id) {
        $this->db->where('waybill_id', $waybill_id);
        $this->db->join('Orders', 'Orders.order_id = WaybillItems.order_id');
        $this->db->join('OrderItems', 'OrderItems.order_id = WaybillItems.order_id');
        $this->db->join('Products', 'Products.product_id = WaybillItems.product_id');
        $this->db->join('Users', 'Users.user_id = Orders.client_id');
        $query = $this->db->get('WaybillItems');
        return $query->result_array();
    }

    // Получение последнего номера путевого листа с заданным префиксом
    public function get_last_waybill_number($prefix) {
        $this->db->like('waybill_number', $prefix, 'after');
        $this->db->order_by('waybill_number', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('Waybills');
        $result = $query->row_array();
        return $result ? $result['waybill_number'] : null;
    }

    // Обновление статуса путевого листа
    public function update_waybill_status($waybill_id, $status) {
        $this->db->where('waybill_id', $waybill_id);
        return $this->db->update('Waybills', array('status' => $status));
    }

    // Обновление времени отправки/возврата
    public function update_waybill_time($waybill_id, $field, $time) {
        $this->db->where('waybill_id', $waybill_id);
        return $this->db->update('Waybills', array($field => $time));
    }
<<<<<<< HEAD
=======

     // Поиск накладных
    public function search_waybills($start_date, $end_date, $status = null) {
        $this->db->select('*');
        $this->db->from('Waybills');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        
        if ($status) {
            $this->db->where('status', $status);
        }
        
        $this->db->order_by('date', 'DESC');
        $this->db->order_by('waybill_number', 'DESC');
        
        return $this->db->get()->result_array();
    }
>>>>>>> 1502d7de3e3125ed9597fcd2fdb658b8a5a38855
}
?>