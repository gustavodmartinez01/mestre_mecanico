<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-3">
        <div class="small-box bg-warning shadow-sm">
            <div class="inner">
                <h3><?= count(array_filter($ordens, fn($o) => $o['status'] == 'orcamento')) ?></h3>
                <p>Orçamentos Pendentes</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-info shadow-sm">
            <div class="inner">
                <h3><?= count(array_filter($ordens, fn($o) => $o['status'] == 'em_execucao')) ?></h3>
                <p>Em Execução</p>
            </div>
            <div class="icon"><i class="fas fa-tools"></i></div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline shadow">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-2"></i> Listagem de Ordens de Serviço</h3>
        <div class="card-tools">
            <a href="<?= base_url('os/nova') ?>" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus"></i> Abrir Nova OS
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <table id="tabela-os" class="table table-bordered table-striped table-hover w-100">
            <thead class="bg-dark text-white">
                <tr>
                    <th style="width: 80px">Nº OS</th>
                    <th>Cliente / Veículo</th>
                    <th class="text-center">Abertura</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Total (R$)</th>
                    <th class="text-center" style="width: 100px">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ordens as $o): 
                    $status_badges = [
                        'orcamento'   => 'badge-secondary',
                        'aberta'      => 'badge-warning',
                        'em_execucao' => 'badge-info',
                        'finalizada'  => 'badge-success',
                        'cancelada'   => 'badge-danger'
                    ];
                ?>
                <tr>
                    <td class="text-bold text-primary">#<?= $o['id'] ?></td>
                    <td>
                        <strong><?= $o['cliente_nome'] ?></strong><br>
                        <small class="text-muted"><?= $o['veiculo_modelo'] ?> - <span class="badge badge-light border"><?= $o['veiculo_placa'] ?></span></small>
                    </td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($o['data_abertura'])) ?></td>
                    <td class="text-center">
                        <span class="badge <?= $status_badges[$o['status']] ?? 'badge-dark' ?> p-2 text-uppercase" style="min-width: 100px">
                            <?= str_replace('_', ' ', $o['status']) ?>
                        </span>
                    </td>
                    <td class="text-right text-bold">R$ <?= number_format($o['valor_total'], 2, ',', '.') ?></td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="<?= base_url('os/gerenciar/'.$o['id']) ?>" class="btn btn-default btn-sm border" title="Gerenciar">
                                <i class="fas fa-cog text-primary"></i>
                            </a>
                            <a href="<?= base_url('os/imprimir/'.$o['id']) ?>" target="_blank" class="btn btn-default btn-sm border" title="Imprimir">
                                <i class="fas fa-print text-danger"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
        $("#tabela-os").DataTable({
            "responsive": true, 
            "autoWidth": false,
            "order": [[0, "desc"]], // Ordena pelo ID (OS mais nova)
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>