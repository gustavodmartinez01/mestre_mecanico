<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<style>
    /* Otimização para Checklists */
    .checklist-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0 15px;
    }
    .checklist-item {
        flex: 1 1 100%; /* Mudado para 100% para acomodar bem o campo de texto */
        border-bottom: 1px solid #f4f4f4;
        padding: 10px 0;
    }
    .foto-container:hover .btn-excluir-foto { display: block !important; }
    .text-xs { font-size: 0.75rem; }
</style>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-left border-primary">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="mb-1"><i class="fas fa-tools text-primary mr-2"></i> OS #<?= $os['id'] ?> 
                            <small class="badge <?= $os['status'] == 'aberta' ? 'badge-warning' : 'badge-success' ?> ml-2">
                                <?= strtoupper($os['status']) ?>
                            </small>
                        </h4>
                        <p class="mb-0 text-muted">
                            <strong>Cliente:</strong> <?= $os['cliente_nome'] ?> | <strong>Placa:</strong> <?= $os['veiculo_placa'] ?><br>
                            <strong>Técnico:</strong> <span class="text-primary font-weight-bold"><?= $os['tecnico_nome'] ?? 'Não atribuído' ?></span>
                        </p>
                    </div>
                    <div class="col-md-5 text-md-right mt-3 mt-md-0">
                        <div class="btn-group shadow-sm">
                            <a href="<?= base_url('os/imprimir/'.$os['id']) ?>" target="_blank" class="btn btn-default border"><i class="fas fa-print text-danger"></i> PDF</a>
                            <a href="<?= base_url('os/relatorio/'.$os['id']) ?>" target="_blank" class="btn btn-default border">
                                     <i class="fas fa-file-pdf text-danger"></i> RELATÓRIO
                            </a>
                            <a href="<?= base_url('os/whatsapp/'.$os['id']) ?>" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                            <?php if($os['status'] != 'finalizada'): ?>
                                <a href="<?= base_url('os/finalizar/'.$os['id']) ?>" class="btn btn-primary" onclick="return confirm('Finalizar OS?')"><i class="fas fa-check-circle"></i> Finalizar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card card-outline card-navy shadow h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-2"></i> Itens e Peças</h3>
                <div class="card-tools">
                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalNovoItem"><i class="fas fa-plus"></i> Novo Item</button>
                </div>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Descrição</th>
                            <th class="text-center">Qtd</th>
                            <th class="text-right">Unitário</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $i): ?>
                        <tr>
                            <td><?= $i['descricao'] ?></td>
                            <td class="text-center"><?= (float)$i['quantidade'] ?></td>
                            <td class="text-right text-muted">R$ <?= number_format($i['valor_unitario'], 2, ',', '.') ?></td>
                            <td class="text-right font-weight-bold">R$ <?= number_format($i['subtotal'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('os/remover_item/'.$i['id'].'/'.$os['id']) ?>" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-navy text-right"><h4>TOTAL: R$ <?= number_format($os['valor_total'] ?? 0, 2, ',', '.') ?></h4></div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-warning shadow mb-4">
            <div class="card-header p-2">
                <h3 class="card-title font-weight-bold text-xs mt-1">INSP. ENTRADA</h3>
                <div class="card-tools"><button class="btn btn-xs btn-outline-dark" data-toggle="modal" data-target="#modalCheckEntrada"><i class="fas fa-plus"></i></button></div>
            </div>
            <div class="card-body p-2">
                <div class="checklist-container">
                    <?php foreach($itens_checklist as $ic): if($ic['tipo'] == 'entrada'): ?>
                    <div class="checklist-item w-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold"><?= $ic['descricao'] ?></span>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-xs <?= $ic['status'] == 'ok' ? 'btn-success' : 'btn-outline-success' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'ok')"><i class="fas fa-thumbs-up"></i></button>
                                <button class="btn btn-xs <?= $ic['status'] == 'nao_ok' ? 'btn-danger' : 'btn-outline-danger' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'nao_ok')"><i class="fas fa-thumbs-down"></i></button>
                            </div>
                        </div>
                        <input type="text" id="observacao-<?= $ic['id'] ?>" class="form-control form-control-sm border-0 bg-light text-xs" placeholder="Obs / Medição..." value="<?= $ic['observacao'] ?>" onblur="atualizarCheck(<?= $ic['id'] ?>, '<?= $ic['status'] ?>')">
                    </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary shadow mb-4">
            <div class="card-header p-2">
                <h3 class="card-title font-weight-bold text-xs mt-1">CONF. TÉCNICA</h3>
                <div class="card-tools"><button class="btn btn-xs btn-outline-dark" data-toggle="modal" data-target="#modalCheckTecnico"><i class="fas fa-plus"></i></button></div>
            </div>
            <div class="card-body p-2">
                <div class="checklist-container">
                    <?php foreach($itens_checklist as $ic): if($ic['tipo'] == 'servico'): ?>
                    <div class="checklist-item w-100">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold"><?= $ic['descricao'] ?></span>
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-xs <?= $ic['status'] == 'ok' ? 'btn-success' : 'btn-outline-success' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'ok')"><i class="fas fa-thumbs-up"></i></button>
                                <button class="btn btn-xs <?= $ic['status'] == 'nao_ok' ? 'btn-danger' : 'btn-outline-danger' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'nao_ok')"><i class="fas fa-thumbs-down"></i></button>
                            </div>
                        </div>
                        <input type="text" id="observacao-<?= $ic['id'] ?>" class="form-control form-control-sm border-0 bg-light text-xs" placeholder="Obs / Medição..." value="<?= $ic['observacao'] ?>" onblur="atualizarCheck(<?= $ic['id'] ?>, '<?= $ic['status'] ?>')">
                    </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCheckEntrada" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning p-2 text-center text-dark"><h6 class="modal-title w-100 font-weight-bold">INCLUIR ITEM DE ENTRADA</h6></div>
            <div class="modal-body">
                <div class="form-group"><label>Item:</label>
                    <select id="select_entrada" class="form-control select2" style="width: 100%">
                        <?php foreach($lista_check_entrada as $e): ?><option value="<?= $e['id'] ?>"><?= $e['descricao'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Observação / Medição (Ex: 85.000km):</label>
                    <input type="text" id="obs_entrada" class="form-control" placeholder="Informe a condição atual">
                </div>
            </div>
            <div class="modal-footer p-2"><button type="button" class="btn btn-primary btn-block font-weight-bold" onclick="salvarCheck('entrada')">INCLUIR</button></div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCheckTecnico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-primary shadow-lg">
            <div class="modal-header bg-primary text-white p-2 text-center"><h6 class="modal-title w-100 font-weight-bold">INCLUIR CONFERÊNCIA TÉCNICA</h6></div>
            <div class="modal-body">
                <div class="form-group"><label>Item:</label>
                    <select id="select_tecnico" class="form-control select2" style="width: 100%">
                        <?php foreach($lista_check_servicos as $s): ?><option value="<?= $s['id'] ?>"><?= $s['descricao'] ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Observação Ténica:</label>
                    <input type="text" id="obs_tecnico" class="form-control" placeholder="Ex: Pressão medida, espessura, etc">
                </div>
            </div>
            <div class="modal-footer p-2"><button type="button" class="btn btn-primary btn-block font-weight-bold" onclick="salvarCheck('servico')">INCLUIR</button></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function salvarCheck(tipo) {
    const checkId = (tipo === 'entrada') ? $('#select_entrada').val() : $('#select_tecnico').val();
    const observacao = (tipo === 'entrada') ? $('#obs_entrada').val() : $('#obs_tecnico').val();
    const osId = '<?= $os['id'] ?>';

    if (!checkId) return;

    $.post('<?= base_url('os/incluir_item_checklist') ?>', { 
        os_id: osId, 
        checklist_id: checkId, 
        tipo: tipo,
        observacao: observacao 
    }, function() { 
        location.reload(); 
    }).fail(function() { alert("Erro ao incluir item."); });
}

function atualizarCheck(id, status) {
    // RESOLVIDO: Captura a observação do input correto antes de enviar
    const observacao = $('#observacao-' + id).val();
    
    console.log("Atualizando item " + id + " para " + status + " com obs: " + observacao);

    $.post('<?= base_url('os/atualizar_item_checklist') ?>', { 
        item_id: id, 
        status: status,
        observacao: observacao
    }, function() { 
        location.reload(); 
    }).fail(function() {
        alert("Erro ao atualizar o item.");
    });
}
</script>
<?= $this->endSection() ?>