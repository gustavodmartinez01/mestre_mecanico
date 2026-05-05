<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    
    <!-- Cabeçalho de Ações -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h4 class="font-weight-bold text-dark">
            <i class="fas fa-file-invoice mr-2 text-primary"></i> OS #<?= $os['id'] ?>
        </h4>
        <div>
            <button onclick="window.print()" class="btn btn-light border shadow-sm">
                <i class="fas fa-print mr-1"></i> Imprimir
            </button>
            <a href="<?= base_url('os/pdf/' . $os['id']) ?>" class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf mr-1"></i> Gerar PDF
            </a>
            <?php if($os['status'] != 'finalizada'): ?>
                <a href="<?= base_url('os/editar/' . $os['id']) ?>" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Editar OS
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <!-- Coluna da Esquerda: Dados do Cliente e Veículo -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informações Gerais</h6>
                </div>
                <div class="card-body">
                    <label class="small font-weight-bold text-muted mb-0">STATUS ATUAL</label>
                    <div class="mb-3">
                        <span class="badge p-2 w-100 <?= $os['status'] == 'finalizada' ? 'badge-success' : 'badge-warning' ?>" style="font-size: 14px;">
                            <?= strtoupper($os['status']) ?>
                        </span>
                    </div>

                    <label class="small font-weight-bold text-muted mb-0">CLIENTE</label>
                    <p class="font-weight-bold mb-1"><?= $os['cliente_nome'] ?></p>
                    <p class="small"><i class="fas fa-phone mr-1"></i> <?= $os['cliente_fone'] ?></p>
                    <hr>
                    <label class="small font-weight-bold text-muted mb-0">VEÍCULO</label>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-dark mr-2 p-2"><?= $os['placa'] ?></span>
                        <span class="font-weight-bold"><?= $os['marca'] ?> <?= $os['modelo'] ?></span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-left-info">
                <div class="card-body">
                    <label class="small font-weight-bold text-info">RECLAMAÇÃO / SINTOMAS</label>
                    <p class="small text-dark"><?= nl2br(esc($os['defeito_reclamado'] ?? 'Não informado.')) ?></p>
                    <hr>
                    <label class="small font-weight-bold text-info">DIAGNÓSTICO TÉCNICO</label>
                    <p class="small text-dark"><?= nl2br(esc($os['observacoes'] ?? 'Aguardando diagnóstico.')) ?></p>
                </div>
            </div>
        </div>

        <!-- Coluna da Direita: Itens e Financeiro -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Peças e Serviços</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Item / Descrição</th>
                                <th class="text-center">Qtd</th>
                                <th class="text-right">Unitário</th>
                                <th class="text-right pr-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($itens)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum item adicionado a esta OS.</td></tr>
                            <?php endif; ?>
                            <?php foreach($itens as $item): ?>
                            <tr>
                                <td class="pl-4">
                                    <?= $item['descricao'] ?>
                                    <br><small class="text-muted text-uppercase"><?= $item['tipo'] ?></small>
                                </td>
                                <td class="text-center"><?= number_format($item['quantidade'], 2, ',', '.') ?></td>
                                <td class="text-right">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                                <td class="text-right pr-4 font-weight-bold">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white pt-4">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Total Peças/Serviços:</td>
                                    <td class="text-right font-weight-bold">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Desconto:</td>
                                    <td class="text-right text-danger">- R$ <?= number_format($os['desconto'] ?? 0, 2, ',', '.') ?></td>
                                </tr>
                                <tr class="border-top">
                                    <td class="h5 font-weight-bold">TOTAL FINAL:</td>
                                    <td class="h5 font-weight-bold text-right text-primary">R$ <?= number_format($os['valor_total'] - ($os['desconto'] ?? 0), 2, ',', '.') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Datas de Controle -->
            <div class="card shadow-sm">
                <div class="card-body py-2 px-4">
                    <div class="row text-center small text-muted">
                        <div class="col-md-4"><strong>ABERTURA:</strong> <?= date('d/m/Y H:i', strtotime($os['data_abertura'])) ?></div>
                        <div class="col-md-4"><strong>PREVISÃO:</strong> <?= $os['data_previsao'] ? date('d/m/Y', strtotime($os['data_previsao'])) : '-' ?></div>
                        <div class="col-md-4"><strong>FECHAMENTO:</strong> <?= $os['data_fechamento'] ? date('d/m/Y H:i', strtotime($os['data_fechamento'])) : '-' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    .card { border: none !important; shadow: none !important; }
}
</style>
<?= $this->endSection() ?>