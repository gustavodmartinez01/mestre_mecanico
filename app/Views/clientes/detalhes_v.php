<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <?php 
                        $cor = ($c['classificacao'] == 'Ouro') ? 'success' : (($c['classificacao'] == 'Prata') ? 'warning' : 'danger');
                        $icon = ($c['classificacao'] == 'Ouro') ? 'star' : (($c['classificacao'] == 'Prata') ? 'medal' : 'exclamation-triangle');
                    ?>
                    <span class="badge badge-<?= $cor ?> p-3 shadow-sm mb-3" style="font-size: 1.1rem; min-width: 80%;">
                        <i class="fas fa-<?= $icon ?> mr-2"></i> CLIENTE <?= strtoupper($c['classificacao']) ?>
                    </span>
                </div>
                <h3 class="profile-username text-center"><?= $c['nome_razao'] ?></h3>
                <p class="text-muted text-center"><?= $c['documento'] ?></p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Pontuação Score</b> <a class="float-right text-bold text-<?= $cor ?>"><?= $c['score_total'] ?> pts</a>
                    </li>
                    <li class="list-group-item">
                        <b>Crédito Recomendado</b> 
                        <span class="float-right badge badge-light border">
                            <?= ($c['classificacao'] == 'Ouro') ? 'Liberado / Faturado' : (($c['classificacao'] == 'Prata') ? 'Parcelamento Curto' : 'Somente à Vista') ?>
                        </span>
                    </li>
                </ul>

                <div class="bg-light p-3 rounded border">
                    <strong><i class="fas fa-map-marker-alt mr-1 text-primary"></i> Localização</strong>
                    <p class="text-muted small mb-2">
                        <?= $c['logradouro'] ?>, <?= $c['numero'] ?> <?= $c['complemento'] ?><br>
                        <?= $c['bairro'] ?> - <?= $c['cidade'] ?>/<?= $c['estado'] ?><br>
                        CEP: <?= $c['cep'] ?>
                    </p>
                    <hr class="my-2">
                    <strong><i class="fas fa-phone mr-1 text-primary"></i> Contatos</strong>
                    <p class="text-muted small mb-0">
                        Whats: <strong><?= $c['celular'] ?></strong><br>
                        Email: <?= $c['email'] ?>
                    </p>
                </div>
                
                <?php if($c['observacoes_financeiras']): ?>
                    <div class="mt-3 p-2 border-left border-warning bg-light small">
                        <strong><i class="fas fa-comment-dollar mr-1"></i> Notas Financeiras:</strong><br>
                        <?= nl2br($c['observacoes_financeiras']) ?>
                    </div>
                <?php endif; ?>
                <div class="mt-3">
    <a href="<?= base_url('clientes/pdf/'.$c['id']) ?>" target="_blank" class="btn btn-default btn-block btn-sm">
        <i class="fas fa-print mr-1"></i> Imprimir Ficha Completa
    </a>
</div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-dark">
            <div class="card-header border-transparent">
                <h3 class="card-title"><i class="fas fa-car mr-2"></i> Frota do Cliente</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNovoVeiculo">
                        <i class="fas fa-plus mr-1"></i> Adicionar Veículo
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Marca/Modelo</th>
                                <th>Ano/Cor</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($veiculos as $v): ?>
                            <tr>
                                <td><span class="badge badge-dark p-2" style="font-size: 1rem; letter-spacing: 1px;"><?= $v['placa'] ?></span></td>
                                <td><strong><?= $v['marca'] ?> <?= $v['modelo'] ?></strong></td>
                                <td><?= $v['ano'] ?> / <?= $v['cor'] ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="verVeiculo(<?= $v['id'] ?>)" title="Ver Checklist">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="editarVeiculo(<?= $v['id'] ?>)" title="Editar Veículo/Checklist">
                                             <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= base_url('veiculos/excluir/'.$v['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Excluir este veículo permanentemente?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($veiculos)): ?>
                            <tr>
                                <td colspan="4" class="text-center p-4">
                                    <i class="fas fa-car-crash fa-2x text-muted mb-2"></i><br>
                                    Nenhum veículo cadastrado.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovoVeiculo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('veiculos/salvar') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Novo Veículo: <?= $c['nome_razao'] ?></h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="veiculo_id" value="">
                    <input type="hidden" name="cliente_id" value="<?= $c['id'] ?>">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Placa *</label>
                                <input type="text" name="placa" id="input_placa" class="form-control text-uppercase" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Marca *</label>
                                <input type="text" name="marca" class="form-control" placeholder="Ex: Fiat" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Modelo *</label>
                                <input type="text" name="modelo" class="form-control" placeholder="Ex: Uno Mille" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Ano *</label>
                                <input type="number" name="ano" class="form-control" value="<?= date('Y') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cor *</label>
                                <input type="text" name="cor" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>RENAVAM</label>
                                <input type="text" name="renavam" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Chassi</label>
                                <input type="text" name="chassis" class="form-control">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i> Checklist de Entrada</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lataria</label>
                                <input type="text" name="condicao_lataria" class="form-control" placeholder="Ex: Risco porta esquerda">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pintura</label>
                                <input type="text" name="condicao_pintura" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Vidros/Parabrisa</label>
                                <input type="text" name="condicao_vidros" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lanternas/Faróis</label>
                                <input type="text" name="condicao_lanternas" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Estofamento</label>
                                <input type="text" name="condicao_estofamento" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Seguro (Cia)</label>
                                <input type="text" name="seguro_veicular" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Observações Gerais</label>
                        <textarea name="observacoes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Salvar Veículo</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalVerVeiculo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white"><i class="fas fa-clipboard mr-2"></i> Inspeção do Veículo</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <span class="badge badge-dark p-2 mb-2" id="v_placa" style="font-size: 1.4rem;"></span>
                    <h4 id="v_nome" class="text-bold text-uppercase"></h4>
                </div>
                
                <div class="list-group">
                    <div class="list-group-item"><strong>Lataria:</strong> <span id="v_lataria" class="float-right text-muted"></span></div>
                    <div class="list-group-item"><strong>Pintura:</strong> <span id="v_pintura" class="float-right text-muted"></span></div>
                    <div class="list-group-item"><strong>Vidros:</strong> <span id="v_vidros" class="float-right text-muted"></span></div>
                    <div class="list-group-item"><strong>Lanternas:</strong> <span id="v_lanternas" class="float-right text-muted"></span></div>
                    <div class="list-group-item"><strong>Estofamento:</strong> <span id="v_estofamento" class="float-right text-muted"></span></div>
                    <div class="list-group-item"><strong>Seguro:</strong> <span id="v_seguro" class="float-right text-muted"></span></div>
                </div>

                <div class="mt-3 p-3 bg-light border rounded">
                    <strong><i class="fas fa-sticky-note mr-1"></i> Observações Técnicas:</strong><br>
                    <span id="v_obs" class="small"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Fechar Visualização</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Máscara de Placa (Antiga e Mercosul)
    $('#input_placa').inputmask({
        mask: ["AAA-9999", "AAA9A99"],
        keepStatic: true,
        casing: "upper"
    });
});

