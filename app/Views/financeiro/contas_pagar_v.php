<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <style>
        .text-xs { font-size: 0.75rem !important; }
        .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .small-box { border-radius: 8px; }
        /* Efeito de pulso para contas vencidas */
        .anim-pulse { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<div class="content-header p-0 mb-3">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 1.5rem;">
                    <i class="fas fa-file-invoice-dollar text-danger mr-2"></i>Contas a Pagar
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <button type="button" class="btn btn-danger shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalNovoLancamento">
                    <i class="fas fa-plus mr-1"></i> NOVO LANÇAMENTO
                </button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
    <div class="col-lg col-md-4 col-6">
        <div class="small-box bg-danger shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($resumo['vencidas'] ?? 0, 2, ',', '.') ?></h3>
                <p>Contas Vencidas</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>

    <div class="col-lg col-md-4 col-6">
        <div class="small-box bg-warning shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($resumo['hoje'] ?? 0, 2, ',', '.') ?></h3>
                <p>Vencendo Hoje</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
    </div>

    <div class="col-lg col-md-4 col-6">
        <div class="small-box bg-info shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($resumo['a_pagar_mes'] ?? 0, 2, ',', '.') ?></h3>
                <p>A Pagar (Mês)</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
    </div>

    <div class="col-lg col-md-6 col-6">
        <div class="small-box bg-success shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($resumo['pagas_mes'] ?? 0, 2, ',', '.') ?></h3>
                <p>Pagas no Mês</p>
            </div>
            <div class="icon"><i class="fas fa-check-double"></i></div>
        </div>
    </div>

    <div class="col-lg col-md-6 col-12">
        <div class="small-box bg-navy shadow-sm border-left border-dark">
            <div class="inner">
                <h3>R$ <?= number_format($resumo['previsao_futura'] ?? 0, 2, ',', '.') ?></h3>
                <p>Previsão Futura</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
</div>

    <div class="card card-danger card-outline shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tabela-contas-pagar" class="table table-bordered table-hover table-striped mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="1%"></th>
                            <th class="text-xs text-uppercase">Identificação / Categoria</th>
                            <th class="text-center text-xs text-uppercase">Parcelas</th>
                            <th class="text-center text-xs text-uppercase">Vencimento</th>
                            <th class="text-right text-xs text-uppercase">Valor Parcela</th>
                            <th class="text-center text-xs text-uppercase">Situação</th>
                            <th class="text-right text-xs text-uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($contas)): ?>
                            <?php foreach($contas as $c): 
                                $hoje = strtotime(date('Y-m-d'));
                                $venc = strtotime($c['data_vencimento']);
                                $atrasado = ($c['status'] == 'pendente' && $venc < $hoje);
                            ?>
                            <tr class="border-left <?= $atrasado ? 'border-danger' : 'border-info' ?>">
                                <td class="align-middle px-3 text-center">
                                    <i class="fas <?= ($c['status'] == 'paga') ? 'fa-check-circle text-success' : ($atrasado ? 'fa-exclamation-circle text-danger anim-pulse' : 'fa-clock text-info') ?> fa-lg"></i>
                                </td>
                                <td>
                                    <small class="text-xs text-muted font-weight-bold">REF: <?= $c['id_agrupador'] ?></small><br>
                                    <strong class="text-danger text-uppercase"><?= $c['descricao'] ?></strong><br>
                                    <span class="text-muted text-xs text-uppercase font-weight-bold">
                                        <i class="fas fa-tag mr-1"></i> <?= $c['categoria_nome'] ?? 'DESPESA' ?>
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-light border shadow-xs px-2 py-1">
                                        <?= $c['parcela_atual'] ?> / <?= $c['total_parcelas'] ?>x
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="font-weight-bold text-sm <?= $atrasado ? 'text-danger' : '' ?>">
                                        <i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y', $venc) ?>
                                    </span>
                                </td>
                                <td class="text-right align-middle">
                                    <span class="h6 font-weight-bold text-dark">R$ <?= number_format($c['valor_original'], 2, ',', '.') ?></span>
                                </td>
                                <td class="text-center align-middle">
                                    <?php if ($c['status'] == 'paga'): ?>
                                        <span class="badge badge-success text-xs">PAGO</span>
                                    <?php elseif ($atrasado): ?>
                                        <span class="badge badge-danger text-xs">VENCIDO</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning text-xs">PENDENTE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right align-middle px-3">
                                    <div class="btn-group shadow-sm">
                                      
                                            <button type="button" 
                                              
                                                    class="btn btn-success btn-sm" 
                                                    title="Baixar Pagamento">
                                                <i class="fas fa-dollar-sign px-1"></i>
                                            </button>
                                     
                                    
                                        <button type="button" class="btn btn-default btn-sm border dropdown-toggle dropdown-icon" data-toggle="dropdown"></button>
                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                            <?php if($c['status'] == 'pendente'): ?>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalPagamento('<?= $c['id_agrupador'] ?>')">
                                                    <i class="fas fa-check-circle text-success mr-2"></i> BAIXAR PARCELA
                                                </a>
                                               <?php endif; ?> 
                                            <a class="dropdown-item" href="<?= base_url('contas-pagar/detalhes/'.$c['id_agrupador']) ?>">
                                                <i class="fas fa-layer-group text-primary mr-2"></i> Ver Todo o Grupo
                                            </a>
                                            
                                            <?php if(!empty($c['arquivo_origem'])): ?>
                                                <a class="dropdown-item" href="<?= base_url('uploads/contas_pagar/origem/'.$c['arquivo_origem']) ?>" target="_blank">
                                                    <i class="fas fa-file-pdf text-danger mr-2"></i> Ver Boleto Original
                                                </a>
                                            <?php endif; ?>

                                            <div class="dropdown-divider"></div>
                                              <?php if($c['status'] == 'pendente'): ?>
                                            <a class="dropdown-item text-xs text-danger font-weight-bold" href="javascript:void(0)" onclick="confirmarCancelamentoParcela('<?= $c['id'] ?>')">
                                                <i class="fas fa-times mr-2"></i> CANCELAR ESTA PARCELA
                                            </a>
                                               <?php endif; ?> 
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-search-dollar fa-3x mb-3 opacity-50"></i><br>
                                    Nenhum lançamento pendente encontrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 
    include('modais/modal_conta_pagar_v.php'); 
    include('modais/modal_pagamento_v.php'); 
?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>

<script>
    $(function () {
        $("#tabela-contas-pagar").DataTable({
            "responsive": true, 
            "autoWidth": false, 
            "order": [[3, "asc"]], // Ordenar por vencimento
            "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json" },
            "columnDefs": [{ "orderable": false, "targets": 6 }]
        });

        // Flashdata (Igual ao Receber)
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({ icon: 'success', title: 'Sucesso!', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, confirmButtonColor: '#28a745' });
        <?php endif; ?>
    });

    function abrirModalPagamento(id) {
        $.get("<?= base_url('contas-pagar/obter-proxima') ?>/" + id, function(data) {
            $('#pagar_id_parcela').val(data.id);
            $('#pagar_descricao_display').html('<strong>' + data.descricao + '</strong>');
            $('#pagar_parcela_label').text(data.parcela_atual + ' / ' + data.total_parcelas);
            $('#pagar_valor_final').val(data.valor_original);
            $('#modalPagamento').modal('show');
        });
    }

    function confirmarCancelamentoParcela(id) {
        Swal.fire({
            title: 'Cancelar parcela?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('contas-pagar/cancelar-parcela/') ?>/" + id;
            }
        });
    }


