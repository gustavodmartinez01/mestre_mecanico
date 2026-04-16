<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Fornecedores Cadastrados</h3>
                <div class="card-tools">
                    <a href="<?= base_url('fornecedores/novo') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Novo Fornecedor
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <table id="tabela-fornecedores" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nome / Razão Social</th>
                            <th>Documento</th>
                            <th>Categoria</th>
                            <th>Especialidade</th>
                            <th>Contato</th>
                            <th>Status</th>
                            <th style="width: 110px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fornecedores as $f): ?>
                        <tr>
                            <td>
                                <strong><?= $f['nome_razao'] ?></strong><br>
                                <small class="text-muted"><?= $f['nome_fantasia'] ?></small>
                            </td>
                            <td><?= $f['documento'] ?></td>
                            <td>
                                <?php 
                                    $cor = 'secondary';
                                    if($f['categoria'] == 'Produtos') $cor = 'info';
                                    if($f['categoria'] == 'Serviços') $cor = 'warning';
                                    if($f['categoria'] == 'Ambos') $cor = 'purple';
                                ?>
                                <span class="badge bg-<?= $cor ?>"><?= $f['categoria'] ?></span>
                            </td>
                            <td><?= $f['especialidade'] ?? '-' ?></td>
                            <td>
                                <i class="fab fa-whatsapp text-success"></i> <?= $f['celular'] ?><br>
                                <small><?= $f['email'] ?></small>
                            </td>
                            <td>
                                <span class="badge badge-<?= $f['ativo'] ? 'success' : 'danger' ?>">
                                    <?= $f['ativo'] ? 'Ativo' : 'Inativo' ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= base_url('fornecedores/pdf/'.$f['id']) ?>" class="btn btn-info btn-sm" title="Imprimir Ficha" target="_blank">
                                         <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="<?= base_url('fornecedores/editar/'.$f['id']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if (in_array(session()->get('nivel_acesso'), ['admin', 'gerente'])): ?>
                                        <a href="<?= base_url('fornecedores/excluir/'.$f['id']) ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Tem certeza que deseja desativar este fornecedor?')" 
                                           title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($fornecedores)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum fornecedor cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        // Se você quiser ativar o DataTables (busca rápida e paginação) 
        // certifique-se de ter o plugin DataTables no seu layout_v.php
        $("#tabela-fornecedores").DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>