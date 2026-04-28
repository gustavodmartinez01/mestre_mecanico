<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-light border-left-success">
            <div class="card-body py-2">
                <small class="font-weight-bold text-success">ENTRADAS</small>
                <h5 class="mb-0">R$ <?= number_format($resumo['entradas'], 2, ',', '.') ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light border-left-danger">
            <div class="card-body py-2">
                <small class="font-weight-bold text-danger">SAÍDAS</small>
                <h5 class="mb-0">R$ <?= number_format($resumo['saidas'], 2, ',', '.') ?></h5>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card <?= $resumo['saldo'] >= 0 ? 'bg-success' : 'bg-danger' ?> text-white">
            <div class="card-body py-2">
                <small class="font-weight-bold">SALDO DO PERÍODO</small>
                <h5 class="mb-0">R$ <?= number_format($resumo['saldo'], 2, ',', '.') ?></h5>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="pl-3">Data</th>
                    <th>Descrição</th>
                    <th>Origem</th>
                    <th class="text-right pr-3">Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($movimentacoes as $m): ?>
                <tr>
                    <td class="pl-3 small"><?= date('d/m/Y', strtotime($m['data_movimentacao'])) ?></td>
                    <td class="small"><?= esc($m['descricao']) ?></td>
                    <td><span class="badge badge-light border"><?= strtoupper($m['origem_tabela']) ?></span></td>
                    <td class="text-right pr-3 font-weight-bold <?= $m['tipo'] == 'entrada' ? 'text-success' : 'text-danger' ?>">
                        <?= $m['tipo'] == 'entrada' ? '+' : '-' ?> R$ <?= number_format($m['valor'], 2, ',', '.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>