<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storekeeper extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Ingredient_model');
        $this->load->model('Recipe_model');
        $this->load->model('Report_model');
        $this->load->model('User_model');
        $this->load->model('Consumption_model');

        
    }
    
    public function index() {
        $data['page_title'] = 'Главная';
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/index.php');
        $this->load->view('templates/footer.php');
    }
    
    public function prihod() {
        $data['page_title'] = 'Приход сырья';
        $data['ingredients'] = $this->Ingredient_model->get_active_ingredients();
        $data['receipts'] = $this->Recipe_model->get_receipts();
        
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/prihod.php', $data);
        $this->load->view('templates/footer.php');
    }
    
    public function create_receipt() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('document_number', 'Номер документа', 'required');
        $this->form_validation->set_rules('date', 'Дата', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $receipt_data = array(
                'document_number' => $this->input->post('document_number'),
                'date' => $this->input->post('date'),
                'received_by' => $this->session->userdata('user_id'),
                'notes' => $this->input->post('notes'),
                'total_cost' => 0
            );
            
            $receipt_id = $this->Recipe_model->create_receipt($receipt_data);
            
            if ($receipt_id) {
                $ingredients = $this->input->post('ingredient');
                $quantities = $this->input->post('quantity');
                $prices = $this->input->post('price');
                
                $total_cost = 0;
                
                foreach ($ingredients as $ingredient_id => $value) {
                    if (!empty($quantities[$ingredient_id]) && $quantities[$ingredient_id] > 0) {
                        $cost_per_unit = $prices[$ingredient_id];
                        $item_cost = $quantities[$ingredient_id] * $cost_per_unit;
                        
                        $this->Recipe_model->add_receipt_item(
                            $receipt_id,
                            $ingredient_id,
                            $quantities[$ingredient_id],
                            $cost_per_unit,
                            $item_cost
                        );
                        
                        // Увеличиваем остатки на складе
                        $this->Ingredient_model->increase_stock($ingredient_id, $quantities[$ingredient_id]);
                        
                        $total_cost += $item_cost;
                    }
                }
                
                // Обновляем общую стоимость накладной
                $this->Recipe_model->update_receipt_total($receipt_id, $total_cost);
                
                $this->session->set_flashdata('success', 'Приходная накладная успешно создана');
            } else {
                $this->session->set_flashdata('error', 'Ошибка при создании приходной накладной');
            }
        }
        
        redirect('Storekeeper/prihod');
    }
    
    public function rashod() {
        $data['page_title'] = 'Расход сырья';
        $data['ingredients'] = $this->Ingredient_model->get_ingredients_with_stock();
        $data['consumptions'] = $this->Consumption_model->get_consumptions();
        $data['staff'] = $this->User_model->get_active_users(); // Для выбора сотрудников
        
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/rashod.php', $data);
        $this->load->view('templates/footer.php');
    }

   
    
    public function create_consumption() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('document_number', 'Номер документа', 'required');
        $this->form_validation->set_rules('date', 'Дата', 'required');
        $this->form_validation->set_rules('issued_by', 'Кто выдал', 'required|numeric');
        $this->form_validation->set_rules('issued_to', 'Кому выдано', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $consumption_data = array(
                'document_number' => $this->input->post('document_number'),
                'date' => $this->input->post('date'),
                //'task_id' => $this->input->post('task_id'),
                'issued_by' => $this->input->post('issued_by'),
                'issued_to' => $this->input->post('issued_to'),
                'notes' => $this->input->post('notes')
            );
            
            $consumption_id = $this->Consumption_model->create_consumption($consumption_data);
            
            if ($consumption_id) {
                $ingredients = $this->input->post('ingredient');
                $quantities = $this->input->post('quantity');
                
                foreach ($ingredients as $ingredient_id => $value) {
                    if (!empty($quantities[$ingredient_id]) && $quantities[$ingredient_id] > 0) {
                        $this->Consumption_model->add_consumption_item(
                            $consumption_id,
                            $ingredient_id,
                            $quantities[$ingredient_id]
                        );
                        
                        // Уменьшаем остатки на складе
                        $this->Ingredient_model->reduce_stock($ingredient_id, $quantities[$ingredient_id]);
                    }
                }
                
                $this->session->set_flashdata('success', 'Расходная накладная успешно создана');
            } else {
                $this->session->set_flashdata('error', 'Ошибка при создании расходной накладной');
            }
        }
        
        redirect('Storekeeper/rashod');
    }
    
    public function view_consumption($consumption_id) {
        $data['page_title'] = 'Просмотр расходной накладной';
        $data['consumption'] = $this->Consumption_model->get_consumption($consumption_id);
        $data['items'] = $this->Consumption_model->get_consumption_items($consumption_id);
        $data['issued_by_user'] = $this->User_model->get_user($data['consumption']['issued_by']);
        
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/view_consumption.php', $data);
        $this->load->view('templates/footer.php');
    }
    
    public function ingredients() {
        $data['page_title'] = 'Управление сырьем';
        $data['ingredients'] = $this->Ingredient_model->get_active_ingredients();
        $data['low_stock'] = $this->Ingredient_model->get_ingredients_below_min_stock();
        
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/ingredients.php', $data);
        $this->load->view('templates/footer.php');
    }
    
    public function reports() {
        $data['page_title'] = 'Отчеты';
    
        // Получение даты из POST или текущего месяца
        $start_date = $this->input->post('start_date') ?? date('Y-m-01');
        $end_date = $this->input->post('end_date') ?? date('Y-m-t');
    
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
    
        $data['consumption_report'] = $this->Report_model->get_ingredient_consumption($start_date, $end_date);
        $data['production_report'] = $this->Report_model->get_production_report($start_date, $end_date);
        $data['demand_report'] = $this->Report_model->get_demand_analysis($start_date, $end_date);
    
        $this->load->view('templates/head.php', $data);
        $this->load->view('templates/navbar_storekeeper.php');
        $this->load->view('storekeeper/reports.php', $data);
        $this->load->view('templates/footer.php');
    }
    

   
    
    // Дополнительные методы для обработки форм
    public function add_ingredient() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('name', 'Название', 'required');
        $this->form_validation->set_rules('unit', 'Единица измерения', 'required');
        $this->form_validation->set_rules('min_stock', 'Минимальный запас', 'numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'unit_of_measure' => $this->input->post('unit'),
                'min_stock' => $this->input->post('min_stock'),
                'current_stock' => 0,
                'is_active' => 1
            );
            
            if ($this->Ingredient_model->add_ingredient($data)) {
                $this->session->set_flashdata('success', 'Сырье успешно добавлено');
            } else {
                $this->session->set_flashdata('error', 'Ошибка при добавлении сырья');
            }
        }
        
        redirect('Storekeeper/ingredients');
    }
    
    public function process_receipt() {
        // Обработка приходной накладной
        $post_data = $this->input->post();
        
        $receipt_data = array(
            'date' => date('Y-m-d H:i:s'),
            'received_by' => $this->session->userdata('user_id'),
            'total_cost' => 0,
            'notes' => $post_data['notes']
        );
        
        $receipt_id = $this->Ingredient_model->create_receipt($receipt_data);
        
        if ($receipt_id) {
            $total_cost = 0;
            
            foreach ($post_data['ingredient'] as $ingredient_id => $quantity) {
                if ($quantity > 0) {
                    $ingredient = $this->Ingredient_model->get_ingredient($ingredient_id);
                    $cost_per_unit = $post_data['price'][$ingredient_id];
                    $item_cost = $quantity * $cost_per_unit;
                    
                    $this->Ingredient_model->add_receipt_item(
                        $receipt_id,
                        $ingredient_id,
                        $quantity,
                        $cost_per_unit,
                        $item_cost
                    );
                    
                    // Обновляем остатки
                    $this->Ingredient_model->update_stock($ingredient_id, $quantity);
                    
                    $total_cost += $item_cost;
                }
            }
            
            // Обновляем общую стоимость накладной
            $this->Ingredient_model->update_receipt_total($receipt_id, $total_cost);
            
            $this->session->set_flashdata('success', 'Приходная накладная успешно создана');
        } else {
            $this->session->set_flashdata('error', 'Ошибка при создании накладной');
        }
        
        redirect('Storekeeper/prihod');
    }
}