<form method="POST" >
<div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Номер договора</label>
    <input type="text" name='contract_number' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Дата договора</label>
    <input type="date" name='contract_date' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Срок действия от</label>
    <input type="date" name='start_date' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Срок действия до</label>
    <input type="date" name='end_date' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  
  
  
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Вид оплаты</label>
    <input type="text" name='payment_terms' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Условия поставки</label>
    <input type="text" name='delivery_terms' class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>
  <button type="submit" name="dog" class="btn btn-primary">Заключить договор</button>
</form>

<div class="container mt-5">
    <?php foreach($contracts as $contract): ?>
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-primary mb-4 shadow">
        <div class="card-header bg-primary text-white">
          <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Договор № <?=$contract['contract_number']?></h4>
            <span class="badge bg-light text-primary fs-6"><?=$contract['is_active']?'Активен':'Не активен'?></span>
          </div>
        </div>
        
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-building me-2 fs-4 text-primary"></i>
                <div>
                  <h6 class="mb-0 text-muted">Клиент</h6>
                  <p class="mb-0 fs-5"><?=$contract['client_id']?></p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-calendar-date me-2 fs-4 text-primary"></i>
                <div>
                  <h6 class="mb-0 text-muted">Дата договора</h6>
                  <p class="mb-0 fs-5"><?=$contract['contract_date']?></p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row mb-4">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-calendar-check me-2 fs-4 text-primary"></i>
                <div>
                  <h6 class="mb-0 text-muted">Действует с</h6>
                  <p class="mb-0 fs-5"><?=$contract['start_date']?></p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-calendar-x me-2 fs-4 text-primary"></i>
                <div>
                  <h6 class="mb-0 text-muted">Действует по</h6>
                  <p class="mb-0 fs-5"><?=$contract['end_date']?></p>
                </div>
              </div>
            </div>
          </div>
          
          <hr class="my-4">
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <h5 class="text-primary"><i class="bi bi-credit-card me-2"></i>Условия оплаты</h5>
              <div class="ps-4">
                <p class="mb-1"><?=$contract['payment_terms']?></p>
                
              </div>
            </div>
            
            <div class="col-md-6 mb-3">
              <h5 class="text-primary"><i class="bi bi-truck me-2"></i>Условия поставки</h5>
              <div class="ps-4">
                <p class="mb-1"><?=$contract['delivery_terms']?></p>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>


<style>
  .card {
    border-radius: 0.5rem;
  }
  .card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
  }
  .card-footer {
    border-radius: 0 0 0.5rem 0.5rem !important;
  }
</style>