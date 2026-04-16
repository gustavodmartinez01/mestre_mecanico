<?= $this->extend('common/layout_v') ?>
<?= $this->section('conteudo') ?>

<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title font-weight-bold"><i class="fas fa-plus mr-2"></i> Novo Lançamento de Receita</h3>
    </div>
    <form action="<?= base_url('contas-receber/salvar') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-uppercase">Cliente</label>
                        <select name="cliente_id" class="form-control select2" required>
                            <option value="">Selecione o Cliente...</option>
                            <?php foreach($clientes as $cli): ?>
                                <option value="<?= $cli['id'] ?>"><?= $cli['nome_razao'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-uppercase">Descrição do Lançamento</label>
                        <input type="text" name="descricao" class="form-control" placeholder="Ex: Honorários, Venda de Peças, etc." required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-uppercase">Valor Total (R$)</label>
                        <input type="number" name="valor_total" step="0.01" class="form-control font-weight-bold" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-uppercase">Quantidade de Parcelas</label>
                        <select name="parcelas" class="form-control">
                            <?php for($i=1; $i<=12; $i++): ?>
                                <option value="<?= $i ?>"><?= $i ?>x</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold text-uppercase">Data do 1º Vencimento</label>
                        <input type="date" name="data_vencimento" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light text-right">
            <a href="<?= base_url('contas-receber') ?>" class="btn btn-default btn-sm font-weight-bold">CANCELAR</a>
            <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold shadow-sm">
                <i class="fas fa-save mr-2"></i> SALVAR LANÇAMENTO
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>