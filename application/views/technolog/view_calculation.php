<form class="mt-4" method='POST'>
<div class="mb-3">
  <label for="exampleInputEmail1" class="form-label">Договор</label>
    <select class="form-control" name='contract_id'>
        <?php foreach($contracts as $contract): ?>
            <option value="<?=$contract['contract_id']?>"><?='№ '.$contract['contract_number'].' Клиент:  '.$contract['client_id'] ?></option>
            <?php endforeach; ?>
    </select>
</div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Дата заказа</label>
    <input type="date" name="order_date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>

  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Колицечтво продукции</label>
    <input type="number" name="vits" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="product-selector">
    <?php foreach($products as $product): ?>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" 
               name="product_ids[]" 
               value="<?=$product['product_id']?>:<?=$product['price']?>" 
               id="product_<?=$product['product_id']?>">
        <label class="form-check-label" for="product_<?=$product['product_id']?>">
            <?=$product['name']?>
        </label>
    </div>
    <?php endforeach; ?>
</div>
  <button type="submit" name="dog" class="btn btn-primary">Зделать заказ</button>
</form>