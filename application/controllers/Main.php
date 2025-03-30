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
		} else if ($this->session->userdata('role') == 'owner') {
			$this->load->view('templates/navbar_owner.php');
		} else if ($this->session->userdata('role') == 'storekeeper') {
			$this->load->view('templates/navbar_storekeeper.php');
		} else {
			$this->load->view('templates/navbar.php');
		}
		$this->load->view('view_index.php');
		$this->load->view('templates/footer.php');
	}

	public function login(){
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar.php');
		$this->load->view('form_login.php');
		$this->load->view('templates/footer.php');
	}

	public function selectUser(){
		if(!empty(($_POST))){
			$username = $_POST['username'];
			$password = $_POST['password'];
			$this -> load->model('user');
			$data = $this -> user -> login( $username, $password )[0];
			if($data){
				$this->session->set_flashdata('role',$data['role']);
				redirect('Main/index');
			}else{
				echo "Неверный логин или пароль";
			}
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('main/index');
	}
}
