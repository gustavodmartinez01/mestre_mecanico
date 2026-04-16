<div class="modal fade" id="modalBaixa" tabindex="-1" role="dialog" aria-labelledby="modalBaixaLabel" aria-hidden="true">
    <div class="modal-dialog shadow-lg" role="document">
        <form action="<?= base_url('contas-receber/processar-baixa') ?>" method="post">
            <?= csrf_field() ?>
            
            <input type="hidden" name="id_parcela" id="baixa_id_parcela">
            <input type="hidden" id="valor_base_original">
            
            <div class="modal-content border-top border-success">
                <div class="modal-header bg-light py-2">
                    <h5 class="modal-title font-weight-bold text-success text-sm text-uppercase">
                        <i class="fas fa-cash-register mr-2"></i> Receber Parcela: <span id="label_parcela" class="badge badge-success">0/0</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div class="callout callout-info p-2 mb-3 bg-light border-left-3">
                        <span class="text-xs text-muted text-uppercase d-block font-weight-bold">Identificação / Cliente</span>
                        <div id="baixa_descricao" class="text-sm">-</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 border-right">
                            <label class="text-xs font-weight-bold text-uppercase">Vencimento Original</label>
                            <input type="text" id="baixa_vencimento" class="form-control form-control-sm bg-white" readonly>
                        </div>
                        <div class="col-6 pl-3">
                            <label class="text-xs font-weight-bold text-uppercase">Valor da Parcela</label>
                            <input type="text" id="baixa_valor_view" class="form-control form-control-sm font-weight-bold text-dark" readonly>
                        </div>
                    </div>

                    <div class="row border-top pt-3 pb-2 bg-light rounded shadow-sm mx-0">
                        
                        <div class="col-md-4 border-right">
                            <div class="custom-control custom-switch custom-switch-on-danger">
                                <input type="checkbox" class="custom-control-input chk-ajuste" id="chk_multa">
                                <label class="custom-control-label text-xs font-weight-bold" for="chk_multa">MULTA (%)</label>
                            </div>
                            <div id="div_multa" class="mt-2" style="display:none;">
                                <div class="input-group input-group-sm">
                                    <input type="number" id="perc_multa" step="0.01" class="form-control input-perc" placeholder="0.00">
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                </div>
                                <input type="number" name="valor_multa" id="valor_multa" step="0.01" class="form-control form-control-sm mt-1 text-danger font-weight-bold bg-white" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 border-right">
                            <div class="custom-control custom-switch custom-switch-on-danger">
                                <input type="checkbox" class="custom-control-input chk-ajuste" id="chk_juros">
                                <label class="custom-control-label text-xs font-weight-bold" for="chk_juros">JUROS (%)</label>
                            </div>
                            <div id="div_juros" class="mt-2" style="display:none;">
                                <div class="input-group input-group-sm">
                                    <input type="number" id="perc_juros" step="0.01" class="form-control input-perc" placeholder="0.00">
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                </div>
                                <input type="number" name="valor_juros" id="valor_juros" step="0.01" class="form-control form-control-sm mt-1 text-danger font-weight-bold bg-white" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="custom-control custom-switch custom-switch-on-success">
                                <input type="checkbox" class="custom-control-input chk-ajuste" id="chk_desconto">
                                <label class="custom-control-label text-xs font-weight-bold" for="chk_desconto">DESCONTO</label>
                            </div>
                            <div id="div_desconto" class="mt-2" style="display:none;">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                                    <input type="number" name="valor_desconto" id="valor_desconto" step="0.01" value="0.00" class="form-control border-success input-calc">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded border bg-navy shadow-sm">
                        <label class="text-xs text-uppercase font-weight-bold mb-1 d-block text-white">Total Final a Receber (R$)</label>
                        <input type="number" name="valor_recebido" id="baixa_valor_final" step="0.01" class="form-control form-control-lg font-weight-bold text-success" required>
                        <small class="text-gray-300 text-xs mt-1 d-block">Confirme o valor físico (dinheiro/pix) antes de salvar.</small>
                    </div>

                    <div class="row mt-3">
                        <div class="col-6">
                            <label class="text-xs font-weight-bold text-uppercase">Forma de Pagto</label>
                            <select name="forma_pagamento" class="form-control form-control-sm select2" required>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="pix" selected>PIX</option>
                                <option value="cartao_debito">Cartão de Débito</option>
                                <option value="cartao_credito">Cartão de Crédito</option>
                                <option value="transferencia">Transferência</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="text-xs font-weight-bold text-uppercase">Data Recebimento</label>
                            <input type="date" name="data_pagamento" class="form-control form-control-sm font-weight-bold" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm text-uppercase">
                        <i class="fas fa-check-double mr-2"></i> Confirmar Baixa
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>