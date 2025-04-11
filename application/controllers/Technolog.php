<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Technolog extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->model('Recipe_model');
		$this->load->model('Order_model');
		$this->load->model('Production_model');
		$this->load->model('Ingredient_model');
	}

    public function index()
	{ 
		$data['tasks'] = $this->Production_model->get_tasks_by_date(date('Y-m-d'));
        $this->load->view('templates/head.php');
		
		$this->load->model('product_model');
		$data['products'] = $this->product_model->get_active_products();
		$this->load->view('templates/navbar_technolog.php', $data);
		
		$this->load->view('view_index.php');
		$this->load->view('templates/footer.php');
       
	}

	
    public function tasks()
	{ 

		$data['tasks'] = $this->Production_model->get_tasks_by_date(date('Y-m-d'));
		$data['products'] = $this->Product_model->get_active_products();

        $this->load->view('templates/head.php');
		
		$this->load->view('templates/navbar_technolog.php', $data);
		
		$this->load->view('technolog/view_tasks.php');
		$this->load->view('templates/footer.php');
       
	}
	
	public function recipes(){
		$this->load->view('templates/head.php');
		$data['products'] = $this->Product_model->get_active_products();
		$data['ingredients'] = $this->Ingredient_model->get_active_ingredients();
		$data['categories'] = $this->Product_model->get_categories();
		
		$this->load->view('templates/navbar_technolog.php');
		
		$this->load->view('technolog/view_recipes.php', $data);
		$this->load->view('templates/footer.php');
	}

	// AJAX метод для получения рецепта
public function get_recipe($product_id) {
    $this->load->model('Recipe_model');
    $recipe = $this->Recipe_model->get_recipe_by_product($product_id);
    echo json_encode($recipe);
}

// AJAX метод для добавления ингредиента
public function add_ingredient() {
    $this->load->model('Recipe_model');
    
    $product_id = $this->input->post('product_id');
    $ingredient_id = $this->input->post('ingredient_id');
    $quantity = $this->input->post('quantity');
    
    $result = $this->Recipe_model->add_ingredient_to_recipe($product_id, $ingredient_id, $quantity);
    
    echo json_encode(['success' => $result]);
}

public function add_product(){
	
	$this->load->model('Product_model');
	$result = $this->Product_model->add_product($_POST);
    
    echo json_encode(['success' => $result]);

}

public function remove_ingredient($recipe_id) {
    $this->load->model('Recipe_model');
    $result = $this->Recipe_model->remove_ingredient_from_recipe($recipe_id);
    
    echo json_encode(['success' => $result]);
}
	public function calculation(){
		$this->load->model('Product_model');
		$this->load->model('Ingredient_model');

		$data['products'] = $this->Product_model->get_active_products();
		$data['ingredients'] = $this->Ingredient_model->get_active_ingredients();

		$this->load->view('templates/head.php');
		
		$this->load->view('templates/navbar_technolog.php');
		

		$this->load->view('technolog/view_calculation.php', $data);
		$this->load->view('templates/footer.php');
	}

	// AJAX метод для расчета
public function calculate_requirements() {
    $this->load->model('Recipe_model');
    
    $products = $this->input->post('products');
    
    // Преобразуем JSON строку в массив, если нужно
    if (is_string($products)) {
        $products = json_decode($products, true);
    }
    
    $result = $this->Recipe_model->calculate_daily_ingredients($products);
    
    echo json_encode($result);
}

	public function create_task() {
		
		$date = $_POST['date'];
		$products = $_POST['products'];
		
		$created_by = $this->session->userdata('user_id');
		
		if (is_string($products)) {
			$products = json_decode($products, true);
		}
		
		

		$this->load->model('Production_model');
		
		$task_id = $this->Production_model->create_task($date, $created_by, $products);
		
		if ($task_id) {
			echo json_encode(['success' => true, 'task_id' => $task_id]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Ошибка создания задания']);
		}
	}

	public function orders(){
		$this->load->model('Order_model');

		$row_orders = $this->Order_model->get_orders_by_date(date('Y-m-d'));
		$orders = [];
		foreach ($row_orders as $item) {
			$order_id = $item['order_id'];
			
			if (!isset($orders[$order_id])) {
				$orders[$order_id] = [
					'order_id' => $item['order_id'],
					'client_id' => $item['client_id'],
					'contract_id' => $item['contract_id'],
					'order_date' => $item['order_date'],
					'status' => $item['status'],
					'notes' => $item['notes'],
					'client_name' => $item['client_name'],
					'items' => [],
					'total' => 0
				];
			}
			
			$orders[$order_id]['items'][] = [
				'order_item_id' => $item['order_item_id'],
				'product_id' => $item['product_id'],
				'product_name' => $item['product_name'],
				'unit_of_measure' => $item['unit_of_measure'],
				'quantity' => $item['quantity'],
				'price' => $item['price']
			];
			
			$orders[$order_id]['total'] += $item['quantity'] * $item['price'];
		}
		$data['orders'] = array_values($orders);



		$this->load->view('templates/head.php');
		
		$this->load->view('templates/navbar_technolog.php');
		

		$this->load->view('technolog/view_orders.php',$data);
		$this->load->view('templates/footer.php');
	}


	public function get_order_details($order_id) {
		$this->load->model('Order_model');
		
		$order = $this->Order_model->get_order($order_id);
		
		if (!$order) {
			echo json_encode(['success' => false, 'message' => 'Заказ не найден']);
			return;
		}
		
		// Получаем товары в заказе
		$items = $this->Order_model->get_order_items($order_id);
		
		echo json_encode([
			'success' => true,
			'order' => $order,
			'items' => $items
		]);
	}

	public function update_order_status($order_id) {
		$this->load->model('Order_model');
		
		$status = $this->input->post('status');
		$result = $this->Order_model->update_order_status($order_id, $status);
		
		echo json_encode(['success' => $result]);
	}
	public function get_task_details() {
		$task_id = $_GET['task_id'];
		
		$this->load->model('Production_model');
		
		$task = $this->Production_model->get_task($task_id);
		
		if (!$task) {
			echo json_encode(['success' => false, 'message' => 'Задание не найдено']);
			return;
		}
		
		$task['task_date_formatted'] = date('d.m.Y', strtotime($task['task_date']));
		
		// Получаем информацию о создателе
		$this->load->model('User_model');
		$user = $this->User_model->get_user($task['created_by']);
		$task['created_by_name'] = $user ? $user['full_name'] : 'Неизвестно';
		
		$products = $this->Production_model->get_task_items($task_id);
		
		// Рассчитываем требуемые ингредиенты
		$ingredients = [];
		$this->load->model('Recipe_model');
		
		foreach ($products as $product) {
			$recipe = $this->Recipe_model->get_recipe_by_product($product['product_id']);
			
			foreach ($recipe as $item) {
				$ingredient_id = $item['ingredient_id'];
				
				if (!isset($ingredients[$ingredient_id])) {
					$ingredients[$ingredient_id] = [
						'name' => $item['name'],
						'unit_of_measure' => $item['unit_of_measure'],
						'required_quantity' => 0
					];
				}
				
				$ingredients[$ingredient_id]['required_quantity'] += $item['quantity'] * $product['quantity'];
			}
		}		
		
		echo json_encode([
			'success' => true,
			'task' => $task,
			'products' => $products,
			'ingredients' => array_values($ingredients)
		]);
	}
}