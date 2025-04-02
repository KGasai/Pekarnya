<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Model {

	public function selectAllClients()
	{
		$sql = "SELECT * FROM `clients`";
        $query = $this->db->query($sql);
        return $query -> result_array();
	}
}
