<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Получение всех активных автомобилей
    public function get_active_vehicles() {
        $this->db->where('is_active', 1);
        $query = $this->db->get('DeliveryVehicles');
        return $query->result_array();
    }

    // Получение автомобиля по ID
    public function get_vehicle($vehicle_id) {
        $this->db->where('vehicle_id', $vehicle_id);
        $query = $this->db->get('DeliveryVehicles');
        return $query->row_array();
    }

    // Добавление нового автомобиля
    public function add_vehicle($data) {
        return $this->db->insert('DeliveryVehicles', $data);
    }

    // Обновление данных автомобиля
    public function update_vehicle($vehicle_id, $data) {
        $this->db->where('vehicle_id', $vehicle_id);
        return $this->db->update('DeliveryVehicles', $data);
    }

    // Деактивация автомобиля
    public function deactivate_vehicle($vehicle_id) {
        $this->db->where('vehicle_id', $vehicle_id);
        return $this->db->update('DeliveryVehicles', array('is_active' => 0));
    }

    // Проверка существования автомобиля с таким номером
    public function license_plate_exists($license_plate) {
        $this->db->where('license_plate', $license_plate);
        $query = $this->db->get('DeliveryVehicles');
        return $query->num_rows() > 0;
    }

    // Получение маршрутов автомобиля
    public function get_vehicle_routes($vehicle_id) {
        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->join('Users', 'Users.user_id = DeliveryRoutes.client_id');
        $this->db->order_by('delivery_order', 'ASC');
        $query = $this->db->get('DeliveryRoutes');
        return $query->result_array();
    }
}
?>