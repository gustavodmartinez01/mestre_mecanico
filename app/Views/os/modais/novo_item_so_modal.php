<div class="modal fade" id="modalNovoItem" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content border-navy">
            <form action="<?= base_url('os/adicionar_item') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="ordem_servico_id" value="<?= $os['id'] ?>">
                
                <div class="modal-header bg-navy text-white p-2">
                    <h5 class="modal-title w-100 text-center">ADICIONAR ITEM</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Tipo de Item:</label>
                        <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                            <label class="btn btn-outline-primary active w-100">
                                <input type="radio" name="tipo" value="produto" checked onchange="carregarItens('produto')"> PRODUTO / PEÇA
                            </label>
                            <label class="btn btn-outline-primary w-100">
                                <input type="radio" name="tipo" value="servico" onchange="carregarItens('servico')"> SERVIÇO / M.O.
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label id="label_item">Selecionar Produto</label>
                        <select name="produto_id" id="select_item_busca" class="form-control select2" style="width: 100%" required>
                            <option value="">Aguardando seleção...</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Qtd</label>
                                <input type="number" name="quantidade" id="item_qtd" step="0.01" class="form-control" value="1" required oninput="calcularSubtotal()">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Preço Unitário (R$)</label>
                                <input type="number" name="valor_unitario" id="item_preco" step="0.01" class="form-control" required oninput="calcularSubtotal()">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border text-right mb-0">
                        <span class="text-muted">Subtotal:</span>
                        <h4 class="mb-0 text-navy" id="exibir_subtotal">R$ 0,00</h4>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">ADICIONAR À OS</button>
                </div>
            </form>
        </div>
    </div>
</div>