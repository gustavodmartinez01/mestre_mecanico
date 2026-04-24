<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-dark"><i class="fas fa-chart-bar mr-2 text-info"></i> Central de Relatórios</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-header"><h3 class="card-title font-weight-bold">Financeiro</h3></div>
                <div class="card-body">
                    <p class="text-muted small">Visualize entradas, saídas e saúde financeira.</p>
                    <a href="<?= base_url('relatorios/fluxo-caixa') ?>" class="btn btn-primary btn-block">
                        <i class="fas fa-eye mr-1"></i> Abrir Fluxo de Caixa
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-success shadow-sm h-100">
                <div class="card-header"><h3 class="card-title font-weight-bold">Suprimentos</h3></div>
                <div class="card-body">
                    <p class="text-muted small">Acompanhe compras e movimentação de estoque.</p>
                    <a href="<?= base_url('relatorios/compras') ?>" class="btn btn-success btn-block">
                        <i class="fas fa-eye mr-1"></i> Abrir Relatório de Compras
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>