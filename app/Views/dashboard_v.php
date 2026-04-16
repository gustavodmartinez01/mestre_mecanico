<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bem-vindo ao Sistema</h3>
            </div>
            <div class="card-body">
                Olá, <b><?= session()->get('nome') ?></b>! Você está logado no painel da <b><?= session()->get('empresa_nome') ?></b>.
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>