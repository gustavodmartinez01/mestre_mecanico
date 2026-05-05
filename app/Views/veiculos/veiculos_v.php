<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <!-- Cabeçalho com Ação Principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark">
            <i class="fas fa-car mr-2 text-primary"></i> Gerenciamento de Frota
        </h4>
        <a href="<?= base_url('veiculos/novo') ?>" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus mr-1"></i> Novo Veículo
        </a>
    </div>

    <!-- Filtros e Listagem -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped datatable align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th width="120">Placa</th>
                            <th>Veículo / Marca</th>
                            <th>Ano / Cor</th>
                            <th>Cliente Atual (Possuidor)</th>
                            <th class="text-center">Histórico</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($veiculos)): ?>
                            <?php foreach ($veiculos as $v): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-dark p-2 font-weight-bold" style="letter-spacing: 1px; font-size: 13px; border: 1px solid #fff;">
                                        <?= strtoupper($v['placa']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= $v['modelo'] ?></div>
                                    <div class="small text-muted text-uppercase"><?= $v['marca'] ?></div>
                                </td>
                                <td>
                                    <div><?= $v['ano'] ?></div>
                                    <div class="small text-muted text-capitalize"><?= $v['cor'] ?></div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-user small text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small font-weight-bold"><?= $v['nome_cliente'] ?></div>
                                            <?php if($v['proprietario']): ?>
                                                <div class="small text-muted" style="font-size: 10px;">Prop: <?= $v['proprietario'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('veiculos/detalhes/' . $v['id']) ?>" class="btn btn-sm btn-outline-info rounded-pill px-3" title="Ver Prontuário">
                                        <i class="fas fa-history mr-1"></i> Histórico
                                    </a>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle btn btn-sm btn-light border" href="#" role="button" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                                            <div class="dropdown-header">Gestão de Veículo:</div>
                                            <a class="dropdown-item" href="<?= base_url('veiculos/editar/' . $v['id']) ?>">
                                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Editar Dados
                                            </a>
                                            <a class="dropdown-item" href="<?= base_url('veiculos/detalhes/' . $v['id']) ?>">
                                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Ordem de Serviços
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmarExclusao('<?= $v['id'] ?>', '<?= $v['placa'] ?>')">
                                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-danger"></i> Remover Frota
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-car-crash fa-3x mb-3 opacity-2"></i><br>
                                    Nenhum veículo encontrado na base de dados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">Confirmar Exclusão</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o veículo de placa <strong id="placaExcluir"></strong> da frota?</p>
                <p class="small text-muted">Atenção: Isso não apagará o histórico de Ordens de Serviço já realizadas, mas o veículo não estará mais disponível para novos agendamentos.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" id="linkConfirmarExclusao" class="btn btn-danger font-weight-bold">Remover Veículo</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function confirmarExclusao(id, placa) {
        $('#placaExcluir').text(placa);
        $('#linkConfirmarExclusao').attr('href', '<?= base_url("veiculos/excluir/") ?>/' + id);
        $('#modalExcluir').modal('show');
    }

    $(document).ready(function() {
        $('.datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            },
            "order": [[ 1, "asc" ]]
        });
    });
</script>
<?= $this->endSection() ?>