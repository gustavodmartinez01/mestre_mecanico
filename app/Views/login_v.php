<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mestre Mecânico | Login</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
</head>
<body class="hold-transition login-page bg-dark">
<div class="login-box">
  <div class="login-logo">
    <a href="#" class="text-white"><b>Mestre</b> Mecânico</a>
  </div>
  <div class="card card-outline card-primary">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Faça login para iniciar sua sessão</p>

     <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="icon fas fa-ban"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

      <form action="<?= base_url('login/autenticar') ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="E-mail" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        
        <div class="input-group mb-3">
          <input type="password" name="senha" id="senha" class="form-control" placeholder="Senha" required>
          <div class="input-group-append">
            <div class="input-group-text" style="cursor: pointer;" onclick="toggleSenha()">
              <span class="fas fa-eye" id="olhinho"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Entrar no Sistema</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>

<script>
function toggleSenha() {
    const inputSenha = document.getElementById("senha");
    const icone = document.getElementById("olhinho");
    
    if (inputSenha.type === "password") {
        inputSenha.type = "text";
        icone.classList.remove("fa-eye");
        icone.classList.add("fa-eye-slash");
    } else {
        inputSenha.type = "password";
        icone.classList.remove("fa-eye-slash");
        icone.classList.add("fa-eye");
    }
}
</script>
</body>
</html>