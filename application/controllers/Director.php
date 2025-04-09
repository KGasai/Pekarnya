<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Director extends CI_Controller {

    public function index() {
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/dashboard');
        $this->load->view('templates/footer');
    }

    // Прайс-лист
    public function price_list() {
        $this->load->model('Product_model');
        $data['products'] = $this->Product_model->get_active_products();
        
        $this->load->view('templates/head.php');
        $this->load->view('templates/navbar_owner.php');
        $this->load->view('director/price_list.php', $data);
        $this->load->view('templates/footer.php');
    }

    // Список клиентов
    public function clients() {
        $this->load->model('Client_model');
        $data['clients'] = $this->Client_model->get_active_clients();
        
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/clients', $data);
        $this->load->view('templates/footer');
    }

    // Расход сырья за период
    public function ingredient_consumption($start_date = null, $end_date = null) {
        $this->load->model('Report_model');
        $start_date = $start_date ?? date('Y-m-01');
        $end_date = $end_date ?? date('Y-m-d');
        
        $data['consumption'] = $this->Report_model->get_ingredient_consumption($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['title'] = 'Расход сырья';
        
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/ingredient_consumption', $data);
        $this->load->view('templates/footer');
    }
    // Отчет о произведенной продукции
    public function production_report($start_date = null, $end_date = null) {
        $this->load->model('Report_model');
        $start_date = $start_date ?? date('Y-m-01');
        $end_date = $end_date ?? date('Y-m-d');
        
        $data['production'] = $this->Report_model->get_production_report($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/production_report', $data);
        $this->load->view('templates/footer');
    }

    // Заявки клиента
<<<<<<< HEAD
    public function client_orders($client_id = null, $start_date = null, $end_date = null) {
        $this->load->model('Order_model');
        $this->load->model('Client_model');
    
        // Если client_id не передан, перенаправить на список клиентов
        if ($client_id === null) {
            redirect('director/clients');
        }
    
        $start_date = $start_date ?? date('Y-m-01');
        $end_date = $end_date ?? date('Y-m-d');
        
        $data['orders'] = $this->Order_model->get_client_orders($client_id, $start_date, $end_date);
        $data['client'] = $this->Client_model->get_client($client_id);
=======

    public function client_orders($start_date = null, $end_date = null) {
        $this->load->model('Order_model');
        $this->load->model('Client_model');
        $this->load->model('Contract_model');
    
        // Устанавливаем даты по умолчанию (текущий месяц)
        $start_date = $start_date ?? date('Y-m-01');
        $end_date = $end_date ?? date('Y-m-d');
        
        // Получаем всех клиентов
        $clients = $this->Client_model->get_all_clients();
        
        // Для каждого клиента получаем заказы и контракты
        $data['client_orders'] = [];
        foreach ($clients as $client) {
            $orders = $this->Order_model->get_client_orders($client->user_id, $start_date, $end_date);
            $contracts = $this->Contract_model->get_client_contracts($client->user_id);
            
            if (!empty($orders)) {
                $data['client_orders'][] = [
                    'client' => $client,
                    'orders' => $orders,
                    'contracts' => $contracts
                ];
            }
        }
        
>>>>>>> 1502d7de3e3125ed9597fcd2fdb658b8a5a38855
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
    
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/client_orders', $data);
        $this->load->view('templates/footer');
    }

    // Анализ спроса
    public function demand_analysis($start_date = null, $end_date = null) {
        $this->load->model('Report_model');
        $start_date = $start_date ?? date('Y-m-01');
        $end_date = $end_date ?? date('Y-m-d');
        
        $data['demand'] = $this->Report_model->get_demand_analysis($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_owner');
        $this->load->view('director/demand_analysis', $data);
        $this->load->view('templates/footer');
    }
}
?>