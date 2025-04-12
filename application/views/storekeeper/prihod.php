<div class="container mt-4">
    <h2 class="mb-4">Приход сырья</h2>
    
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
                    <h5>Создать приходную накладную</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('Storekeeper/create_receipt'); ?>" method="post">
                        <div class="mb-3">
                            <label for="document_number" class="form-label">Номер документа</label>
                            <input type="text" class="form-control" id="document_number" name="document_number" 
                                    required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Дата</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                       
                        
                        <div class="mb-3">
                            <label class="form-label">Список сырья</label>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Сырье</th>
                                            <th>Кол-во</th>
                                            <th>Цена за ед.</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($ingredients as $ingredient): ?>
                                        <tr>
                                            <td>
                                                <?php echo $ingredient['name']; ?>
                                                <input type="hidden" name="ingredient[<?php echo $ingredient['ingredient_id']; ?>]" value="1">
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[<?php echo $ingredient['ingredient_id']; ?>]" 
                                                       class="form-control form-control-sm quantity-input" 
                                                       min="0" step="0.001" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="price[<?php echo $ingredient['ingredient_id']; ?>]" 
                                                       class="form-control form-control-sm price-input" 
                                                       min="0" step="0.01" value="0">
                                            </td>
                                            <td class="item-total">0.00</td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                            <td class="total-sum">0.00</td>
                                        </tr>
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
                    <h5>История приходов</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Номер</th>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($receipts as $receipt): ?>
                                <tr>
                                    <td><?php echo $receipt['document_number']; ?></td>
                                    <td><?php echo date('d.m.Y', strtotime($receipt['date'])); ?></td>
                                    <td><?php echo number_format($receipt['total_cost'], 2); ?></td>
                                    
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
    // Расчет суммы при изменении количества или цены
    $('.quantity-input, .price-input').on('input', function() {
        var row = $(this).closest('tr');
        var quantity = parseFloat(row.find('.quantity-input').val()) || 0;
        var price = parseFloat(row.find('.price-input').val()) || 0;
        var total = quantity * price;
        
        row.find('.item-total').text(total.toFixed(2));
        
        // Пересчет общей суммы
        var grandTotal = 0;
        $('tbody tr').each(function() {
            if (!$(this).hasClass('total-row')) {
                var itemTotal = parseFloat($(this).find('.item-total').text()) || 0;
                grandTotal += itemTotal;
            }
        });
        
        $('.total-sum').text(grandTotal.toFixed(2));
    });
});
</script>