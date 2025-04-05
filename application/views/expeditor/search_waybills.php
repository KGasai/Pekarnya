<div class="container">
    <h2 class="mb-4">
        <i class="fas fa-search"></i> Поиск путевых листов
        <small class="text-muted">с <?= date('d.m.Y', strtotime($start_date)) ?> по <?= date('d.m.Y', strtotime($end_date)) ?></small>
    </h2>
    
    <form method="get" action="<?= site_url('expeditor/search_waybills') ?>" class="mb-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <i class="fas fa-filter"></i> Параметры поиска
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-3 mb-2">
                        <label for="start_date">Дата с:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="end_date">Дата по:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="status">Статус:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Все</option>
                            <option value="planned" <?= $status == 'planned' ? 'selected' : '' ?>>Запланированные</option>
                            <option value="in_progress" <?= $status == 'in_progress' ? 'selected' : '' ?>>В процессе</option>
                            <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Завершенные</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Поиск
                        </button>
                        <a href="<?= site_url('expeditor/search_waybills') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Сброс
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <?php if (!empty($waybills)): ?>
    <div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-list"></i> Результаты поиска (<?= count($waybills) ?>)
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>№ путевого листа</th>
                        <th>Дата</th>
                        <th>Автомобиль</th>
                        <th>Водитель</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($waybills as $waybill): ?>
                    <tr>
                        <td><?= htmlspecialchars($waybill['waybill_number']) ?></td>
                        <td><?= date('d.m.Y', strtotime($waybill['date'])) ?></td>
                        <td><?= htmlspecialchars($waybill['license_plate']) ?> (<?= htmlspecialchars($waybill['model']) ?>)</td>
                        <td><?= htmlspecialchars($waybill['full_name']) ?></td>
                        <td>
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
                        </td>
                        <td>
                            <a href="<?= site_url('expeditor/view_waybill/' . $waybill['waybill_id']) ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Просмотр
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> По заданным критериям путевых листов не найдено
    </div>
    <?php endif; ?>
</div>