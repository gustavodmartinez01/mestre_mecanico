<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-12">
        <form action="<?= base_url('funcionarios/salvar') ?>" method="post">
            <input type="hidden" name="id" value="<?= $funcionario['id'] ?? '' ?>">
            <input type="hidden" name="usuario_id" value="<?= $funcionario['usuario_id'] ?? '' ?>">
            
            <div class="card card-primary card-outline shadow">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i> Cadastro Completo de Funcionário</h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" name="nome" class="form-control" value="<?= $funcionario['nome'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail de Contato</label>
                                <input type="email" name="email" class="form-control" value="<?= $funcionario['email'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CPF <span class="text-danger">*</span></label>
                                <input type="text" name="cpf" class="form-control cpf-mask" value="<?= $funcionario['cpf'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>RG</label>
                                <input type="text" name="rg" class="form-control" value="<?= $funcionario['rg'] ?? '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Matrícula</label>
                                <input type="text" name="matricula" class="form-control" value="<?= $funcionario['matricula'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cargo</label>
                                <select name="cargo" class="form-control">
                                    <?php $cargos = ['Mecânico','Técnico','Gerente','Recepcionista','Auxiliar','Administrador','Caixa']; 
                                    foreach($cargos as $c): ?>
                                        <option value="<?= $c ?>" <?= (isset($funcionario['cargo']) && $funcionario['cargo'] == $c) ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Comissão Serv. (%)</label>
                                <input type="number" name="comissao_servico" class="form-control" value="<?= $funcionario['comissao_servico'] ?? '0.00' ?>" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Comissão Prod. (%)</label>
                                <input type="number" name="comissao_produto" class="form-control" value="<?= $funcionario['comissao_produto'] ?? '0.00' ?>" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="trabalhando" <?= (isset($funcionario['status']) && $funcionario['status'] == 'trabalhando') ? 'selected' : '' ?>>Trabalhando</option>
                                    <option value="ferias" <?= (isset($funcionario['status']) && $funcionario['status'] == 'ferias') ? 'selected' : '' ?>>Férias</option>
                                    <option value="desligado" <?= (isset($funcionario['status']) && $funcionario['status'] == 'desligado') ? 'selected' : '' ?>>Desligado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3 text-secondary"><i class="fas fa-map-marker-alt"></i> Endereço Residencial</h5>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control cep-mask" value="<?= $funcionario['cep'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $funcionario['logradouro'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" name="numero" id="numero" class="form-control" value="<?= $funcionario['numero'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $funcionario['bairro'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    <hr>
<h5 class="mb-3 text-secondary"><i class="fas fa-map-marker-alt"></i> Endereço Residencial</h5>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label>CEP</label>
            <input type="text" name="cep" id="cep" class="form-control cep-mask" value="<?= $funcionario['cep'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Logradouro</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $funcionario['logradouro'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Número</label>
            <input type="text" name="numero" id="numero" class="form-control" value="<?= $funcionario['numero'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Complemento</label>
            <input type="text" name="complemento" class="form-control" value="<?= $funcionario['complemento'] ?? '' ?>" placeholder="Ex: Apto 101, Bloco B">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>Bairro</label>
            <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $funcionario['bairro'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            <label>Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $funcionario['cidade'] ?? '' ?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Estado (UF)</label>
            <input type="text" name="estado" id="estado" class="form-control" value="<?= $funcionario['estado'] ?? '' ?>" maxlength="2">
        </div>
    </div>
</div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $funcionario['cidade'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Estado (UF)</label>
                                <input type="text" name="estado" id="estado" class="form-control" value="<?= $funcionario['estado'] ?? '' ?>" maxlength="2">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="bg-light p-3 border rounded shadow-sm">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="permitir_acesso" class="custom-control-input" id="permitirAcesso" value="1" <?= (isset($funcionario['usuario_id']) && $funcionario['usuario_id']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="permitirAcesso">Habilitar Acesso ao Sistema (Login)</label>
                        </div>
                        <div id="campos-acesso" style="<?= (isset($funcionario['usuario_id']) && $funcionario['usuario_id']) ? '' : 'display:none;' ?>" class="mt-2">
                            <small class="text-primary font-weight-bold">
                                <i class="fas fa-key"></i> O e-mail informado acima será o Login. Senha padrão: 123456
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="<?= base_url('funcionarios') ?>" class="btn btn-default mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-5 shadow">Salvar Funcionário</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.cpf-mask').inputmask('999.999.999-99');
    $('.cep-mask').inputmask('99999-999');

    // BUSCA CEP VIA API
    $("#cep").blur(function() {
        var cep = $(this).val().replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                $("#logradouro").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#estado").val("...");

                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        $("#logradouro").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#estado").val(dados.uf);
                        $("#numero").focus();
                    } else {
                        alert("CEP não encontrado.");
                    }
                });
            }
        }
    });

    $('#permitirAcesso').change(function() {
        if($(this).is(':checked')) $('#campos-acesso').fadeIn();
        else $('#campos-acesso').fadeOut();
    });
});
</script>
<?= $this->endSection() ?>