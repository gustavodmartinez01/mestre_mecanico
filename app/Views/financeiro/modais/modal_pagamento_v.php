<div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="fas fa-check-double mr-2"></i> Confirmar Pagamento?</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="form-baixa-pagar" enctype="multipart/form-data">
                <input type="hidden" name="id_parcela" id="pagar_id_parcela">
                <div class="modal-body">
                    <div class="callout callout-info">
                        <p id="pagar_descricao_display"></p>
                        <h5 class="text-bold">Parcela: <span id="pagar_parcela_label"></span></h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data do Pagamento</label>
                                <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Valor Pago</label>
                                <input type="number" step="0.01" name="valor_pagamento" id="pagar_valor_final" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Anexar Comprovante (PDF/Imagem)</label>
                        <div class="custom-file">
                            <input type="file" name="comprovante" class="custom-file-input" id="comprovanteFile">
                            <label class="custom-file-label" for="comprovanteFile">Escolher arquivo</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Observações</label>
                        <textarea name="observacoes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">CONFIRMAR PAGAMENTO</button>
                </div>
            </form>
        </div>
    </div>
</div>