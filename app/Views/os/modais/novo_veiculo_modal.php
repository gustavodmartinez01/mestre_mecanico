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
