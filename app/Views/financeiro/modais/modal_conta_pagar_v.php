<div class="modal fade" id="modalNovoLancamento" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar mr-2"></i> Nova Conta a Pagar</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="form-novo-pagar" action="<?= base_url('contas-pagar/salvar') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Descrição do Gasto</label>
                                <input type="text" name="descricao" class="form-control" placeholder="Ex: Aluguel, Internet, Compra de Peças..." required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Tipo de Lançamento</label>
                                <select name="tipo_fluxo" id="tipo_fluxo" class="form-control" onchange="ajustarLabelValor()">
                                    <option value="unico">Pagamento Único</option>
                                    <option value="parcelado">Parcelar (Dividir valor total)</option>
                                    <option value="repetir">Repetir (Valor fixo mensal)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="label_valor_principal">Valor Total</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                                    <input type="number" step="0.01" name="valor_total" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label id="label_qtd_parcelas">Quantidade</label>
                                <input type="number" name="total_parcelas" class="form-control" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>1º Vencimento</label>
                                <input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select name="categoria_id" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Boleto / Documento (Opcional)</label>
                                <div class="custom-file">
                                    <input type="file" name="arquivo_origem" class="custom-file-input" id="fileOrigem">
                                    <label class="custom-file-label" for="fileOrigem">Anexar PDF/Foto</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Centro de Custo</label>
                                <select name="centro_custo_id" class="form-control">
                                    <?php foreach ($centrosCusto as $cc): ?>
                                        <option value="<?= $cc['id'] ?>"><?= $cc['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Forma de Pagto. Sugerida</label>
                                <select name="forma_pagamento" class="form-control">
                                    <option value="pix">PIX</option>
                                    <option value="boleto">Boleto</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="cartao_credito">Cartão de Crédito</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger text-bold">GERAR LANÇAMENTOS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function ajustarLabelValor() {
    const tipo = document.getElementById('tipo_fluxo').value;
    const labelValor = document.getElementById('label_valor_principal');
    const labelQtd = document.getElementById('label_qtd_parcelas');

    if (tipo === 'repetir') {
        labelValor.innerText = 'Valor da Parcela (Mensal)';
        labelQtd.innerText = 'Repetir por quantos meses?';
    } else if (tipo === 'parcelado') {
        labelValor.innerText = 'Valor Total da Compra';
        labelQtd.innerText = 'Número de Parcelas';
    } else {
        labelValor.innerText = 'Valor Total';
        labelQtd.innerText = 'Quantidade';
    }
}
</script>