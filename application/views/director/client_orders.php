<div class="container mt-4">
    <h2>Заявки клиентов</h2>


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
</div>