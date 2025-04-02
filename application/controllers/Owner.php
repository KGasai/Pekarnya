<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Owner extends CI_Controller {

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

    public function priceList()
	{
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar_owner.php');
        $this -> load -> model('Product');
        $data['Products'] = $this ->Product ->selectAllProducts();
		$this->load->view('view_priceList.php', $data);
		$this->load->view('templates/footer.php');
	}

    public function listClients()
	{
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar_owner.php');
        $this -> load -> model('Product');
        $data['Clients'] = $this ->Product ->selectAllProducts();
		$this->load->view('view_listClients.php', $data);
		$this->load->view('templates/footer.php');
	}

    public function printingMaterialCosts()
	{
		$this->load->view('templates/head.php');
		$this->load->view('templates/navbar_owner.php');
        $this -> load -> model('Product');
        $data['Clients'] = $this ->Product ->selectAllProducts();
		$this->load->view('view_printingMaterialCosts.php');
		$this->load->view('templates/footer.php');
	}

    // public function otchets()
	// {
	// 	$this->load->view('templates/head.php');
	// 	$this->load->view('templates/navbar_owner.php');
	// 	$this->load->view('view_otchets.php');
	// 	$this->load->view('templates/footer.php');
	// }
    // public function applecationsOfClients()
	// {
	// 	$this->load->view('templates/head.php');
	// 	$this->load->view('templates/navbar_owner.php');
	// 	$this->load->view('view_applecationsOfClients.php');
	// 	$this->load->view('templates/footer.php');
	// }

    // public function productDemand()
	// {
	// 	$this->load->view('templates/head.php');
	// 	$this->load->view('templates/navbar_owner.php');
	// 	$this->load->view('view_productDemand.php');
	// 	$this->load->view('templates/footer.php');
	// }
}
