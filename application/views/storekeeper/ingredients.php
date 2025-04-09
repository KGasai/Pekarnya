<div class="container mt-4">
    <h2 class="mb-4">Управление сырьем</h2>
    
    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Добавить новое сырье</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('Storekeeper/add_ingredient'); ?>" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Название</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Единица измерения</label>
                            <input type="text" class="form-control" id="unit" name="unit" required>
                        </div>
                        <div class="mb-3">
                            <label for="min_stock" class="form-label">Минимальный запас</label>
                            <input type="number" class="form-control" id="min_stock" name="min_stock" step="0.01">
                        </div>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Список сырья</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Ед.изм.</th>
                                    <th>Остаток</th>
                                    <th>Мин.запас</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ingredients as $ingredient): ?>
                                <tr>
                                    <td><?php echo $ingredient['name']; ?></td>
                                    <td><?php echo $ingredient['unit_of_measure']; ?></td>
                                    <td><?php echo $ingredient['current_stock']; ?></td>
                                    <td><?php echo $ingredient['min_stock']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if(!empty($low_stock)): ?>
    <div class="card border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h5>Сырье с низким запасом</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Ед.изм.</th>
                            <th>Остаток</th>
                            <th>Мин.запас</th>
                            <th>Разница</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($low_stock as $ingredient): ?>
                        <tr>
                            <td><?php echo $ingredient['name']; ?></td>
                            <td><?php echo $ingredient['unit_of_measure']; ?></td>
                            <td><?php echo $ingredient['current_stock']; ?></td>
                            <td><?php echo $ingredient['min_stock']; ?></td>
                            <td class="text-danger"><?php echo $ingredient['min_stock'] - $ingredient['current_stock']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>