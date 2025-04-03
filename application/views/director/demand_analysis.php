<div class="container">
    <h2 class="mb-4">Анализ спроса на продукцию с <?= date('d.m.Y', strtotime($start_date)) ?> по <?= date('d.m.Y', strtotime($end_date)) ?></h2>
    
    <form method="get" action="<?= site_url('director/demand_analysis') ?>" class="mb-4">
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
                        <th>Вид продукции</th>
                        <th>Наименование продукции</th>
                        <th>Ед. измер.</th>
                        <th>Количество</th>
                        <th>Цена (руб)</th>
                        <th>Сумма (руб)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($demand as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['unit_of_measure']) ?></td>
                        <td class="text-right"><?= number_format($item['total_quantity'], 3, ',', ' ') ?></td>
                        <td class="text-right"><?= number_format($item['avg_price'], 2, ',', ' ') ?></td>
                        <td class="text-right"><?= number_format($item['total'], 2, ',', ' ') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>