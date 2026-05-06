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

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-money-bill-wave text-danger mr-2"></i> Contas a Pagar</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalNovoLancamento">
                        <i class="fas fa-plus-circle mr-1"></i> NOVO LANÇAMENTO
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-outline card-danger">
                <div class="card-body">
                    <table id="tabela-pagar" class="table table-bordered table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Descrição / Agrupador</th>
                                <th>Vencimento</th>
                                <th>Valor Total</th>
                                <th>Parcelas</th>
                                <th>Status</th>
                                <th width="100">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contas as $c): ?>
                                <?php 
                                    $badge = 'badge-secondary';
                                    $texto = 'Pendente';
                                    if($c['situacao_grupo'] == 'quitado') { $badge = 'badge-success'; $texto = 'Quitado'; }
                                    if($c['situacao_grupo'] == 'atrasado') { $badge = 'badge-danger'; $texto = 'Atrasado'; }
                                    if($c['situacao_grupo'] == 'cancelada') { $badge = 'badge-dark'; $texto = 'Cancelado'; }
                                ?>
                                <tr>
                                    <td>
                                        <span class="text-bold"><?= $c['descricao'] ?></span><br>
                                        <small class="text-muted">Ref: <?= $c['id_agrupador'] ?></small>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($c['primeira_vencimento'])) ?> 
                                        <?= ($c['qtd_parcelas'] > 1) ? ' <small>...</small> ' . date('d/m/Y', strtotime($c['ultima_vencimento'])) : '' ?>
                                    </td>
                                    <td class="text-bold text-danger">R$ <?= number_format($c['valor_total'], 2, ',', '.') ?></td>
                                    <td><?= $c['qtd_parcelas'] ?>x</td>
                                    <td class="text-center"><span class="badge <?= $badge ?>"><?= strtoupper($texto) ?></span></td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="abrirModalPagamento('<?= $c['id_agrupador'] ?>')">
                                                    <i class="fas fa-check-circle text-success mr-2"></i> BAIXAR PARCELA
                                                </a>
                                                <a class="dropdown-item" href="<?= base_url('contas-pagar/detalhes/'.$c['id_agrupador']) ?>">
                                                    <i class="fas fa-eye text-info mr-2"></i> VER DETALHES
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmarCancelamento('<?= $c['id_agrupador'] ?>')">
                                                    <i class="fas fa-times-circle mr-2"></i> CANCELAR TUDO
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include('modais/modal_pagamento_v.php');?>
<?php include('modais/modal_conta_pagar_v.php');?>
<?php include('modais/modal_pagamento_v.php');?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script>
    $(function () {
        // Inicializa DataTable
        $("#tabela-pagar").DataTable({
            "responsive": true, "autoWidth": false, "order": [[1, "asc"]],
            "language": { "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json" }
        });

        
 /* Envio do formulário de pagamento com arquivo
 */
$('#form-baixa-pagar').on('submit', function(e) {
    e.preventDefault();
    
    // IMPORTANTE: Para upload de arquivos usamos FormData
    var formData = new FormData(this);

    $.ajax({
        url: "<?= base_url('contas-pagar/confirmar-pagamento') ?>",
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            if (response.status) {
                Swal.fire('Pago!', response.msg, 'success').then(() => location.reload());
            } else {
                Swal.fire('Erro', response.msg, 'error');
            }
        }
    });
});

// Script para mostrar o nome do arquivo no input customizado do Bootstrap
$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});
    });

    function confirmarCancelamento(id) {
        Swal.fire({
            title: 'Cancelar este grupo?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sim, cancelar tudo!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('contas-pagar/cancelar/') ?>/" + id;
            }
        });
    }

 /* Abre o modal de pagamento buscando dados da parcela
 */
function abrirModalPagamento(id_agrupador) {
    $.get("<?= base_url('contas-pagar/obter-proxima') ?>/" + id_agrupador, function(data) {
        if (data.status_operacao === false) {
            Swal.fire('Aviso', 'Não há parcelas pendentes para este grupo.', 'info');
            return;
        }

        $('#pagar_id_parcela').val(data.id);
        $('#pagar_descricao_display').html('<strong>' + data.descricao + '</strong>');
        $('#pagar_parcela_label').text(data.parcela_atual + ' / ' + data.total_parcelas);
        $('#pagar_valor_final').val(data.valor_original);
        
        $('#modalPagamento').modal('show');
    });
}
</script>


<?= $this->endSection() ?>
