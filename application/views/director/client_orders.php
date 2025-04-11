<div class="container mt-4">
    <h2>Заявки клиентов</h2>

    <form method="get" action="<?= site_url('director/client_orders') ?>" class="mb-4">
            <div class="col-md-2 mb-2 d-flex align-items-end">
                
                <button type="button" class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Печать
                </button>
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
                        Клиент: <?= $client_data['client']['name']; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>ИНН:</strong> <?= $client_data['client']['inn']; ?>
                        </div>
                        <div class="col-md-4">
                            <strong>Адрес:</strong><?= $client_data['client']['address']; ?> 
                        </div>
                        <div class="col-md-4">
                            <strong>Телефон:</strong> <?= $client_data['client']['phone']; ?> 
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>