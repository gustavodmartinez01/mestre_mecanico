<?= $this->extend('common/layout_v') ?>

<?= $this->section('styles') ?>
<style>
    .card-title { font-weight: bold; }
    .required::after { content: " *"; color: red; }
    #secao_acesso { display: none; background: #f8f9fa; border-radius: 8px; padding: 15px; border-left: 5px solid #17a2b8; }
    /* Ajuste para alinhar o ícone de ver senha */
    .input-group-text { cursor: pointer; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<div class="container-fluid">
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger alert-dismissible shadow-sm">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-ban"></i> Atenção!</h5>
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('funcionarios/salvar') ?>" method="post" id="form-funcionario" autocomplete="off">
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
                                <input type="text" name="nome" class="form-control" value="<?= old('nome', $funcionario['nome'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label class="required">CPF</label>
                                <input type="text" name="cpf" id="cpf" class="form-control" value="<?= old('cpf', $funcionario['cpf'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>RG</label>
                                <input type="text" name="rg" class="form-control" value="<?= old('rg', $funcionario['rg'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Data de Nascimento</label>
                                <input type="date" name="data_nascimento" class="form-control" value="<?= old('data_nascimento', $funcionario['data_nascimento'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Matrícula</label>
                                <input type="text" name="matricula" class="form-control" value="<?= old('matricula', $funcionario['matricula'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="required">Cargo</label>
                                <select name="cargo" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php 
                                    $cargos = ['Administrador', 'Gerente', 'Técnico', 'Caixa', 'Auxiliar'];
                                    $atual = old('cargo', $funcionario['cargo'] ?? '');
                                    foreach($cargos as $c): ?>
                                        <option value="<?= $c ?>" <?= ($atual == $c) ? 'selected' : '' ?>><?= $c ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>E-mail Pessoal</label>
                                <input type="email" name="email" class="form-control" value="<?= old('email', $funcionario['email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Telefone Fixo</label>
                                <input type="text" name="telefone" id="telefone" class="form-control" value="<?= old('telefone', $funcionario['telefone'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Celular (WhatsApp)</label>
                                <input type="text" name="celular" id="celular" class="form-control" value="<?= old('celular', $funcionario['celular'] ?? '') ?>">
                            </div>
                        </div>

                        <hr>
                        <h5><i class="fas fa-map-marker-alt text-danger"></i> Endereço</h5>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>CEP</label>
                                <div class="input-group">
                                    <input type="text" name="cep" id="cep" class="form-control" value="<?= old('cep', $funcionario['cep'] ?? '') ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-info" type="button" onclick="buscaCEP()"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 form-group">
                                <label>Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= old('logradouro', $funcionario['logradouro'] ?? '') ?>">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Nº</label>
                                <input type="text" name="numero" class="form-control" value="<?= old('numero', $funcionario['numero'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" value="<?= old('bairro', $funcionario['bairro'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" value="<?= old('cidade', $funcionario['cidade'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Estado</label>
                                <input type="text" name="estado" id="uf" class="form-control" value="<?= old('estado', $funcionario['estado'] ?? '') ?>" maxlength="2">
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
                            <?php $temAcesso = (isset($funcionario['usuario_id']) && $funcionario['usuario_id'] > 0); ?>
                            <input type="checkbox" class="custom-control-input" id="permitir_acesso" name="permitir_acesso" value="1" <?= $temAcesso ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="permitir_acesso">Habilitar login do funcionário</label>
                        </div>

                        <div id="secao_acesso" <?= $temAcesso ? 'style="display:block"' : '' ?>>
                            <div class="form-group">
                             <label>Nível de Acesso</label>
                                <select name="nivel_acesso" class="form-control">
                                <?php 
                                 // Pegamos o nível que vem do banco (ou do 'old' se houver erro de validação)
                                $nivelAtual = old('nivel_acesso', $funcionario['nivel_acesso'] ?? 'funcionario'); 
                               
                                 ?>
                                    <option value="funcionario" <?= ($nivelAtual == 'funcionario') ? 'selected' : '' ?>>Funcionário / Técnico</option>
                                    <option value="gerente"     <?= ($nivelAtual == 'gerente')     ? 'selected' : '' ?>>Gerente</option>
                                    <option value="admin"       <?= ($nivelAtual == 'admin')       ? 'selected' : '' ?>>Administrador</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Senha <?= isset($funcionario['id']) ? '(Vazio para manter)' : '' ?></label>
                                <div class="input-group">
                                    <input type="password" name="senha" id="input_senha" class="form-control" placeholder="Mínimo 6 dígitos">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="btn_toggle_senha">
                                            <i class="fas fa-eye" id="icone_olho"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="trabalhando" <?= (old('status', $funcionario['status'] ?? '') == 'trabalhando') ? 'selected' : '' ?>>Trabalhando</option>
                                <option value="ferias" <?= (old('status', $funcionario['status'] ?? '') == 'ferias') ? 'selected' : '' ?>>Férias</option>
                                <option value="afastado" <?= (old('status', $funcionario['status'] ?? '') == 'afastado') ? 'selected' : '' ?>>Afastado</option>
                                <option value="desligado" <?= (old('status', $funcionario['status'] ?? '') == 'desligado') ? 'selected' : '' ?>>Desligado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Admissão</label>
                            <div class="input-group">
                                <input type="date" name="data_admissao" id="data_admissao" class="form-control" value="<?= old('data_admissao', $funcionario['data_admissao'] ?? '') ?>" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-date" type="button" data-target="data_admissao" title="Habilitar Edição"><i class="fas fa-lock"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Demissão</label>
                            <div class="input-group">
                                <input type="date" name="data_demissao" id="data_demissao" class="form-control" value="<?= old('data_demissao', $funcionario['data_demissao'] ?? '') ?>" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-date" type="button" data-target="data_demissao" title="Habilitar Edição"><i class="fas fa-lock"></i></button>
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
                                <input type="number" step="0.01" name="comissao_servico" class="form-control" value="<?= old('comissao_servico', $funcionario['comissao_servico'] ?? '0.00') ?>">
                            </div>
                            <div class="col-6">
                                <label>Produtos</label>
                                <input type="number" step="0.01" name="comissao_produto" class="form-control" value="<?= old('comissao_produto', $funcionario['comissao_produto'] ?? '0.00') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-right">
                <hr>
                <a href="<?= base_url('funcionarios') ?>" class="btn btn-link text-muted mr-3">Voltar</a>
                <button type="submit" class="btn btn-primary btn-lg shadow px-5">
                    <i class="fas fa-save mr-2"></i> Salvar Cadastro Martínez
                </button>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.8/jquery.inputmask.min.js"></script>

<script>
$(document).ready(function() {
    // Máscaras de entrada
    $('#cpf').inputmask('999.999.999-99');
    $('#cep').inputmask('99999-999');
    $('#telefone').inputmask('(99) 9999-9999');
    $('#celular').inputmask('(99) 99999-9999');

    // Alternar visibilidade da senha
    $('#btn_toggle_senha').on('click', function() {
        const input = $('#input_senha');
        const icone = $('#icone_olho');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icone.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icone.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Exibir/Ocultar campos de login
    $('#permitir_acesso').change(function() {
        if(this.checked) {
            $('#secao_acesso').slideDown();
        } else {
            $('#secao_acesso').slideUp();
        }
    });

    // Habilitar campos de data (Toggle Lock)
    $('.btn-toggle-date').click(function() {
        const targetId = $(this).data('target');
        const input = $('#' + targetId);
        const icon = $(this).find('i');
        
        if(input.is(':disabled')) {
            input.prop('disabled', false).focus();
            icon.removeClass('fa-lock').addClass('fa-unlock text-success');
            $(this).addClass('btn-outline-success').removeClass('btn-outline-secondary');
        } else {
            input.prop('disabled', true);
            icon.removeClass('fa-unlock text-success').addClass('fa-lock');
            $(this).addClass('btn-outline-secondary').removeClass('btn-outline-success');
        }
    });

    // CRÍTICO: Habilitar campos bloqueados antes do envio do formulário
    // Caso contrário, o PHP não receberá os valores das datas
    $('#form-funcionario').on('submit', function() {
        $('#data_admissao, #data_demissao').prop('disabled', false);
    });
});

// Busca CEP na API ViaCEP
function buscaCEP() {
    let cep = $('#cep').val().replace(/\D/g, '');
    if (cep.length === 8) {
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