<div class="container">
    <h2 class="mb-4">Список клиентов </h2>
    
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
                        <th>Наименование заказчика</th>
                        <th>ИНН заказчика</th>
                        <th>Адрес</th>
                        <th>Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['full_name']) ?></td>
                        <td><?= htmlspecialchars($client['inn']) ?></td>
                        <td><?= htmlspecialchars($client['address']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>