<div class="modal fade" id="modalNovoItem" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg shadow-lg">
        <form action="<?= base_url('os/adicionar_item') ?>" method="post" class="modal-content border-0">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title"><i class="fas fa-cart-plus mr-2"></i> Adicionar Item à OS</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-light">
                <input type="hidden" name="ordem_servico_id" value="<?= $os['id'] ?>">
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group text-center">
                            <label class="d-block">O que deseja lançar?</label>
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                <label class="btn btn-outline-primary active">
                                    <input type="radio" name="tipo" id="opt-servico" value="servico" checked> <i class="fas fa-wrench"></i> Serviço
                                </label>
                                <label class="btn btn-outline-warning">
                                    <input type="radio" name="tipo" id="opt-produto" value="produto"> <i class="fas fa-box"></i> Produto
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Buscar no Cadastro</label>
                            <select name="produto_id" class="form-control select2" id="select-item-modal" required style="width: 100%"></select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Quantidade</label>
                            <input type="number" name="quantidade" class="form-control form-control-lg text-center" value="1" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Valor Unitário (R$)</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                                <input type="number" name="valor_unitario" class="form-control form-control-lg text-right" step="0.01" placeholder="0,00">
                            </div>
                            <small class="text-muted text-xs">* Vazio = Preço padrão</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-success px-4">
                    <i class="fas fa-check mr-1"></i> Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    function initSelect2Modal() {
        $('#select-item-modal').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#modalNovoItem'),
            placeholder: 'Pesquise pelo nome...',
            allowClear: true,
            language: "pt-BR"
        });
    }

    // Listas vindas do PHP (usando 'descricao' conforme suas tabelas)
    const listaServicos = `<option value=""></option><?php foreach($servicos as $s): ?><option value="<?= $s['id'] ?>"><?= addslashes($s['descricao']) ?> - R$ <?= number_format($s['preco_venda'], 2, ',', '.') ?></option><?php endforeach; ?>`;
    const listaProdutos = `<option value=""></option><?php foreach($produtos as $p): ?><option value="<?= $p['id'] ?>"><?= addslashes($p['descricao']) ?> - R$ <?= number_format($p['preco_venda'], 2, ',', '.') ?></option><?php endforeach; ?>`;

    $('input[name="tipo"]').on('change', function() {
        const tipo = $(this).val();
        const selectItem = $('#select-item-modal');
        if (selectItem.hasClass('select2-hidden-accessible')) { selectItem.select2('destroy'); }
        selectItem.empty().append(tipo === 'servico' ? listaServicos : listaProdutos);
        initSelect2Modal();
    });

    $('#select-item-modal').append(listaServicos);
    initSelect2Modal();
});
</script>