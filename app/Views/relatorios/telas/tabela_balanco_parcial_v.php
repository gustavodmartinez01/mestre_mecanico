<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase small font-weight-bold">Total Recebido (OS)</h6>
                <h3 class="font-weight-bold">R$ <?= number_format($receitas, 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase small font-weight-bold">Total Pago (Compras)</h6>
                <h3 class="font-weight-bold">R$ <?= number_format($despesas, 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card <?= $lucro >= 0 ? 'bg-info' : 'bg-warning' ?> shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase small font-weight-bold text-white">Resultado Líquido</h6>
                <h3 class="font-weight-bold text-white">R$ <?= number_format($lucro, 2, ',', '.') ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="alert <?= $lucro >= 0 ? 'alert-info' : 'alert-danger' ?> border-0 shadow-sm">
    <i class="fas fa-info-circle mr-2"></i>
    Sua margem operacional neste período foi de <strong><?= $receitas > 0 ? number_format(($lucro / $receitas) * 100, 1) : 0 ?>%</strong>.
</div>