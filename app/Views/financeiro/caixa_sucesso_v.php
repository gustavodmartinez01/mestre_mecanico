<?= $this->extend('layout/main') ?>

<?= $this->section('conteudo') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-check-circle mr-2"></i> Recebimento Processado com Sucesso!</h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h5>Resumo do Acerto</h5>
                        <p class="mb-0"><strong>ID do Agrupador:</strong> <?= $idAgrupador ?></p>
                        <p><strong>Referente à OS:</strong> #<?= $os_id ?></p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="btn-group-vertical">
                            <a href="<?= base_url('caixa/imprimir/'.$os_id.'/recibo') ?>" target="_blank" class="btn btn-default mb-2">
                                <i class="fas fa-print mr-2"></i> Imprimir Recibo Geral
                            </a>
                            <a href="<?= base_url('caixa/imprimir/'.$idAgrupador.'/promissoria') ?>" target="_blank" class="btn btn-info mb-2">
                                <i class="fas fa-file-contract mr-2"></i> Gerar Notas Promissórias
                            </a>
                            <a href="<?= base_url('os/whatsapp/'.$os_id) ?>" class="btn btn-success">
                                <i class="fab fa-whatsapp mr-2"></i> Enviar Comprovante
                            </a>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-light">
                            <th class="text-center">Parcela</th>
                            <th>Vencimento</th>
                            <th>Forma</th>
                            <th class="text-right">Valor</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalGeral = 0;
                        foreach($parcelas as $p): 
                            $totalGeral += $p['valor_original'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $p['parcela_atual'] ?> / <?= $p['total_parcelas'] ?></td>
                            <td><?= date('d/m/Y', strtotime($p['data_vencimento'])) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $p['forma_pagamento'])) ?></td>
                            <td class="text-right">R$ <?= number_format($p['valor_original'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <span class="badge badge-warning">Pendente</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-right">TOTAL DO ACERTO:</td>
                            <td class="text-right text-primary">R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('os') ?>" class="btn btn-default">Voltar para Ordens de Serviço</a>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary float-right">Ir para Dashboard</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>