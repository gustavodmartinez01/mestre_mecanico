<?php if ($critico['is_critico']): ?>
<div class="alert alert-danger shadow-sm border-left-danger mb-4 py-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <i class="fas fa-exclamation-circle fa-3x text-danger animate__animated animate__pulse animate__infinite"></i>
        </div>
        <div class="col">
            <h5 class="font-weight-bold mb-1">ALERTA DE RISCO FINANCEIRO</h5>
            <p class="mb-0">
                Atenção: O total de contas <strong>VENCIDAS</strong> (R$ <?= number_format($critico['vencidas_total'], 2, ',', '.') ?>) 
                é maior que o total <strong>A RECEBER</strong> (R$ <?= number_format($critico['receber_total'], 2, ',', '.') ?>). 
                Isso indica um déficit imediato na saúde do caixa.
            </p>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body py-3">
                <h6 class="text-muted small font-weight-bold text-uppercase">Saldo Vencido (Pagar)</h6>
                <h4 class="font-weight-bold text-danger mb-0">R$ <?= number_format($critico['vencidas_total'], 2, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body py-3">
                <h6 class="text-muted small font-weight-bold text-uppercase">Total Pendente (Receber)</h6>
                <h4 class="font-weight-bold text-success mb-0">R$ <?= number_format($critico['receber_total'], 2, ',', '.') ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 <?= ($critico['receber_total'] - $critico['vencidas_total'] >= 0) ? 'bg-info' : 'bg-warning' ?>">
            <div class="card-body py-3 text-white">
                <h6 class="small font-weight-bold text-uppercase opacity-75">Expectativa de Saldo</h6>
                <h4 class="font-weight-bold mb-0">R$ <?= number_format($critico['receber_total'] - $critico['vencidas_total'], 2, ',', '.') ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm mb-4 border-left-warning">
            <div class="card-header bg-white py-2">
                <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-redo-alt mr-2"></i>Contas Recorrentes do Mês</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm table-hover mb-0 small">
                        <thead><tr class="bg-light"><th>Vencimento</th><th>Descrição</th><th class="text-right">Valor</th></tr></thead>
                        <tbody>
                            <?php foreach($alertas['recorrentes'] as $rec): ?>
                            <tr>
                                <td class="pl-3"><?= date('d/m/Y', strtotime($rec['data_vencimento'])) ?></td>
                                <td><?= esc($rec['descricao']) ?></td>
                                <td class="text-right pr-3 font-weight-bold text-danger">R$ <?= number_format($rec['valor_original'], 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; if(empty($alertas['recorrentes'])): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">Nenhuma pendência recorrente.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm mb-4 border-left-info">
            <div class="card-header bg-white py-2">
                <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-user-clock mr-2"></i>Devedores do Mês</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 250px;">
                    <table class="table table-sm table-hover mb-0 small">
                        <thead><tr class="bg-light"><th>Devedor</th><th>Referência</th><th class="text-right">Valor</th></tr></thead>
                        <tbody>
                            <?php foreach($alertas['devedores'] as $dev): ?>
                            <tr>
                                <td class="pl-3 font-weight-bold text-dark"><?= esc($dev['devedor_nome']) ?></td>
                                <td><?= esc($dev['descricao']) ?></td>
                                <td class="text-right pr-3 font-weight-bold text-primary">R$ <?= number_format($dev['valor_original'], 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; if(empty($alertas['devedores'])): ?>
                            <tr><td colspan="3" class="text-center py-3 text-muted">Nenhum devedor pendente este mês.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white py-2">
        <h6 class="m-0 font-weight-bold"><i class="fas fa-file-invoice-dollar mr-2"></i>Extrato de Movimentações e Saldo Acumulado</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3 py-2">Data</th>
                        <th class="py-2">Descrição</th>
                        <th class="py-2 text-center">Fluxo</th>
                        <th class="py-2 text-right">Valor</th>
                        <th class="py-2 text-right text-primary pr-3">Saldo Acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $acumulado = 0;
                    $movPorData = [];
                    foreach($movimentacoes as $m) { $movPorData[$m['data_movimentacao']][] = $m; }

                    if(!empty($movPorData)):
                        foreach($movPorData as $data => $itens):
                            foreach($itens as $index => $item):
                                $v = (float)$item['valor'];
                                if($item['tipo'] == 'entrada') { $acumulado += $v; } else { $acumulado -= $v; }
                    ?>
                        <tr>
                            <td class="pl-3 small"><?= ($index === 0) ? "<strong>".date('d/m/y', strtotime($data))."</strong>" : "" ?></td>
                            <td class="small"><?= esc($item['descricao']) ?></td>
                            <td class="text-center">
                                <i class="fas <?= $item['tipo'] == 'entrada' ? 'fa-arrow-up text-success' : 'fa-arrow-down text-danger' ?>"></i>
                            </td>
                            <td class="text-right font-weight-bold <?= $item['tipo'] == 'entrada' ? 'text-success' : 'text-danger' ?>">
                                <?= ($item['tipo'] == 'saida' ? '-' : '') ?> R$ <?= number_format($v, 2, ',', '.') ?>
                            </td>
                            <td class="text-right pr-3 font-weight-bold <?= ($acumulado >= 0) ? 'text-primary' : 'text-danger' ?>">
                                R$ <?= number_format($acumulado, 2, ',', '.') ?>
                            </td>
                        </tr>
                    <?php 
                            endforeach; 
                        endforeach; 
                    else: 
                    ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted small">Nenhuma movimentação financeira processada no período.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>