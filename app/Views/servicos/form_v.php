<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-tools mr-2"></i> <?= $titulo ?></h3>
    </div>
    
    <form action="<?= base_url('servicos/salvar') ?>" method="post">
        <input type="hidden" name="id" value="<?= $s['id'] ?? '' ?>">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Nome do Serviço *</label>
                        <input type="text" name="nome" class="form-control" value="<?= $s['nome'] ?? '' ?>" placeholder="Ex: Retífica de Motor, Troca de Óleo..." required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="ativo" class="form-control">
                            <option value="1" <?= (isset($s) && $s['ativo'] == 1) ? 'selected' : '' ?>>Ativo</option>
                            <option value="0" <?= (isset($s) && $s['ativo'] == 0) ? 'selected' : '' ?>>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Custo (Se terceirizado)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="preco_custo" class="form-control money" value="<?= isset($s) ? number_format($s['preco_custo'], 2, ',', '.') : '0,00' ?>">
                        </div>
                        <small class="text-muted">Quanto a oficina paga para terceiros.</small>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Preço de Venda *</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text text-bold text-success">R$</span></div>
                            <input type="text" name="preco_venda" class="form-control money" value="<?= isset($s) ? number_format($s['preco_venda'], 2, ',', '.') : '0,00' ?>" required>
                        </div>
                        <small class="text-muted">Valor cobrado do cliente final.</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group text-center">
                        <label>Lucro Estimado</label>
                        <div id="lucro_badge" class="h4 mt-1 font-weight-bold text-success">R$ 0,00</div>
                    </div>
                </div>
            </div>

            <hr>
            <h5><i class="far fa-clock mr-2"></i> Tempo Estimado de Execução</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Dias</label>
                        <input type="number" name="tempo_dias" class="form-control" min="0" max="30" value="<?= $s['tempo_dias'] ?? 0 ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Horas</label>
                        <input type="number" name="tempo_hours" class="form-control" min="0" max="23" value="<?= $s['tempo_horas'] ?? 0 ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Minutos</label>
                        <select name="tempo_minutos" class="form-control">
                            <?php for($m=0; $m < 60; $m+=5): ?>
                                <option value="<?= $m ?>" <?= (isset($s) && $s['tempo_minutos'] == $m) ? 'selected' : '' ?>><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?> min</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Descrição Detalhada / Procedimento</label>
                <textarea name="descricao" class="form-control" rows="3" placeholder="O que está incluso neste serviço?"><?= $s['descricao'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="<?= base_url('servicos') ?>" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5">Salvar Serviço</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script>
$(document).ready(function() {
    // Configura máscara de moeda
    $(".money").maskMoney({
        prefix: '',
        allowNegative: false,
        thousands: '.',
        decimal: ',',
        affixesStay: false
    });

    // Função para calcular lucro em tempo real na tela
    function calcularLucro() {
        var custo = $("input[name='preco_custo']").maskMoney('unmasked')[0] || 0;
        var venda = $("input[name='preco_venda']").maskMoney('unmasked')[0] || 0;
        var lucro = venda - custo;

        var formato = lucro.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        $('#lucro_badge').text(formato);

        if(lucro < 0) {
            $('#lucro_badge').removeClass('text-success').addClass('text-danger');
        } else {
            $('#lucro_badge').removeClass('text-danger').addClass('text-success');
        }
    }

    $(".money").on('keyup', calcularLucro);
    calcularLucro(); // Executa ao carregar (edição)
});
</script>
<?= $this->endSection() ?>