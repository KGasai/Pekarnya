<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
        <h2 class="text-center">Список клиентов</h2>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Наименование заказчика</th>
                    <th scope="col">ИНН заказчика</th>
                    <th scope="col">Адрес</th>
                    <th scope="col">Телефон</th>
                </tr>
            </thead>
            <tbody>

            <?php 
                foreach($Clients as $Client){
                    echo "
                <tr>
                    <th>$Client[name]</th>
                    <td>$Client[inn]</td>
                    <td>$Client[address]</td>
                    <td>$Client[phone]</td>
                </tr>
                ";
                }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-1"></div>
</div>