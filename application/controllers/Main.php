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

		$this->load->model('Product_model');
		$data['Products'] = $this->Product_model->get_active_products();
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
		if(!empty(($_POST))){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$this -> load->model('User_model');
			$data = $this -> User_model -> login( $username, $password );
			if($data){
				$this->session->set_userdata('role',$data['role']);
				$this->session->set_userdata('user_id',$data['user_id']);
				redirect('Main/index');
			}else{
				echo "Неверный логин или пароль";
			}
		}
	}

	public function insertUser(){
		if(!empty(($_POST))){
			$full_name = $_POST['full_name'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$phone = $_POST['phone'];
			$email = $_POST['email'];
			$inn = $_POST['inn'];
			$adress = $_POST['adress'];

			$data = array(
				'username'=> $username,
				'full_name'=> $full_name,
				'password' => $password,
				'phone' => $phone,
				'email' => $email,
				'inn' => $inn,
				'address' => $adress,
			 );

			$this -> load->model('User_model');
			$this -> User_model -> add_user( $data );
			$data = $this -> User_model -> get_user_by_username_and_password( $username, $password );
			if($data){
				$this->session->set_userdata('role',$data['role']);
				$this->session->set_userdata('user_id',$data['user_id']);
				redirect('Main/index');
			}else{
				echo "Неверный логин или пароль";
			}
		}
	}

	public function order(){
		if(isset(($_POST))){
			$product_id = $_POST["product_id"];
			$start_date = $_POST["start_date"];
			$end_date = $_POST["end_date"];
			$delivery_terms = $_POST["delivery_terms"];
			$payment_terms = $_POST["payment_terms"];

			$this -> load->model('create_contract');
			$contract = array(
				'client_id' => $this->session->userdata('user_id'),
				'start_date' => $start_date,
				'start_date' => $end_date,
				'delivery_terms' => $delivery_terms,
				'payment_terms' => $payment_terms,
);
			$this -> Order_model -> create_order($contract );

			$this -> load->model('Order_model');

			$this -> Order_model -> create_order( );

		}
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect('main/index');
	}
}
