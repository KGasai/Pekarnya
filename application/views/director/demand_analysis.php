<div class="container">
    <h2 class="mb-4">Анализ спроса на продукцию </h2>
    
    <form method="get" action="<?= site_url('director/demand_analysis') ?>" class="mb-4">
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