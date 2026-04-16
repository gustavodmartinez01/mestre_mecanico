<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>

<?php 
    $isFinalizada = ($os['status'] == 'finalizada' || $os['status'] == 'cancelada');
    $isOrcamento = ($os['status'] == 'orcamento');
    $isAberta = ($os['status'] == 'aberta');
?>

<style>
    .checklist-container { display: flex; flex-wrap: wrap; gap: 0 10px; }
    /* Item de checklist com borda lateral para indicar foco */
    .checklist-item { 
        flex: 1 1 100%; 
        border-bottom: 1px solid #eee; 
        padding: 12px 0;
        transition: all 0.2s;
    }
    .checklist-item:hover { background-color: #fcfcfc; }
    .text-xs { font-size: 0.75rem; }
    .disabled-view { pointer-events: none; opacity: 0.7; }
    /* Estilo para a bordinha do input de obs */
    .obs-input {
        border-left: 3px solid #dee2e6 !important;
        margin-top: 5px;
        background-color: #f8f9fa !important;
    }
    .obs-input:focus {
        border-left-color: #007bff !important;
        background-color: #fff !important;
    }
</style>

<div class="row mb-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-left <?= $os['status'] == 'cancelada' ? 'border-danger' : 'border-primary' ?>">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h4 class="mb-1"><i class="fas fa-tools text-primary mr-2"></i> OS #<?= $os['numero_os'] ?> 
                            <small class="badge 
                                <?php 
                                    switch($os['status']) {
                                        case 'orcamento':   echo 'badge-secondary'; break;
                                        case 'aberta':      echo 'badge-warning';   break;
                                        case 'em_execucao': echo 'badge-info';      break;
                                        case 'finalizada':  echo 'badge-success';   break;
                                        case 'cancelada':   echo 'badge-danger';    break;
                                        default:            echo 'badge-dark';
                                    }
                                ?> ml-2">
                                <?= strtoupper(str_replace('_', ' ', $os['status'])) ?>
                            </small>
                        </h4>
                        <p class="mb-0 text-muted">
                            <strong>Cliente:</strong> <?= $os['cliente_nome'] ?>
                        </p>    
                        <p class="mb-0">
                         <strong>Veículo:</strong> <?= $os['veiculo_modelo'] ?> |  <strong>Placa:</strong> <span class="badge badge-dark"><?= $os['veiculo_placa'] ?></span>
                        </p>    
                        <p>
                            <strong>Técnico:</strong> <span class="text-primary font-weight-bold"><?= $os['tecnico_nome'] ?? 'Não atribuído' ?></span>
                        </p>
                    </div>
                    <div class="col-md-5 text-md-right mt-3 mt-md-0">
                       <div class="btn-group shadow-sm">
                            <a href="<?= base_url('os/imprimir/'.$os['id']) ?>" target="_blank" class="btn btn-default border"><i class="fas fa-print text-danger"></i> O.S.</a>
                            <a href="<?= base_url('os/relatorio/'.$os['id']) ?>" target="_blank" class="btn btn-default border"><i class="fas fa-file-pdf text-primary"></i> Checklist</a>
                            <a href="<?= base_url('os/whatsapp/'.$os['id']) ?>" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>

                            <?php if (!$isFinalizada): ?>
                                <?php if ($isOrcamento): ?>
                                    <a href="<?= base_url('os/aprovar/'.$os['id']) ?>" class="btn btn-info"><i class="fas fa-thumbs-up"></i> Aprovar</a>
                                <?php elseif ($isAberta): ?>
                                    <a href="<?= base_url('os/iniciar_execucao/'.$os['id']) ?>" class="btn btn-info"><i class="fas fa-play"></i> Iniciar</a>
                                <?php else: ?>
                                    <a href="<?= base_url('os/finalizar/'.$os['id']) ?>" class="btn btn-primary" onclick="return confirm('Finalizar OS?')"><i class="fas fa-check-circle"></i> Finalizar</a>
                                <?php endif; ?>
                                <a href="<?= base_url('os/cancelar/'.$os['id']) ?>" class="btn btn-danger" onclick="return confirm('Cancelar OS?')"><i class="fas fa-times-circle"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row <?= $isFinalizada ? 'disabled-view' : '' ?>">
    <div class="col-lg-8 mb-4">
        <div class="card card-outline card-navy shadow h-100">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-box-open mr-2"></i> Itens e Peças</h3>
                <div class="card-tools">
                    <?php if (!$isFinalizada): ?>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalNovoItem"><i class="fas fa-plus"></i> Novo Item</button>
                    <?php endif; ?>
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
                            <?php if (!$isFinalizada): ?><th class="text-center">#</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($itens as $i): ?>
                        <tr>
                            <td><?= $i['descricao'] ?></td>
                            <td class="text-center"><?= (float)$i['quantidade'] ?></td>
                            <td class="text-right">R$ <?= number_format($i['valor_unitario'], 2, ',', '.') ?></td>
                            <td class="text-right font-weight-bold">R$ <?= number_format($i['subtotal'], 2, ',', '.') ?></td>
                            <?php if (!$isFinalizada): ?>
                            <td class="text-center">
                                <a href="<?= base_url('os/remover_item/'.$i['id'].'/'.$os['id']) ?>" class="text-danger"><i class="fas fa-trash-alt"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($itens)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Nenhum item adicionado ainda.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-navy text-right">
                <h4 class="mb-0">TOTAL: R$ <?= number_format($os['valor_total'] ?? 0, 2, ',', '.') ?></h4>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card card-outline card-warning shadow mb-4">
            <div class="card-header p-2">
                <h3 class="card-title font-weight-bold text-xs mt-1 text-uppercase"><i class="fas fa-sign-in-alt mr-1"></i> Insp. Entrada</h3>
                <div class="card-tools">
                    <button class="btn btn-xs btn-outline-dark" data-toggle="modal" data-target="#modalCheckEntrada"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="checklist-container">
                    <?php foreach($itens_checklist as $ic): if($ic['tipo'] == 'entrada'): ?>
                    <div class="checklist-item w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge <?= $ic['status'] == 'ok' ? 'badge-success' : ($ic['status'] == 'nao_ok' ? 'badge-danger' : 'badge-warning') ?> mr-1">
                                    <i class="fas <?= $ic['status'] == 'ok' ? 'fa-check' : ($ic['status'] == 'nao_ok' ? 'fa-exclamation-triangle' : 'fa-clock') ?>"></i>
                                </span>
                                <span class="font-weight-bold"><?= $ic['descricao'] ?></span><br>
                                <small class="text-muted text-xs uppercase"><?= $ic['categoria'] ?? 'Geral' ?></small>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-xs <?= $ic['status'] == 'ok' ? 'btn-success' : 'btn-outline-success' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'ok')"><i class="fas fa-thumbs-up"></i></button>
                                <button type="button" class="btn btn-xs <?= $ic['status'] == 'nao_ok' ? 'btn-danger' : 'btn-outline-danger' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'nao_ok')"><i class="fas fa-thumbs-down"></i></button>
                            </div>
                        </div>
                        <input type="text" id="observacao-<?= $ic['id'] ?>" class="form-control form-control-sm border-0 obs-input text-xs" placeholder="Obs / Medição..." value="<?= $ic['observacao'] ?>" onblur="atualizarCheck(<?= $ic['id'] ?>, '<?= $ic['status'] ?>')">
                    </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary shadow mb-4">
            <div class="card-header p-2">
                <h3 class="card-title font-weight-bold text-xs mt-1 text-uppercase"><i class="fas fa-microchip mr-1"></i> Conf. Técnica</h3>
                <div class="card-tools">
                    <button class="btn btn-xs btn-outline-dark" data-toggle="modal" data-target="#modalCheckTecnico"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="checklist-container">
                    <?php foreach($itens_checklist as $ic): if($ic['tipo'] == 'servico'): ?>
                    <div class="checklist-item w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge <?= $ic['status'] == 'ok' ? 'badge-success' : ($ic['status'] == 'nao_ok' ? 'badge-danger' : 'badge-warning') ?> mr-1">
                                    <i class="fas <?= $ic['status'] == 'ok' ? 'fa-check' : ($ic['status'] == 'nao_ok' ? 'fa-exclamation-triangle' : 'fa-clock') ?>"></i>
                                </span>
                                <span class="font-weight-bold"><?= $ic['descricao'] ?></span><br>
                                <small class="text-muted text-xs uppercase"><?= $ic['categoria'] ?? 'Geral' ?></small>
                            </div>
                            <div class="btn-group shadow-sm">
                                <button type="button" class="btn btn-xs <?= $ic['status'] == 'ok' ? 'btn-success' : 'btn-outline-success' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'ok')"><i class="fas fa-thumbs-up"></i></button>
                                <button type="button" class="btn btn-xs <?= $ic['status'] == 'nao_ok' ? 'btn-danger' : 'btn-outline-danger' ?>" onclick="atualizarCheck(<?= $ic['id'] ?>, 'nao_ok')"><i class="fas fa-thumbs-down"></i></button>
                            </div>
                        </div>
                        <input type="text" id="observacao-<?= $ic['id'] ?>" class="form-control form-control-sm border-0 obs-input text-xs" placeholder="Obs / Medição..." value="<?= $ic['observacao'] ?>" onblur="atualizarCheck(<?= $ic['id'] ?>, '<?= $ic['status'] ?>')">
                    </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    include ('modais/novo_item_so_modal.php'); 
    include ('modais/checklist_modal.php'); 
