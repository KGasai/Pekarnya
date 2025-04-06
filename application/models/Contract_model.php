<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Получение всех активных договоров
    public function get_active_contracts() {
        $this->db->where('is_active', 1);
        $this->db->join('Users', 'Users.user_id = Contracts.client_id');
        $query = $this->db->get('Contracts');
        return $query->result_array();
    }

    // Получение договора по ID
    public function get_contract($contract_id) {
        $this->db->where('contract_id', $contract_id);
        $this->db->join('Users', 'Users.user_id = Contracts.client_id');
        $query = $this->db->get('Contracts');
        return $query->row_array();
    }

    // Получение договоров клиента
    public function get_client_contracts($client_id) {
        $this->db->where('client_id', $client_id);
        $this->db->where('is_active', 1);
        return $this->db->get('Contracts')->result();
    }

    // Создание нового договора
    public function create_contract($data) {
        return $this->db->insert('Contracts', $data);
    }

    // Обновление договора
    public function update_contract($contract_id, $data) {
        $this->db->where('contract_id', $contract_id);
        return $this->db->update('Contracts', $data);
    }

    // Деактивация договора
    public function deactivate_contract($contract_id) {
        $this->db->where('contract_id', $contract_id);
        return $this->db->update('Contracts', array('is_active' => 0));
    }

    // Проверка существования договора с таким номером и датой
    public function contract_exists($contract_number, $contract_date) {
        $this->db->where('contract_number', $contract_number);
        $this->db->where('contract_date', $contract_date);
        $query = $this->db->get('Contracts');
        return $query->num_rows() > 0;
    }
}
?>