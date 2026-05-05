<!DOCTYPE html>
<html lang="pt-br">
<head>
  <?= $this->renderSection('styles') ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mestre Mecânico | Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* === MANTENDO O SEU VISUAL PERSONALIZADO === */
    .sidebar-dark-navy { background: linear-gradient(180deg, #0c2443 0%, #0a1f3a 100%); }
    .brand-link { border-bottom: 1px solid #1a3c5e !important; background-color: rgba(0, 0, 0, 0.1); min-height: 60px; display: flex; align-items: center; }
    .brand-text { font-size: 1.1rem; letter-spacing: 0.5px; }
    .nav-icon.text-info { color: #17a2b8 !important; }
    .nav-icon.text-warning { color: #ffc107 !important; }
    .nav-icon.text-success { color: #28a745 !important; }
    .nav-icon.text-danger { color: #dc3545 !important; }
    .nav-icon.text-primary { color: #007bff !important; }
    .nav-icon.text-orange { color: #fd7e14 !important; }
    .nav-icon.text-cyan { color: #17a2b8 !important; }
    .nav-icon.text-purple { color: #6f42c1 !important; }
    .nav-sidebar > .nav-item > .nav-link.active { background: linear-gradient(90deg, #1e5799 0%, #153a6b 100%); box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3); font-weight: 500; }
    .nav-sidebar > .nav-item > .nav-link:hover { background-color: rgba(255, 255, 255, 0.1); transform: translateX(3px); transition: all 0.2s ease; }
    .nav-header { color: #6c757d; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-top: 15px; padding-left: 1rem; font-weight: 600; }
    .user-panel { border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem !important; padding-bottom: 1rem !important; }
    
    /* Dropdown do perfil na sidebar */
    .user-dropdown .dropdown-menu { background-color: #1a3c5e; border: 1px solid #2c4e72; }
    .user-dropdown .dropdown-item { color: #fff; }
    .user-dropdown .dropdown-item:hover { background-color: #244b7a; }
  </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar Superior -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link">
            <b><i class="fas fa-building mr-1"></i>Empresa:</b> 
            <?= session()->get('empresa_nome') ?? 'Mestre Mecânico' ?>
        </span>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link text-danger" href="<?= base_url('logout') ?>" role="button" title="Sair do Sistema">
          <i class="fas fa-sign-out-alt"></i> <span class="d-none d-sm-inline">Sair</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar Lateral -->
  <aside class="main-sidebar sidebar-dark-navy elevation-4">
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <i class="fas fa-wrench fa-lg mr-2 text-warning"></i>
      <span class="brand-text font-weight-bold text-uppercase">Mestre Mecânico</span>
    </a>

    <div class="sidebar">
      
      <!-- Painel do Usuário Atualizado com Dropdown -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex dropdown user-dropdown">
        <div class="image">
          <i class="fas fa-user-circle fa-2x text-light"></i>
        </div>
        <div class="info">
            <a href="#" class="d-block text-white font-weight-bold dropdown-toggle" data-toggle="dropdown">
                <?= esc(session()->get('nome') ?? 'Usuário') ?>
            </a>
            <div class="dropdown-menu shadow">
                <a class="dropdown-item" href="<?= base_url('perfil') ?>">
                    <i class="fas fa-user-edit fa-sm mr-2"></i> Meu Perfil
                </a>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#modalAlterarSenha">
                    <i class="fas fa-key fa-sm mr-2"></i> Alterar Senha
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                    <i class="fas fa-sign-out-alt fa-sm mr-2"></i> Sair
                </a>
            </div>
            <span class="text-muted" style="font-size: 11px;">
                <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i> Online
            </span>
        </div>
      </div>

      <!-- Menu de Navegação -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            
            <li class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-tachometer-alt text-info"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <li class="nav-header">OPERACIONAL</li>
            
            <!-- OS e Checklists (Mantidos conforme seu original) -->
            <li class="nav-item <?= (strpos(current_url(), 'os') !== false) ? 'menu-open' : '' ?>">
                <a href="#" class="nav-link <?= (strpos(current_url(), 'os') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-clipboard-list text-warning"></i>
                    <p>Ordens de Serviço <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item"><a href="<?= base_url('os/nova') ?>" class="nav-link"><i class="far fa-circle nav-icon text-success"></i><p>Nova OS</p></a></li>
                    <li class="nav-item"><a href="<?= base_url('os') ?>" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Gerenciar OS</p></a></li>
                </ul>
            </li>

            <!-- Restante do menu... -->
            <li class="nav-header">FINANCEIRO</li>
            <li class="nav-item"><a href="<?= base_url('fluxo-caixa') ?>" class="nav-link"><i class="nav-icon fas fa-chart-line text-info"></i><p>Fluxo de Caixa</p></a></li>

            <li class="nav-header">RELATÓRIOS</li>
            <li class="nav-item"><a href="<?= base_url('relatorios') ?>" class="nav-link"><i class="nav-icon fas fa-chart-bar text-info"></i><p>Relatórios Gerais</p></a></li>
            
            <?php if(session()->get('nivel') === 'admin'): ?>
            <li class="nav-header">ADMINISTRAÇÃO</li>
            <li class="nav-item"><a href="<?= base_url('usuarios') ?>" class="nav-link"><i class="nav-icon fas fa-user-shield text-warning"></i><p>Usuários</p></a></li>
            <?php endif; ?>

        </ul>
      </nav>
    </div>
  </aside>

  <!-- Conteúdo Principal -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <!-- Alertas de Feedback -->
        <?php if (session()->getFlashdata('success')): ?>
            <script>Swal.fire({ icon: 'success', title: 'Sucesso!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000 });</script>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <script>Swal.fire({ icon: 'error', title: 'Erro!', text: '<?= session()->getFlashdata('error') ?>' });</script>
        <?php endif; ?>

        <?= $this->renderSection('conteudo') ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-inline"><b>Mestre Mecânico</b> v2.0</div>
    <strong>Copyright &copy; <?= date('Y') ?> <a href="#">Gustavo D. Martínez</a>.</strong>
  </footer>
</div>

<!-- MODAL DE ALTERAR SENHA (GLOBAL) -->
<div class="modal fade" id="modalAlterarSenha" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('usuario/alterarSenha') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title"><i class="fas fa-key mr-2"></i>Alterar Minha Senha</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nova Senha</label>
                        <input type="password" name="nova_senha" class="form-control" placeholder="Mínimo 6 caracteres" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" name="confirmar_senha" class="form-control" placeholder="Repita a nova senha" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Salvar Nova Senha</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>