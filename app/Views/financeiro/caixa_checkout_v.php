<?= $this->extend('layout/main') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-cash-register mr-2"></i> 
                    Finalizar Acerto: OS #<?= $os['id'] ?>
                </h3>
            </div>
            
            <form action="<?= base_url('caixa/processar') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="os_id" value="<?= $os['id'] ?>">
                <input type="hidden" name="cliente_id" value="<?= $os['cliente_id'] ?>">

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h6><strong>Cliente:</strong> <?= $os['cliente_nome'] ?></h6>
                            <h6><strong>Veículo:</strong> <?= $os['veiculo_modelo'] ?> (<?= $os['veiculo_placa'] ?>)</h6>
                        </div>
                        <div class="col-sm-6 text-right">
                            <h4 class="text-muted">Total Bruto</h4>
                            <h3>R$ <?= number_format($os['total_bruto'], 2, ',', '.') ?></h3>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="desconto">Desconto (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-minus text-danger"></i></span></div>
                                    <input type="number" step="0.01" name="desconto" id="desconto" class="form-control" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="acrescimo">Acréscimo/Juros (R$)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-plus text-success"></i></span></div>
                                    <input type="number" step="0.01" name="acrescimo" id="acrescimo" class="form-control" value="0.00">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Valor Líquido</label>
                                <input type="text" name="valor_final" id="valor_final" class="form-control form-control-lg bg-dark text-warning font-weight-bold text-center" value="<?= $os['total_bruto'] ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Forma de Pagamento Principal</label>
                                <select name="forma_pagamento" class="form-control select2" required style="width: 100%;">
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="pix">PIX</option>
                                    <option value="cartao_credito">Cartão de Crédito</option>
                                    <option value="cartao_debito">Cartão de Débito</option>
                                    <option value="duplicata">Duplicata</option>
                                    <option value="promissoria">Nota Promissória</option>
                                    <option value="boleto">Boleto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nº Parcelas</label>
                                <input type="number" name="num_parcelas" id="num_parcelas" min="1" max="48" class="form-control text-center" value="1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>1º Vencimento</label>
                                <input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observações do Financeiro</label>
                        <textarea name="observacoes" class="form-control" rows="2" placeholder="Ex: Recebido entrada de 50% via PIX e saldo em 2x no boleto..."></textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-save mr-1"></i> Confirmar Acerto e Gerar Títulos
                    </button>
                    <a href="<?= base_url('os/gerenciar/'.$os['id']) ?>" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Itens da OS</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $item): ?>
                        <tr>
                            <td>
                                <small class="text-muted d-block"><?= ($item['tipo'] == 'peca') ? 'Peça' : 'Mão de Obra' ?></small>
                                <?= $item['descricao'] ?>
                            </td>
                            <td class="text-right">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        const totalOriginal = parseFloat(<?= $os['total_bruto'] ?>);
        
        // Função para recalcular o valor líquido
        function atualizarValorFinal() {
            let desc = parseFloat($('#desconto').val()) || 0;
            let acre = parseFloat($('#acrescimo').val()) || 0;
            let final = totalOriginal - desc + acre;
            
            // Garante que o valor não seja negativo
            final = Math.max(0, final);
            $('#valor_final').val(final.toFixed(2));
        }

        $('#desconto, #acrescimo').on('input', atualizarValorFinal);
        
        // Inicializa o valor final
        atualizarValorFinal();
    });
</script>
<?= $this->endSection() ?>