<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-dark"><i class="fas fa-file-invoice-dollar mr-2"></i> Requisições de Compra</h4>
        </div>
        <div class="col-sm-6 text-right">
            <a href="<?= base_url('compras/nova') ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle mr-1"></i> Nova Requisição
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabela-requisicoes">
                    <thead class="bg-light">
                        <tr>
                            <th># ID</th>
                            <th>Data</th>
                            <th>Fornecedor</th>
                            <th>Solicitante</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($requisicoes)): ?>
                            <?php foreach($requisicoes as $r): ?>
                                <tr>
                                    <td class="font-weight-bold">#<?= str_pad($r['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= date('d/m/Y', strtotime($r['data_requisicao'])) ?></td>
                                    <td><?= $r['fornecedor_nome'] ?></td>
                                    <td><span class="badge badge-light border text-muted"><?= $r['usuario_nome'] ?></span></td>
                                    <td class="font-weight-bold text-dark">R$ <?= number_format($r['valor_total'], 2, ',', '.') ?></td>
                                    <td>
                                        <?php 
                                            $status_class = [
                                                'aberta'     => 'badge-warning',
                                                'cotacao'    => 'badge-info',
                                                'aprovada'   => 'badge-primary',
                                                'rejeitada'  => 'badge-danger',
                                                'finalizada' => 'badge-success'
                                            ];
                                            $badge = $status_class[$r['status']] ?? 'badge-secondary';
                                        ?>
                                        <span class="badge <?= $badge ?> text-uppercase"><?= $r['status'] ?></span>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <a href="<?= base_url('compras/detalhes/'.$r['id']) ?>" class="btn btn-xs btn-default border" title="Ver Detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <?php if($r['status'] == 'aberta'): ?>
                                                <a href="<?= base_url('compras/editar/'.$r['id']) ?>" class="btn btn-xs btn-default border" title="Editar Requisição">
                                                    <i class="fas fa-edit text-orange"></i>
                                                </a>
                                                <a href="<?= base_url('compras/fechar/'.$r['id']) ?>" class="btn btn-xs btn-success border" title="Finalizar/Receber">
                                                    <i class="fas fa-check-double mr-1"></i> Finalizar
                                                </a>
                                                <button class="btn btn-xs btn-danger border" onclick="confirmarExclusao(<?= $r['id'] ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Nenhuma requisição encontrada.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmarExclusao(id) {
        Swal.fire({
            title: 'Excluir Requisição?',
            text: "Esta ação não pode ser desfeita.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('compras/excluir/') ?>/" + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>