<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
        <h2 class="text-center">Прайс лист</h2>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Вид продукции</th>
                    <th scope="col">Наименование продукции</th>
                    <th scope="col">Ед. измер.</th>
                    <th scope="col">Цена</th>
                </tr>
            </thead>
            <tbody>

            <?php 
                foreach($Products as $Product){
                    echo "
                <tr>
                    <th>$Product[type_id]</th>
                    <td>$Product[name]</td>
                    <td>$Product[weight]</td>
                    <td>$Product[price]</td>
                </tr>
                ";
                }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-1"></div>
</div>