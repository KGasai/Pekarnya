<div class="container mt-4">
    <h2>Расчет сырья</h2>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Калькулятор сырья
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Добавьте продукты для расчета</h5>
                            <div id="calculationProducts">
                                <div class="product-row mb-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <select class="form-select product-select">
                                                <option value="" selected disabled>Выберите продукт</option>
                                                <?php foreach ($products as $product): ?>
                                                <option value="<?= $product['product_id'] ?>">
                                                    <?= $product['name'] ?> (<?= $product['unit_of_measure'] ?>)
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" min="1" value="1" class="form-control product-quantity">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-danger remove-product-btn" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary mt-2" id="addProductBtn">
                                <i class="bi bi-plus"></i> Добавить продукт
                            </button>
                            <button class="btn btn-primary mt-2 ms-2" id="calculateBtn">
                                <i class="bi bi-calculator"></i> Рассчитать
                            </button>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Результаты расчета</h5>
                            <div id="calculationResult" style="display: none;">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ингредиент</th>
                                            <th>Требуется</th>
                                            <th>Ед. изм.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultTableBody">
                                        <!-- Результаты будут здесь -->
                                    </tbody>
                                </table>
                                <div class="alert alert-warning mt-3" id="warningMessage" style="display: none;">
                                    <i class="bi bi-exclamation-triangle"></i> Некоторые ингредиенты в недостаточном количестве!
                                </div>
                            </div>
                            <div id="emptyResult" class="text-muted">
                                Добавьте продукты и нажмите "Рассчитать"
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function() {
    $('#addProductBtn').click(function() {
        const newRow = `
        <div class="product-row mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <select class="form-select product-select">
                        <option value="" selected disabled>Выберите продукт</option>
                        <?php foreach ($products as $product): ?>
                        <option value="<?= $product['product_id'] ?>">
                            <?= $product['name'] ?> (<?= $product['unit_of_measure'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" min="1" value="1" class="form-control product-quantity">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-danger remove-product-btn">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>`;
        
        $('#calculationProducts').append(newRow);
        
        // Активируем кнопки удаления
        $('.remove-product-btn').prop('disabled', false);
    });
    
    // Удаление строки с продуктом
    $(document).on('click', '.remove-product-btn', function() {
        if ($('.product-row').length > 1) {
            $(this).closest('.product-row').remove();
            
            // Деактивируем кнопку удаления, если осталась одна строка
            if ($('.product-row').length === 1) {
                $('.remove-product-btn').prop('disabled', true);
            }
        }
    });
    
    // Расчет сырья
    $('#calculateBtn').click(function() {
        const products = [];
        let hasErrors = false;
        
        // Собираем данные о продуктах
        $('.product-row').each(function() {
            const productId = $(this).find('.product-select').val();
            const quantity = $(this).find('.product-quantity').val();
            
            if (!productId) {
                $(this).find('.product-select').addClass('is-invalid');
                hasErrors = true;
            } else {
                $(this).find('.product-select').removeClass('is-invalid');
            }
            
            if (!quantity || quantity < 1) {
                $(this).find('.product-quantity').addClass('is-invalid');
                hasErrors = true;
            } else {
                $(this).find('.product-quantity').removeClass('is-invalid');
            }
            
            if (productId && quantity && quantity >= 1) {
                products.push({
                    product_id: productId,
                    quantity: quantity
                });
            }
        });
        
        if (hasErrors || products.length === 0) {
            alert('Заполните правильно все поля!');
            return;
        }
        
        // Отправка запроса на расчет
        $.ajax({
            url: '<?= base_url("Technolog/calculate_requirements") ?>',
            method: 'POST',
            data: {products: products},
            dataType: 'json',
            success: function(response) {
                displayResults(response);
            },
            error: function() {
                alert('Ошибка сервера при расчете');
            }
        });
    });
    
    // Отображение результатов расчета
    function displayResults(data) {
        const resultBody = $('#resultTableBody');
        resultBody.empty();
        
        let hasShortage = false;
        
        // Сортируем ингредиенты по названию
        const sortedIngredients = Object.values(data).sort((a, b) => a.name.localeCompare(b.name));
        
        sortedIngredients.forEach(function(ingredient) {
            const rowClass = ingredient.current_stock < ingredient.quantity ? 'table-danger' : '';
            console.log(ingredient);
            resultBody.append(`
                <tr class="${rowClass}">
                    <td>${ingredient.name}</td>
                    <td>${ingredient.quantity.toFixed(2)}</td>
                    <td>${ingredient.unit_of_measure}</td>
                    <td></td>
                </tr>
            `);
            
            if (ingredient.current_stock < ingredient.quantity) {
                hasShortage = true;
            }
        });
        
        // Показываем/скрываем предупреждение
        if (hasShortage) {
            $('#warningMessage').show();
        } else {
            $('#warningMessage').hide();
        }
        
        // Показываем результаты
        $('#emptyResult').hide();
        $('#calculationResult').show();
    }
});
</script>