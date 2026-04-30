<?= $this->extend('common/layout_v') ?>

<?= $this->section('conteudo') ?>
<div class="container-fluid">
    <h4 class="font-weight-bold text-dark mb-4"><i class="fas fa-exchange-alt mr-2 text-primary"></i> Fluxo de Caixa</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold">DE</label>
                    <input type="date" id="filtro_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold">ATÉ</label>
                    <input type="date" id="filtro_fim" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-3">
                    <button onclick="carregarFluxo()" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Filtrar Movimentações</button>
                </div>
            </div>
        </div>
    </div>

    <div id="div_resultado_fluxo">
        </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function carregarFluxo() {
        const inicio = $('#filtro_inicio').val();
        const fim = $('#filtro_fim').val();
        
        $('#div_resultado_fluxo').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');

        $.ajax({
            url: '<?= base_url("fluxo-caixa/fluxo-caixa-dados") ?>',
            type: 'GET',
            data: { inicio, fim },
            dataType: 'json',
            success: function(res) {
                $('#div_resultado_fluxo').html(res.html);
            }
        });
    }

    $(document).ready(function() { carregarFluxo(); });
</script>
<?= $this->endSection() ?>