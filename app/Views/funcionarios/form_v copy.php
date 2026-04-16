<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
<style>
    .card-title { font-weight: bold; }
    .required::after { content: " *"; color: red; }
    #secao_acesso { display: none; background: #f8f9fa; border-radius: 8px; padding: 15px; border-left: 5px solid #17a2b8; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Erro ao Salvar!</h5>
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div class="container-fluid">
    <form action="<?= base_url('funcionarios/salvar') ?>" method="post" autocomplete="off">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $funcionario['id'] ?? '' ?>">
        <input type="hidden" name="usuario_id" value="<?= $funcionario['usuario_id'] ?? '' ?>">

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-outline card-primary shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i> Informações do Colaborador</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label class="required">Nome Completo</label>
                                <input type="text" name="nome" class="form-control" value="<?= $funcionario['nome'] ?? '' ?>" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="required">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" value="<?= $funcionario['cpf'] ?? '' ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>RG</label>
                                <input type="text" name="rg" class="form-control" value="<?= $funcionario['rg'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" class="form-control" value="<?= $funcionario['data_nascimento'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Matrícula</label>
                                <input type="text" name="matricula" class="form-control" value="<?= $funcionario['matricula'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="required">Cargo</label>
                                <select name="cargo" class="form-control select2" required>
                                    <option value="">Selecione...</option>
                                    <?php 
                                    $cargos = ['Administrador', 'Gerente', 'Técnico', 'Caixa', 'Auxiliar'];
                                    foreach($cargos as $c): ?>
                                        <option value="<?= $c ?>" <?= (isset($funcionario['cargo']) && $funcionario['cargo'] == $c) ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>E-mail Pessoal</label>
                                <input type="email" name="email" class="form-control" value="<?= $funcionario['email'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Telefone Fixo</label>
                                <input type="text" name="telefone" id="telefone" class="form-control" value="<?= $funcionario['telefone'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Celular (WhatsApp)</label>
                                <input type="text" name="celular" id="celular" class="form-control" value="<?= $funcionario['celular'] ?? '' ?>">
                            </div>
                        </div>

                        <hr>
                        <h5><i class="fas fa-map-marker-alt text-danger"></i> Endereço</h5>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>CEP</label>
                                <div class="input-group">
                                    <input type="text" name="cep" id="cep" class="form-control" value="<?= $funcionario['cep'] ?? '' ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="buscaCEP()"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 form-group">
                                <label>Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $funcionario['logradouro'] ?? '' ?>">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Nº</label>
                                <input type="text" name="numero" class="form-control" value="<?= $funcionario['numero'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $funcionario['bairro'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $funcionario['cidade'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Estado</label>
                                <input type="text" name="estado" id="uf" class="form-control" value="<?= $funcionario['estado'] ?? '' ?>" maxlength="2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-info shadow">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-key mr-2"></i> Acesso ao Sistema</h3>
                    </div>
                    <div class="card-body">
                        <div class="custom-control custom-switch mb-3">
                            <input type="checkbox" class="custom-control-input" id="permitir_acesso" name="permitir_acesso" value="1" 
                                <?= (isset($funcionario['usuario_id']) && $funcionario['usuario_id'] > 0) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="permitir_acesso">Permitir login no sistema</label>
                        </div>

                        <div id="secao_acesso" <?= (isset($funcionario['usuario_id']) && $funcionario['usuario_id'] > 0) ? 'style="display:block"' : '' ?>>
                            <div class="form-group">
                                <label>Nível de Acesso</label>
                                <select name="nivel_acesso" class="form-control">
                                    <option value="funcionario">Funcionário / Técnico</option>
                                    <option value="gerente">Gerente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Senha <?= isset($funcionario['id']) ? '(Deixe em branco para não alterar)' : '' ?></label>
                                <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Status Atual</label>
                            <select name="status" class="form-control">
                                <option value="trabalhando" <?= (isset($funcionario['status']) && $funcionario['status'] == 'trabalhando') ? 'selected' : '' ?>>Trabalhando</option>
                                <option value="ferias" <?= (isset($funcionario['status']) && $funcionario['status'] == 'ferias') ? 'selected' : '' ?>>Férias</option>
                                <option value="afastado" <?= (isset($funcionario['status']) && $funcionario['status'] == 'afastado') ? 'selected' : '' ?>>Afastado</option>
                                <option value="desligado" <?= (isset($funcionario['status']) && $funcionario['status'] == 'desligado') ? 'selected' : '' ?>>Desligado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data de Admissão</label>
                            <div class="input-group">
                                <input type="date" name="data_admissao" id="data_admissao" class="form-control" value="<?= $funcionario['data_admissao'] ?? '' ?>" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-date" type="button" data-target="data_admissao"><i class="fas fa-unlock"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Data de Demissão</label>
                            <div class="input-group">
                                <input type="date" name="data_demissao" id="data_demissao" class="form-control" value="<?= $funcionario['data_demissao'] ?? '' ?>" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-date" type="button" data-target="data_demissao"><i class="fas fa-unlock"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow card-success card-outline">
                    <div class="card-header"><h3 class="card-title">Comissões (%)</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <label>Serviços</label>
                                <input type="number" step="0.01" name="comissao_servico" class="form-control" value="<?= $funcionario['comissao_servico'] ?? '0.00' ?>">
                            </div>
                            <div class="col-6">
                                <label>Produtos</label>
                                <input type="number" step="0.01" name="comissao_produto" class="form-control" value="<?= $funcionario['comissao_produto'] ?? '0.00' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow p-3">
                    <label>Observações</label>
                    <textarea name="observacoes" class="form-control" rows="3"><?= $funcionario['observacoes'] ?? '' ?></textarea>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('funcionarios') ?>" class="btn btn-default mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm"><i class="fas fa-save mr-2"></i> Salvar Funcionário</button>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>

<script>
$(document).ready(function() {
    // Máscaras
    $('#cpf').inputmask('999.999.999-99');
    $('#cep').inputmask('99999-999');
    $('#telefone').inputmask('(99) 9999-9999');
    $('#celular').inputmask('(99) 99999-9999');

    // Toggle Seção de Acesso
    $('#permitir_acesso').change(function() {
        if(this.checked) {
            $('#secao_acesso').slideDown();
        } else {
            $('#secao_acesso').slideUp();
        }
    });

    // Habilitar campos de data bloqueados
    $('.btn-toggle-date').click(function() {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const icon = $(this).find('i');
        
        if(input.is(':disabled')) {
            input.prop('disabled', false);
            icon.removeClass('fa-unlock').addClass('fa-lock text-danger');
        } else {
            input.prop('disabled', true);
            icon.removeClass('fa-lock text-danger').addClass('fa-unlock');
        }
    });
});

function buscaCEP() {
    let cep = $('#cep').val().replace(/\D/g, '');
    if (cep != "") {
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
            if (!("erro" in dados)) {
                $("#logradouro").val(dados.logradouro);
                $("#bairro").val(dados.bairro);
                $("#cidade").val(dados.localidade);
                $("#uf").val(dados.uf);
                $("[name='numero']").focus();
            } else {
                alert("CEP não encontrado.");
            }
        });
    }
}
</script>
<?= $this->endSection() ?>