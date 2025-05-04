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
                    Клиент: <?= $client_data['client']['name']; ?> <br>
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

        <div class="card mb-4">
            <?php
            $contracts = $this->Contract_model->get_client_contracts($client_data['client']['user_id']);
            foreach ($contracts as $contract): ?>
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        № Договора: <?= $contract['contract_id']; ?> <br>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Дата заключения:</strong> <?= $contract['contract_date']; ?> 
                        </div>
                        <div class="col-md-3">
                            <strong>(</strong> <?= $contract['start_date']; ?> <strong>-</strong> <?= $contract['end_date']; ?> <strong>)</strong>
                        </div>
                        <div class="col-md-3">
                            <strong>Вид оплаты::</strong> <?= $contract['payment_terms']; ?> 
                        </div>
                        <div class="col-md-3">
                            <strong>Условия поставки:</strong> <?= $contract['delivery_terms']; ?> 
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div>