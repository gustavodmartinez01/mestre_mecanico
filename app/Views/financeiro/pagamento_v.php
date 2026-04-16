<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card card-outline card-navy shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-file-invoice-dollar mr-2"></i> Resumo da Cobrança</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr class="bg-light">
                            <th>Item/Serviço</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="fas fa-tools text-muted mr-1"></i> Ordem de Serviço #<?= $os['id'] ?></td>
                            <td class="text-right font-weight-bold">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                        </tr>
                        <tr id="linha-venda-extra" class="text-success" style="display:none;">
                            <td><i class="fas fa-plus-circle mr-1"></i> Venda Adicional</td>
                            <td class="text-right font-weight-bold" id="valor-venda-extra">R$ 0,00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light text-right">
                <span class="text-muted mr-2 text-uppercase font-weight-bold">Subtotal:</span>
                <h3 class="d-inline font-weight-bold text-navy" id="subtotal-geral">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></h3>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-6">
                <button class="btn btn-outline-secondary btn-block disabled"><i class="fas fa-receipt"></i> Recibo</button>
            </div>
            <div class="col-6">
                <button class="btn btn-outline-secondary btn-block disabled"><i class="fas fa-file-contract"></i> Promissória</button>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card card-primary shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-cash-register mr-2"></i> Caixa / Finalização</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-danger"><i class="fas fa-minus-circle"></i> Desconto (R$)</label>
                            <input type="number" step="0.01" id="desconto" class="form-control form-control-lg text-right text-danger font-weight-bold" value="0.00" oninput="calcularTotal()">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary"><i class="fas fa-plus-circle"></i> Juros / Multa (R$)</label>
                            <input type="number" step="0.01" id="juros" class="form-control form-control-lg text-right text-primary font-weight-bold" value="0.00" oninput="calcularTotal()">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center bg-dark p-3 rounded mb-4">
                    <span class="text-uppercase text-xs text-white-50">Total a Receber</span>
                    <h1 class="display-4 font-weight-bold text-warning" id="total-final">R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></h1>
                </div>

                <div class="form-group">
                    <label>Forma de Pagamento Principal:</label>
                    <select class="form-control form-control-lg select2" id="forma_pgto">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="pix">PIX (Instantâneo)</option>
                        <option value="debito">Cartão de Débito</option>
                        <option value="credito">Cartão de Crédito</option>
                        <option value="boleto">Boleto Bancário</option>
                        <option value="carteira">Conta Corrente (Pendura / Prazo)</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Valor Pago pelo Cliente:</label>
                            <input type="number" step="0.01" id="valor_pago" class="form-control form-control-lg bg-light font-weight-bold" placeholder="0.00" oninput="calcularTotal()">
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <label>Troco:</label>
                        <div class="h3 font-weight-bold text-success" id="troco-exibicao">R$ 0,00</div>
                    </div>
                </div>

            </div>
            <div class="card-footer p-0">
                <button class="btn btn-success btn-lg btn-block font-weight-bold py-3" onclick="confirmarPagamento()">
                    <i class="fas fa-check-double mr-2"></i> CONFIRMAR E FINALIZAR O.S.
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    // Valor original da OS vindo do PHP
    const VALOR_OS_ORIGINAL = <?= $os['valor_total'] ?? 0 ?>;

    function calcularTotal() {
        // 1. Captura os valores dos inputs (garante que sejam números)
        let desconto = parseFloat($('#desconto').val()) || 0;
        let juros    = parseFloat($('#juros').val()) || 0;
        let valorPagoClient = parseFloat($('#valor_pago').val()) || 0;

        // 2. Calcula o Total Final (OS - Desconto + Juros)
        let totalFinal = VALOR_OS_ORIGINAL - desconto + juros;
        
        // Impede que o total seja negativo
        if (totalFinal < 0) totalFinal = 0;

        // 3. Atualiza a exibição do Total Final (Grande em Amarelo)
        $('#total-final').text(formatarMoeda(totalFinal));

        // 4. Calcula o Troco
        let troco = 0;
        if (valorPagoClient > totalFinal) {
            troco = valorPagoClient - totalFinal;
            $('#troco-exibicao').removeClass('text-muted').addClass('text-success');
        } else {
            $('#troco-exibicao').removeClass('text-success').addClass('text-muted');
        }

        // 5. Atualiza a exibição do Troco
        $('#troco-exibicao').text(formatarMoeda(troco));
    }

    // Função auxiliar para formatar em R$ (Ex: 1.250,50)
    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-br', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    function confirmarPagamento() {
        const dados = {
            os_id: <?= $os['id'] ?>,
            desconto: $('#desconto').val(),
            juros: $('#juros').val(),
            forma_pgto: $('#forma_pgto').val(),
            valor_pago: $('#valor_pago').val(),
            total_final: (VALOR_OS_ORIGINAL - (parseFloat($('#desconto').val()) || 0) + (parseFloat($('#juros').val()) || 0))
        };

        if (confirm('Deseja confirmar o recebimento e finalizar esta O.S.?')) {
            $.post('<?= base_url('financeiro/processar_pagamento') ?>', dados, function(response) {
                if (response.status === 'success') {
                    alert('Pagamento processado com sucesso!');
                    window.location.href = '<?= base_url('os/gerenciar/'.$os['id']) ?>';
                } else {
                    alert('Erro: ' + response.message);
                }
            }, 'json').fail(function() {
                alert('Erro na comunicação com o servidor.');
            });
        }
    }
</script>
<?= $this->endSection() ?>