<div class="container">
    <h2 class="mb-4">Панель экспедитора</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-truck"></i> Сегодняшние доставки
                </div>
                <div class="card-body">
                    <?php if (!empty($today_waybills)): ?>
                        <ul class="list-group">
                            <?php foreach ($today_waybills as $waybill): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= site_url('expeditor/view_waybill/' . $waybill['waybill_id']) ?>">
                                    Путевой лист №<?= $waybill['waybill_number'] ?>
                                </a>
                                <span class="badge badge-<?= 
                                    $waybill['status'] == 'completed' ? 'success' : 
                                    ($waybill['status'] == 'in_progress' ? 'warning' : 'primary') 
                                ?>">
                                    <?= $waybill['status'] == 'completed' ? 'Завершен' : 
                                       ($waybill['status'] == 'in_progress' ? 'В процессе' : 'Запланирован') ?>
                                </span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Нет запланированных доставок на сегодня</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="<?= site_url('expeditor/create_waybill/' . date('Y-m-d')) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Создать новый путевой лист
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-calendar-alt"></i> Ближайшие доставки
                </div>
                <div class="card-body">
                    <?php if (!empty($upcoming_waybills)): ?>
                        <ul class="list-group">
                            <?php foreach ($upcoming_waybills as $waybill): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= date('d.m.Y', strtotime($waybill['date'])) ?></strong><br>
                                    <a href="<?= site_url('expeditor/view_waybill/' . $waybill['waybill_id']) ?>">
                                        Путевой лист №<?= $waybill['waybill_number'] ?>
                                    </a>
                                </div>
                                <span class="badge badge-primary">Запланирован</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Нет запланированных доставок на ближайшие дни</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="fas fa-search"></i> Поиск путевых листов
        </div>
        <div class="card-body">
            <form method="get" action="<?= site_url('expeditor/search_waybills') ?>">
                <div class="form-row">
                    <div class="col-md-3 mb-2">
                        <label for="start_date">Дата с:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= date('Y-m-01') ?>">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="end_date">Дата по:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="status">Статус:</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Все</option>
                            <option value="planned">Запланированные</option>
                            <option value="in_progress">В процессе</option>
                            <option value="completed">Завершенные</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Поиск</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>