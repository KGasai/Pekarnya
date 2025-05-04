<div class="container">
    <h2 class="mb-4">Результаты поиска путевых листов</h2>
    
    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <span>
                    Период: <?= date('d.m.Y', strtotime($start_date)) ?> - <?= date('d.m.Y', strtotime($end_date)) ?>
                    <?= $status ? " | Статус: " . ucfirst($status) : '' ?>
                </span>
                <a href="<?= site_url('expeditor') ?>" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Назад
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <?php if (!empty($waybills)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>№ путевого листа</th>
                                <th>Дата</th>
                                <th>Автомобиль</th>
                                <th>Водитель</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($waybills as $waybill): ?>
                            <tr>
                                <td><?= $waybill['waybill_number'] ?></td>
                                <td><?= date('d.m.Y', strtotime($waybill['date'])) ?></td>
                                <td><?= $waybill['vehicle_id'] ?></td>
                                <td><?= $waybill['driver_id'] ?></td>
                                <td>
                                    <span <?= 
                                        $waybill['status'] == 'completed' ? 'success' : 
                                        ($waybill['status'] == 'in_progress' ? 'warning' : 'primary') 
                                    ?>">
                                        <?= $waybill['status'] == 'completed' ? 'Завершен' : 
                                           ($waybill['status'] == 'in_progress' ? 'В процессе' : 'Запланирован') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Путевых листов по заданным критериям не найдено</div>
            <?php endif; ?>
        </div>
    </div>
</div>