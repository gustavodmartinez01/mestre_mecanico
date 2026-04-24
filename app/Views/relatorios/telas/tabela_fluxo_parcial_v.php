<?php
$totalEntradas = 0;
$totalSaidas = 0;
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold text-uppercase">OS Concluídas</span>
                <span class="info-box-number h5 mb-0"><?= $stats['total_concluidas'] ?></span>
                <span class="progress-description text-muted small">
                    Média: <?= $stats['media_mensal'] ?> /mês
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ban"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold text-uppercase">OS Canceladas</span>
                <span class="info-box-number h5 mb-0"><?= $stats['total_canceladas'] ?></span>
                <span class="progress-description text-muted small">
                    No período selecionado
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold text-uppercase">Lead Time Médio</span>
                <span class="info-box-number h5 mb-0"><?= $stats['tempo_medio'] ?> <small>h</small></span>
                <span class="progress-description text-muted small">
                    Aprovação ao Fechamento
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-pie"></i></span>
            <div class="info-box-content">
                <?php 
                    $totalGeral = $stats['total_concluidas'] + $stats['total_canceladas'];
                    $eficiencia = $totalGeral > 0 ? ($stats['total_concluidas'] / $totalGeral) * 100 : 0;
                ?>
                <span class="info-box-text text-muted small font-weight-bold text-uppercase">Conversão</span>
                <span class="info-box-number h5 mb-0"><?= number_format($eficiencia, 1) ?>%</span>
                <div class="progress progress-xxs mt-1">
                    <div class="progress-bar bg-warning" style="width: <?= $eficiencia ?>%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-left-success shadow-sm">
            <div class="card-header bg-light py-2">
                <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-arrow-up mr-1"></i> Entradas (OS)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="pl-3">Data</th>
                                <th>Cliente</th>
                                <th class="text-right pr-3">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($entradas)): ?>
                                <?php foreach ($entradas as $e): $totalEntradas += $e['valor_total']; ?>
                                    <tr>
                                        <td class="pl-3 small"><?= date('d/m/y', strtotime($e['data_fechamento'])) ?></td>
                                        <td class="small text-truncate" style="max-width: 180px;"><?= esc($e['cliente_nome']) ?></td>
                                        <td class="text-right pr-3 font-weight-bold text-success">R$ <?= number_format($e['valor_total'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">Sem entradas registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-right">
                <span class="small text-muted">SUBTOTAL:</span> 
                <span class="h6 font-weight-bold text-success ml-2">R$ <?= number_format($totalEntradas, 2, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-left-danger shadow-sm">
            <div class="card-header bg-light py-2">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-arrow-down mr-1"></i> Saídas (Compras)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th class="pl-3">Data</th>
                                <th>Favorecido</th>
                                <th class="text-right pr-3">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($saidas)): ?>
                                <?php foreach ($saidas as $s): $totalSaidas += $s['valor_total']; ?>
                                    <tr>
                                        <td class="pl-3 small"><?= date('d/m/y', strtotime($s['data_fechamento'])) ?></td>
                                        <td class="small text-truncate" style="max-width: 180px;"><?= esc($s['favorecido']) ?></td>
                                        <td class="text-right pr-3 font-weight-bold text-danger">R$ <?= number_format($s['valor_total'], 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">Sem saídas registradas.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-right">
                <span class="small text-muted">SUBTOTAL:</span> 
                <span class="h6 font-weight-bold text-danger ml-2">R$ <?= number_format($totalSaidas, 2, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3 mb-5">
    <div class="col-12">
        <div class="card bg-dark text-white">
            <div class="card-body py-2 d-flex justify-content-between align-items-center">
                <span class="font-weight-bold small"><i class="fas fa-balance-scale mr-2"></i> SALDO LÍQUIDO NO PERÍODO</span>
                <h4 class="mb-0 font-weight-bold <?= ($totalEntradas - $totalSaidas >= 0) ? 'text-info' : 'text-warning' ?>">
                    R$ <?= number_format($totalEntradas - $totalSaidas, 2, ',', '.') ?>
                </h4>
            </div>
        </div>
    </div>
</div>