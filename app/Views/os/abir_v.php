<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-12">
        <form action="<?= base_url('ordemservico/salvar') ?>" method="post">
            <input type="hidden" name="usuario_id" value="<?= session()->get('id') ?>">
            
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice mr-2"></i> 
                        Nova Ordem de Serviço: <span class="badge badge-dark">#<?= str_pad($proximo_numero, 6, '0', STR_PAD_LEFT) ?></span>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cliente <span class="text-danger">*</span></label>
                                <select name="cliente_id" id="cliente_id" class="form-control select2" required>
                                    <option value="">-- Selecione o Cliente --</option>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= $c['nome'] ?> (<?= $c['cpf_cnpj'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Veículo <span class="text-danger">*</span></label>
                                <select name="veiculo_id" id="veiculo_id" class="form-control select2" required>
                                    <option value="">-- Selecione o Veículo --</option>
                                    <?php foreach ($veiculos as $v): ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['placa'] ?> - <?= $v['modelo'] ?> (<?= $v['marca'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row bg-light p-3 border rounded">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-primary"><i class="fas fa-user-cog mr-1"></i> Mecânico Responsável</label>
                                <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                                    <option value="">-- Atribuir serviço para... --</option>
                                    <?php foreach ($equipe as $f): ?>
                                        <?php 
                                            $sobrecarregado = ($f['os_ativas'] >= 5);
                                            $status_texto = $sobrecarregado ? "⚠️ SOBRECARREGADO ({$f['os_ativas']} OS)" : "✅ Disponível ({$f['os_ativas']} OS)";
                                        ?>
                                        <option value="<?= $f['id'] ?>" <?= $sobrecarregado ? 'class="text-danger font-weight-bold"' : '' ?>>
                                            <?= $f['nome'] ?> --- <?= $status_texto ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-text text-muted">
                                    O sistema recomenda equilibrar as ordens entre a equipe para evitar atrasos.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Previsão de Entrega</label>
                                <input type="datetime-local" name="data_previsao" class="form-control">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Defeito Relatado / Sintomas</label>
                                <textarea name="defeito_relatado" class="form-control" rows="3" placeholder="Descreva o que o cliente relatou..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observações Internas (Checklist visual, objetos no carro, etc.)</label>
                                <textarea name="observacoes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="<?= base_url('ordemservico') ?>" class="btn btn-default">Cancelar</a>
                    <button type="submit" class="btn btn-success px-5 shadow">
                        <i class="fas fa-save mr-2"></i> Abrir Ordem de Serviço
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inicializa Select2 para busca rápida de cliente/veículo
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: 'Pesquisar...'
        });

        // Alerta visual imediato ao selecionar mecânico cheio
        $('#tecnico_id').change(function() {
            var selecionado = $(this).find('option:selected').text();
            if (selecionado.includes('SOBRECARREGADO')) {
                alert('Atenção: Este mecânico já possui 5 ou mais ordens abertas. Verifique se ele conseguirá cumprir o prazo.');
            }
        });
    });
</script>
<?= $this->endSection() ?>