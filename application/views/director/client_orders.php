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
</div>