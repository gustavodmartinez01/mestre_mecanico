<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow-sm">
            <div class="inner">
                <h3><?= $qtd_abertas ?></h3>
                <p>OS em Aberto</p>
            </div>
            <div class="icon">
                <i class="fas fa-folder-open"></i>
            </div>
            <a href="<?= base_url('os') ?>" class="small-box-footer">Ver Lista <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm">
            <div class="inner">
                <h3>R$ <?= number_format($total_aberto, 2, ',', '.') ?></h3>
                <p>Total a Receber</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <span class="small-box-footer text-dark">Previsão de Entrada</span>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm">
            <div class="inner">
                <h3>R$ <?= number_format($faturamento_mes, 2, ',', '.') ?></h3>
                <p>Faturamento (Mês)</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="small-box-footer">Mês Atual</span>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary shadow-sm">
            <div class="inner">
                <h3><?= $qtd_hoje ?></h3>
                <p>Novas OS Hoje</p>
            </div>
            <div class="icon">
                <i class="fas fa-car-side"></i>
            </div>
            <span class="small-box-footer">Entrada na Oficina</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-outline card-secondary shadow-sm">
            <div class="card-header border-0">
                <h3 class="card-title text-bold"><i class="fas fa-history mr-2"></i> Últimas 10 Ordens de Serviço</h3>
                <div class="card-tools">
                    <a href="<?= base_url('os/nova') ?>" class="btn btn-tool btn-sm">
                        <i class="fas fa-plus"></i> Nova OS
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead>
                            <tr>
                                <th>OS nº</th>
                                <th>Cliente</th>
                                <th>Veículo</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($ultimas_os)): ?>
                                <?php foreach($ultimas_os as $os): ?>
                                <tr>
                                    <td><strong>#<?= str_pad($os['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                                    <td><?= $os['cliente_nome'] ?></td>
                                    <td><?= $os['veiculo_placa'] ?></td>
                                    <td>
                                        <span class="badge <?= ($os['status'] == 'aberta') ? 'badge-primary' : 'badge-success' ?>">
                                            <?= ucfirst($os['status']) ?>
                                        </span>
                                    </td>
                                    <td>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                                    <td>
                                        <a href="<?= base_url('os/gerenciar/'.$os['id']) ?>" class="btn btn-xs btn-default shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">Nenhuma movimentação registrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-users-cog mr-2"></i> Carga da Equipe</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-valign-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Funcionário</th>
                            <th class="text-center">Abertas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($resumo_equipe)): ?>
                            <?php foreach($resumo_equipe as $item): ?>
                            <tr>
                                <td><?= $item['funcionario'] ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($item['total'] >= 5) ? 'badge-danger' : 'badge-info' ?> px-3 py-1" style="font-size: 0.9rem;">
                                        <?= $item['total'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center py-5 text-muted">
                                    <i class="fas fa-mug-hot fa-2x mb-2"></i><br>
                                    Sem tarefas ativas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-box bg-light border shadow-sm mt-3">
            <span class="info-box-icon"><i class="fas fa-info-circle text-primary"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted">Atenção</span>
                <span class="info-box-number" style="font-weight: normal; font-size: 0.85rem;">
                    Badges em <span class="text-danger font-weight-bold">Vermelho</span> indicam mecânicos com mais de 5 OS simultâneas.
                </span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>