/**
 * Evento de Submissão do Formulário de Baixa
 */
$(document).on('submit', '#form-baixa-pagar', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const btn = form.find('button[type="submit"]');
    const formData = new FormData(this); // Necessário para envio de arquivos (upload)

    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');

    $.ajax({
        url: "<?= base_url('contas-pagar/confirmar-pagamento') ?>",
        type: "POST",
        data: formData,
        processData: false, // Importante para FormData
        contentType: false, // Importante para FormData
        dataType: "json",
        success: function(response) {
            if (response.status) {
                $('#modalPagamento').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Pago!',
                    text: 'O pagamento foi registrado com sucesso.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Recarrega para atualizar a tabela e os cards de resumo
                });
            } else {
                Swal.fire('Erro', response.msg || 'Erro ao processar pagamento.', 'error');
                btn.prop('disabled', false).text('CONFIRMAR PAGAMENTO');
            }
        },
        error: function() {
            Swal.fire('Erro', 'Falha crítica na comunicação com o servidor.', 'error');
            btn.prop('disabled', false).text('CONFIRMAR PAGAMENTO');
        }
    });
});

// Script auxiliar para mostrar o nome do arquivo selecionado no campo custom-file do AdminLTE/Bootstrap
$(document).on('change', '.custom-file-input', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});



</script>

<?= $this->endSection() ?>