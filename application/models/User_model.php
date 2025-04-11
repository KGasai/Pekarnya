<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Проверка авторизации пользователя
    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('is_active', 1);
        $query = $this->db->get('Users');
        
        if ($query->num_rows() == 1) {
            $user = $query->row_array();
            
            if ($user['password'] === $password) {
                return $user;
            }
        }
        
        return $user;
    }
 
    // Получение пользователя по ID
    public function get_user($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('Users');
        return $query->row_array();
    }

    // Получение пользователей по роли
    public function get_users_by_role($role) {
        $this->db->where('role', $role);
        $this->db->where('is_active', 1);
        $query = $this->db->get('Users');
        return $query->result_array();
    }

    // Получение всех активных пользователей
    public function get_active_users() {
        $this->db->where('is_active', 1);
        $query = $this->db->get('Users');
        return $query->result_array();
    }

    // Добавление нового пользователя
    public function add_user($data) {
        return $this->db->insert('Users', $data);
    }

    // Обновление данных пользователя
    public function update_user($user_id, $data) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('Users', $data);
    }

    // Деактивация пользователя
    public function deactivate_user($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->update('Users', array('is_active' => 0));
    }
}
?>