?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/os-gerenciamento.js') ?>"></script>

<script>
$(document).ready(function() {
    const BASE_URL = '<?= base_url() ?>';

    // Inicializa como produto por padrão
    inicializarSelect2('produto', BASE_URL);

    $('#select_item_busca').on('select2:select', function (e) {
        const data = e.params.data;
        $('#item_preco').val(data.preco);
        calcularSubtotal();
        setTimeout(() => $('#item_qtd').focus().select(), 100);
    });
});

function carregarItens(tipo) {
    inicializarSelect2(tipo, '<?= base_url() ?>');
}

function salvarCheck(tipo) {
    const checkId = (tipo === 'entrada') ? $('#select_entrada').val() : $('#select_tecnico').val();
    const osId = '<?= $os['id'] ?>';

    if (!checkId) return;

    $.post('<?= base_url('os/incluir_item_checklist') ?>', { 
        os_id: osId, 
        checklist_id: checkId, 
        tipo: tipo
    }, function() { 
        location.reload(); 
    }).fail(function() { alert("Erro ao incluir item."); });
}

function atualizarCheck(id, status) {
    const observacao = $('#observacao-' + id).val();
    
    $.post('<?= base_url('os/atualizar_item_checklist') ?>', { 
        item_id: id, 
        status: status,
        observacao: observacao
    }, function() { 
        // Em vez de reload total, poderíamos atualizar apenas o badge via JS
        // Mas o reload garante que os totais e status da OS se mantenham íntegros
        location.reload(); 
    }).fail(function() {
        alert("Erro ao atualizar o item.");
    });
}
</script>
<?= $this->endSection() ?>