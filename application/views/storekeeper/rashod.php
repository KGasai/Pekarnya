<div class="container mt-4">
    <h2 class="mb-4">Расход сырья</h2>
    
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
                    <h5>Создать расходную накладную</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('Storekeeper/create_consumption'); ?>" method="post">
                        <div class="mb-3">
                            <label for="document_number" class="form-label">Номер документа</label>
                            <input type="text" class="form-control" id="document_number" name="document_number" 
                                   value="<?php echo $this->Consumption_model->get_next_document_number(); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Дата</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="issued_by" class="form-label">Кто выдал</label>
                            <select class="form-select" id="issued_by" name="issued_by" required>
                                <option value="">Выберите сотрудника</option>
                                <?php foreach($staff as $user): ?>
                                <option value="<?php echo $user['user_id']; ?>"><?php echo $user['full_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="issued_to" class="form-label">Кому выдано</label>
                            <input type="text" class="form-control" id="issued_to" name="issued_to" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="task_id" class="form-label">Номер задания (если есть)</label>
                            <input type="number" class="form-control" id="task_id" name="task_id">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Список сырья</label>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Сырье</th>
                                            <th>Остаток</th>
                                            <th>Кол-во</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($ingredients as $ingredient): ?>
                                        <tr>
                                            <td>
                                                <?php echo $ingredient['name']; ?>
                                                <input type="hidden" name="ingredient[<?php echo $ingredient['ingredient_id']; ?>]" value="1">
                                            </td>
                                            <td><?php echo $ingredient['current_stock'] . ' ' . $ingredient['unit_of_measure']; ?></td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $ingredient['ingredient_id']; ?>]" 
                                                       class="form-control form-control-sm" min="0" 
                                                       max="<?php echo $ingredient['current_stock']; ?>" step="0.001">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Примечание</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Создать накладную</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>История расходов</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Номер</th>
                                    <th>Дата</th>
                                    <th>Кто выдал</th>
                                    <th>Кому</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($consumptions as $consumption): ?>
                                <tr>
                                    <td><?php echo $consumption['document_number']; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($consumption['date'])); ?></td>
                                    <td><?php echo $consumption['issued_by_name']; ?></td>
                                    <td><?php echo $consumption['issued_to']; ?></td>
                                   
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>