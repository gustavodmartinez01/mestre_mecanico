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
            <div class="card card-outline card-primary shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-money-bill-wave mr-2 text-success"></i> Financeiro</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Fluxo de Caixa (Entradas/Saídas)
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalFiltro" data-tipo="fluxo">
                                <i class="fas fa-filter"></i>
                            </button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Contas a Pagar / Compras
                            <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalFiltro" data-tipo="compras">
                                <i class="fas fa-filter"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-tools mr-2 text-info"></i> Operacional</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Produtividade por Técnico
                            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modalFiltro" data-tipo="tecnico">
                                <i class="fas fa-filter"></i>
                            </button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Histórico de Veículos
                            <button class="btn btn-sm btn-outline-info" data-toggle="modal" data-target="#modalFiltro" data-tipo="veiculo">
                                <i class="fas fa-filter"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-boxes mr-2 text-warning"></i> Estoque</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Posição de Estoque Atual
                            <a href="<?= base_url('relatorios/estoque-atual') ?>" target="_blank" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-print"></i>
                            </a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Peças com Estoque Baixo
                            <a href="<?= base_url('relatorios/estoque-baixo') ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFiltro" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= base_url('relatorios/gerar') ?>" method="GET" target="_blank">
                <input type="hidden" name="tipo_relatorio" id="inputTipo">
                <div class="modal-header bg-light">
                    <h5 class="modal-title font-weight-bold">Filtrar Relatório</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Data Início</label>
                            <input type="date" name="data_inicio" class="form-control" required value="<?= date('Y-m-01') ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Data Fim</label>
                            <input type="date" name="data_fim" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-pdf mr-1"></i> Gerar PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Script para passar o tipo de relatório para o modal
    $('#modalFiltro').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var tipo = button.data('tipo');
        $(this).find('#inputTipo').val(tipo);
    });
</script>
<?= $this->endSection() ?>