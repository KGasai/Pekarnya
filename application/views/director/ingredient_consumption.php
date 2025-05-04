<div class="container">
    <h2 class="mb-4">Расход сырья </h2>

    <form method="get" action="<?= site_url('director/ingredient_consumption') ?>" class="mb-4">
        <button type="button" class="btn btn-secondary" onclick="window.print()">
            <i class="fas fa-print"></i> Печать
        </button>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Наименование сырья</th>
                        <th>Ед. измер.</th>
                        <th>Количество</th>
                        <th>Цена (руб)</th>
                        <th>Сумма (руб)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consumption as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['unit_of_measure']) ?></td>
                            <td class="text-right"><?= number_format($item['total_outgoing'], 3, ',', ' ') ?></td>
                            <td class="text-right"><?= number_format($item['cost_per_unit'], 2, ',', ' ') ?></td>
                            <td class="text-right"><?= number_format($item['total_cost'], 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>