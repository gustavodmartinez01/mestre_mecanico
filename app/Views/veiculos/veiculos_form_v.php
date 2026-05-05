<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="font-weight-bold text-dark">
            <i class="fas fa-car-side mr-2 text-primary"></i> 
            <?= isset($veiculo) ? 'Editar Veículo: ' . $veiculo['placa'] : 'Novo Cadastro de Veículo' ?>
        </h4>
        <a href="<?= base_url('veiculos') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Voltar
        </a>
    </div>

    <form action="<?= base_url('veiculos/salvar') ?>" method="POST">
        <?= csrf_field() ?>
        <?php if(isset($veiculo)): ?>
            <input type="hidden" name="id" value="<?= $veiculo['id'] ?>">
        <?php endif; ?>

        <div class="row">
            <!-- COLUNA 1: DADOS TÉCNICOS -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Informações do Veículo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">CLIENTE POSSUIDOR *</label>
                                <select name="cliente_id" class="form-control select2" required>
                                    <option value="">Selecione o Cliente</option>
                                    <?php foreach($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= (isset($veiculo) && $veiculo['cliente_id'] == $c['id']) ? 'selected' : '' ?>>
                                            <?= $c['nome_razao'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">PLACA *</label>
                                <input type="text" name="placa" class="form-control text-uppercase" placeholder="AAA0000" maxlength="8" required value="<?= $veiculo['placa'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold">MARCA</label>
                                <input type="text" name="marca" class="form-control" placeholder="Ex: VW" value="<?= $veiculo['marca'] ?? '' ?>">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="small font-weight-bold">MODELO</label>
                                <input type="text" name="modelo" class="form-control" placeholder="Ex: Gol G5" value="<?= $veiculo['modelo'] ?? '' ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold">ANO</label>
                                <input type="number" name="ano" class="form-control" placeholder="2024" value="<?= $veiculo['ano'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">PROPRIETÁRIO (DOC)</label>
                                <input type="text" name="proprietario" class="form-control" value="<?= $veiculo['proprietario'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">CPF/CNPJ PROPRIETÁRIO</label>
                                <input type="text" name="documento_proprietario" class="form-control" value="<?= $veiculo['documento_proprietario'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-fingerprint mr-2"></i>Identificação e Valor</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">RENAVAM</label>
                                <input type="text" name="renavam" class="form-control" value="<?= $veiculo['renavam'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">CHASSIS</label>
                                <input type="text" name="chassis" class="form-control" value="<?= $veiculo['chassis'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">VALOR FIPE</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">R$</span></div>
                                    <input type="text" name="valor_fipe" class="form-control money" value="<?= $veiculo['valor_fipe'] ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold">SEGURO VEICULAR</label>
                                <input type="text" name="seguro_veicular" class="form-control" placeholder="Nome da Seguradora" value="<?= $veiculo['seguro_veicular'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUNA 2: INSPEÇÃO TÉCNICA (O DIFERENCIAL) -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4 border-left-info">
                    <div class="card-header bg-info text-white py-3">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-clipboard-check mr-2"></i>Check-in: Estado de Conservação</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="small font-weight-bold text-info">LATARIA</label>
                            <input type="text" name="condicao_lataria" class="form-control" placeholder="Ex: Risco na porta esquerda, pequeno amassado no capô" value="<?= $veiculo['condicao_lataria'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small font-weight-bold text-info">PINTURA</label>
                            <input type="text" name="condicao_pintura" class="form-control" placeholder="Ex: Queimada de sol no teto" value="<?= $veiculo['condicao_pintura'] ?? '' ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-info">VIDROS</label>
                                <input type="text" name="condicao_vidros" class="form-control" placeholder="Ex: Parabrisa trincado" value="<?= $veiculo['condicao_vidros'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-info">LANTERNAS/FARÓIS</label>
                                <input type="text" name="condicao_lanternas" class="form-control" placeholder="Ex: Farol direito opaco" value="<?= $veiculo['condicao_lanternas'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small font-weight-bold text-info">ESTOFAMENTO / INTERIOR</label>
                            <input type="text" name="condicao_estofamento" class="form-control" placeholder="Ex: Banco motorista rasgado" value="<?= $veiculo['condicao_estofamento'] ?? '' ?>">
                        </div>
                        <div class="mb-0">
                            <label class="small font-weight-bold text-info">OBSERVAÇÕES GERAIS</label>
                            <textarea name="observacoes" class="form-control" rows="4"><?= $veiculo['observacoes'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold">
                            <i class="fas fa-save mr-2"></i> SALVAR VEÍCULO NO SISTEMA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>