<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-dark">
                <a href="<?= base_url('contas-pagar') ?>" class="btn btn-sm btn-default mr-2"><i class="fas fa-arrow-left"></i></a>
                Detalhes do Lançamento: <?= $info['id_agrupador'] ?>
            </h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-navy">
                    <h3 class="card-title">Resumo do Contrato</h3>
                </div>
                <div class="card-body">
                    <label>Descrição:</label>
                    <p class="text-uppercase"><strong><?= $info['descricao'] ?></strong></p>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <label>Total do Grupo:</label>
                            <p class="h5 text-primary">R$ <?= number_format($total_grupo, 2, ',', '.') ?></p>
                        </div>
                        <div class="col-6">
                            <label>Parcelamento:</label>
                            <p><?= count($parcelas) ?> Parcelas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Parcela</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th class="text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($parcelas as $p): 
                                $atrasado = ($p['status'] == 'pendente' && strtotime($p['data_vencimento']) < strtotime(date('Y-m-d')));
                            ?>
                            <tr>
                                <td><strong><?= $p['parcela_atual'] ?> / <?= $p['total_parcelas'] ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($p['data_vencimento'])) ?></td>
                                <td>R$ <?= number_format($p['valor_original'], 2, ',', '.') ?></td>
                                <td>
                                    <?php if($p['status'] == 'paga'): ?>
                                        <span class="badge badge-success">PAGO</span>
                                    <?php elseif($atrasado): ?>
                                        <span class="badge badge-danger">VENCIDO</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">PENDENTE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <?php if($p['status'] == 'pendente'): ?>
                                        <button class="btn btn-xs btn-success" onclick="abrirModalPagamento(<?= $p['id'] ?>)">
                                            <i class="fas fa-dollar-sign"></i> Baixar
                                        </button>
                                    <?php elseif($p['status'] == 'paga' && !empty($p['comprovante'])): ?>
                                        <a href="<?= base_url('uploads/comprovantes/'.$p['comprovante']) ?>" target="_blank" class="btn btn-xs btn-info">
                                            <i class="fas fa-paperclip"></i> Ver Comprovante
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('modais/modal_pagamento_v.php'); ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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