// Função para abrir o modal em modo de EDIÇÃO
function editarVeiculo(id) {

    var listaVeiculos = <?= json_encode($veiculos) ?>;
    var v = listaVeiculos.find(x => x.id == id);

    if(v) {
        // Muda o título e a cor do header para diferenciar
        $('#modalNovoVeiculo .modal-title').text('Editar Veículo: ' + v.placa);
        $('#modalNovoVeiculo .modal-header').removeClass('bg-primary').addClass('bg-warning');
        
        // Preenche os campos básicos
        $('#veiculo_id').val(v.id);
        $('#input_placa').val(v.placa);
        $('input[name="marca"]').val(v.marca);
        $('input[name="modelo"]').val(v.modelo);
        $('input[name="ano"]').val(v.ano);
        $('input[name="cor"]').val(v.cor);
        $('input[name="renavam"]').val(v.renavam);
        $('input[name="chassis"]').val(v.chassis);
        
        // Preenche o Checklist
        $('input[name="condicao_lataria"]').val(v.condicao_lataria);
        $('input[name="condicao_pintura"]').val(v.condicao_pintura);
        $('input[name="condicao_vidros"]').val(v.condicao_vidros);
        $('input[name="condicao_lanternas"]').val(v.condicao_lanternas);
        $('input[name="condicao_estofamento"]').val(v.condicao_estofamento);
        $('input[name="seguro_veicular"]').val(v.seguro_veicular);
        $('textarea[name="observacoes"]').val(v.observacoes);

        $('#modalNovoVeiculo').modal('show');
    }
}

// Resetar o modal quando ele for fechado (para não ficar com dados de edição ao clicar em "Novo")
$('#modalNovoVeiculo').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
    $('#veiculo_id').val('');
    $('#modalNovoVeiculo .modal-title').text('Novo Veículo: <?= $c['nome_razao'] ?>');
    $('#modalNovoVeiculo .modal-header').removeClass('bg-warning').addClass('bg-primary');
});



// Função para abrir a lupa e popular os dados
function verVeiculo(id) {
    
    // Pegamos a lista de veículos que o PHP enviou para a página
    var listaVeiculos = <?= json_encode($veiculos) ?>;
    
    // Encontramos o veículo clicado
    var v = listaVeiculos.find(x => x.id == id);

    if(v) {
        $('#v_placa').text(v.placa);
        $('#v_nome').text(v.marca + ' ' + v.modelo + ' (' + v.ano + ')');
        $('#v_lataria').text(v.condicao_lataria || 'Sem avarias relatadas');
        $('#v_pintura').text(v.condicao_pintura || 'Sem detalhes');
        $('#v_vidros').text(v.condicao_vidros || 'Ok');
        $('#v_lanternas').text(v.condicao_lanternas || 'Ok');
        $('#v_estofamento').text(v.condicao_estofamento || 'Ok');
        $('#v_seguro').text(v.seguro_veicular || 'N/A');
        $('#v_obs').text(v.observacoes || 'Nenhuma nota adicional.');

        $('#modalVerVeiculo').modal('show');
    } else {
        alert('Erro ao carregar dados do veículo.');
    }
}
</script>
<?= $this->endSection() ?>