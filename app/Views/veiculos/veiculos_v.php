<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark"><i class="fas fa-car mr-2 text-primary"></i> Frota de Veículos</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead class="bg-light">
                        <tr>
                            <th>Placa</th>
                            <th>Veículo</th>
                            <th>Ano</th>
                            <th>Cliente Atual</th>
                            <th>Última OS</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($veiculos as $v): ?>
                        <tr>
                            <td><span class="badge badge-dark p-2"><?= $v['placa'] ?></span></td>
                            <td><strong><?= $v['marca'] ?> <?= $v['modelo'] ?></strong><br><small class="text-muted"><?= $v['cor'] ?></small></td>
                            <td><?= $v['ano'] ?></td>
                            <td><i class="fas fa-user small mr-1"></i> <?= $v['nome_cliente'] ?></td>
                            <td><small><?= date('d/m/Y', strtotime($v['created_at'])) ?></small></td>
                            <td class="text-center">
                                <a href="<?= base_url('veiculos/detalhes/'.$v['id']) ?>" class="btn btn-sm btn-info" title="Ver Histórico">
                                    <i class="fas fa-history"></i>
                                </a>
                                <button onclick="editarVeiculo(<?= htmlspecialchars(json_encode($v)) ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
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