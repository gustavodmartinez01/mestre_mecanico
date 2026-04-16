<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-truck-loading mr-2"></i> <?= $titulo ?></h3>
    </div>
    
    <form action="<?= base_url('fornecedores/salvar') ?>" method="post">
        <input type="hidden" name="id" value="<?= $forn['id'] ?? '' ?>">
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de Pessoa</label>
                        <select name="tipo_pessoa" id="tipo_pessoa" class="form-control bg-light">
                            <option value="J" <?= (isset($forn) && $forn['tipo_pessoa'] == 'J') ? 'selected' : '' ?>>Pessoa Jurídica (Empresa)</option>
                            <option value="F" <?= (isset($forn) && $forn['tipo_pessoa'] == 'F') ? 'selected' : '' ?>>Pessoa Física (Autônomo)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label id="label_doc">CNPJ *</label>
                        <input type="text" name="documento" id="documento" class="form-control" value="<?= $forn['documento'] ?? '' ?>" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label id="label_nome">Razão Social *</label>
                        <input type="text" name="nome_razao" id="nome_razao" class="form-control" value="<?= $forn['nome_razao'] ?? '' ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nome Fantasia / Apelido</label>
                        <input type="text" name="nome_fantasia" class="form-control" value="<?= $forn['nome_fantasia'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" class="form-control">
                            <option value="Produtos" <?= (isset($forn) && $forn['categoria'] == 'Produtos') ? 'selected' : '' ?>>Peças/Produtos</option>
                            <option value="Serviços" <?= (isset($forn) && $forn['categoria'] == 'Serviços') ? 'selected' : '' ?>>Serviços Externos</option>
                            <option value="Ambos" <?= (isset($forn) && $forn['categoria'] == 'Ambos') ? 'selected' : '' ?>>Ambos</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Especialidade</label>
                        <input type="text" name="especialidade" class="form-control" placeholder="Ex: Retífica, Injeção..." value="<?= $forn['especialidade'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 border-right">
                    <h5 class="text-muted border-bottom pb-2">Contatos</h5>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?= $forn['email'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Telefone Fixo</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" data-inputmask="'mask': '(99) 9999-9999'" data-mask value="<?= $forn['telefone'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Celular/WhatsApp</label>
                        <input type="text" name="celular" id="celular" class="form-control" data-inputmask="'mask': '(99) 99999-9999'" data-mask value="<?= $forn['celular'] ?? '' ?>">
                    </div>
                </div>

                <div class="col-md-8">
                    <h5 class="text-muted border-bottom pb-2">Localização (CEP com Busca Automática)</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CEP</label>
                                <input type="text" name="cep" id="cep" class="form-control" data-inputmask="'mask': '99999-999'" data-mask value="<?= $forn['cep'] ?? '' ?>" onblur="buscaCep(this.value)">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?= $forn['logradouro'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nº</label>
                                <input type="text" name="numero" class="form-control" value="<?= $forn['numero'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" name="complemento" class="form-control" value="<?= $forn['complemento'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" name="bairro" id="bairro" class="form-control" value="<?= $forn['bairro'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" name="cidade" id="cidade" class="form-control" value="<?= $forn['cidade'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>UF</label>
                                <input type="text" name="estado" id="uf" class="form-control" value="<?= $forn['estado'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <label>Observações sobre este Fornecedor</label>
                <textarea name="observacoes" class="form-control" rows="3"><?= $forn['observacoes'] ?? '' ?></textarea>
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="<?= base_url('fornecedores') ?>" class="btn btn-default">Cancelar</a>
            <button type="submit" class="btn btn-info px-5">Salvar Fornecedor</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Inicializa máscaras do AdminLTE
    $('[data-mask]').inputmask();

    // Lógica de troca de Pessoa Física / Jurídica
    $('#tipo_pessoa').on('change', function() {
        var valor = $(this).val();
        var inputDoc = $('#documento');
        
        // Limpa o valor atual para evitar conflito de máscara
        inputDoc.val(''); 

        if (valor === 'F') {
            $('#label_doc').text('CPF *');
            $('#label_nome').text('Nome Completo *');
            inputDoc.inputmask("999.999.999-99");
        } else {
            $('#label_doc').text('CNPJ *');
            $('#label_nome').text('Razão Social *');
            inputDoc.inputmask("99.999.999/9999-99");
        }
    }).trigger('change'); // Executa ao carregar para casos de edição
});

// Busca CEP idêntica à de funcionários
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