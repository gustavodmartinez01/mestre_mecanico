<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-lock mr-2"></i> Alterar Minha Senha</h3>
            </div>
            <form action="<?= base_url('perfil/atualizar-senha') ?>" method="post">
                <div class="card-body">
                    <p class="text-muted">Olá <strong><?= session()->get('nome') ?></strong>, preencha os campos abaixo para atualizar sua segurança.</p>
                    
                    <div class="form-group">
                        <label>Senha Atual</label>
                        <input type="password" name="senha_atual" class="form-control" required placeholder="Digite sua senha atual">
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Nova Senha</label>
                        <input type="password" name="nova_senha" id="nova_senha" class="form-control" required placeholder="Mínimo 6 caracteres">
                    </div>

                    <div class="form-group">
                        <label>Confirmar Nova Senha</label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required placeholder="Repita a nova senha">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Atualizar Senha</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Validação simples de match no front-end
    $('form').on('submit', function(e) {
        if ($('#nova_senha').val() !== $('#confirmar_senha').val()) {
            alert('As novas senhas não coincidem!');
            e.preventDefault();
        }
    });
</script>
<?= $this->endSection() ?>