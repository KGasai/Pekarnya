
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
                <input type="date" name="date" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">№ договора</label>
                <select name="contract_id" class="form-control">
                    <option value="<?= $contracts['contract_id'];?>"><?= $contracts['contract_number'];?></option>
                </select>
            </div>
            <div class="mb-3">
                <label for="form-control">Наименование продукции<:<?= $product[0]['name'];?> </label>
                <input type="hidden" name="product_id" value="<?= $product[0]['product_id'];?>">
                <input type="hidden" name="price" value="<?= $product[0]['price'];?>"> <?= $product[0]['price'];?> 
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