<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label>Data Início</label>
                    <input type="date" id="data_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label>Data Fim</label>
                    <input type="date" id="data_fim" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 text-right">
                    <button onclick="filtrarRelatorio()" class="btn btn-primary">
                        <i class="fas fa-sync mr-1"></i> Atualizar Dados
                    </button>
                    <button onclick="gerarPDF()" class="btn btn-danger">
                        <i class="fas fa-file-pdf mr-1"></i> Imprimir PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h3 class="card-title font-weight-bold">Resumo Visual</h3></div>
                <div class="card-body">
                    <canvas id="graficoFluxo" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div id="resultado_ajax">
        </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let meuGrafico;

    function filtrarRelatorio() {
        const inicio = document.getElementById('data_inicio').value;
        const fim = document.getElementById('data_fim').value;

        // Chamada AJAX para buscar os dados
        $.ajax({
            url: '<?= base_url("relatorios/fluxo-caixa-dados") ?>',
            type: 'GET',
            data: { inicio, fim },
            dataType: 'json',
            success: function(res) {
                // 1. Atualiza a Tabela
                $('#resultado_ajax').html(res.html);

                // 2. Atualiza o Gráfico
              //  renderizarGrafico(res.totais);
              renderizarGrafico(res.grafico);
            },
            error: function() {
                alert('Erro ao carregar dados.');
            }
        });
    }

   function renderizarGrafico(dadosGrafico) {
    const ctx = document.getElementById('graficoFluxo').getContext('2d');
    
    if (meuGrafico) meuGrafico.destroy();

    meuGrafico = new Chart(ctx, {
        type: 'line', // Mudamos para linha
        data: {
            labels: dadosGrafico.labels,
            datasets: [
                {
                    label: 'Entradas (R$)',
                    data: dadosGrafico.entradas,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3 // Deixa a linha suave (curvada)
                },
                {
                    label: 'Saídas (R$)',
                    data: dadosGrafico.saidas,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { callback: value => 'R$ ' + value.toLocaleString('pt-BR') }
                }
            }
        }
    });
}

    function gerarPDF() {
        const inicio = document.getElementById('data_inicio').value;
        const fim = document.getElementById('data_fim').value;
        window.open(`<?= base_url('relatorios/pdf') ?>?tipo=fluxo&inicio=${inicio}&fim=${fim}`, '_blank');
    }

    // Carrega ao iniciar
    $(document).ready(function() {
        filtrarRelatorio();
    });
</script>
<?= $this->endSection() ?>