
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
    <label for="exampleInputEmail1" class="form-label">Дата и время заказа</label>
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


<div class="container mt-4">
    <h2>Мои заказы</h2>

    
    <div class="card">
        <div class="card-header">
            <h4>История заказов</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Дата</th>
                                <th>Номер</th>
                                <th>Статус</th>
                                <th>Товары</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?= date('d.m.Y', strtotime($order['order_date'])) ?></td>
                                    <td>#<?= $order['order_id'] ?></td>
                                    <td>
                                        <span>
                                        <?=$order['status']?>
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled">
                                            <li>
                                                <strong><?= $order['product_name'] ?></strong> - 
                                                <?= $order['quantity'] ?> <?= $order['unit_of_measure'] ?> × 
                                                <?= number_format($order['price'], 2) ?> ₽
                                            </li>
                                        </ul>
                                    </td>
                                    <td><?= number_format($order['total'], 2) ?> ₽</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    У вас пока нет заказов.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>