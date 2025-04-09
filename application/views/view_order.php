
<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
    <h2 class="text-center">Оформлениt заказа</h2>
        <form action="Main/order" method="post">
            <div class="mb-3">
                <input type="hidden" name="client_id" value="<?= $this -> session -> userdata('user_id') ;?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">Дата</label>
                <input type="date" name="start_date" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">№ договора</label>
                <select name="id_contract" class="form-control">
                    <?php foreach($contracts as $contract): ?>
                    <option value="<?=$contract['contract_id']; ?>"><?=$contract['contract_id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="form-control">Наименование продукции</label>
                <select name="product_id" class="form-control">
                    <option value=""></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="form-control">Количество</label>
                <input type="number" name="quantity" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Оформить</button>
        </form>
    </div>
    <div class="col-1"></div>
</div>