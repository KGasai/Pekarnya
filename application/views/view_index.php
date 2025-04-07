<h2 class="text-center">Главная</h2>

<div class="row">
    <?php foreach($Products as $Product): ?>
    <div class="col-4">
        <h3><?=$Product['name']; ?></h3>
        <h4><?=$Product['price']; ?></h4>
        <a href="Main/order?product_id=<?=$Product['product_id'];?>" class="btn btn-success">Заказать</a>
    </div>
    <?php endforeach;?>
</div>