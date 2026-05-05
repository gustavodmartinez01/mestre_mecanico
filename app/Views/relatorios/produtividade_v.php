<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <h4 class="font-weight-bold text-dark mb-4"><i class="fas fa-chart-line mr-2 text-primary"></i> Produtividade da Oficina</h4>

    <!-- Filtro de Data -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">DATA INÍCIO</label>
                    <input type="date" name="inicio" class="form-control" value="<?= $inicio ?>">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">DATA FIM</label>
                    <input type="date" name="fim" class="form-control" value="<?= $fim ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cards de Indicadores -->
    <div class="row">
        <?php 
            $totalGeral = 0;
            $qtdOS = 0;
            foreach($resumoOS as $r) {
                $totalGeral += $r['financeiro'];
                $qtdOS += $r['total'];
            }
            $ticketMedio = $qtdOS > 0 ? $totalGeral / $qtdOS : 0;
        ?>
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Volume Total (Período)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?= number_format($totalGeral, 2, ',', '.') ?></div>
                    <div class="mt-2 small text-muted"><?= $qtdOS ?> Ordens de Serviço abertas</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Ticket Médio por OS</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?= number_format($ticketMedio, 2, ',', '.') ?></div>
                    <div class="mt-2 small text-muted">Média de faturamento por entrada</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Conversão de Finalizadas</div>
                    <?php 
                        $finalizadas = array_filter($resumoOS, fn($f) => $f['status'] == 'finalizada');
                        $qtdFin = !empty($finalizadas) ? reset($finalizadas)['total'] : 0;
                        $percentual = $qtdOS > 0 ? ($qtdFin / $qtdOS) * 100 : 0;
                    ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($percentual, 1) ?>%</div>
                    <div class="progress progress-sm mr-2 mt-2">
                        <div class="progress-bar bg-info" style="width: <?= $percentual ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ranking de Serviços/Peças -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Top 10 Itens (Mais Rentáveis)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Descrição do Item</th>
                                    <th class="text-center">Qtd Vendida</th>
                                    <th class="text-right pr-4">Total Bruto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($rankingItens as $item): ?>
                                <tr>
                                    <td class="font-weight-bold"><?= $item['descricao'] ?></td>
                                    <td class="text-center"><?= number_format($item['qtd'], 0) ?></td>
                                    <td class="text-right pr-4 text-success font-weight-bold">R$ <?= number_format($item['total_gerado'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo por Status -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 text-center">
                    <h6 class="m-0 font-weight-bold text-dark">Distribuição por Status</h6>
                </div>
                <div class="card-body">
                    <?php foreach($resumoOS as $status): ?>
                        <div class="mb-3">
                            <div class="small font-weight-bold"><?= strtoupper($status['status']) ?> <span class="float-right"><?= $status['total'] ?> OS</span></div>
                            <div class="progress mb-1">
                                <div class="progress-bar bg-primary" style="width: <?= ($status['total'] / $qtdOS) * 100 ?>%"></div>
                            </div>
                            <small class="text-muted">Total: R$ <?= number_format($status['financeiro'], 2, ',', '.') ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>