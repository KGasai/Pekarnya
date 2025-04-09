<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="fas fa-clipboard-list"></i> Путевой лист №<?= htmlspecialchars($waybill['waybill_number']) ?>
            <small class="text-muted">от <?= date('d.m.Y', strtotime($waybill['date'])) ?></small>
        </h2>
        <div>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Печать
            </button>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-info-circle"></i> Основная информация
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong><i class="fas fa-car"></i> Автомобиль:</strong><br>
                    <?= htmlspecialchars($waybill['license_plate']) ?> (<?= htmlspecialchars($waybill['model']) ?>)</p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-user"></i> Водитель:</strong><br>
                    <?= htmlspecialchars($waybill['full_name']) ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-info-circle"></i> Статус:</strong><br>
                    <?php 
                    $status_badge = [
                        'planned' => ['badge' => 'badge-primary', 'text' => 'Запланирован'],
                        'in_progress' => ['badge' => 'badge-warning', 'text' => 'В процессе'],
                        'completed' => ['badge' => 'badge-success', 'text' => 'Завершен']
                    ];
                    ?>
                    <span class="badge <?= $status_badge[$waybill['status']]['badge'] ?>">
                        <?= $status_badge[$waybill['status']]['text'] ?>
                    </span>
                    </p>
                </div>
            </div>
            
            <?php if ($waybill['departure_time']): ?>
            <div class="row mt-3">
                <div class="col-md-4">
                    <p><strong><i class="fas fa-sign-out-alt"></i> Время выезда:</strong><br>
                    <?= date('H:i', strtotime($waybill['departure_time'])) ?></p>
                </div>
                <div class="col-md-4">
                    <p><strong><i class="fas fa-sign-in-alt"></i> Время возврата:</strong><br>
                    <?= $waybill['return_time'] ? date('H:i', strtotime($waybill['return_time'])) : 'еще не вернулся' ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-boxes"></i> Товарная часть
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>№</th>
                        <th>Наименование заказчика</th>
                        <th>ИНН заказчика</th>
                        <th>Адрес</th>
                        <th>Наименование продукции</th>
                        <th>Ед. измер.</th>
                        <th>Количество</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['client_name']) ?></td>
                        <td><?= htmlspecialchars($item['inn']) ?></td>
                        <td><?= htmlspecialchars($item['address']) ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= htmlspecialchars($item['unit_of_measure']) ?></td>
                        <td class="text-right"><?= number_format($item['quantity'], 3, ',', ' ') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if ($waybill['status'] == 'planned'): ?>
    <div class="text-right mt-3">
        <button class="btn btn-success" onclick="startDelivery(<?= $waybill['waybill_id'] ?>)">
            <i class="fas fa-truck"></i> Начать доставку
        </button>
    </div>
    <?php elseif ($waybill['status'] == 'in_progress' && !$waybill['return_time']): ?>
    <div class="text-right mt-3">
        <button class="btn btn-warning" onclick="completeDelivery(<?= $waybill['waybill_id'] ?>)">
            <i class="fas fa-check"></i> Завершить доставку
        </button>
    </div>
    <?php endif; ?>
</div>

<script>
function startDelivery(waybillId) {
    if (confirm('Вы уверены, что хотите начать доставку? После подтверждения будет зафиксировано время выезда.')) {
        window.location.href = '<?= site_url('expeditor/start_delivery/') ?>' + waybillId;
    }
}

function completeDelivery(waybillId) {
    if (confirm('Вы уверены, что хотите завершить доставку? После подтверждения будет зафиксировано время возврата.')) {
        window.location.href = '<?= site_url('expeditor/complete_delivery/') ?>' + waybillId;
    }
}
</script>