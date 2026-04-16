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
                <h3 class="card-title font-weight-bold"><i class="fas fa-users mr-2"></i> Gestão de Clientes e Score de Risco</h3>
                <div class="card-tools">
                    <a href="<?= base_url('clientes/novo') ?>" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Novo Cliente
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <table id="tabela-clientes" class="table table-bordered table-striped table-hover w-100">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Cliente</th>
                            <th>CPF/CNPJ</th>
                            <th class="text-center">Score</th>
                            <th class="text-center">Classificação</th>
                            <th>Contato</th>
                            <th class="text-center" style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clientes as $c): ?>
                        <tr>
                            <td>
                                <strong class="text-primary"><?= $c['nome_razao'] ?></strong>
                                <br><small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> <?= date('d/m/Y', strtotime($c['created_at'])) ?></small>
                            </td>
                            <td><?= $c['documento'] ?></td>
                            <td class="text-center">
                                <span class="badge badge-light border"><?= $c['score_total'] ?> pts</span>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $classe = 'secondary';
                                    $icon = 'info-circle';
                                    if($c['classificacao'] == 'Ouro') { $classe = 'success'; $icon = 'star'; }
                                    if($c['classificacao'] == 'Prata') { $classe = 'warning'; $icon = 'medal'; }
                                    if($c['classificacao'] == 'Bronze') { $classe = 'danger'; $icon = 'exclamation-triangle'; }
                                ?>
                                <span class="badge badge-<?= $classe ?> p-2 shadow-sm" style="min-width: 100px;">
                                    <i class="fas fa-<?= $icon ?> mr-1"></i> <?= strtoupper($c['classificacao']) ?>
                                </span>
                            </td>
                            <td>
                                <i class="fab fa-whatsapp text-success mr-1"></i> <?= $c['celular'] ?><br>
                                <small class="text-muted"><i class="fas fa-envelope mr-1"></i> <?= $c['email'] ?></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="<?= base_url('clientes/detalhes/'.$c['id']) ?>" class="btn btn-default btn-sm border" title="Veículos e Histórico">
                                        <i class="fas fa-car text-info"></i>
                                    </a>
                                    <a href="<?= base_url('clientes/editar/'.$c['id']) ?>" class="btn btn-default btn-sm border" title="Editar Cadastro">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <button onclick="confirmarExclusao(<?= $c['id'] ?>)" class="btn btn-default btn-sm border" title="Excluir">
                                        <i class="fas fa-trash text-danger"></i>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function () {
        $("#tabela-clientes").DataTable({
            "responsive": true, 
            "autoWidth": false,
            "order": [[0, "asc"]], // Ordena pelo Nome do Cliente
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": 5 } // Ações não são ordenáveis
            ]
        });
    });

    function confirmarExclusao(id) {
        if(confirm('Atenção: Ao excluir o cliente, todos os veículos vinculados também serão afetados. Deseja continuar?')) {
            window.location.href = "<?= base_url('clientes/excluir/') ?>/" + id;
        }
    }
</script>
<?= $this->endSection() ?>