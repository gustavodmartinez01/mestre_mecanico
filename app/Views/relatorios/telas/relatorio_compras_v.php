<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="card shadow-sm border-top-success">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-shopping-basket mr-2 text-success"></i> 
                Relatório de Compras por Período
                <small class="text-muted ml-2">(<?= date('d/m/Y', strtotime($inicio)) ?> a <?= date('d/m/Y', strtotime($fim)) ?>)</small>
            </h3>
            <div class="card-tools ml-auto">
                <a href="<?= base_url('relatorios') ?>" class="btn btn-default btn-sm border shadow-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Voltar
                </a>
                
                <a href="<?= base_url("relatorios/pdf?tipo=compras&inicio=$inicio&fim=$fim") ?>" 
                   target="_blank" 
                   class="btn btn-danger btn-sm shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Gerar PDF Profissional
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="pl-4">ID</th>
                            <th>Data</th>
                            <th>Fornecedor (Razão Social)</th>
                            <th class="text-center">Status</th>
                            <th class="text-right pr-4">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalAcumulado = 0; 
                        foreach($compras as $c): 
                            $totalAcumulado += $c['valor_total'];
                        ?>
                        <tr>
                            <td class="pl-4 font-weight-bold">#<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
                            <td><?= $c['nome_fornecedor'] ?? '<span class="text-muted">Não informado</span>' ?></td>
                            <td class="text-center">
                                <?php if($c['status'] == 'finalizada'): ?>
                                    <span class="badge badge-success px-2 py-1">FINALIZADA</span>
                                <?php else: ?>
                                    <span class="badge badge-warning px-2 py-1">ABERTA</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right pr-4 font-weight-bold text-dark">
                                R$ <?= number_format($c['valor_total'], 2, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($compras)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle fa-2x d-block mb-2"></i>
                                Nenhuma compra encontrada para este período.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-0">
                        * Este relatório considera todas as requisições geradas no período selecionado.
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted text-uppercase mr-2 font-weight-bold" style="letter-spacing: 1px;">Soma do Período:</span>
                    <h3 class="d-inline-block font-weight-bold text-success mb-0">
                        R$ <?= number_format($totalAcumulado, 2, ',', '.') ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>