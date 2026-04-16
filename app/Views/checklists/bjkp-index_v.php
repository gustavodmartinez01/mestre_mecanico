<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-list-check mr-2"></i> Itens de Inspeção Cadastrados</h3>
                <div class="card-tools">
                    <a href="<?= base_url('checklists/novo') ?>" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Novo Item
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table id="tabela-checklists" class="table table-bordered table-striped table-hover w-100">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th style="width: 50px">Ordem</th>
                            <th>Categoria</th>
                            <th>Descrição do Item</th>
                            <th class="text-center" style="width: 120px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($checklists as $c): ?>
                        <tr>
                            <td class="text-bold text-primary text-center"><?= $c['ordem_exibicao'] ?></td>
                            <td>
                                <span class="badge badge-light border px-2 py-1">
                                    <?= strtoupper($c['categoria']) ?>
                                </span>
                            </td>
                            <td><?= $c['descricao'] ?></td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="<?= base_url('checklists/editar/'.$c['id']) ?>" class="btn btn-default btn-sm border" title="Editar">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <a href="<?= base_url('checklists/excluir/'.$c['id']) ?>" class="btn btn-default btn-sm border" title="Excluir" onclick="return confirm('Deseja realmente excluir este item de checklist?')">
                                        <i class="fas fa-trash text-danger"></i>
                                    </a>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function () {
        $("#tabela-checklists").DataTable({
            "responsive": true, 
            "autoWidth": false,
            // Ordena primeiro por Categoria (coluna 1) e depois pela Ordem (coluna 0)
            "order": [[1, "asc"], [0, "asc"]], 
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Desabilita ordenação na coluna de Ações
            ]
        });
    });
</script>
<?= $this->endSection() ?>