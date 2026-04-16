<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-5">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exchange-alt mr-2"></i> Nova Movimentação</h3>
            </div>
            <form action="<?= base_url('produtos/processar_movimentacao') ?>" method="post">
                <input type="hidden" name="produto_id" value="<?= $p['id'] ?>">
                <div class="card-body">
                    <div class="alert alert-light border">
                        <h5><strong><?= $p['nome'] ?></strong></h5>
                        <p class="mb-0 text-muted">Marca: <?= $p['marca'] ?></p>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Estoque Atual:</span>
                            <span class="h4 mb-0 text-primary font-weight-bold"><?= $p['estoque_atual'] ?> <?= $p['unidade_medida'] ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Operação</label>
                        <select name="tipo" class="form-control form-control-lg" required>
                            <option value="E">⬆ ENTRADA (Compra/Reposição)</option>
                            <option value="S">⬇ SAÍDA (Ajuste/Perda/Consumo)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quantidade (<?= $p['unidade_medida'] ?>)</label>
                        <input type="number" name="quantidade" class="form-control form-control-lg" min="1" value="1" required>
                    </div>

                    <div class="form-group">
                        <label>Motivo / Observação</label>
                        <textarea name="observacao" class="form-control" rows="3" placeholder="Ex: NF 455 - Fornecedor X ou Peça danificada" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-block">Confirmar Movimentação</button>
                    <a href="<?= base_url('produtos') ?>" class="btn btn-default btn-block">Voltar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card card-dark">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history mr-2"></i> Últimas Movimentações</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-sm table-striped m-0">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th class="text-center">Qtd</th>
                                <th>Origem/Obs</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($historico as $h): ?>
                            <tr>
                                <td class="small"><?= date('d/m/Y H:i', strtotime($h['data_movimento'])) ?></td>
                                <td>
                                    <?php if($h['tipo'] == 'E'): ?>
                                        <span class="badge badge-success"><i class="fas fa-arrow-up"></i> Entrada</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fas fa-arrow-down"></i> Saída</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center font-weight-bold"><?= $h['quantidade'] ?></td>
                                <td class="small">
                                    <strong><?= $h['origem'] ?></strong><br>
                                    <span class="text-muted"><?= $h['observacao'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($historico)): ?>
                                <tr><td colspan="4" class="text-center p-4">Nenhuma movimentação registrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>