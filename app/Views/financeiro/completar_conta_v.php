<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<div class="row">
    <div class="col-md-4">
        <div class="card card-outline card-navy shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold text-uppercase text-xs"><i class="fas fa-cog mr-2"></i> Configurar Plano</h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info p-2 mb-3">
                    <small class="text-muted d-block">Cliente:</small>
                    <strong><?= $conta['cliente_nome'] ?></strong><br>
                    <small class="text-muted d-block mt-1">Origem:</small>
                    <strong><?= $conta['descricao'] ?></strong>
                </div>

                <div class="form-group">
                    <label class="text-xs">VALOR TOTAL DA OS</label>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                        <input type="number" id="valor_total" class="form-control form-control-lg font-weight-bold text-primary" value="<?= $conta['valor_original'] ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="text-xs">Nº PARCELAS</label>
                            <input type="number" id="qtd_parcelas" class="form-control" value="1" min="1">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="text-xs">INTERVALO (DIAS)</label>
                            <input type="number" id="intervalo_dias" class="form-control" value="30">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="text-xs">VENCIMENTO DA 1ª PARCELA</label>
                    <input type="date" id="data_primeiro_venc" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <button type="button" id="btn-gerar-grade" class="btn btn-primary btn-block shadow-sm">
                    <i class="fas fa-sync-alt mr-2"></i> CALCULAR PARCELAS
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <form action="<?= base_url('contas-receber/salvar-parcelamento') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id_agrupador" value="<?= $conta['id_agrupador'] ?>">
            <input type="hidden" name="modo" value="<?= $modo ?>">

            <div class="card card-outline card-success shadow h-100">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-uppercase text-xs"><i class="fas fa-calendar-check mr-2"></i> Conferência de Vencimentos</h3>
                    <div class="card-tools">
                        <span class="badge badge-info p-2" id="badge-total-conferido">SOMA: R$ 0,00</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr class="bg-light text-xs text-uppercase">
                                <th class="text-center py-2" width="15%">Parcela</th>
                                <th class="py-2" width="35%">Data Vencimento</th>
                                <th class="py-2" width="35%">Valor da Parcela (R$)</th>
                                <th width="15%"></th>
                            </tr>
                        </thead>
                        <tbody id="corpo-grade">
                            <tr><td colspan="4" class="text-center py-5 text-muted">Aguardando geração da grade...</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-right">
                    <a href="<?= base_url('contas-receber') ?>" class="btn btn-default border text-xs font-weight-bold">CANCELAR</a>
                    <button type="submit" id="btn-salvar-financeiro" class="btn btn-success px-4 ml-2 text-xs font-weight-bold" disabled>
                        <i class="fas fa-check-circle mr-2"></i> CONFIRMAR E SALVAR
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#btn-gerar-grade').click(function() {
        const total = parseFloat($('#valor_total').val());
        const qtd = parseInt($('#qtd_parcelas').val());
        const intervalo = parseInt($('#intervalo_dias').val());
        let dataBase = new Date($('#data_primeiro_venc').val() + 'T00:00:00');

        let valorParcela = (total / qtd).toFixed(2);
        let resto = (total - (valorParcela * qtd)).toFixed(2);
        
        let html = '';
        for (let i = 1; i <= qtd; i++) {
            // Adiciona o resto (centavos) na última parcela para bater o total
            let vFinal = (i === qtd) ? (parseFloat(valorParcela) + parseFloat(resto)).toFixed(2) : valorParcela;
            let dataFormatada = dataBase.toISOString().split('T')[0];

            html += `
                <tr>
                    <td class="text-center align-middle font-weight-bold">${i}/${qtd}</td>
                    <td><input type="date" name="parcelas[${i}][data]" class="form-control form-control-sm" value="${dataFormatada}"></td>
                    <td><input type="number" step="0.01" name="parcelas[${i}][valor]" class="form-control form-control-sm input-valor-grade" value="${vFinal}"></td>
                    <td class="text-center align-middle"><i class="fas fa-lock text-muted opacity-50"></i></td>
                </tr>
            `;
            dataBase.setDate(dataBase.getDate() + intervalo);
        }

        $('#corpo-grade').html(html);
        $('#btn-salvar-financeiro').prop('disabled', false);
        calcularSomaGrade();
    });

    $(document).on('change', '.input-valor-grade', function() { calcularSomaGrade(); });

    function calcularSomaGrade() {
        let soma = 0;
        $('.input-valor-grade').each(function() { soma += parseFloat($(this).val()) || 0; });
        $('#badge-total-conferido').text('SOMA: R$ ' + soma.toLocaleString('pt-br', {minimumFractionDigits: 2}));
        
        const original = parseFloat($('#valor_total').val());
        if (Math.abs(soma - original) > 0.02) {
            $('#badge-total-conferido').addClass('badge-danger').removeClass('badge-info');
            $('#btn-salvar-financeiro').prop('disabled', true);
        } else {
            $('#badge-total-conferido').addClass('badge-info').removeClass('badge-danger');
            $('#btn-salvar-financeiro').prop('disabled', false);
        }
    }
});
</script>
<?= $this->endSection() ?>