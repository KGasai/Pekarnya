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
        $this->load->model('product_model');
        $this->load->model('contract_model');

        $client_id = $this->session->userdata('user_id');

        if (isset($_POST['dog'])) {
            $contract_id = (int) $_POST['contract_id'];
            $order_date = $_POST['order_date'];
            $items = [];
            foreach ($_POST['product_ids'] as $product_id) {
                $product_id_n = explode(':', $product_id)[0];
                $product_price = explode(':', $product_id)[1];
                $items[] = ['product_id' => $product_id_n, 'quantity' => (int) $_POST['vits'], 'price' => $product_price];
            }


            $this->order_model->create_order($client_id, $contract_id, $order_date, $items);

        }

        $this->load->view('templates/head.php');
        $this->load->view('templates/navbar_client.php');


        $contracts = $this->contract_model->get_client_contracts($client_id);
        $products = $this->product_model->get_active_products();
        $orders = $this->order_model->get_client_orders($client_id, '0000-00-00', '9999-12-31');

        $data = [];
        $data['contracts'] = $contracts;
        $data['products'] = $products;
        $data['orders'] = $orders;
        $this->load->view('clients/view_order.php', $data);
        $this->load->view('templates/footer.php');
    }

}
