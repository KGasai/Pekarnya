<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client_model extends CI_Model {

    // Получение всех активных клиентов
    public function get_active_clients() {
        $query = $this->db->get('Clients');
        return $query->result_array();
    }

    // Получение клиента по ID
    public function get_client($client_id) {
        $this->db->where('user_id', $client_id);
        $this->db->where('role', 'client');
        $query = $this->db->get('Users');
        return $query->row_array();
    }

    // Добавление нового клиента
    public function add_client($data) {
        $data['role'] = 'client';
        $data['is_client'] = 1;
        return $this->db->insert('Users', $data);
    }

    // Обновление данных клиента
    public function update_client($client_id, $data) {
        $this->db->where('user_id', $client_id);
        $this->db->where('role', 'client');
        return $this->db->update('Users', $data);
    }

    // Деактивация клиента
    public function deactivate_client($client_id) {
        $this->db->where('user_id', $client_id);
        $this->db->where('role', 'client');
        return $this->db->update('Users', array('is_active' => 0));
    }
}
?>