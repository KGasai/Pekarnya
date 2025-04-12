<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
        <h2>Личные данные</h2>
        <form action="Client/editData" method="post">
            <div class="mb-3">
                <label for="form-control">Введите ИНН:</label>
                <input type="number" name="innn" id="form-control" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">Введите адрес:</label>
                <input type="text" name="address" id="form-control" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">Введите телефон:</label>
                <input type="text" name="phone" id="form-control" class="form-control">
            </div>
            <div class="mb-3">
                <label for="form-control">Введите контактное лицо:</label>
                <input type="text" name="contact_person" id="form-control" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Заполнить</button>
        </form>
    </div>
    <div class="col-1"></div>
</div>