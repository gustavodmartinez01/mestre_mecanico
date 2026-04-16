<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-dark">
                <i class="fas fa-file-invoice mr-2 text-primary"></i> 
                Requisição #<?= str_pad($requisicao['id'], 5, '0', STR_PAD_LEFT) ?>
            </h4>
        </div>
        <div class="col-sm-6 text-right">
            <a href="<?= base_url('compras') ?>" class="btn btn-default border shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
            
            <a href="<?= base_url('compras/imprimir/'.$requisicao['id']) ?>" target="_blank" class="btn btn-info shadow-sm">
                <i class="fas fa-print mr-1"></i> Gerar PDF
            </a>

            <?php if($requisicao['status'] == 'aberta'): ?>
                <a href="<?= base_url('compras/editar/'.$requisicao['id']) ?>" class="btn btn-warning shadow-sm">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-outline <?= $requisicao['status'] == 'aberta' ? 'card-warning' : 'card-success' ?> shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Resumo do Pedido</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tr>
                            <td class="font-weight-bold text-muted border-top-0">Status:</td>
                            <td class="border-top-0">
                                <?php if($requisicao['status'] == 'aberta'): ?>
                                    <span class="badge badge-warning px-3 py-2">ABERTA</span>
                                <?php else: ?>
                                    <span class="badge badge-success px-3 py-2">FINALIZADA</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-muted">Fornecedor:</td>
                            <td><i class="fas fa-truck mr-1"></i> <?= $requisicao['nome_fornecedor'] ?? 'S/ Fornecedor' ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold text-muted">Data Emissão:</td>
                            <td><?= date('d/m/Y H:i', strtotime($requisicao['created_at'])) ?></td>
                        </tr>
                        <?php if($requisicao['data_fechamento']): ?>
                        <tr>
                            <td class="font-weight-bold text-muted text-success">Fechamento:</td>
                            <td class="text-success font-weight-bold">
                                <?= date('d/m/Y H:i', strtotime($requisicao['data_fechamento'])) ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="card-footer bg-light text-center py-4">
                    <small class="text-muted d-block mb-1">VALOR TOTAL</small>
                    <h3 class="text-primary font-weight-bold">R$ <?= number_format($requisicao['valor_total'], 2, ',', '.') ?></h3>
                </div>
            </div>

            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white font-weight-bold">Observações</div>
                <div class="card-body">
                    <p class="text-muted small">
                        <?= !empty($requisicao['observacoes']) ? nl2br($requisicao['observacoes']) : 'Nenhuma observação interna registrada.' ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold text-muted">
                        <i class="fas fa-list mr-1"></i> Itens da Requisição
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Descrição / Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-right">Unitário</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-center">Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($itens as $it): ?>
                                <tr>
                                    <td>
                                        <span class="font-weight-bold d-block text-dark"><?= $it['descricao_item'] ?></span>
                                        <?php if($it['produto_id']): ?>
                                            <span class="badge badge-light border text-primary small">PRODUTO ID: <?= $it['produto_id'] ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-light border text-muted small">SERVIÇO / DIVERSOS</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?= number_format($it['quantidade'], 2, ',', '.') ?>
                                    </td>
                                    <td class="text-right align-middle text-muted">
                                        R$ <?= number_format($it['valor_unitario'], 2, ',', '.') ?>
                                    </td>
                                    <td class="text-right align-middle font-weight-bold">
                                        R$ <?= number_format($it['subtotal'], 2, ',', '.') ?>
                                    </td>
                                    <td class="text-center align-middle">
                                        <?php if($it['situacao'] == 'atendido'): ?>
                                            <span class="text-success" title="Atendido/Recebido">
                                                <i class="fas fa-check-double fa-lg"></i>
                                            </span>
                                        <?php elseif($it['situacao'] == 'nao_atendido'): ?>
                                            <span class="text-danger" title="Não Atendido">
                                                <i class="fas fa-times-circle fa-lg"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-warning" title="Pendente">
                                                <i class="fas fa-clock fa-lg"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info border-0 shadow-sm mt-3">
                <i class="fas fa-info-circle mr-2"></i>
                Esta requisição foi gerada automaticamente pelo sistema e está sujeita a aprovação financeira.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>