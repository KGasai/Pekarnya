<div class="container">
    <h2 class="mb-4">Создание путевого листа </h2>
    
    <form method="post" action="<?= site_url('expeditor/save_waybill') ?>">
        <input type="hidden" name="date" value="<?= $date ?>">
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-info-circle"></i> Основная информация
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="vehicle_id"><i class="fas fa-car"></i> Автомобиль</label>
                        <select class="form-control" id="vehicle_id" name="vehicle_id" required>
                            <option value="">Выберите автомобиль</option>
                            <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?= $vehicle['vehicle_id'] ?>">
                                <?= htmlspecialchars($vehicle['license_plate']) ?> (<?= htmlspecialchars($vehicle['model']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="driver_id"><i class="fas fa-user"></i> Водитель</label>
                        <select class="form-control" id="driver_id" name="driver_id" required>
                            <option value="">Выберите водителя</option>
                            <?php foreach ($drivers as $driver): ?>
                            <option value="<?= $driver['user_id'] ?>">
                                <?= htmlspecialchars($driver['full_name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <i class="fas fa-boxes"></i> Заказы на доставку
            </div>
            <div class="card-body">
                <?php if (!empty($orders)): ?>
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Наименование заказчика</th>
                                <th>Дата</th>
                                <th>Наименование продукции</th>
                                <th>Ед. измер.</th>
                                <th>Заказано</th>
                                <th>К выдаче</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['client_name']) ?></td>
                                <td><?= htmlspecialchars($order['order_date']) ?></td>
                                <td><?= htmlspecialchars($order['product_name']) ?></td>
                                <td><?= htmlspecialchars($order['unit_of_measure']) ?></td>
                                <td class="text-right"><?= number_format($order['quantity'], 3, ',', ' ') ?></td>
                                <td class="text-right" style="width: 120px;">
                                    <input type="hidden" name="orders[<?= $order['order_id'] ?>]" value="0">
                                    <input type="number" class="form-control" name="orders[<?= $order['order_id'] ?>]" 
                                           min="0" max="<?= $order['quantity'] ?>" step="0.001" 
                                           value="<?= $order['quantity'] ?>" style="width: 100px;">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Нет заказов для доставки на выбранную дату
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="text-right">
            <a href="<?= site_url('expeditor') ?>" class="btn btn-secondary mr-2">
                <i class="fas fa-arrow-left"></i> Отмена
            </a>
            <button type="submit" class="btn btn-primary" <?= empty($orders) ? 'disabled' : '' ?>>
                <i class="fas fa-save"></i> Сохранить путевой лист
            </button>
        </div>
    </form>
</div>