<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$this->load->view('templates/head.php');
		if ($this->session->userdata('role') == 'technologist') {
			$this->load->view('templates/navbar_technologist.php');
		} else if ($this->session->userdata('role') == 'expeditor') {
			$this->load->view('templates/navbar_expeditor.php');
		} else if ($this->session->userdata('role') == 'director') {
			$this->load->view('templates/navbar_owner.php');
		} else if ($this->session->userdata('role') == 'storekeeper') {
			$this->load->view('templates/navbar_storekeeper.php');
		} else if ($this->session->userdata('role') == 'client') {
			$this->load->view('templates/navbar_client.php');
		} else {
			$this->load->view('templates/navbar.php');
		}

		$this->load->model('product_model');
		$data['products'] = $this->product_model->get_active_products();
		$this->load->view('view_index.php',$data);
		$this->load->view('templates/footer.php');
	}

	public function login(){
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar.php');
		$this->load->view('form_login.php');
		$this->load->view('templates/footer.php');
	}

	public function register(){
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar.php');
		$this->load->view('form_register.php');
		$this->load->view('templates/footer.php');
	}

	public function selectUser(){
		if(isset(($_POST))){
			$this -> load->model('User_model');
			$data = $this -> User_model -> login($_POST);
			if($data){
				$this->session->set_userdata('role',$data[0]['role']);
				$this->session->set_userdata('user_id',$data[0]['user_id']);
				redirect('Main/index');
			}else{
				echo "Неверный логин или пароль";
			}
		}
	}

	public function insertUser(){
		if(!empty(($_POST))){
			$this -> load->model('User_model');
			$this -> User_model -> add_user( $_POST );
			$data = $this -> User_model -> login( $_POST);
			if($data){
				$this->session->set_userdata('role',$data['role']);
				$this->session->set_userdata('user_id',$data['user_id']);
				redirect('Main/index');
			}else{
				echo "Неверный логин или пароль";
			}
		}
	}

	public function doOrder(){
		$this -> load->model('Contract_model');
		$this -> load->model('Product_model');

		$data['contracts'] = $this -> Contract_model -> get_contract($this -> session -> userdata['user_id']);
		$data['product'] = $this -> Product_model -> get_product($_GET["product_id"]);

		$this->load->view('templates/head');
		$this->load->view('templates/navbar_client');
		$this->load->view('view_order', $data);
		$this->load->view('templates/footer');
	}
	public function order(){
		if(isset(($_POST))){
			$product_id = $_POST["product_id"];
			$Date = $_POST["date"];
			$contract_id = $_POST["contract_id"];
			$quantity = $_POST["quantity"];

			$this -> load->model('Order_model');
			$order = array(
				'client_id' => $this->session->userdata('user_id'),
				'contract_id' => $contract_id,
				'order_date' => $Date,
				'quantity' => $quantity,
				'product_id' => $product_id,);
			$this -> Order_model -> create_order($order);
			redirect('main/index');
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('main/index');
	}
}
