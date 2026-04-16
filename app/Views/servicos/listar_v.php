<?php helper('text'); ?>
<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-tools mr-2 text-primary"></i> Catálogo de Serviços
                </h3>
                             
                <div class="card-tools">
                    <a href="<?= base_url('servicos/pdf') ?>" target="_blank" class="btn btn-default btn-sm mr-2 shadow-sm">
                        <i class="fas fa-file-pdf text-danger"></i> Exportar PDF
                    </a>
                    <a href="<?= base_url('servicos/novo') ?>" class="btn btn-success btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Novo Serviço
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table id="tabela-servicos" class="table table-bordered table-striped table-hover w-100">
                    <thead class="bg-dark">
                        <tr>
                            <th>Serviço / Procedimento</th>
                            <th class="text-center">Tempo Est.</th>
                            <th class="text-right">Custo (R$)</th>
                            <th class="text-right">Venda (R$)</th>
                            <th class="text-center">Lucro</th>
                            <th class="text-center" style="width: 100px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($servicos as $s): ?>
                        <tr>
                            <td>
                                <span class="text-bold"><?= $s['nome'] ?></span><br>
                                <small class="text-muted">
                                    <?= $s['descricao'] ? character_limiter($s['descricao'], 60) : '<em>Sem descrição.</em>' ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info shadow-sm">
                                    <i class="far fa-clock"></i> <?= (new \App\Models\ServicoModel())->getTempoFormatado($s) ?>
                                </span>
                            </td>
                            <td class="text-right">R$ <?= number_format($s['preco_custo'], 2, ',', '.') ?></td>
                            <td class="text-right text-primary text-bold">R$ <?= number_format($s['preco_venda'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <?php 
                                    $lucro = $s['preco_venda'] - $s['preco_custo'];
                                    $cor = ($lucro >= 0) ? 'success' : 'danger';
                                ?>
                                <span class="badge badge-<?= $cor ?>-light text-<?= $cor ?> text-bold">
                                    R$ <?= number_format($lucro, 2, ',', '.') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= base_url('servicos/editar/'.$s['id']) ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="confirmarExclusao(<?= $s['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ajustes finos para o DataTables no AdminLTE */
    .dataTables_wrapper .dataTables_filter { float: right; }
    .dataTables_wrapper .dataTables_paginate { float: right; }
    .badge-success-light { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .badge-danger-light { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        // Inicializa o DataTable garantindo que não haja duplicidade
        if ($.fn.DataTable.isDataTable('#tabela-servicos')) {
            $('#tabela-servicos').DataTable().destroy();
        }

        var table = $("#tabela-servicos").DataTable({
            "responsive": true,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "asc"]],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "pageLength": 10
        });
    });

    function confirmarExclusao(id) {
        if(confirm('Tem certeza que deseja remover este serviço permanentemente?')) {
            window.location.href = "<?= base_url('servicos/excluir') ?>/" + id;
        }
    }
</script>
<?= $this->endSection() ?>