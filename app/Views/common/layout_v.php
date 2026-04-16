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
    /* === AZUL ESCURO PERSONALIZADO DA OFICINA === */
    .sidebar-dark-navy {
        background: linear-gradient(180deg, #0c2443 0%, #0a1f3a 100%);
    }
    
    /* Logo com destaque */
    .brand-link {
        border-bottom: 1px solid #1a3c5e !important;
        background-color: rgba(0, 0, 0, 0.1);
        min-height: 60px;
        display: flex;
        align-items: center;
    }
    
    .brand-text {
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    
    /* Ícones coloridos nos menus */
    .nav-icon.text-info { color: #17a2b8 !important; }
    .nav-icon.text-warning { color: #ffc107 !important; }
    .nav-icon.text-success { color: #28a745 !important; }
    .nav-icon.text-danger { color: #dc3545 !important; }
    .nav-icon.text-primary { color: #007bff !important; }
    .nav-icon.text-orange { color: #fd7e14 !important; }
    .nav-icon.text-cyan { color: #17a2b8 !important; }
    .nav-icon.text-purple { color: #6f42c1 !important; }
    
    /* Item ativo com destaque */
    .nav-sidebar > .nav-item > .nav-link.active {
        background: linear-gradient(90deg, #1e5799 0%, #153a6b 100%);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        font-weight: 500;
    }
    
    /* Hover suave */
    .nav-sidebar > .nav-item > .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(3px);
        transition: all 0.2s ease;
    }
    
    /* Separador de seções */
    .nav-header {
        color: #6c757d;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 15px;
        padding-left: 1rem;
        font-weight: 600;
    }
    
    /* Badge de notificação */
    .right.badge {
        font-size: 10px;
        padding: 3px 8px;
        margin-left: 5px;
    }
    
    /* User panel com borda sutil */
    .user-panel {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    /* Ícone de usuário maior e centralizado */
    .user-panel .image i {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Ajuste para mobile */
    @media (max-width: 768px) {
        .brand-text {
            font-size: 1rem;
        }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar Superior -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link">
            <b><i class="fas fa-building mr-1"></i>Empresa:</b> 
            <?= session()->get('empresa_nome') ?? 'Mestre Mecânico Matriz' ?>
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

  <!-- Sidebar Lateral - Tema Navy Personalizado -->
  <aside class="main-sidebar sidebar-dark-navy elevation-4">
    
    <!-- Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <i class="fas fa-wrench fa-lg mr-2 text-warning"></i>
      <span class="brand-text font-weight-bold text-uppercase">Mestre Mecânico</span>
    </a>

    <!-- Conteúdo da Sidebar -->
    <div class="sidebar">
      
      <!-- Painel do Usuário -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <i class="fas fa-user-circle fa-2x text-light"></i>
        </div>
        <div class="info">
            <a href="<?= base_url('perfil/senha') ?>" class="d-block text-white font-weight-bold">
                <?= esc(session()->get('nome') ?? 'Usuário') ?> 
                <i class="fas fa-edit text-muted ml-1" style="font-size: 12px;"></i>
            </a>
            <span class="text-muted" style="font-size: 11px;">
                <i class="fas fa-circle text-success mr-1" style="font-size: 8px;"></i>
                Online
            </span>
        </div>
      </div>

      <!-- Menu de Navegação -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (current_url() == base_url('dashboard')) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-tachometer-alt text-info"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            <!-- Cabeçalho: OPERACIONAL -->
            <li class="nav-header">OPERACIONAL</li>
            
            <!-- Ordens de Serviço -->
            <li class="nav-item <?= (strpos(current_url(), 'os') !== false) ? 'menu-open' : '' ?>">
                <a href="#" class="nav-link <?= (strpos(current_url(), 'os') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-clipboard-list text-warning"></i>
                    <p>Ordens de Serviço <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= base_url('os/nova') ?>" class="nav-link">
                            <i class="far fa-circle nav-icon text-success"></i>
                            <p><i class="fas fa-plus mr-1"></i>Nova OS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('os') ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p><i class="fas fa-list mr-1"></i>Gerenciar OS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('os/historico') ?>" class="nav-link">
                            <i class="far fa-circle nav-icon text-muted"></i>
                            <p><i class="fas fa-history mr-1"></i>Histórico</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Checklists -->
            <li class="nav-item <?= (strpos(current_url(), 'checklist') !== false) ? 'menu-open' : '' ?>">
                <a href="#" class="nav-link <?= (strpos(current_url(), 'checklist') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-clipboard-check text-cyan"></i>
                    <p>Checklists <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?= base_url('checklist/modelos') ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Modelos de Checklist</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('checklist/preenchidos') ?>" class="nav-link">
                            <i class="far fa-circle nav-icon text-muted"></i>
                            <p>Checklists Preenchidos</p>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Agenda / Agendamentos -->
            <li class="nav-item">
                <a href="<?= base_url('agenda') ?>" class="nav-link <?= (strpos(current_url(), 'agenda') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-calendar-alt text-purple"></i>
                    <p>Agenda</p>
                    <?php 
                    // Exemplo de badge para agendamentos do dia (opcional)
                    // $total = \Config\Database::connect()->query("SELECT COUNT(*) as t FROM agendamentos WHERE DATE(data) = CURDATE()")->getRow()->t ?? 0;
                    // if($total > 0): 
                    ?>
                    <!-- <span class="right badge badge-danger"><?= 'total' ?></span> -->
                    <?php // endif; ?>
                </a>
            </li>

            <!-- Cabeçalho: CADASTROS -->
            <li class="nav-header">CADASTROS</li>
            
            <li class="nav-item">
                <a href="<?= base_url('clientes') ?>" class="nav-link <?= (strpos(current_url(), 'clientes') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-users text-primary"></i>
                    <p>Clientes</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('funcionarios') ?>" class="nav-link <?= (strpos(current_url(), 'funcionarios') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-user-cog text-cyan"></i>
                    <p>Funcionários</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('veiculos') ?>" class="nav-link <?= (strpos(current_url(), 'veiculos') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-car text-danger"></i>
                    <p>Veículos</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('produtos') ?>" class="nav-link <?= (strpos(current_url(), 'produtos') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-boxes text-orange"></i>
                    <p>Produtos / Peças</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('servicos') ?>" class="nav-link <?= (strpos(current_url(), 'servicos') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-hand-holding-usd text-success"></i>
                    <p>Serviços / Mão de Obra</p>
                </a>
            </li>

            <!-- Cabeçalho: FINANCEIRO -->
            <li class="nav-header">FINANCEIRO</li>
            
            <li class="nav-item">
                <a href="<?= base_url('contas-receber') ?>" class="nav-link <?= (strpos(current_url(), 'contas-receber') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-arrow-down text-success"></i>
                    <p>Contas a Receber</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('contas-pagar') ?>" class="nav-link <?= (strpos(current_url(), 'contas-pagar') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-arrow-up text-danger"></i>
                    <p>Contas a Pagar</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('fluxo-caixa') ?>" class="nav-link <?= (strpos(current_url(), 'fluxo-caixa') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-chart-line text-info"></i>
                    <p>Fluxo de Caixa</p>
                </a>
            </li>

            <!-- Cabeçalho: RELATÓRIOS -->
            <li class="nav-header">RELATÓRIOS</li>
            
            <li class="nav-item">
                <a href="<?= base_url('relatorios') ?>" class="nav-link <?= (strpos(current_url(), 'relatorios') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-chart-bar text-info"></i>
                    <p>Relatórios Gerais</p>
                </a>
            </li>

            <!-- Cabeçalho: SISTEMA (Apenas Admin) -->
            <?php if(session()->get('nivel') === 'admin'): ?>
            <li class="nav-header">ADMINISTRAÇÃO</li>
            
            <li class="nav-item">
                <a href="<?= base_url('configuracoes') ?>" class="nav-link <?= (strpos(current_url(), 'configuracoes') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-cogs text-muted"></i>
                    <p>Configurações</p>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('usuarios') ?>" class="nav-link <?= (strpos(current_url(), 'usuarios') !== false) ? 'active' : '' ?>">
                    <i class="nav-icon fas fa-user-shield text-warning"></i>
                    <p>Usuários do Sistema</p>
                </a>
            </li>
            <?php endif; ?>

            <!-- Logout -->
            <li class="nav-item mt-3">
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                    <p>Sair do Sistema</p>
                </a>
            </li>
            
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Conteúdo Principal -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <?= $this->renderSection('conteudo') ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
        <i class="fas fa-wrench mr-1"></i>Oficina Inteligente
    </div>
    <strong>
        Copyright &copy; <?= date('Y') ?> 
        <a href="#" class="text-decoration-none">Gustavo D. Martinez</a>.
    </strong> 
    | <?= session()->get('empresa_nome') ?? 'Mestre Mecânico' ?> 
    - CNPJ: <?= session()->get('empresa_cnpj') ?? '00.000.000/0000-00' ?>
  </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>

<?= $this->renderSection('scripts') ?>

<script>
    // Inicialização opcional para elementos da interface
    $(document).ready(function() {
        // Ativar tooltips se necessário
        $('[data-toggle="tooltip"]').tooltip();
        
        // Suavizar transições da sidebar
        $('.nav-link').on('click', function() {
            $('.nav-link').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>
</body>
</html>