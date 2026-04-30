<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Ficha Técnica do Veículo -->
        <div class="col-md-4">
            <div class="card shadow-sm border-left-primary mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ficha Técnica</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="badge badge-dark p-3 shadow-sm" style="font-size: 20px; border: 2px solid #fff; border-radius: 8px;">
                            <?= $veiculo['placa'] ?>
                        </span>
                        <h5 class="mt-3 font-weight-bold"><?= $veiculo['marca'] ?> <?= $veiculo['modelo'] ?></h5>
                    </div>
                    <hr>
                    <p><strong>Cor:</strong> <?= $veiculo['cor'] ?></p>
                    <p><strong>Ano:</strong> <?= $veiculo['ano'] ?></p>
                    <p><strong>Renavam:</strong> <?= $veiculo['renavam'] ?? '-' ?></p>
                    <p><strong>Chassis:</strong> <?= $veiculo['chassis'] ?? '-' ?></p>
                    <hr>
                    <p class="small text-muted"><strong>Observações:</strong><br><?= $veiculo['observacoes'] ?></p>
                </div>
            </div>
        </div>

        <!-- Linha do Tempo de Serviços (Histórico de OS) -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-tools mr-2"></i> Histórico de Manutenções</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th>OS #</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th>Valor Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($historico)): ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted">Nenhuma Ordem de Serviço registrada para este veículo.</td></tr>
                                <?php endif; ?>
                                
                                <?php foreach($historico as $os): ?>
                                <tr>
                                    <td class="text-center font-weight-bold">#<?= $os['id'] ?></td>
                                    <td class="text-center"><?= date('d/m/Y', strtotime($os['created_at'])) ?></td>
                                    <td class="text-center">
                                        <span class="badge <?= $os['status'] == 'finalizada' ? 'badge-success' : 'badge-warning' ?>">
                                            <?= strtoupper($os['status']) ?>
                                        </span>
                                    </td>
                                    <td class="text-right pr-4">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('os/detalhes/'.$os['id']) ?>" class="btn btn-sm btn-light border">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>