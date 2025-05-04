<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expeditor extends CI_Controller {
    public function index() {
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_expeditor');
        $this->load->view('expeditor/dashboard');
        $this->load->view('templates/footer');
    }

    // Создание путевого листа
    public function create_waybill($date = null) {
        $this->load->model('User_model');
        $this->load->model('Vehicle_model');
        $this->load->model('Order_model');
        $date = $date ?? date('Y-m-d');
        
        $data['orders'] = $this->Order_model->get_orders_for_delivery();
        $data['vehicles'] = $this->Vehicle_model->get_active_vehicles();
        $data['drivers'] = $this->User_model->get_users_by_role('driver');
        $data['date'] = $date;

        $this->load->view('templates/head');
        $this->load->view('templates/navbar_expeditor');
        $this->load->view('expeditor/create_waybill', $data);
        $this->load->view('templates/footer');
    }

    // Сохранение путевого листа
    public function save_waybill() {
        $this->load->model('Delivery_model');
        $this->form_validation->set_rules('vehicle_id', 'Автомобиль', 'required');
        $this->form_validation->set_rules('driver_id', 'Водитель', 'required');
        $this->form_validation->set_rules('date', 'Дата', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            $this->create_waybill($this->input->post('date'));
        } else {
            $waybill_data = array(
                'waybill_number' => $this->generate_waybill_number(),
                'date' => $this->input->post('date'),
                'vehicle_id' => $this->input->post('vehicle_id'),
                'driver_id' => $this->input->post('driver_id'),
                'expeditor_id' => $this->session->userdata('user_id'),
                'status' => 'planned'
            );
            
            $waybill_id = $this->Delivery_model->create_waybill($waybill_data);
            
            if ($waybill_id) {
                $orders = $this->input->post('orders');
                foreach ($orders as $order_id => $quantity) {
                    if ($quantity > 0) {
                        $this->Delivery_model->add_waybill_item($waybill_id, $order_id, $quantity);
                    }
                }
                
                $this->session->set_flashdata('success', 'Путевой лист успешно создан');
                redirect('expeditor/view_waybill/' . $waybill_id);
            } else {
                $this->session->set_flashdata('error', 'Ошибка при создании путевого листа');
                redirect('expeditor/create_waybill/' . $this->input->post('date'));
            }
        }
    }

    // Просмотр путевого листа
    public function view_waybill($waybill_id) {
        $this->load->model('Delivery_model');

        $data['waybill'] = $this->Delivery_model->get_waybill($waybill_id);
        $data['items'] = $this->Delivery_model->get_waybill_items($waybill_id);
        
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_expeditor');
        $this->load->view('expeditor/view_waybill', $data);
        $this->load->view('templates/footer');
    }

    // Генерация номера путевого листа
    private function generate_waybill_number() {
        $this->load->model('Delivery_model');
        $prefix = 'WB-' . date('Ymd') . '-';
        $last_number = $this->Delivery_model->get_last_waybill_number($prefix);
        $next_number = $last_number ? intval(substr($last_number, strlen($prefix))) + 1 : 1;
        
        return $prefix . str_pad($next_number, 4, '0', STR_PAD_LEFT);
    }

// Генерация номера путевого листа
    public function search_waybills() {
        $this->load->model('Delivery_model');
        
        $start_date = $this->input->get('start_date') ?? date('Y-m-01');
        $end_date = $this->input->get('end_date') ?? date('Y-m-d');
        $status = $this->input->get('status') ?? null;
        
        $data['waybills'] = $this->Delivery_model->search_waybills($start_date, $end_date, $status);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['status'] = $status;
        
        $this->load->view('templates/head');
        $this->load->view('templates/navbar_expeditor');
        $this->load->view('expeditor/search_results', $data);
        $this->load->view('templates/footer');
    }
}
?>