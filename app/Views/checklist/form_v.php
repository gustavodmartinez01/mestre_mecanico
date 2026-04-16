<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<form action="<?= base_url('checklists/salvar') ?>" method="post">
    <input type="hidden" name="id" value="<?= $c['id'] ?? '' ?>">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Configuração do Modelo</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label>Nome do Checklist (Ex: Revisão de Freios)</label>
                <input type="text" name="nome" class="form-control" value="<?= $c['nome'] ?? '' ?>" required>
            </div>
            
            <hr>
            <h5>Itens de Verificação</h5>
            <table class="table table-sm" id="tabela-itens">
                <thead>
                    <tr>
                        <th>Descrição do Item</th>
                        <th class="text-center" style="width: 100px;">Obrigatório?</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($itens)): foreach($itens as $idx => $it): ?>
                        <tr>
                            <td><input type="text" name="itens[]" class="form-control" value="<?= $it['descricao'] ?>"></td>
                            <td class="text-center"><input type="checkbox" name="obrigatorio[<?= $idx ?>]" <?= $it['obrigatorio'] ? 'checked' : '' ?>></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td><input type="text" name="itens[]" class="form-control" placeholder="Ex: Verificar nível do óleo"></td>
                            <td class="text-center"><input type="checkbox" name="obrigatorio[0]"></td>
                            <td></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="button" class="btn btn-info btn-sm" id="add-item"><i class="fas fa-plus"></i> Adicionar Linha</button>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Salvar Modelo</button>
            <a href="<?= base_url('checklists') ?>" class="btn btn-default">Voltar</a>
        </div>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let rowIdx = <?= isset($itens) ? count($itens) : 1 ?>;
        
        $('#add-item').click(function() {
            let newRow = `<tr>
                <td><input type="text" name="itens[]" class="form-control"></td>
                <td class="text-center"><input type="checkbox" name="obrigatorio[${rowIdx}]"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
            </tr>`;
            $('#tabela-itens tbody').append(newRow);
            rowIdx++;
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
<?= $this->endSection() ?>