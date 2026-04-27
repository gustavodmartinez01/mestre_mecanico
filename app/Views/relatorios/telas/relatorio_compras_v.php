<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <h4 class="font-weight-bold text-dark mb-3"><i class="fas fa-shopping-cart mr-2 text-success"></i> Suprimentos e Compras</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">INÍCIO</label>
                    <input type="date" id="data_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">FIM</label>
                    <input type="date" id="data_fim" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 text-right">
                    <button onclick="filtrarCompras()" class="btn btn-success shadow-sm"><i class="fas fa-sync-alt mr-1"></i> Filtrar</button>
                    <button onclick="window.print()" class="btn btn-secondary shadow-sm"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h6 class="font-weight-bold mb-0">Gastos por Categoria de Produto</h6></div>
                <div class="card-body">
                    <canvas id="graficoCategorias" style="height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div id="resultado_ajax_compras">
        </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartCompras;

    function filtrarCompras() {
        const inicio = document.getElementById('data_inicio').value;
        const fim = document.getElementById('data_fim').value;

        $('#resultado_ajax_compras').html('<div class="text-center py-5"><div class="spinner-border text-success"></div></div>');

        $.ajax({
            url: '<?= base_url("relatorios/compras-dados") ?>',
            type: 'GET',
            data: { inicio, fim },
            dataType: 'json',
            success: function(res) {
                $('#resultado_ajax_compras').html(res.html);
                renderizarGrafico(res.grafico);
            }
        });
    }

    function renderizarGrafico(dados) {
        const ctx = document.getElementById('graficoCategorias').getContext('2d');
        if (chartCompras) chartCompras.destroy();

        chartCompras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dados.labels,
                datasets: [{
                    label: 'Total Gasto (R$)',
                    data: dados.valores,
                    backgroundColor: '#28a745'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    $(document).ready(function() { filtrarCompras(); });
</script>
<?= $this->endSection() ?>