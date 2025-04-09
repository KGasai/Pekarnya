<div class="container mt-4">
    <h2 class="mb-4">Добро пожаловать, кладовщик!</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-seam"></i> Приход сырья</h5>
                    <p class="card-text">Управление приходом сырья на склад</p>
                    <a href="<?php echo site_url('Storekeeper/prihod'); ?>" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-box-arrow-right"></i> Расход сырья</h5>
                    <p class="card-text">Управление расходом сырья со склада</p>
                    <a href="<?php echo site_url('Storekeeper/rashod'); ?>" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-archive"></i> Управление сырьем</h5>
                    <p class="card-text">Добавление и редактирование сырья</p>
                    <a href="<?php echo site_url('Storekeeper/ingredients'); ?>" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
    </div>
</div>