<?php helper('text'); ?>
<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-3">
        <div class="small-box bg-info shadow-sm">
            <div class="inner">
                <h3><?= count($produtos) ?></h3>
                <p>Produtos Cadastrados</p>
            </div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <?php 
            $criticos = array_filter($produtos, function($p) { return $p['estoque_atual'] <= $p['estoque_minimo']; });
        ?>
        <div class="small-box bg-danger shadow-sm">
            <div class="inner">
                <h3><?= count($criticos) ?></h3>
                <p>Estoque Crítico</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header border-0">
        <h3 class="card-title font-weight-bold"><i class="fas fa-cubes mr-2"></i> Gestão de Inventário</h3>
        <div class="card-tools">
            <a href="<?= base_url('produtos/pdf') ?>" target="_blank" class="btn btn-default btn-sm mr-2 shadow-sm">
                <i class="fas fa-file-pdf text-danger"></i> Relatório de Estoque
            </a>
            <a href="<?= base_url('produtos/novo') ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <table id="tabela-produtos" class="table table-bordered table-striped table-hover w-100">
            <thead class="bg-dark">
                <tr>
                    <th>Cód / Produto / Marca</th>
                    <th class="text-right">Custo (R$)</th>
                    <th class="text-right">Venda (R$)</th>
                    <th class="text-center">Qtd Atual</th>
                    <th class="text-center">Mínimo</th>
                    <th class="text-center" style="width: 140px">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos as $p): ?>
                <?php 
                    $isCritico = ($p['estoque_atual'] <= $p['estoque_minimo']);
                    $corStatus = $isCritico ? 'text-danger font-weight-bold' : '';
                ?>
                <tr>
                    <td>
                        <strong><?= $p['nome'] ?></strong><br>
                        <small class="text-muted">
                            <i class="fas fa-barcode"></i> <?= $p['codigo_barras'] ?: 'N/A' ?> | 
                            <i class="fas fa-tag"></i> <?= $p['marca'] ?>
                        </small>
                    </td>
                    <td class="text-right">R$ <?= number_format($p['preco_custo'], 2, ',', '.') ?></td>
                    <td class="text-right text-primary text-bold">R$ <?= number_format($p['preco_venda'], 2, ',', '.') ?></td>
                    <td class="text-center <?= $corStatus ?>">
                        <span class="h5"><?= $p['estoque_atual'] ?></span> <small><?= $p['unidade_medida'] ?></small>
                        <?php if($isCritico): ?>
                            <br><span class="badge badge-danger">REPOR</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center text-muted"><?= $p['estoque_minimo'] ?></td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm">
                            <a href="<?= base_url('produtos/movimentar/'.$p['id']) ?>" class="btn btn-outline-info btn-sm" title="Movimentar Estoque">
                                <i class="fas fa-exchange-alt"></i>
                            </a>
                            <a href="<?= base_url('produtos/editar/'.$p['id']) ?>" class="btn btn-outline-warning btn-sm" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmarExclusao(<?= $p['id'] ?>)" class="btn btn-outline-danger btn-sm" title="Excluir">
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

<style>
    .dataTables_filter { float: right; }
    .dataTables_paginate { float: right; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tabela-produtos')) {
            $('#tabela-produtos').DataTable().destroy();
        }

        $("#tabela-produtos").DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [[3, "asc"]], // Ordena pelo estoque atual (menores primeiro)
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            },
            "pageLength": 10
        });
    });

    function confirmarExclusao(id) {
        if(confirm('Tem certeza que deseja excluir este produto? Todo o histórico de movimentação será apagado!')) {
            window.location.href = "<?= base_url('produtos/excluir') ?>/" + id;
        }
    }
</script>
<?= $this->endSection() ?>