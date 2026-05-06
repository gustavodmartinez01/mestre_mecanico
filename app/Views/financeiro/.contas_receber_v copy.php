<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($stats['vencidas'], 2, ',', '.') ?></h3>
                <p>Contas Vencidas</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-times"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($stats['hoje'], 2, ',', '.') ?></h3>
                <p>Vencendo Hoje</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow-sm border-left border-dark">
            <div class="inner">
                <h3><?= $stats['incompletas'] ?></h3>
                <p>OS p/ Completar</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($stats['recebido_mes'], 2, ',', '.') ?></h3>
                <p>Recebido (Mês)</p>
            </div>
            <div class="icon"><i class="fas fa-cash-register"></i></div>
        </div>
    </div>
</div>

<div class="card card-outline card-primary shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="<?= base_url('contas-receber') ?>" method="get">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <label class="text-xs text-uppercase font-weight-bold">Busca Rápida</label>
                    <input type="text" name="busca" class="form-control form-control-sm" placeholder="Cliente, Descrição ou Agrupador..." value="<?= $filter['busca'] ?>">
                </div>
                <div class="col-md-2">
                    <label class="text-xs text-uppercase font-weight-bold">Status Pagto</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="pendente" <?= $filter['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="paga" <?= $filter['status'] == 'paga' ? 'selected' : '' ?>>Paga</option>
                        <option value="vencida" <?= $filter['status'] == 'vencida' ? 'selected' : '' ?>>Vencida</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-xs text-uppercase font-weight-bold">Tipo de Conta</label>
                    <select name="completa" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="0" <?= $filter['completa'] === '0' ? 'selected' : '' ?>>Incompleta (OS)</option>
                        <option value="1" <?= $filter['completa'] === '1' ? 'selected' : '' ?>>Completa</option>
                    </select>
                </div>
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i> Filtrar</button>
                    <a href="<?= base_url('contas-receber') ?>" class="btn btn-sm btn-default border">Limpar</a>
                    <a href="<?= base_url('contas-receber/novo') ?>" class="btn btn-sm btn-success ml-2"><i class="fas fa-plus"></i> Novo</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card card-navy shadow">
    <div class="card-header">
        <h3 class="card-title font-weight-bold text-uppercase text-sm"><i class="fas fa-list-ul mr-2"></i> Gestão de Recebimentos</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th width="1%"></th>
                        <th>Identificação / Cliente</th>
                        <th class="text-center">Parcelas</th>
                        <th class="text-center">Vencimento</th>
                        <th class="text-right">Valor Total</th>
                        <th class="text-center">Situação</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($contas as $c): 
                        $isIncompleta = ($c['completa'] == 0);
                    ?>
                    <tr class="border-left <?= $isIncompleta ? 'border-warning' : 'border-primary' ?>">
                        <td class="align-middle">
                            <i class="fas <?= $isIncompleta ? 'fa-exclamation-circle text-warning' : 'fa-check-circle text-primary' ?>"></i>
                        </td>
                        <td>
                            <small class="text-xs text-muted">AGRUPADOR: <?= $c['id_agrupador'] ?></small><br>
                            <strong><?= $c['descricao'] ?></strong><br>
                            <span class="text-muted text-xs text-uppercase"><i class="fas fa-user mr-1"></i> <?= $c['cliente_nome'] ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="badge badge-light border"><?= $c['total_parcelas_grupo'] ?>x</span>
                        </td>
                        <td class="text-center align-middle">
                            <span class="font-weight-bold text-sm"><?= date('d/m/Y', strtotime($c['primeira_venc'])) ?></span>
                            <?php if($c['total_parcelas_grupo'] > 1): ?>
                                <div class="text-xs text-muted">até <?= date('d/m/Y', strtotime($c['ultima_venc'])) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="text-right align-middle">
                            <span class="h6 font-weight-bold">R$ <?= number_format($c['valor_total_grupo'], 2, ',', '.') ?></span>
                        </td>
                        <td class="text-center align-middle">
                            <?php if($isIncompleta): ?>
                                <span class="badge badge-warning shadow-sm px-2">INCOMPLETA</span>
                            <?php else: ?>
                                <span class="badge badge-primary shadow-sm px-2">COMPLETA</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-right align-middle px-3">
                            <?php if($isIncompleta): ?>
                                <a href="<?= base_url('contas-receber/completar/'.$c['id_agrupador']) ?>" class="btn btn-warning btn-sm font-weight-bold shadow-sm">
                                    <i class="fas fa-arrow-right mr-1"></i> COMPLETAR
                                </a>
                            <?php else: ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm border dropdown-toggle shadow-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Opções
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalRecebimento('<?= $c['id_agrupador'] ?>', '<?= $c['descricao'] ?>', '<?= $c['valor_total_grupo'] ?>')">
                                            <i class="fas fa-hand-holding-usd text-success mr-2"></i> Receber Pagto
                                        </a>
                                        <a class="dropdown-item" href="<?= base_url('contas-receber/imprimir-extrato/'.$c['id_agrupador']) ?>" target="_blank">
                                            <i class="fas fa-print text-info mr-2"></i> Imprimir Extrato
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmarCancelamento('<?= $c['id_agrupador'] ?>')">
                                            <i class="fas fa-times mr-2"></i> Cancelar Grupo
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($contas)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Nenhum registro encontrado para os filtros aplicados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('modais/modal_baixa.php');?>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    // Verifica se existe mensagem de sucesso vinda do Controller
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: '<?= session()->getFlashdata('success') ?>',
            timer: 3000,
            confirmButtonColor: '#28a745'
        });
    <?php endif; ?>

    // Verifica se existe mensagem de erro vinda do Controller
    <?php if (session()->getFlashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Ops!',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#d33'
        });
    <?php endif; ?>
});
    
