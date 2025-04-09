<<<<<<< HEAD
<div class="container">
    <h2 class="mb-4">Заявки клиента <?= htmlspecialchars($client['name']) ?>, ИНН <?= htmlspecialchars($client['inn']) ?></h2>
    <h4 class="mb-4">Адрес: <?= htmlspecialchars($client['address']) ?>, телефон: <?= htmlspecialchars($client['phone']) ?></h4>
    <h5 class="mb-4">с <?= date('d.m.Y', strtotime($start_date)) ?> по <?= date('d.m.Y', strtotime($end_date)) ?></h5>
    
    <form method="get" action="<?= site_url('director/client_orders/' . $client['user_id']) ?>" class="mb-4">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <label for="start_date">Дата с:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>" required>
            </div>
            <div class="col-md-3 mb-2">
                <label for="end_date">Дата по:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>" required>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Показать</button>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Печать
                </button>
            </div>
        </div>
    </form>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Дата</th>
                        <th>Вид продукции</th>
                        <th>Наименование продукции</th>
                        <th>Ед. измер.</th>
                        <th>Количество</th>
                        <th>Цена (руб)</th>
                        <th>Сумма (руб)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= date('d.m.Y', strtotime($order['order_date'])) ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= htmlspecialchars($order['unit_of_measure']) ?></td>
                        <td class="text-right"><?= number_format($order['quantity'], 3, ',', ' ') ?></td>
                        <td class="text-right"><?= number_format($order['price'], 2, ',', ' ') ?></td>
                        <td class="text-right"><?= number_format($order['total'], 2, ',', ' ') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
=======
<div class="container mt-4">
    <h2>Заявки клиентов</h2>

    <!-- Фильтр по датам -->
    <form method="get" action="<?= base_url('client_orders') ?>" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="start_date" class="form-label">С даты:</label>
                <input type="date" name="start_date" id="start_date" value="<?= $start_date ?>" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">По дату:</label>
                <input type="date" name="end_date" id="end_date" value="<?= $end_date ?>" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Применить</button>
            </div>
        </div>
    </form>

    <?php if (empty($client_orders)): ?>
        <div class="alert alert-info">Нет заказов за выбранный период</div>
    <?php else: ?>
        <?php foreach ($client_orders as $client_data): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        Клиент: <?= htmlspecialchars($client_data['client']->full_name) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>ИНН:</strong> <?= htmlspecialchars($client_data['client']->inn) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Адрес:</strong> <?= htmlspecialchars($client_data['client']->address) ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Телефон:</strong> <?= htmlspecialchars($client_data['client']->phone) ?>
                        </div>
                    </div>

                    <h6 class="mt-3">Контракты:</h6>
                    <ul class="list-group mb-3">
    
                        <?php foreach ($client_data['contracts'] as $contract): ?>
                            <li class="list-group-item">
                                № <?= $contract->contract_id ?> (<?= $contract->start_date ?> - <?= $contract->end_date ?>)
                                - <?= $contract->adress ?> - <?= $contract->payment_terms ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <h6 class="mt-3">Заказы:</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>№ заказа</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>Товары</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($client_data['orders'] as $order): ?>
                                    <tr>
                                        <td><?= $order->order_id ?></td>
                                        <td><?= $order->order_date ?></td>
                                        <td>
                                            <?php
                                            $status_labels = [
                                                'new' => 'Новый',
                                                'in_progress' => 'В работе',
                                                'completed' => 'Завершен',
                                                'canceled' => 'Отменен'
                                            ];
                                            echo $status_labels[$order->status] ?? $order->status;
                                            ?>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php foreach ($order->items as $item): ?>
                                                    <li><?= $item->product_name ?> (<?= $item->quantity ?>
                                                        <?= $item->unit_of_measure ?>)</li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <?= array_reduce($order->items, function ($carry, $item) {
                                                return $carry + $item->quantity;
                                            }, 0) ?>
                                        </td>
                                        <td>
                                            <?= array_reduce($order->items, function ($carry, $item) {
                                                return $carry + ($item->quantity * $item->price);
                                            }, 0) ?> руб.
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
>>>>>>> 1502d7de3e3125ed9597fcd2fdb658b8a5a38855
</div>