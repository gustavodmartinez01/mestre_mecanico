<?php $totalGeral = 0; ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="info-box shadow-sm border-left-success">
            <span class="info-box-icon bg-success"><i class="fas fa-shopping-basket"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold">INVESTIMENTO TOTAL</span>
                <span class="info-box-number h4 mb-0">R$ <?= number_format($stats['valor_total'], 2, ',', '.') ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box shadow-sm border-left-info">
            <span class="info-box-icon bg-info"><i class="fas fa-file-invoice-dollar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold">TICKET MÉDIO/COMPRA</span>
                <span class="info-box-number h4 mb-0">R$ <?= number_format($stats['ticket_medio'], 2, ',', '.') ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box shadow-sm border-left-warning">
            <span class="info-box-icon bg-warning"><i class="fas fa-tags"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted small font-weight-bold">MARCAS ADQUIRIDAS</span>
                <span class="info-box-number h4 mb-0"><?= $stats['marcas_distintas'] ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light py-2">
        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-list mr-1 text-success"></i> Detalativo de Requisições Finalizadas</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3">Data Fechamento</th>
                        <th>Fornecedor</th>
                        <th class="text-center">Qtd. Itens</th>
                        <th class="text-right pr-3">Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($compras)): ?>
                        <?php foreach($compras as $c): $totalGeral += $c['valor_total']; ?>
                        <tr>
                            <td class="pl-3 small"><?= date('d/m/Y H:i', strtotime($c['data_fechamento'])) ?></td>
                            <td class="small"><?= esc($c['fornecedor_nome'] ?: 'Fornecedor não informado') ?></td>
                            <td class="text-center small">
                                <span class="badge badge-light border">Ver detalhes</span>
                            </td>
                            <td class="text-right pr-3 font-weight-bold text-dark">
                                R$ <?= number_format($c['valor_total'], 2, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">Nenhuma compra finalizada neste período.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white text-right">
        <span class="text-muted small font-weight-bold">TOTAL GASTO NO PERÍODO:</span>
        <span class="h5 font-weight-bold text-success ml-2">R$ <?= number_format($totalGeral, 2, ',', '.') ?></span>
    </div>
</div>