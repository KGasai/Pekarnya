<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Client extends CI_Controller
{

    public function index()
    {
        $this->load->view('templates/head.php');

        $this->load->view('templates/navbar_client.php');

        $this->load->model('product_model');
        $data['products'] = $this->product_model->get_active_products();
        $this->load->view('view_index.php', $data);
        $this->load->view('templates/footer.php');
        /*$this -> load->model('Contract_model');
        $client_id = $this->session->userdata('user_id');

        if(isset($_POST['dog'])){
            $this->contract->addContract( $client_id, $_POST);


        }
        $this->load->view('templates/head.php');
        
        if($this->session->userdata('role') == 'client'){
            $this->load->view('templates/client/navbar.php');

        }
        $contracts = $this->contract->getContracts($client_id);
    
        
        $data = []; 
        $data['contracts'] = $contracts;
        $this->load->view('view_index.php', $data);
        $this->load->view('templates/footer.php');
        */
    }

    public function contracts()
    {
        $this->load->model('contract_model');
        $client_id = $this->session->userdata('user_id');
        if (isset($_POST['dog'])) {
            $d = $_POST;
            $d['client_id'] = $client_id;
            unset($d['dog']);

            $this->contract_model->create_contract($d);
        }

        $this->load->view('templates/head.php');
        $this->load->view('templates/navbar_client.php');

        $contracts = $this->contract_model->get_client_contracts($client_id);
        $data['contracts'] = $contracts;
        $this->load->view('clients/view_contracts.php', $data);
        $this->load->view('templates/footer.php');
    }

    public function orders()
    {
        $this->load->model('order_model');

        $client_id = $this->session->userdata('user_id');

        $this->load->view('templates/head.php');
        $this->load->view('templates/navbar_client.php');

        $data['orders'] = $this -> order_model->get_order_Client($client_id);
        $this->load->view('clients/view_order.php', $data);
        $this->load->view('templates/footer.php');
    }

}
