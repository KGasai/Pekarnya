<div class="container mt-4">
    <h2>Производственные задания</h2>
    
    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Задания на <?= date('d.m.Y') ?></span>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">
                    Создать задание
                </button>
            </div>
        </div>
        
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Дата</th>
                        <th>Смена</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?= $task['task_id'] ?></td>
                        <td><?= date('d.m.Y', strtotime($task['task_date'])) ?></td>
                        <td><?= $task['shift'] == 'morning' ? 'Утренняя' : 'Вечерняя' ?></td>
                        <td>
                            <span class="badge bg-<?= $task['status'] == 'completed' ? 'success' : ($task['status'] == 'in_progress' ? 'warning' : 'secondary') ?>">
                                <?= $task['status'] == 'completed' ? 'Завершено' : ($task['status'] == 'in_progress' ? 'В работе' : 'Запланировано') ?>
                            </span>
                        </td>
                        <td>
                        <button class="btn btn-sm btn-info view-task-btn" 
                                    data-taskid="<?= $task['task_id'] ?>" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#viewTaskModal">
                                Просмотр
                            </button>                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Модальное окно создания задания -->
<div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTaskModalLabel">Новое производственное задание</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createTaskForm">
                    <div class="mb-3">
                        <label for="taskDate" class="form-label">Дата</label>
                        <input type="date" class="form-control" id="taskDate" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="taskShift" class="form-label">Смена</label>
                        <select class="form-select" id="taskShift">
                            <option value="morning">Утренняя</option>
                            <option value="evening">Вечерняя</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Продукция</label>
                        <div id="productsList">
                            <!-- Первая строка с продуктом -->
                            <div class="product-row mb-2">
                                <div class="row g-2">
                                    <div class="col-md-8">
                                        <select class="form-select product-select" required>
                                            <option value="" selected disabled>Выберите продукт</option>
                                            <?php foreach ($products as $product): ?>
                                            <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control product-quantity" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-product" disabled>
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addProductBtn">
                            <i class="bi bi-plus"></i> Добавить продукт
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="createTaskBtn">Создать</button>
            </div>
        </div>
    </div>
</div>
<!-- Модальное окно просмотра задания -->
<div class="modal fade" id="viewTaskModal" tabindex="-1" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTaskModalLabel">Детали задания #<span id="taskId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Дата:</strong> <span id="taskDateView"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Смена:</strong> <span id="taskShiftView"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Статус:</strong> <span id="taskStatusView" class="badge"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Создано:</strong> <span id="taskCreatedBy"></span></p>
                    </div>
                </div>
                
                <h5>Продукция:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Продукт</th>
                            <th>Количество</th>
                            <th>Ед. измерения</th>
                        </tr>
                    </thead>
                    <tbody id="taskProductsView">
                        <!-- Данные будут загружаться через AJAX -->
                    </tbody>
                </table>
                
                <h5 class="mt-3">Требуемое сырье:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ингредиент</th>
                            <th>Количество</th>
                            <th>Ед. измерения</th>
                        </tr>
                    </thead>
                    <tbody id="taskIngredientsView">
                        <!-- Данные будут загружаться через AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function() {
    $('#addProductBtn').click(function() {
        const newRow = `
        <div class="product-row mb-2">
            <div class="row g-2">
                <div class="col-md-8">
                    <select class="form-select product-select" required>
                        <option value="" selected disabled>Выберите продукт</option>
                        <?php foreach ($products as $product): ?>
                        <option value="<?= $product['product_id'] ?>"><?= $product['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control product-quantity" min="1" value="1" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-product">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>`;
        $('#productsList').append(newRow);
        
        $('.product-row .remove-product').prop('disabled', false);
    });
    
    $('#productsList').on('click', '.remove-product', function() {
        const rows = $('.product-row');
        if (rows.length > 1) {
            $(this).closest('.product-row').remove();
            
            if ($('.product-row').length === 1) {
                $('.remove-product').prop('disabled', true);
            }
        }
    });
    
    $('#createTaskBtn').click(function() {
        const taskData = {
            date: $('#taskDate').val(),
            shift: $('#taskShift').val(),
            products: []
        };
        
        $('.product-row').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.product-quantity').val();
            
            if (productId && quantity) {
                taskData.products.push({
                    product_id: productId,
                    quantity: quantity
                });
            }
        });
        
        if (!taskData.date || !taskData.shift || taskData.products.length === 0) {
            alert('Заполните все обязательные поля!');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("Technolog/create_task") ?>',
            method: 'POST',
            data: taskData,
            success: function(response) {
                if (response) {
                    alert('Задание успешно создано!');
                    $('#newTaskModal').modal('hide');
                    location.reload(); // Обновляем страницу
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function() {
                alert('Ошибка сервера');
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.view-task-btn', function() {
        const taskId = $(this).data('taskid');
        loadTaskDetails(taskId);
    });
    
    function loadTaskDetails(taskId) {
        $.ajax({
            url: '<?= base_url("Technolog/get_task_details") ?>',
            method: 'GET',
            data: { task_id: taskId },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {

                    $('#taskId').text(response.task.task_id);
                    $('#taskDateView').text(response.task.task_date_formatted);
                    $('#taskShiftView').text(response.task.shift == 'morning' ? 'Утренняя' : 'Вечерняя');
                    $('#taskCreatedBy').text(response.task.created_by_name);
                    
                    // Устанавливаем статус
                    const statusBadge = $('#taskStatusView');
                    statusBadge.removeClass().addClass('badge');
                    
                    if (response.task.status == 'completed') {
                        statusBadge.addClass('bg-success').text('Завершено');
                    } else if (response.task.status == 'in_progress') {
                        statusBadge.addClass('bg-warning').text('В работе');
                    } else {
                        statusBadge.addClass('bg-secondary').text('Запланировано');
                    }
                    
                    // Заполняем список продуктов
                    const productsTable = $('#taskProductsView');
                    productsTable.empty();
                    
                    response.products.forEach(function(product) {
                        productsTable.append(`
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${product.unit_of_measure}</td>
                            </tr>
                        `);
                    });
                    
                    // Заполняем список ингредиентов
                    const ingredientsTable = $('#taskIngredientsView');
                    ingredientsTable.empty();
                    
                    response.ingredients.forEach(function(ingredient) {
                        ingredientsTable.append(`
                            <tr>
                                <td>${ingredient.name}</td>
                                <td>${ingredient.required_quantity}</td>
                                <td>${ingredient.unit_of_measure}</td>
                            </tr>
                        `);
                    });
                } else {
                    alert('Ошибка загрузки данных: ' + response.message);
                }
            },
            error: function() {
                alert('Ошибка сервера при загрузке данных');
            }
        });
    }
    
});
</script>