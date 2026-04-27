<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h4 class="font-weight-bold text-dark">
                <i class="fas fa-chart-line mr-2 text-primary"></i> 
                Balanço e Saúde Financeira
            </h4>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">PERÍODO INICIAL</label>
                    <input type="date" id="data_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">PERÍODO FINAL</label>
                    <input type="date" id="data_fim" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 text-right">
                    <button onclick="gerarBalanco()" class="btn btn-primary shadow-sm px-4">
                        <i class="fas fa-sync-alt mr-1"></i> Calcular Balanço
                    </button>
                    <button onclick="window.print()" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="font-weight-bold m-0">Evolução do Fluxo de Caixa vs. Resultado Diário</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="graficoBalanco"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="resultado_ajax_balanco">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Aguardando processamento de dados...</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chartBalanco;

    /**
     * Busca os dados processados no Controller
     */
    function gerarBalanco() {
        const inicio = document.getElementById('data_inicio').value;
        const fim = document.getElementById('data_fim').value;

        $('#resultado_ajax_balanco').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-2 text-muted">Cruzando dados de OS e Compras...</p></div>');

        $.ajax({
            url: '<?= base_url("relatorios/balanco-dados") ?>',
            type: 'GET',
            data: { inicio, fim },
            dataType: 'json',
            success: function(res) {
                // Injeta os widgets de Lucro/Prejuízo e Margem
                $('#resultado_ajax_balanco').html(res.html);
                
                // Renderiza o gráfico com a linha de saúde financeira
                renderizarGraficoBalanco(res.grafico);
            },
            error: function() {
                $('#resultado_ajax_balanco').html('<div class="alert alert-danger">Erro ao processar balanço. Verifique as datas.</div>');
            }
        });
    }

    /**
     * Gráfico Misto: Barras (Resultado do dia) + Linha (Acumulado)
     */
    function renderizarGraficoBalanco(dados) {
        const ctx = document.getElementById('graficoBalanco').getContext('2d');
        
        if (chartBalanco) chartBalanco.destroy();

        chartBalanco = new Chart(ctx, {
            data: {
                labels: dados.labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Saldo Acumulado (Saúde do Caixa)',
                        data: dados.caixa,
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3
                    },
                    {
                        type: 'bar',
                        label: 'Lucro/Prejuízo do Dia',
                        data: dados.evolucao,
                        backgroundColor: dados.evolucao.map(v => v >= 0 ? '#28a745' : '#dc3545'),
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                }
            }
        });
    }

    // Carregar ao abrir a página
    $(document).ready(function() {
        gerarBalanco();
    });
</script>
<?= $this->endSection() ?>