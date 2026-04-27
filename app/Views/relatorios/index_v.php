<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="font-weight-bold text-dark"><i class="fas fa-chart-pie mr-2"></i> Centro de Relatórios e Inteligência</h3>
            <p class="text-muted">Acompanhe o desempenho financeiro e operacional da sua oficina em tempo real.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 hvr-float">
                <div class="card-body text-center">
                    <div class="icon-circle bg-success text-white mb-3">
                        <i class="fas fa-cash-register fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold">Fluxo de Caixa</h5>
                    <p class="text-muted small">Entradas de OS e Saídas de Compras detalhadas dia a dia.</p>
                    <a href="<?= base_url('relatorios/fluxo-caixa') ?>" class="btn btn-success btn-block rounded-pill">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 hvr-float">
                <div class="card-body text-center">
                    <div class="icon-circle bg-primary text-white mb-3">
                        <i class="fas fa-balance-scale fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold">Saúde Financeira</h5>
                    <p class="text-muted small">Apuração de lucros, prejuízos e evolução do saldo acumulado.</p>
                    <a href="<?= base_url('relatorios/balanco') ?>" class="btn btn-primary btn-block rounded-pill">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 hvr-float">
                <div class="card-body text-center">
                    <div class="icon-circle bg-warning text-white mb-3">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold">Suprimentos</h5>
                    <p class="text-muted small">Gestão de compras, análise de marcas e reposição de estoque.</p>
                    <a href="<?= base_url('relatorios/compras') ?>" class="btn btn-warning btn-block rounded-pill text-white">Acessar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 hvr-float">
                <div class="card-body text-center">
                    <div class="icon-circle bg-info text-white mb-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold">Produtividade</h5>
                    <p class="text-muted small">Ranking de técnicos, tempo de execução e ticket médio por profissional.</p>
                    <a href="<?= base_url('relatorios/produtividade') ?>" class="btn btn-info btn-block rounded-pill">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle { width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; }
    .hvr-float { transition: transform 0.3s; }
    .hvr-float:hover { transform: translateY(-5px); }
</style>
<?= $this->endSection() ?>