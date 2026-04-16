<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <?php 
        $is_edicao = isset($requisicao);
        $url_action = $is_edicao ? base_url('compras/salvar/'.$requisicao['id']) : base_url('compras/salvar');
    ?>

    <form action="<?= $url_action ?>" method="post" id="form-requisicao">
        <?= csrf_field() ?>
        
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title font-weight-bold">
                    <i class="fas <?= $is_edicao ? 'fa-edit' : 'fa-plus-circle' ?> mr-2"></i> 
                    <?= $is_edicao ? 'Editar Requisição #' . str_pad($requisicao['id'], 4, '0', STR_PAD_LEFT) : 'Nova Requisição de Compra' ?>
                </h3>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Fornecedor <span class="text-danger">*</span></label>
                            <select name="fornecedor_id" class="form-control select2" required>
                                <option value="">Selecione um fornecedor...</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <option value="<?= $f['id'] ?>" <?= ($is_edicao && $requisicao['fornecedor_id'] == $f['id']) ? 'selected' : '' ?>>
                                        <?= $f['nome_fantasia'] ?> (<?= $f['documento'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Data da Requisição</label>
                            <input type="date" name="data_requisicao" class="form-control" 
                                   value="<?= $is_edicao ? $requisicao['data_requisicao'] : date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Observações Internas</label>
                            <input type="text" name="observacoes" class="form-control" 
                                   value="<?= $is_edicao ? $requisicao['observacoes'] : '' ?>" placeholder="Ex: Urgente, uso na manutenção...">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabela-itens">
                        <thead class="bg-navy">
                            <tr>
                                <th width="40%">Buscar Produto / Serviço</th>
                                <th width="15%">Qtd</th>
                                <th width="20%">Vlr. Unitário (R$)</th>
                                <th width="20%">Subtotal (R$)</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($is_edicao && !empty($itens)): ?>
                                <?php foreach ($itens as $index => $it): ?>
                                    <tr id="linha_<?= $index ?>">
                                        <td>
                                            <select name="item_vinculo[]" class="form-control select2-remoto" onchange="selecionarItem(this, <?= $index ?>)" required>
                                                <option value="<?= $it['produto_id'] ?: 'S-'.$it['id'] ?>" selected>
                                                    <?= $it['descricao_item'] ?>
                                                </option>
                                            </select>
                                            <input type="hidden" name="item_descricao[]" id="desc_<?= $index ?>" value="<?= $it['descricao_item'] ?>">
                                            <input type="hidden" name="produto_id[]" id="prod_id_<?= $index ?>" value="<?= $it['produto_id'] ?>">
                                            <input type="hidden" name="item_id[]" value="<?= $it['id'] ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="item_quantidade[]" class="form-control qtd" step="0.001" value="<?= $it['quantidade'] ?>" onchange="calcularLinha(<?= $index ?>)" required>
                                        </td>
                                        <td>
                                            <input type="number" name="item_valor[]" id="valor_<?= $index ?>" class="form-control valor" step="0.01" value="<?= $it['valor_unitario'] ?>" onchange="calcularLinha(<?= $index ?>)" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control subtotal" value="<?= number_format($it['quantidade'] * $it['valor_unitario'], 2, '.', '') ?>" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removerLinha(<?= $index ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <td colspan="3" class="text-right font-weight-bold">TOTAL GERAL:</td>
                                <td class="text-right">
                                    <input type="number" name="total_geral" id="total_geral" class="form-control font-weight-bold text-primary" readonly value="<?= $is_edicao ? $requisicao['valor_total'] : '0.00' ?>">
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <button type="button" class="btn btn-success btn-sm mt-2" onclick="adicionarLinha()">
                    <i class="fas fa-plus mr-1"></i> Adicionar Item
                </button>
            </div>

            <div class="card-footer text-right">
                <a href="<?= base_url('compras') ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-primary px-5 shadow">
                    <i class="fas fa-save mr-2"></i> <?= $is_edicao ? 'ATUALIZAR REQUISIÇÃO' : 'SALVAR REQUISIÇÃO' ?>
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let contadorLinhas = <?= ($is_edicao && !empty($itens)) ? count($itens) : 0 ?>;

    $(document).ready(function() {
        // Inicializa Select2 comum (Fornecedor)
        $('.select2').select2({ theme: 'bootstrap4' });
        
        // Se for novo, adiciona a primeira linha
        <?php if (!$is_edicao): ?>
            adicionarLinha();
        <?php else: ?>
            inicializarSelect2Remoto();
        <?php endif; ?>
        
        calcularTotalGeral();
    });

    function adicionarLinha() {
        const index = contadorLinhas++;
        const html = `
            <tr id="linha_${index}">
                <td>
                    <select name="item_vinculo[]" class="form-control select2-remoto" onchange="selecionarItem(this, ${index})" required>
                        <option value="">Digite para buscar...</option>
                    </select>
                    <input type="hidden" name="item_descricao[]" id="desc_${index}">
                    <input type="hidden" name="produto_id[]" id="prod_id_${index}">
                    <input type="hidden" name="item_id[]" value="">
                </td>
                <td>
                    <input type="number" name="item_quantidade[]" class="form-control qtd" step="0.001" value="1.000" onchange="calcularLinha(${index})" required>
                </td>
                <td>
                    <input type="number" name="item_valor[]" id="valor_${index}" class="form-control valor" step="0.01" value="0.00" onchange="calcularLinha(${index})" required>
                </td>
                <td>
                    <input type="number" class="form-control subtotal" value="0.00" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerLinha(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tabela-itens tbody').append(html);
        inicializarSelect2Remoto();
    }

    function inicializarSelect2Remoto() {
        $('.select2-remoto').select2({
            theme: 'bootstrap4',
            width: '100%',
            minimumInputLength: 2,
            ajax: {
                url: '<?= base_url("compras/buscar_itens_ajax") ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { term: params.term };
                },
                processResults: function (data) {
                    return { results: data };
                }
            }
        });
    }

    function selecionarItem(select, index) {
        const data = $(select).select2('data')[0];
        if (data) {
            $(`#desc_${index}`).val(data.text);
            $(`#valor_${index}`).val(data.preco);
            
            if (data.tipo === 'produto') {
                $(`#prod_id_${index}`).val(data.id);
            } else {
                $(`#prod_id_${index}`).val('');
            }
            calcularLinha(index);
        }
    }

    function removerLinha(id) {
        if ($('#tabela-itens tbody tr').length > 1) {
            $(`#linha_${id}`).remove();
            calcularTotalGeral();
        } else {
            Swal.fire('Aviso', 'A requisição deve ter pelo menos um item.', 'warning');
        }
    }

    function calcularLinha(id) {
        const qtd = parseFloat($(`#linha_${id} .qtd`).val()) || 0;
        const valor = parseFloat($(`#linha_${id} .valor`).val()) || 0;
        const subtotal = qtd * valor;
        $(`#linha_${id} .subtotal`).val(subtotal.toFixed(2));
        calcularTotalGeral();
    }

    function calcularTotalGeral() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_geral').val(total.toFixed(2));
    }
</script>
<?= $this->endSection() ?>