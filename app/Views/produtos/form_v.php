<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-box-open mr-2"></i> <?= $titulo ?></h3>
    </div>
    
    <form action="<?= base_url('produtos/salvar') ?>" method="post">
        <input type="hidden" name="id" value="<?= $p['id'] ?? '' ?>">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cód. Barras / SKU</label>
                        <input type="text" name="codigo_barras" class="form-control" value="<?= $p['codigo_barras'] ?? '' ?>" placeholder="Opcional">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nome da Peça/Produto *</label>
                        <input type="text" name="nome" class="form-control" value="<?= $p['nome'] ?? '' ?>" placeholder="Ex: Amortecedor Dianteiro Direito" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" class="form-control" value="<?= $p['marca'] ?? '' ?>" placeholder="Ex: Cofap, Monroe...">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Unidade</label>
                        <select name="unidade_medida" class="form-control">
                            <?php $un = $p['unidade_medida'] ?? 'UN'; ?>
                            <option value="UN" <?= $un == 'UN' ? 'selected' : '' ?>>Unidade (UN)</option>
                            <option value="LITRO" <?= $un == 'LITRO' ? 'selected' : '' ?>>Litro (L)</option>
                            <option value="KG" <?= $un == 'KG' ? 'selected' : '' ?>>Quilo (KG)</option>
                            <option value="PAR" <?= $un == 'PAR' ? 'selected' : '' ?>>Par</option>
                            <option value="CONJ" <?= $un == 'CONJ' ? 'selected' : '' ?>>Conjunto</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Preço de Custo (Un)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                            <input type="text" name="preco_custo" class="form-control money" value="<?= isset($p) ? number_format($p['preco_custo'], 2, ',', '.') : '0,00' ?>">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Preço de Venda (Un)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text text-success text-bold">R$</span></div>
                            <input type="text" name="preco_venda" class="form-control money" value="<?= isset($p) ? number_format($p['preco_venda'], 2, ',', '.') : '0,00' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Estoque Mínimo (Alerta)</label>
                        <input type="number" name="estoque_minimo" class="form-control" value="<?= $p['estoque_minimo'] ?? '1' ?>" min="0">
                        <small class="text-muted">O sistema avisará quando chegar nesta qtd.</small>
                    </div>
                </div>
            </div>
            
            <?php if(!isset($p)): ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-primary">Estoque Inicial</label>
                        <input type="number" name="estoque_atual" class="form-control border-primary" value="0" min="0">
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <div class="card-footer text-right">
            <a href="<?= base_url('produtos') ?>" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5">Salvar Produto</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
$(document).ready(function() {
    $(".money").maskMoney({
        prefix: '', thousands: '.', decimal: ',', affixesStay: false
    });
});
</script>
<?= $this->endSection() ?>