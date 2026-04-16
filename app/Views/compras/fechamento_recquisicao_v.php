<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <form action="<?= base_url('compras/finalizar_requisicao') ?>" method="post" id="form-fechamento">
        <?= csrf_field() ?>
        <input type="hidden" name="id_requisicao" value="<?= $requisicao['id'] ?>">
        <input type="hidden" name="fornecedor_id" value="<?= $requisicao['fornecedor_id'] ?>">

        <div class="row mb-3">
            <div class="col-sm-6">
                <h4 class="font-weight-bold text-dark">
                    <i class="fas fa-clipboard-check mr-2 text-success"></i> 
                    Fechamento: Requisição #<?= str_pad($requisicao['id'], 4, '0', STR_PAD_LEFT) ?>
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-success shadow">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold text-muted">Conferência de Recebimento</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0" id="tabela-fechamento">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80" class="text-center">Atendido</th>
                                    <th>Descrição do Item</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-right">Vlr. Unitário</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($itens as $it): ?>
                                <tr class="item-row">
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="item_status[]" value="<?= $it['id'] ?>" 
                                                   class="custom-control-input chk-atendido" 
                                                   id="chk_<?= $it['id'] ?>" 
                                                   data-valor="<?= $it['quantidade'] * $it['valor_unitario'] ?>" checked>
                                            <label class="custom-control-label" for="chk_<?= $it['id'] ?>"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block font-weight-bold"><?= $it['descricao_item'] ?></span>
                                        <?php if($it['produto_id']): ?>
                                            <small class="badge badge-info"><i class="fas fa-barcode"></i> Produto de Estoque</small>
                                        <?php else: ?>
                                            <small class="badge badge-secondary">Serviço / Avulso</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= number_format($it['quantidade'], 2, ',', '.') ?></td>
                                    <td class="text-right">R$ <?= number_format($it['valor_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-right text-primary font-weight-bold">
                                        R$ <?= number_format($it['quantidade'] * $it['valor_unitario'], 2, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light shadow">
                    <div class="card-header bg-navy">
                        <h3 class="card-title font-weight-bold">Resumo da Compra</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <label class="text-muted small text-uppercase d-block">Valor Total a Lançar</label>
                            <h2 class="text-success font-weight-bold" id="total-final">R$ 0,00</h2>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt mr-1"></i> Data de Vencimento</label>
                            <input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group mt-4">
                            <label class="d-block">A compra já foi paga?</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-secondary w-100 active">
                                    <input type="radio" name="pago_agora" value="nao" checked> <i class="fas fa-clock mr-1"></i> Não
                                </label>
                                <label class="btn btn-outline-success w-100">
                                    <input type="radio" name="pago_agora" value="sim"> <i class="fas fa-check-circle mr-1"></i> Sim
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info small mt-3">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Ao finalizar, os itens marcados entrarão no estoque e uma conta será gerada no financeiro.
                        </div>
                    </div>
                    <div class="card-footer p-0">
                        <button type="submit" class="btn btn-success btn-block btn-lg p-3 font-weight-bold" style="border-radius: 0 0 .25rem .25rem;">
                            <i class="fas fa-check-double mr-2"></i> FINALIZAR RECEBIMENTO
                        </button>
                    </div>
                </div>
                <a href="<?= base_url('compras') ?>" class="btn btn-block btn-link text-muted mt-2">
                    <i class="fas fa-arrow-left mr-1"></i> Voltar para a lista
                </a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    calcularTotalFinal();

    // Evento ao marcar/desmarcar item
    $('.chk-atendido').on('change', function() {
        const row = $(this).closest('tr');
        if ($(this).is(':checked')) {
            row.removeClass('text-muted').css('background-color', 'transparent');
        } else {
            row.addClass('text-muted').css('background-color', '#f8f9fa');
        }
        calcularTotalFinal();
    });

    // Validação antes de enviar
    $('#form-fechamento').on('submit', function(e) {
        if ($('.chk-atendido:checked').length === 0) {
            e.preventDefault();
            Swal.fire('Atenção', 'Você deve marcar pelo menos um item como atendido para finalizar a compra.', 'warning');
        }
    });
});

function calcularTotalFinal() {
    let total = 0;
    $('.chk-atendido:checked').each(function() {
        total += parseFloat($(this).data('valor'));
    });
    
    $('#total-final').text('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>
<?= $this->endSection() ?>