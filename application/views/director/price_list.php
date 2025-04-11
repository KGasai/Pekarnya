<div class="container">
    <h2 class="mb-4">Прайс-лист</h2>
    
    <div class="text-right mb-3">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Печать
        </button>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Вид продукции</th>
                        <th>Наименование продукции</th>
                        <th>Ед. измер.</th>
                        <th>Цена (руб)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['unit_of_measure']) ?></td>
                        <td class="text-right"><?= number_format($product['price'], 2, ',', ' ') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>