/**
 * Abre a modal de recebimento buscando a parcela mais antiga pendente
 * @param {string} id_agrupador - O identificador do grupo de parcelas (Hash ou ID)
 * @param {string} descricao - Descrição original da conta
 * @param {string} cliente - Nome do cliente para exibição
 */
function abrirModalRecebimento(id_agrupador, descricao, cliente) {
    // 1. Busca os dados da próxima parcela a vencer via AJAX
    $.get("<?= base_url('contas-receber/obter-proxima') ?>/" + id_agrupador, function(data) {
        if (!data || data.status === undefined) {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Não existem parcelas pendentes para este lançamento ou o grupo foi finalizado.'
            });
            return;
        }

        // 2. Preenche os campos de identificação na Modal
        $('#baixa_id_parcela').val(data.id);
        $('#label_parcela').text(data.parcela_atual + '/' + data.total_parcelas);
        $('#baixa_descricao').html('<strong>' + cliente + '</strong><br><small>' + data.descricao + '</small>');
        
        // Formata a data de vencimento para o padrão brasileiro
        const dataVenc = new Date(data.data_vencimento + 'T00:00:00'); 
        $('#baixa_vencimento').val(dataVenc.toLocaleDateString('pt-BR'));

        // 3. Gerencia os valores numéricos
        const valorOriginal = parseFloat(data.valor_original);
        $('#valor_base_original').val(valorOriginal); // Input oculto para cálculo
        $('#baixa_valor_view').val('R$ ' + valorOriginal.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
        $('#baixa_valor_final').val(valorOriginal.toFixed(2));

        // 4. Reseta o estado dos seletores de ajuste (Switches)
        $('.chk-ajuste').prop('checked', false);
        $('#div_multa, #div_juros, #div_desconto').hide();
        $('#perc_multa, #perc_juros, #valor_multa, #valor_juros, #valor_desconto').val('0.00');

        // 5. Exibe a Modal
        $('#modalBaixa').modal('show');
    }).fail(function() {
        Swal.fire('Erro', 'Não foi possível carregar os dados da parcela. Verifique a rota.', 'error');
    });
}

/**
 * Lógica de Exibição dos Campos de Ajuste (Switches)
 */
$(document).on('change', '#chk_multa', function() {
    $('#div_multa').toggle(this.checked);
    if (!this.checked) {
        $('#perc_multa').val('0.00');
        $('#valor_multa').val('0.00');
        recalcularTotalBaixa();
    }
});

$(document).on('change', '#chk_juros', function() {
    $('#div_juros').toggle(this.checked);
    if (!this.checked) {
        $('#perc_juros').val('0.00');
        $('#valor_juros').val('0.00');
        recalcularTotalBaixa();
    }
});

$(document).on('change', '#chk_desconto', function() {
    $('#div_desconto').toggle(this.checked);
    if (!this.checked) {
        $('#valor_desconto').val('0.00');
        recalcularTotalBaixa();
    }
});

/**
 * Cálculos em tempo real ao digitar
 */
// Ao digitar o PERCENTUAL de Multa ou Juros
$(document).on('input', '#perc_multa, #perc_juros', function() {
    const valorBase = parseFloat($('#valor_base_original').val()) || 0;
    
    // Multa
    const pMulta = parseFloat($('#perc_multa').val()) || 0;
    const vMulta = valorBase * (pMulta / 100);
    $('#valor_multa').val(vMulta.toFixed(2));

    // Juros
    const pJuros = parseFloat($('#perc_juros').val()) || 0;
    const vJuros = valorBase * (pJuros / 100);
    $('#valor_juros').val(vJuros.toFixed(2));

    recalcularTotalBaixa();
});

// Ao digitar o valor fixo de DESCONTO
$(document).on('input', '#valor_desconto', function() {
    recalcularTotalBaixa();
});

/**
 * Função Mestra de Cálculo do Valor Final
 */
function recalcularTotalBaixa() {
    const base     = parseFloat($('#valor_base_original').val()) || 0;
    const multa    = parseFloat($('#valor_multa').val()) || 0;
    const juros    = parseFloat($('#valor_juros').val()) || 0;
    const desconto = parseFloat($('#valor_desconto').val()) || 0;

    const totalFinal = (base + multa + juros) - desconto;
    
    // Atualiza o campo que será enviado ao banco
    $('#baixa_valor_final').val(totalFinal.toFixed(2));
}

/**
 * Confirmação de Cancelamento de Grupo (SweetAlert2)
 */
function confirmarCancelamento(id) {
    Swal.fire({
        title: 'Cancelar este grupo?',
        text: "Todas as parcelas pendentes serão marcadas como canceladas!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, cancelar!',
        cancelButtonText: 'Manter'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= base_url('contas-receber/cancelar/') ?>/" + id;
        }
    });
}
</script>



<?= $this->endSection() ?>