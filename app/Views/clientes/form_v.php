<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-tie mr-2"></i> <?= $titulo ?></h3>
    </div>
    <form action="<?= base_url('clientes/salvar') ?>" method="post">
        <input type="hidden" name="id" value="<?= $c['id'] ?? '' ?>">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="tipo_pessoa" id="tipo_pessoa" class="form-control">
                            <option value="F" <?= (isset($c) && $c['tipo_pessoa'] == 'F') ? 'selected' : '' ?>>Pessoa Física</option>
                            <option value="J" <?= (isset($c) && $c['tipo_pessoa'] == 'J') ? 'selected' : '' ?>>Pessoa Jurídica</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="label_doc">CPF *</label>
                        <input type="text" name="documento" id="documento" class="form-control" value="<?= $c['documento'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label id="label_nome">Nome Completo *</label>
                        <input type="text" name="nome_razao" id="nome_razao" class="form-control" value="<?= $c['nome_razao'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?= $c['email'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" value="<?= $c['telefone'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Celular/WhatsApp</label>
                        <input type="text" name="celular" id="celular" class="form-control" value="<?= $c['celular'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="bg-light p-3 rounded mb-4 border">
                <h6 class="text-primary font-weight-bold"><i class="fas fa-map-marker-alt"></i> Localização</h6>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" name="cep" id="cep" class="form-control" value="<?= $c['cep'] ?? '' ?>" onblur="buscaCep(this.value)">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $c['logradouro'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Nº</label>
                            <input type="text" name="numero" class="form-control" value="<?= $c['numero'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?= $c['complemento'] ?? '' ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Bairro</label>
                            <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $c['bairro'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $c['cidade'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>UF</label>
                            <input type="text" name="estado" id="uf" class="form-control" value="<?= $c['estado'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </div>

            <h6 class="text-danger font-weight-bold"><i class="fas fa-chart-line"></i> Análise de Crédito e Risco</h6>
            <div class="row border p-3 rounded">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>🔎 Histórico de Pagamentos</label>
                        <select name="score_historico" class="form-control select-score">
                            <option value="40" <?= (isset($c) && $c['score_historico'] == 40) ? 'selected' : '' ?>>Nunca atrasou (40 pts)</option>
                            <option value="30" <?= (isset($c) && $c['score_historico'] == 30) ? 'selected' : '' ?>>Atrasos ocasionais até 5 dias (30 pts)</option>
                            <option value="15" <?= (isset($c) && $c['score_historico'] == 15) ? 'selected' : '' ?>>Atrasos frequentes (15 pts)</option>
                            <option value="0"  <?= (isset($c) && $c['score_historico'] == 0) ? 'selected' : '' ?>>Inadimplente (0 pts)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>💰 Perfil Financeiro</label>
                        <select name="score_perfil" class="form-control select-score">
                            <option value="20" <?= (isset($c) && $c['score_perfil'] == 20) ? 'selected' : '' ?>>Paga sempre à vista (20 pts)</option>
                            <option value="15" <?= (isset($c) && $c['score_perfil'] == 15) ? 'selected' : '' ?>>Parcelamento curto e cumpre (15 pts)</option>
                            <option value="10" <?= (isset($c) && $c['score_perfil'] == 10) ? 'selected' : '' ?>>Usa parcelamento longo (10 pts)</option>
                            <option value="5"  <?= (isset($c) && $c['score_perfil'] == 5) ? 'selected' : '' ?>>Sempre pede prazo informal (5 pts)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>🔁 Frequência e Relacionamento</label>
                        <select name="score_relacionamento" class="form-control select-score">
                            <option value="20" <?= (isset($c) && $c['score_relacionamento'] == 20) ? 'selected' : '' ?>>Recorrente há mais de 2 anos (20 pts)</option>
                            <option value="15" <?= (isset($c) && $c['score_relacionamento'] == 15) ? 'selected' : '' ?>>Recorrente menos de 2 anos (15 pts)</option>
                            <option value="5"  <?= (isset($c) && $c['score_relacionamento'] == 5) ? 'selected' : '' ?>>Cliente eventual (5 pts)</option>
                            <option value="0"  <?= (isset($c) && $c['score_relacionamento'] == 0) ? 'selected' : '' ?>>Primeira vez (0 pts)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>📄 Documentação e Garantias</label>
                        <select name="score_documentacao" class="form-control select-score">
                            <option value="20" <?= (isset($c) && $c['score_documentacao'] == 20) ? 'selected' : '' ?>>Cadastro Completo + Contrato (20 pts)</option>
                            <option value="10" <?= (isset($c) && $c['score_documentacao'] == 10) ? 'selected' : '' ?>>Cadastro Simples (10 pts)</option>
                            <option value="0"  <?= (isset($c) && $c['score_documentacao'] == 0) ? 'selected' : '' ?>>Sem cadastro formal (0 pts)</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-2 text-center">
                    <div class="alert alert-secondary" id="display-score">
                        <strong>Score Estimado: <span id="score-total">0</span> pontos</strong> — 
                        Classificação: <span id="classificacao-texto">Calculando...</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label>Observações Financeiras (Comunicação, Veículo de trabalho, etc)</label>
                <textarea name="observacoes_financeiras" class="form-control" rows="3"><?= $c['observacoes_financeiras'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="<?= base_url('clientes') ?>" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5">Salvar Cliente</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Máscaras iniciais
    $('#cep').inputmask("99999-999");
    $('#telefone').inputmask("(99) 9999-9999");
    $('#celular').inputmask("(99) 99999-9999");

    // Alternância CPF/CNPJ
    $('#tipo_pessoa').on('change', function() {
        var valor = $(this).val();
        $('#documento').val(''); 
        if (valor === 'F') {
            $('#label_doc').text('CPF *');
            $('#label_nome').text('Nome Completo *');
            $('#documento').inputmask("999.999.999-99");
        } else {
            $('#label_doc').text('CNPJ *');
            $('#label_nome').text('Razão Social *');
            $('#documento').inputmask("99.999.999/9999-99");
        }
    }).trigger('change');

    // Cálculo dinâmico do Score em tempo real na tela
    $('.select-score').on('change', function() {
        var total = 0;
        $('.select-score').each(function() {
            total += parseInt($(this).val());
        });
        
        $('#score-total').text(total);
        var classe = "";
        var cor = "";

        if (total >= 80) { classe = "🟢 OURO (Baixo Risco)"; cor = "#d4edda"; }
        else if (total >= 60) { classe = "🟡 PRATA (Médio Risco)"; cor = "#fff3cd"; }
        else { classe = "🔴 BRONZE (Alto Risco)"; cor = "#f8d7da"; }

        $('#classificacao-texto').text(classe);
        $('#display-score').css('background-color', cor);
    }).trigger('change');
});

function buscaCep(valor) {
    var cep = valor.replace(/\D/g, '');
    if (cep.length === 8) {
        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/", function(dados) {
            if (!("erro" in dados)) {
                $("#logradouro").val(dados.logradouro);
                $("#bairro").val(dados.bairro);
                $("#cidade").val(dados.localidade);
                $("#uf").val(dados.uf);
                $("input[name='numero']").focus();
            }
        });
    }
}
</script>
<?= $this->endSection() ?>