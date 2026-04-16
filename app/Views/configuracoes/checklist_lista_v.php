<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Modelos de Checklist</h3>
                <div class="card-tools">
                    <a href="<?= base_url('checklist/novo') ?>" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus"></i> Novo Modelo
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nome do Modelo</th>
                            <th>Descrição</th>
                            <th class="text-center">Itens</th>
                            <th class="text-center">Status</th>
                            <th style="width: 150px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($modelos)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Nenhum modelo cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach($modelos as $m): ?>
                        <tr>
                            <td><?= $m['id'] ?></td>
                            <td><strong><?= $m['nome'] ?></strong></td>
                            <td><?= $m['descricao'] ?></td>
                            <td class="text-center">
                                <span class="badge badge-info">
                                    Checklist Ativo
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-<?= $m['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $m['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <a href="<?= base_url('checklist/editar/'.$m['id']) ?>" class="btn btn-default btn-sm" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('checklist/excluir/'.$m['id']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   title="Excluir"
                                   onclick="return confirm('Tem certeza? Isso não afetará os checklists já aplicados em OSs antigas.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>