<div class="container mt-4">
    <h2>Мои заказы</h2>


    <div class="card">
        <div class="card-body">
            <?php if (isset($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Дата</th>
                                <th>Номер</th>
                                <th>Статус</th>
                                <th>Товар</th>
                                <th>Цена</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <td><?= $order['order_date'];?></td>
                                <td><?= $order['contract_number'];?></td>
                                <td><?= $order['status'];?></td>
                                <td><?= $order['name'];?></td>
                                <td><?= $order['price'];?></td>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    У вас пока нет заказов.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>