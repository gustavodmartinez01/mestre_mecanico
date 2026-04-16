<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-12">
        <form action="<?= base_url('os/salvar_abertura') ?>" method="post">
            <input type="hidden" name="numero_os" value="<?= $proximo_numero ?>">
            <input type="hidden" name="criado_por" value="<?= session()->get('id') ?>">

            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-signature mr-2"></i> 
                        Nova Ordem de Serviço <strong>#<?= $proximo_numero ?></strong>
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Cliente <span class="text-danger">*</span></label>
                                <select name="cliente_id" id="cliente_id" class="form-control select2" required>
                                    <option value="">-- Selecione o Cliente --</option>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>">
                                            <?= $c['nome_razao'] ?> (<?= $c['documento'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Veículo <span class="text-danger">*</span></label>
                                <select name="veiculo_id" id="veiculo_id" class="form-control select2" required>
                                    <option value="">-- Selecione um Cliente Primeiro --</option>
                                    <?php foreach ($veiculos as $v): ?>
                                        <option value="<?= $v['id'] ?>" class="v-cli-<?= $v['cliente_id'] ?>" style="display:none;">
                                            <?= $v['placa'] ?> - <?= $v['modelo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                 
                            
                            </div>
                            </div>
                           
                        </div>
                        
                        
                    </div>

                    <hr>

                    <div class="row bg-light p-3 border rounded shadow-sm">
                        <div class="col-md-4 border-right">
                            <div class="form-group">
                                <label class="text-primary"><i class="fas fa-user-cog mr-1"></i> Mecânico Responsável</label>
                                <select name="tecnico_id" id="tecnico_id" class="form-control" required>
                                    <option value="">-- Atribuir serviço para... --</option>
                                    <?php foreach ($equipe as $f): ?>
                                        <?php 
                                            $sobrecarregado = ($f['os_ativas'] >= 5);
                                            $status_texto = $sobrecarregado ? "⚠️ ({$f['os_ativas']} OS)" : "✅ ({$f['os_ativas']} OS)";
                                        ?>
                                        <option value="<?= $f['id'] ?>" <?= $sobrecarregado ? 'class="text-danger font-weight-bold"' : '' ?>>
                                            <?= $f['nome'] ?> --- <?= $status_texto ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 border-right">
                            <div class="form-group">
                                <label>KM Entrada</label>
                                <input type="number" name="km_entrada" class="form-control" placeholder="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descrição do Problema / Solicitação <span class="text-danger">*</span></label>
                                <textarea name="descricao_problema" class="form-control" rows="2" required 
                                          placeholder="Relato do cliente sobre o defeito..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-right">
                    <a href="<?= base_url('os') ?>" class="btn btn-secondary px-4">Cancelar</a>
                    <button type="submit" class="btn btn-success px-5 shadow">
                        <i class="fas fa-check-circle mr-2"></i> Abrir Ordem de Serviço
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
    // Filtro simples de veículos por cliente
    $('#cliente_id').change(function() {
        const clienteId = $(this).val();
        $('#veiculo_id option').hide();
        $('#veiculo_id').val('');
        
        if (clienteId) {
            $(`.v-cli-${clienteId}`).show();
            $('#veiculo_id').prepend('<option value="" selected>-- Selecione o Veículo --</option>');
        }
    });

    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
<?= $this->endSection() ?>