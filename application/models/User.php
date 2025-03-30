<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Model {

	public function login($username, $password )
	{
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $query = $this->db->query($sql);
        return $query -> result_array();
	}
}
