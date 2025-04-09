<div class="container mt-4">
    <h2><i class="bi bi-cart-check"></i> Заказы клиентов</h2>
    
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-date"></i> Заказы на <?= date('d.m.Y') ?></span>
                <div class="btn-group">
                    <button class="btn btn-light btn-sm" id="refreshBtn">
                        <i class="bi bi-arrow-clockwise"></i> Обновить
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>№</th>
                            <th>Клиент</th>
                            <th>Кол-во позиций</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['order_id'] ?></td>
                            <td><?= $order['client_name'] ?></td>
                            <td><?= count($order['items']) ?></td>
                            <td><?= number_format($order['total'], 2, '.', ' ') ?> ₽</td>
                            <td>
                                <span class="badge bg-<?= 
                                    $order['status'] == 'completed' ? 'success' : 
                                    ($order['status'] == 'processing' ? 'warning' : 'secondary')
                                ?>">
                                    <?= 
                                        $order['status'] == 'completed' ? 'Выполнен' : 
                                        ($order['status'] == 'processing' ? 'В работе' : 'Новый')
                                    ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-order-btn" 
                                        data-orderid="<?= $order['order_id'] ?>"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderDetailsModal">
                                    <i class="bi bi-eye"></i> Просмотр
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (empty($orders)): ?>
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> На сегодня заказов нет
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Модальное окно деталей заказа -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Заказ #<span id="orderId"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Клиент:</strong> <span id="clientName"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Дата:</strong> <span id="orderDate"></span></p>
                        <p><strong>Статус:</strong> <span id="orderStatus" class="badge"></span></p>
                    </div>
                </div>
                
                <h5>Состав заказа:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Продукт</th>
                            <th>Кол-во</th>
                            <th>Ед. изм.</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody id="orderItemsTable">
                        <!-- Данные будут загружены через AJAX -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">Итого:</th>
                            <th id="orderTotal"></th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="mb-3">
                    <label for="orderNotes" class="form-label">Примечания:</label>
                    <textarea class="form-control" id="orderNotes" rows="2" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="me-auto" id="statusButtons">
                    <button class="btn btn-sm btn-outline-secondary status-btn" data-status="new">
                        <i class="bi bi-arrow-counterclockwise"></i> Вернуть в "Новые"
                    </button>
                    <button class="btn btn-sm btn-warning status-btn" data-status="processing">
                        <i class="bi bi-gear"></i> В работу
                    </button>
                    <button class="btn btn-sm btn-success status-btn" data-status="completed">
                        <i class="bi bi-check-circle"></i> Завершить
                    </button>
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="printOrderBtn">
                    <i class="bi bi-printer"></i> Печать
                </button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
    // Обновление списка заказов
    $('#refreshBtn').click(function() {
        location.reload();
    });
    
    $(document).on('click', '.view-order-btn', function() {
        const orderId = $(this).data('orderid');
        
        // Получаем данные из атрибутов строки таблицы
        const row = $(this).closest('tr');
        const clientName = row.find('td:eq(1)').text();
        const orderDate = '<?= date("d.m.Y") ?>'; // Используем текущую дату
        
        // Заполняем основную информацию
        $('#orderId').text(orderId);
        $('#clientName').text(clientName);
        $('#orderDate').text(orderDate);
        
        // Загружаем детали заказа через AJAX
        $.ajax({
            url: '<?= base_url("Technolog/get_order_details/") ?>' + orderId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const order = response.order;
                    const items = response.items;
                    
                    // Обновляем статус
                    const statusBadge = $('#orderStatus');
                    statusBadge.removeClass().addClass('badge');
                    
                    if (order.status == 'completed') {
                        statusBadge.addClass('bg-success').text('Выполнен');
                    } else if (order.status == 'processing') {
                        statusBadge.addClass('bg-warning').text('В работе');
                    } else {
                        statusBadge.addClass('bg-secondary').text('Новый');
                    }
                    
                    // Заполняем примечания
                    $('#orderNotes').val(order.notes || 'нет примечаний');
                    
                    // Заполняем таблицу товаров
                    const $tbody = $('#orderItemsTable');
                    $tbody.empty();
                    
                    let total = 0;
                    
                    items.forEach(function(item) {
                        const sum = Number(item.quantity) * Number(item.price);
                        total += sum;
                        
                        $tbody.append(`
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.unit_of_measure}</td>
                                <td>${item.price} ₽</td>
                                <td>${sum.toFixed(2)} ₽</td>
                            </tr>
                        `);
                    });
                    
                    $('#orderTotal').text(total.toFixed(2) + ' ₽');
                    
                    // Настраиваем кнопки статусов
                    $('#statusButtons').data('orderid', order.order_id);
                    $('.status-btn').show();
                    
                    if (order.status == 'completed') {
                        $('.status-btn[data-status="completed"]').hide();
                    } else if (order.status == 'processing') {
                        $('.status-btn[data-status="processing"]').hide();
                    } else {
                        $('.status-btn[data-status="new"]').hide();
                    }
                }
            },
            error: function() {
                alert('Ошибка загрузки данных заказа');
            }
        });
    });
    
    // Изменение статуса заказа
    $(document).on('click', '.status-btn', function() {
        const orderId = $('#statusButtons').data('orderid');
        const status = $(this).data('status');
        
        if (!confirm('Изменить статус заказа?')) return;
        
        $.ajax({
            url: '<?= base_url("Technolog/update_order_status/") ?>' + orderId,
            method: 'POST',
            data: { status: status },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Статус заказа обновлен');
                    $('#orderDetailsModal').modal('hide');
                    location.reload();
                } else {
                    alert('Ошибка при обновлении статуса');
                }
            },
            error: function() {
                alert('Ошибка сервера');
            }
        });
    });
    
    // Печать заказа
    $('#printOrderBtn').click(function() {
        window.print();
    });
    
    // Форматирование даты
    function formatDateTime(datetime) {
        const date = new Date(datetime);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
});
</script>