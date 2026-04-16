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
                <h3 class="card-title font-weight-bold"><i class="fas fa-users mr-2"></i> Listagem de Funcionários</h3>
                <?php if (in_array(session()->get('nivel_acesso'), ['admin', 'gerente'])): ?>
                    <div class="card-tools">
                        <a href="<?= base_url('funcionarios/novo') ?>" class="btn btn-primary btn-sm shadow-sm">
                            <i class="fas fa-plus"></i> Novo Funcionário
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-body">
                <table id="tabela-funcionarios" class="table table-bordered table-striped table-hover w-100">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>Nome</th>
                            <th>Cargo</th>
                            <th>CPF</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 120px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($funcionarios as $f): ?>
                        <tr>
                            <td class="text-bold text-primary">#<?= $f['id'] ?></td>
                            <td><?= $f['nome'] ?></td>
                            <td><span class="badge badge-light border"><?= $f['cargo'] ?></span></td>
                            <td><?= $f['cpf'] ?></td>
                            <td class="text-center">
                                <span class="badge badge-<?= $f['status'] == 'trabalhando' ? 'success' : 'warning' ?> p-2">
                                    <i class="fas <?= $f['status'] == 'trabalhando' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-1"></i>
                                    <?= ucfirst($f['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="<?= base_url('funcionarios/pdf/'.$f['id']) ?>" class="btn btn-default btn-sm border" title="Gerar PDF">
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    </a>

                                    <?php if (in_array(session()->get('nivel_acesso'), ['admin', 'gerente'])): ?>
                                        <a href="<?= base_url('funcionarios/editar/'.$f['id']) ?>" class="btn btn-default btn-sm border" title="Editar">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        <a href="<?= base_url('funcionarios/excluir/'.$f['id']) ?>" class="btn btn-default btn-sm border" title="Excluir" onclick="return confirm('Deseja realmente excluir este funcionário?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    <?php endif; ?>
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
        $("#tabela-funcionarios").DataTable({
            "responsive": true, 
            "autoWidth": false,
            "order": [[1, "asc"]], // Ordena por Nome em ordem alfabética por padrão
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Desabilita ordenação na coluna de Ações
            ]
        });
    });
</script>
<?= $this->endSection() ?>