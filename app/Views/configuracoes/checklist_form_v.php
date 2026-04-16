<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list mr-2"></i>
                    <?= isset($modelo) ? 'Editar Modelo: ' . $modelo['nome'] : 'Novo Modelo de Checklist' ?>
                </h3>
            </div>

            <form action="<?= base_url('configuracoes/checklists/salvar') ?>" method="post">
                <div class="card-body">
                    <input type="hidden" name="id" value="<?= isset($modelo) ? $modelo['id'] : '' ?>">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome do Modelo <span class="text-danger">*</span></label>
                                <input type="text" name="nome" class="form-control" required 
                                       placeholder="Ex: Checklist de Entrada / Revisão 10k" 
                                       value="<?= isset($modelo) ? $modelo['nome'] : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descrição Curta</label>
                                <input type="text" name="descricao" class="form-control" 
                                       placeholder="Onde este checklist será usado?" 
                                       value="<?= isset($modelo) ? $modelo['descricao'] : '' ?>">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <label><i class="fas fa-tasks mr-1"></i> Itens do Checklist</label>
                            <table class="table table-bordered table-striped" id="tabelaItens">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;" class="text-center">#</th>
                                        <th>Descrição do Item (O que deve ser verificado?)</th>
                                        <th style="width: 150px;" class="text-center">Obrigatório?</th>
                                        <th style="width: 80px;" class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($items) && !empty($items)): ?>
                                        <?php foreach ($items as $idx => $item): ?>
                                            <tr>
                                                <td class="text-center pt-3 handle"><i class="fas fa-grip-lines text-muted"></i></td>
                                                <td>
                                                    <input type="text" name="item_descricao[]" class="form-control" 
                                                           value="<?= $item['descricao'] ?>" required>
                                                </td>
                                                <td class="text-center pt-2">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="item_obrigatorio[<?= $idx ?>]" 
                                                               class="custom-control-input" id="check_<?= $idx ?>" 
                                                               <?= $item['obrigatorio'] ? 'checked' : '' ?>>
                                                        <label class="custom-control-label" for="check_<?= $idx ?>"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remover">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td class="text-center pt-3"><i class="fas fa-grip-lines text-muted"></i></td>
                                            <td><input type="text" name="item_descricao[]" class="form-control" placeholder="Ex: Nível do Óleo" required></td>
                                            <td class="text-center pt-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" name="item_obrigatorio[0]" class="custom-control-input" id="check_0">
                                                    <label class="custom-control-label" for="check_0"></label>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remover">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <button type="button" id="btnAdicionarLinha" class="btn btn-info btn-sm shadow-sm">
                                <i class="fas fa-plus mr-1"></i> Adicionar Novo Item
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-right">
                    <a href="<?= base_url('configuracoes/checklists') ?>" class="btn btn-secondary px-4">Cancelar</a>
                    <button type="submit" class="btn btn-success px-5 shadow">
                        <i class="fas fa-save mr-2"></i> Salvar Modelo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    let contador = <?= isset($items) ? count($items) : 1 ?>;

    // ADICIONAR NOVA LINHA
    $('#btnAdicionarLinha').click(function() {
        const novaLinha = `
            <tr>
                <td class="text-center pt-3"><i class="fas fa-grip-lines text-muted"></i></td>
                <td><input type="text" name="item_descricao[]" class="form-control" required></td>
                <td class="text-center pt-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="item_obrigatorio[${contador}]" class="custom-control-input" id="check_${contador}">
                        <label class="custom-control-label" for="check_${contador}"></label>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remover">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tabelaItens tbody').append(novaLinha);
        contador++;
    });

    // REMOVER LINHA (Com delegação para itens dinâmicos)
    $(document).on('click', '.btn-remover', function() {
        if ($('#tabelaItens tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('O checklist deve ter pelo menos um item.');
        }
    });
});
</script>
<?= $this->endSection() ?>