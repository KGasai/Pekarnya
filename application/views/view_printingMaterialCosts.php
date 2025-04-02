<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
        <h2 class="text-center">Печать расходов сырья за определённый период</h2>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Наименование сырья</th>
                    <th scope="col">Ед. измер.</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Цена</th>
                    <th scope="col">Сумма</th>
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