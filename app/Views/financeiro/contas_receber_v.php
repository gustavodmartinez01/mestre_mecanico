<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <style>
        .text-xs { font-size: 0.75rem !important; }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    </style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<div class="content-header p-0 mb-3">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    <i class="fas fa-hand-holding-usd mr-2"></i>Contas a Receber
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?= base_url('contas-receber/novo') ?>" class="btn btn-success shadow-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> NOVO LANÇAMENTO
                </a>
            </div>
        </div>
    </div>
</div>

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

<div class="card card-navy shadow">
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="tabela-contas" class="table table-bordered table-hover table-striped mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th width="1%"></th>
                        <th class="text-xs text-uppercase">Identificação / Cliente</th>
                        <th class="text-center text-xs text-uppercase">Parcelas</th>
                        <th class="text-center text-xs text-uppercase">Vencimento</th>
                        <th class="text-right text-xs text-uppercase">Valor Total</th>
                        <th class="text-center text-xs text-uppercase">Situação</th>
                        <th class="text-right text-xs text-uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($contas)): ?>
                        <?php foreach($contas as $c): 
                            $isIncompleta = ($c['completa'] == 0);
                            $venc = strtotime($c['primeira_venc']);
                            // Lógica: Se a data passou e ainda existem parcelas a pagar
                            $atrasado = ($venc < strtotime(date('Y-m-d')) && $c['total_parcelas_grupo'] > ($c['parcelas_pagas'] ?? 0));
                        ?>
                        <tr class="border-left <?= $isIncompleta ? 'border-warning' : 'border-primary' ?>">
                            <td class="align-middle px-3 text-center">
                                <i class="fas <?= $isIncompleta ? 'fa-exclamation-circle text-warning' : 'fa-check-circle text-primary' ?> fa-lg"></i>
                            </td>
                            <td>
                                <small class="text-xs text-muted font-weight-bold">AGRUPADOR: <?= $c['id_agrupador'] ?></small><br>
                                <strong class="text-primary text-uppercase"><?= $c['descricao'] ?></strong><br>
                                <span class="text-muted text-xs text-uppercase font-weight-bold"><i class="fas fa-user-tie mr-1"></i> <?= $c['cliente_nome'] ?></span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-light border shadow-xs px-2 py-1">
                                    <?= $c['parcelas_pagas'] ?? 0 ?> / <?= $c['total_parcelas_grupo'] ?>x
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="font-weight-bold text-sm <?= $atrasado ? 'text-danger' : '' ?>">
                                    <i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y', $venc) ?>
                                </span>
                                <?php if($c['total_parcelas_grupo'] > 1): ?>
                                    <div class="text-xs text-muted">até <?= date('d/m/Y', strtotime($c['ultima_venc'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-right align-middle">
                                <span class="h6 font-weight-bold text-dark">R$ <?= number_format($c['valor_total_grupo'], 2, ',', '.') ?></span>
                            </td>
                            <td class="text-center align-middle">
                               <?php if ($c['situacao_grupo'] == 'quitado'): ?>
                                        <span class="badge badge-success text-xs">QUITADO</span>
                                <?php elseif ($c['situacao_grupo'] == 'atrasado'): ?>
                                        <span class="badge badge-danger text-xs">EM ATRASO</span>
                                <?php elseif ($c['situacao_grupo'] == 'cancelada'): ?>
                                        <span class="badge badge-secondary text-xs">CANCELADA</span>
                                <?php else: ?>
                                        <span class="badge badge-info text-xs">EM DIA</span>
                                 <?php endif; ?>
                            </td>
                            <td class="text-right align-middle px-3">
                                <?php if($isIncompleta): ?>
                                    <a href="<?= base_url('contas-receber/completar/'.$c['id_agrupador']) ?>" class="btn btn-warning btn-xs font-weight-bold shadow-sm px-3 py-1">
                                        <i class="fas fa-arrow-right mr-1"></i> COMPLETAR
                                    </a>
                                <?php else: ?>
                                    <div class="btn-group shadow-sm">
                                        
                                        <button type="button" 
                                                onclick="abrirModalRecebimento('<?= $c['id_agrupador'] ?>', '<?= addslashes($c['descricao']) ?>', '<?= addslashes($c['cliente_nome']) ?>')"
                                                class="btn btn-success btn-sm" 
                                                title="Receber Pagto"
                                                <?= (($c['parcelas_pagas'] ?? 0) >= $c['total_parcelas_grupo']) ? 'disabled' : '' ?>>
                                            <i class="fas fa-dollar-sign px-1"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-default btn-sm border dropdown-toggle dropdown-icon" data-toggle="dropdown"></button>
                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                             <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalRecebimento('<?= $c['id_agrupador'] ?>', '<?= $c['descricao'] ?>', '<?= $c['valor_total_grupo'] ?>')">
                                            <i class="fas fa-hand-holding-usd text-success mr-2"></i> Receber Pagto
                                        </a>
                                        <a class="dropdown-item text-xs font-weight-bold" href="javascript:void(0)" 
                                            onclick="abrirModalSelecaoParcelas('<?= $c['id_agrupador'] ?>', '<?= addslashes($c['descricao']) ?>')">
                                            <i class="fas fa-receipt text-warning mr-2"></i> IMPRIMIR RECIBOS
                                        </a>
                                            <a class="dropdown-item text-xs font-weight-bold" href="<?= base_url('contas-receber/imprimir-extrato/'.$c['id_agrupador']) ?>" target="_blank">
                                                <i class="fas fa-print text-info mr-2"></i> IMPRIMIR EXTRATO
                                            </a>
                                            <?php if ($c['situacao_grupo'] == 'cancelada'): ?>
                                            <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-xs font-weight-bold text-danger" href="<?= base_url('contas-receber/imprimir-cancelamento/'.$c['id_agrupador']) ?>" target="_blank">
                                                    <i class="fas fa-file-pdf mr-2"></i> IMPRIMIR CANCELAMENTO
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($c['situacao_grupo'] != 'cancelada' && $c['situacao_grupo'] != 'quitado'): ?>
                                                <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-xs text-danger font-weight-bold" href="javascript:void(0)" onclick="confirmarCancelamento('<?= $c['id_agrupador'] ?>')">
                                                        <i class="fas fa-times mr-2"></i> CANCELAR GRUPO
                                                    </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-search-dollar fa-3x mb-3 opacity-50"></i><br>
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
include('modais/modal_recebimento.php');
include('modais/modal_selecao_parcelas.php');
?>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function () {
        // Inicialização DataTables
        $("#tabela-contas").DataTable({
            "responsive": true, 
            "autoWidth": false,
            "order": [[1, "asc"]], 
            "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json" },
            "columnDefs": [{ "orderable": false, "targets": 6 }]
        });

        // Toast/Feedback de Sessão (Flashdata)
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({ icon: 'success', title: 'Sucesso!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, confirmButtonColor: '#28a745' });
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({ icon: 'error', title: 'Ops!', text: '<?= session()->getFlashdata('error') ?>', confirmButtonColor: '#d33' });
        <?php endif; ?>
    });

    /**
     * Processamento do Formulário de Baixa (Pagamento)
     */
    $(document).on('submit', '#form-baixa-receber', function(e) {
        e.preventDefault();
        const form = $(this);
        const btn = form.find('button[type="submit"]');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');

        $.ajax({
            url: "<?= base_url('contas-receber/processar-baixa') ?>", // Verifique se esta é sua rota de post
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    $('#modalRecebimento').modal('hide');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Pago com sucesso!',
                        text: 'Deseja imprimir o recibo de pagamento?',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-print"></i> Sim, Imprimir',
                        cancelButtonText: 'Não, fechar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Linha mestre: Abre o recibo em nova aba
                            window.open('<?= base_url("contas-receber/imprimir-recibo-lote") ?>/' + response.id_parcela, '_blank');
                        }
                        location.reload(); // Recarrega para atualizar os badges da tabela
                    });
                } else {
                    Swal.fire('Erro', response.msg || 'Erro ao processar.', 'error');
                    btn.prop('disabled', false).text('CONFIRMAR PAGAMENTO');
                }
            },
            error: function() {
                Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
                btn.prop('disabled', false).text('CONFIRMAR PAGAMENTO');
            }
        });
    });

    /**
     * Função para abrir modal de baixa buscando próxima parcela pendente
     */
    function abrirModalRecebimento(id_agrupador, descricao, cliente) {
        $.get("<?= base_url('contas-receber/obter-proxima') ?>/" + id_agrupador, function(data) {
            // Ajustado para a chave que definimos no Controller anteriormente
            if (!data || data.status_operacao === false) {
                Swal.fire({ icon: 'warning', title: 'Atenção', text: 'Não existem parcelas pendentes para este lançamento.' });
                return;
            }

            // Identificação
            $('#baixa_id_parcela').val(data.id);
            $('#label_parcela').text(data.parcela_atual + '/' + data.total_parcelas);
            $('#baixa_descricao').html('<strong>' + cliente + '</strong><br><small>' + data.descricao + '</small>');
            
            // Datas e Valores
            const dataVenc = new Date(data.data_vencimento + 'T00:00:00'); 
            $('#baixa_vencimento').val(dataVenc.toLocaleDateString('pt-BR'));

            const valorOriginal = parseFloat(data.valor_original);
            $('#valor_base_original').val(valorOriginal); 
            $('#baixa_valor_view').val('R$ ' + valorOriginal.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
            $('#baixa_valor_final').val(valorOriginal.toFixed(2));

            // Resetar ajustes da modal
            $('.chk-ajuste').prop('checked', false);
            $('#div_multa, #div_juros, #div_desconto').hide();
            $('#perc_multa, #perc_juros, #valor_multa, #valor_juros, #valor_desconto').val('0.00');

            $('#modalRecebimento').modal('show');
        }).fail(function() {
            Swal.fire('Erro', 'Não foi possível carregar os dados financeiros.', 'error');
        });
    }

    /**
     * Cálculos dinâmicos da Modal (Multa/Juros/Desconto)
     */
    $(document).on('input', '#perc_multa, #perc_juros, #valor_desconto', function() {
        const base = parseFloat($('#valor_base_original').val()) || 0;
        const pM = parseFloat($('#perc_multa').val()) || 0;
        const pJ = parseFloat($('#perc_juros').val()) || 0;
        const vD = parseFloat($('#valor_desconto').val()) || 0;

        const vM = base * (pM / 100);
        const vJ = base * (pJ / 100);
        
        $('#valor_multa').val(vM.toFixed(2));
        $('#valor_juros').val(vJ.toFixed(2));

        const total = (base + vM + vJ) - vD;
        $('#baixa_valor_final').val(total.toFixed(2));
    });

    $(document).on('change', '.chk-ajuste', function() {
        const alvo = $(this).data('target');
        $(alvo).toggle(this.checked);
        if(!this.checked) $(alvo).find('input').val('0.00').trigger('input');
    });

    function confirmarCancelamento(id) {
        Swal.fire({
            title: 'Cancelar este grupo?',
            text: "Todas as parcelas pendentes serão removidas!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "<?= base_url('contas-receber/cancelar/') ?>/" + id;
        });
    }

/**
 * Abre modal e busca apenas parcelas com status 'paga'
 */
function abrirModalSelecaoParcelas(id_agrupador, descricao) {
    $('#info_agrupador_modal').html('Agrupador: <strong>' + id_agrupador + '</strong> - ' + descricao);
    $('#lista_parcelas_pagas').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando...</td></tr>');
    $('#btn-gerar-lote').prop('disabled', true);

    $.get("<?= base_url('contas-receber/listar-pagas') ?>/" + id_agrupador, function(data) {
        let html = '';
        if (data.length > 0) {
            data.forEach(function(p) {
                html += `
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="ids_parcelas[]" value="${p.id}" class="chk-parcela-paga">
                        </td>
                        <td>Parc. ${p.id}</td>
                        <td>${new Date(p.data_vencimento + 'T00:00:00').toLocaleDateString('pt-BR')}</td>
                        <td>${new Date(p.data_pagamento + 'T00:00:00').toLocaleDateString('pt-BR')}</td>
                        <td class="text-right">R$ ${parseFloat(p.valor_pago).toLocaleString('pt-BR', {minimumFractionDigits: 2})}</td>
                    </tr>`;
            });
            $('#btn-gerar-lote').prop('disabled', false);
        } else {
            html = '<tr><td colspan="5" class="text-center text-muted">Nenhuma parcela paga encontrada para este grupo.</td></tr>';
        }
        $('#lista_parcelas_pagas').html(html);
        $('#modalSelecaoParcelas').modal('show');
    });
}

// Controle de seleção (Marcar todas)
$(document).on('change', '#selecionar_todas_pagas', function() {
    $('.chk-parcela-paga').prop('checked', this.checked);
});

// Impede envio se nada estiver marcado
$('#form-imprimir-multiplos').on('submit', function(e) {
    if ($('.chk-parcela-paga:checked').length === 0) {
        e.preventDefault();
        Swal.fire('Atenção', 'Selecione ao menos uma parcela.', 'warning');
    }
});

</script>
<?= $this->endSection() ?>