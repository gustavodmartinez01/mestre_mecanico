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
                    <i class="fas fa-truck-loading mr-2 text-success"></i> 
                    Recebimento de Materiais: Req #<?= str_pad($requisicao['id'], 4, '0', STR_PAD_LEFT) ?>
                </h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card card-outline card-success shadow">
                    <div class="card-header bg-white">
                        <h3 class="card-title font-weight-bold text-muted">
                            <i class="fas fa-list-check mr-1"></i> Itens da Nota / Pedido
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0" id="tabela-conferencia">
                            <thead class="bg-light">
                                <tr>
                                    <th width="80" class="text-center">Recebido?</th>
                                    <th>Descrição / Tipo</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-right">Unitário</th>
                                    <th class="text-right">Total Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($itens as $it): ?>
                                <tr class="linha-conferencia">
                                    <td class="text-center">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="item_status[]" value="<?= $it['id'] ?>" 
                                                   class="custom-control-input chk-recebido" 
                                                   id="it_<?= $it['id'] ?>" 
                                                   data-valor="<?= $it['quantidade'] * $it['valor_unitario'] ?>" checked>
                                            <label class="custom-control-label cursor-pointer" for="it_<?= $it['id'] ?>"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark d-block"><?= $it['descricao_item'] ?></span>
                                        <?php if($it['produto_id']): ?>
                                            <small class="text-primary font-weight-bold"><i class="fas fa-boxes"></i> ENTRADA NO ESTOQUE</small>
                                        <?php else: ?>
                                            <small class="text-muted"><i class="fas fa-hand-holding-usd"></i> CUSTO DE SERVIÇO</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= number_format($it['quantidade'], 2, ',', '.') ?></td>
                                    <td class="text-right text-muted">R$ <?= number_format($it['valor_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-right font-weight-bold">
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
                <div class="card shadow border-0">
                    <div class="card-header bg-navy text-center py-3">
                        <h3 class="card-title float-none font-weight-bold uppercase">Resumo do Fechamento</h3>
                    </div>
                    <div class="card-body py-4">
                        <div class="text-center mb-4">
                            <label class="text-muted small font-weight-bold mb-0">TOTAL A PAGAR (ITENS ATENDIDOS)</label>
                            <h2 class="text-success font-weight-bold" id="total-recebido">R$ 0,00</h2>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label><i class="fas fa-calendar-day mr-1"></i> Data de Vencimento</label>
                            <input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group mt-4">
                            <label class="d-block mb-3">Situação do Pagamento:</label>
                            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                                <label class="btn btn-outline-secondary w-100 active py-2">
                                    <input type="radio" name="pago_agora" value="nao" checked> <i class="fas fa-clock mr-1"></i> Pendente
                                </label>
                                <label class="btn btn-outline-success w-100 py-2">
                                    <input type="radio" name="pago_agora" value="sim"> <i class="fas fa-check-circle mr-1"></i> Pago Hoje
                                </label>
                            </div>
                        </div>

                        <div class="callout callout-info mt-4">
                            <p class="small mb-0">
                                <strong>Nota:</strong> Ao finalizar, os itens marcados como recebidos atualizarão automaticamente o estoque.
                            </p>
                        </div>
                    </div>
                    <div class="card-footer p-0">
                        <button type="submit" class="btn btn-success btn-block btn-lg p-3 font-weight-bold shadow-lg" style="border-radius: 0 0 .25rem .25rem;">
                            <i class="fas fa-file-invoice-dollar mr-2"></i> FINALIZAR COMPRA
                        </button>
                    </div>
                </div>
                <a href="<?= base_url('compras') ?>" class="btn btn-block btn-outline-secondary btn-sm mt-3">
                    <i class="fas fa-arrow-left"></i> Voltar sem finalizar
                </a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    calcularResumoFechamento();

    // Monitora a mudança nos checkboxes/switches de recebimento
    $('.chk-recebido').on('change', function() {
        const tr = $(this).closest('tr');
        if ($(this).is(':checked')) {
            tr.removeClass('bg-light opacity-50');
        } else {
            tr.addClass('bg-light opacity-50');
        }
        calcularResumoFechamento();
    });

    // Validação de segurança
    $('#form-fechamento').on('submit', function(e) {
        const marcados = $('.chk-recebido:checked').length;
        if (marcados === 0) {
            e.preventDefault();
            Swal.fire('Atenção', 'Você precisa marcar pelo menos um item para finalizar o recebimento.', 'warning');
        }
    });
});

function calcularResumoFechamento() {
    let total = 0;
    $('.chk-recebido:checked').each(function() {
        total += parseFloat($(this).data('valor')) || 0;
    });
    
    $('#total-recebido').text('R$ ' + total.toLocaleString('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>

<style>
    .opacity-50 { opacity: 0.5; }
    .cursor-pointer { cursor: pointer; }
    .uppercase { text-transform: uppercase; }
</style>
<?= $this->endSection() ?>