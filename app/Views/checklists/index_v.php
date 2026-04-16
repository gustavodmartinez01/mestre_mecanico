<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Itens de Inspeção Padrão</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" onclick="novoItem()">
                        <i class="fas fa-plus"></i> Novo Item
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                
                    <table id="tabelaChecklists" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Ordem</th>
                            <th>Categoria</th>
                            <th>Descrição do Item</th>
                            <th class="text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($checklists as $item): ?>
                        <tr>
                            <td width="80"><?= $item['ordem_exibicao'] ?></td>
                            <td><span class="badge badge-info"><?= $item['categoria'] ?></span></td>
                            <td><?= $item['descricao'] ?></td>
                            <td class="text-right">
                                <button class="btn btn-warning btn-xs" onclick="editarItem(<?= htmlspecialchars(json_encode($item)) ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= base_url('checklists/excluir/'.$item['id']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Excluir este item padrão?')">
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

<div class="modal fade" id="modalChecklist" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= base_url('checklists/salvar') ?>" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModal">Novo Item de Checklist</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="item_id">
                    
                    <div class="form-group">
                        <label>Categoria</label>
                        <select name="categoria" id="categoria" class="form-control" required>
                            <option value="EXTERIOR">EXTERIOR (Lataria, Vidros, Pneus)</option>
                            <option value="INTERIOR">INTERIOR (Painel, Estofado, Tapetes)</option>
                            <option value="MECÂNICA">MECÂNICA (Níveis, Ruídos, Luzes)</option>
                            <option value="OUTROS">OUTROS (Documentação, Pertences)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Descrição do Item</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" placeholder="Ex: Riscos no para-choque dianteiro" required>
                    </div>

                    <div class="form-group">
                        <label>Ordem de Exibição</label>
                        <input type="number" name="ordem_exibicao" id="ordem_exibicao" class="form-control" value="0">
                        <small class="text-muted">Números menores aparecem primeiro.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function novoItem() {
    $('#item_id').val('');
    $('#descricao').val('');
    $('#tituloModal').text('Novo Item de Checklist');
    $('#modalChecklist').modal('show');
}

function editarItem(dados) {
    $('#item_id').val(dados.id);
    $('#categoria').val(dados.categoria);
    $('#descricao').val(dados.descricao);
    $('#ordem_exibicao').val(dados.ordem_exibicao);
    $('#tituloModal').text('Editar Item');
    $('#modalChecklist').modal('show');
}
</script>
<script>
    $(document).ready(function() {
        // Inicialização do DataTables com tradução e ordenação padrão
        $('#tabelaChecklists').DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "order": [[1, "asc"], [0, "asc"]], // 1º Ordena por Categoria, 2º pela Ordem de Exibição
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"
            },
            "columnDefs": [
                { "orderable": true, "targets": 3 } // Desabilita ordenação na coluna de Ações
            ]
        });
    });
</script>
<?= $this->endSection() ?>