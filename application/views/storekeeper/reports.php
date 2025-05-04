<div class="container mt-4">
    <h2><?= $page_title ?></h2>

    <hr>

    <h4>📦 Расход сырья (<?= date('d.m.Y', strtotime($start_date)) ?> - <?= date('d.m.Y', strtotime($end_date)) ?>)</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ингредиент</th>
                <th>Ед. изм.</th>
                <th>Расход</th>
                <th>Цена за ед.</th>
                <th>Общая стоимость</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($consumption_report as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td><?= $item['unit_of_measure'] ?></td>
                    <td><?= number_format($item['total_outgoing'], 2) ?></td>
                    <td><?= number_format($item['cost_per_unit'], 2) ?> ₽</td>
                    <td><?= number_format($item['total_cost'], 2) ?> ₽</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h4>🏭 Производство</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Продукт</th>
                <th>Ед. изм.</th>
                <th>Объём производства</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($production_report as $item): ?>
                <tr>
                    <td><?= $item['product_name'] ?></td>
                    <td><?= $item['unit_of_measure'] ?></td>
                    <td><?= number_format($item['total_quantity'], 2) ?></td>
                    <td><?= number_format($item['price'], 2) ?> ₽</td>
                    <td><?= number_format($item['total'], 2) ?> ₽</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h4>📈 Анализ спроса</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Продукт</th>
                <th>Ед. изм.</th>
                <th>Количество</th>
                <th>Средняя цена</th>
                <th>Общая выручка</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demand_report as $item): ?>
                <tr>
                    <td><?= $item['product_name'] ?></td>
                    <td><?= $item['unit_of_measure'] ?></td>
                    <td><?= number_format($item['total_quantity'], 2) ?></td>
                    <td><?= number_format($item['avg_price'], 2) ?> ₽</td>
                    <td><?= number_format($item['total'], 2) ?> ₽</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
