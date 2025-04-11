<style>
    .recipe-product.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .recipe-product .badge {
        background-color: rgba(255,255,255,0.2);
    }
    .recipe-product.active .badge {
        background-color: rgba(255,255,255,0.3);
    }
    #recipeContainer table {
        margin-bottom: 0;
    }
    #addIngredientForm {
        background-color: #f8f9fa;
    }
</style>
<div class="container mt-4">
    <h2>Управление рецептурами</h2>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Список продукции</span>
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#newProductModal">
                        <i class="bi bi-plus"></i> Добавить
                    </button>
                </div>
                <div class="card-body">
                    <div class="list-group" id="productsList">
                        <?php foreach ($products as $product): ?>
                        <a href="#" class="list-group-item list-group-item-action recipe-product" 
                           data-productid="<?= $product['product_id'] ?>">
                            <div class="d-flex justify-content-between">
                                <span><?= $product['name'] ?></span>
                                <span class="badge bg-secondary"></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Рецептура: <span id="currentProductName">Выберите продукт</span></span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" id="showAddIngredientForm">
                            <i class="bi bi-plus-circle"></i> Добавить ингредиент
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="recipeContainer">
                        <p class="text-muted">Выберите продукт для просмотра или редактирования рецептуры</p>
                    </div>
                    
                    <div class="mt-4 border p-3 rounded" id="addIngredientForm" style="display: none;">
                        <h5>Добавить ингредиент</h5>
                        <form id="addIngredientFormData">
                            <input type="hidden" id="currentProductId" value="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Ингредиент</label>
                                    <select class="form-select" id="ingredientSelect" required>
                                        <option value="" selected disabled>Выберите ингредиент</option>
                                        <?php foreach ($ingredients as $ingredient): ?>
                                        <option value="<?= $ingredient['ingredient_id'] ?>"><?= $ingredient['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Количество</label>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="ingredientQuantity" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success" id="addIngredientBtn">
                                        <i class="bi bi-check"></i> Добавить
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно добавления нового продукта -->
<div class="modal fade" id="newProductModal" tabindex="-1" aria-labelledby="newProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProductModalLabel">Новый продукт</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createProductForm">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Название</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Категория</label>
                        <select class="form-select" id="productCategory" required>
                            <option value="" selected disabled>Выберите категорию</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['category_id'] ?>"><?= $category['name_cat'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="productUnit" class="form-label">Единица измерения</label>
                        <input type="text" class="form-control" id="productUnit" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Цена</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="productPrice" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="createProductBtn">Создать</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function() {
    // Загрузка рецепта при выборе продукта
    $('.recipe-product').click(function(e) {
        e.preventDefault();
        const productId = $(this).data('productid');
        const productName = $(this).find('span:first').text();
        
        // Подсветка выбранного продукта
        $('.recipe-product').removeClass('active');
        $(this).addClass('active');
        
        // Установка названия продукта
        $('#currentProductName').text(productName);
        $('#currentProductId').val(productId);
        
        // Скрываем форму добавления
        $('#addIngredientForm').hide();
        
        // Загрузка рецепта
        loadRecipe(productId);
    });
    
    // Показать форму добавления ингредиента
    $('#showAddIngredientForm').click(function() {
        if (!$('#currentProductId').val()) {
            alert('Сначала выберите продукт');
            return;
        }
        $('#addIngredientForm').toggle();
    });
    
    // Добавление ингредиента
    $('#addIngredientFormData').submit(function(e) {
        e.preventDefault();
        
        const productId = $('#currentProductId').val();
        const ingredientId = $('#ingredientSelect').val();
        const quantity = $('#ingredientQuantity').val();
        
        if (!productId || !ingredientId || !quantity) {
            alert('Заполните все поля');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("Technolog/add_ingredient") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                ingredient_id: ingredientId,
                quantity: quantity
            },
            success: function(response) {
                if (response) {
                    loadRecipe(productId);
                    $('#ingredientSelect').val('');
                    $('#ingredientQuantity').val('');
                    $('#addIngredientForm').hide();
                } else {
                    alert('Ошибка при добавлении ингредиента');
                }
            },
            error: function() {
                alert('Ошибка сервера');
            }
        });
    });
    
    // Удаление ингредиента
    $(document).on('click', '.remove-ingredient-btn', function() {
        if (!confirm('Удалить этот ингредиент из рецептуры?')) return;
        
        const recipeId = $(this).data('recipeid');
        const productId = $('#currentProductId').val();
        
        $.ajax({
            url: '<?= base_url("Technolog/remove_ingredient/") ?>' + recipeId,
            method: 'GET',
            success: function(response) {
                if (response) {
                    loadRecipe(productId);
                } else {
                    alert('Ошибка при удалении ингредиента');
                }
            },
            error: function() {
                alert('Ошибка сервера');
            }
        });
    });
    
    // Функция загрузки рецепта
    function loadRecipe(productId) {
        $.ajax({
            url: '<?= base_url("Technolog/get_recipe/") ?>' + productId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const container = $('#recipeContainer');
                container.empty();
                
                if (response.length > 0) {
                    const table = `
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Ингредиент</th>
                                <th>Количество</th>
                                <th>Ед. измерения</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody id="recipeItemsList">
                        </tbody>
                    </table>`;
                    
                    container.append(table);
                    
                    response.forEach(function(item) {
                        $('#recipeItemsList').append(`
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.quantity}</td>
                                <td>${item.unit_of_measure}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-ingredient-btn" 
                                            data-recipeid="${item.recipe_id}">
                                        <i class="bi bi-trash"></i> Удалить
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    container.append('<p class="text-muted">Для этого продукта еще не добавлены ингредиенты</p>');
                }
            },
            error: function() {
                alert('Ошибка загрузки рецептуры');
            }
        });
    }
    
    // Создание нового продукта
    $('#createProductBtn').click(function() {
        const productData = {
            name: $('#productName').val(),
            category_id: $('#productCategory').val(),
            unit_of_measure: $('#productUnit').val(),
            price: $('#productPrice').val()
        };
        
        // Валидация
        if (!productData.name || !productData.category_id || !productData.unit_of_measure || !productData.price) {
            alert('Заполните все поля');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("Technolog/add_product") ?>',
            method: 'POST',
            data: productData,
            success: function(response) {
                if (response) {
                    alert('Продукт успешно добавлен');
                    $('#newProductModal').modal('hide');
                    location.reload();
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function() {
                alert('Ошибка сервера');
            }
        });
    });
});
</script>