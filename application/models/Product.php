<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Model {

	public function selectAllProducts()
	{
		$sql = "SELECT * FROM `products`";
        $query = $this->db->query($sql);
        return $query -> result_array();
	}
}
