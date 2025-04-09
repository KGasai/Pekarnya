<div class="container mt-4">
    <h2 class="mb-4">Расходная накладная <?php echo $consumption['document_number']; ?></h2>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Основная информация</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Дата:</strong> <?php echo date('d.m.Y', strtotime($consumption['date'])); ?></p>
                    <p><strong>Кто выдал:</strong> <?php echo $consumption['issued_by_name']; ?></p>
                    <p><strong>Кому выдано:</strong> <?php echo $consumption['issued_to']; ?></p>
                </div>
                <div class="col-md-6">
                    <?php if($consumption['task_id']): ?>
                    <p><strong>Номер задания:</strong> <?php echo $consumption['task_id']; ?></p>
                    <?php endif; ?>
                    <p><strong>Примечание:</strong> <?php echo $consumption['notes']; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5>Список сырья</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Сырье</th>
                            <th>Количество</th>
                            <th>Ед.изм.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td><?php echo $item['ingredient_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['unit_of_measure']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="<?php echo site_url('Storekeeper/rashod'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Назад к списку
        </a>
    </div>
</div>