<form action="main/selectUser" method="post" >
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h2 class="text-center">Авторизация</h2>
            <div class="d-flex flex-column me-auto ms-auto" style="width:400px;">
                <div class="mb-3">
                    <label for="form-control">Введите логин</label>
                    <input type="text" name="username" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="form-control">Введите пароль</label>
                    <input type="text" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Войти</button>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</form>