<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3><?= $total_os_hoje ?></h3>
                <p>Novas OS Hoje</p>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
            <a href="<?= base_url('os') ?>" class="small-box-footer">Ver todas <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-outline card-primary shadow">
            <div class="card-header">
                <h3 class="card-title text-bold"><i class="fas fa-user-clock mr-2"></i> Carga de Trabalho atual</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Funcionário / Mecânico</th>
                            <th class="text-center">OS em Aberto</th>
                            <th style="width: 40px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($resumo_equipe)): ?>
                            <?php foreach($resumo_equipe as $item): ?>
                            <tr>
                                <td><?= $item['funcionario'] ?></td>
                                <td class="text-center"><span class="badge badge-primary px-3" style="font-size: 1rem;"><?= $item['total'] ?></span></td>
                                <td>
                                    <?php if($item['total'] > 5): ?>
                                        <i class="fas fa-exclamation-triangle text-warning" title="Carga alta"></i>
                                    <?php else: ?>
                                        <i class="fas fa-check-circle text-success" title="Disponível"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">Nenhum mecânico com OS ativa no momento.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-box mb-3 bg-light border">
            <span class="info-box-icon"><i class="fas fa-info-circle text-info"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dica de Gestão</span>
                <span class="info-box-number" style="font-weight: normal;">
                    Mecânicos com mais de 5 ordens simultâneas podem ter queda de produtividade. 
                    Tente balancear as tarefas entre a equipe.
                </span>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>