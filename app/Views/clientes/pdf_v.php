<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; margin: 0; }
        .topo-tabela { width: 100%; border-bottom: 2px solid #007bff; margin-bottom: 10px; }
        .oficina-nome { font-size: 16px; font-weight: bold; color: #007bff; text-transform: uppercase; }
        .score-box { 
            border: 1px solid #ddd; padding: 5px; text-align: center; background: #f8f9fa;
        }
        .classificacao { font-weight: bold; font-size: 12px; display: block; }
        .ouro { color: #28a745; } .prata { color: #fd7e14; } .bronze { color: #dc3545; }
        
        .secao-titulo { background: #007bff; color: white; padding: 4px 8px; font-weight: bold; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 6px; border: 1px solid #eee; }
        .bg-cinza { background: #fcfcfc; font-weight: bold; width: 100px; }
    </style>
</head>
<body>

    <table class="topo-tabela">
        <tr>
            <td style="border:none; width: 70%;">
                <span class="oficina-nome"><?= $oficina['nome_fantasia'] ?? $oficina['razao_social'] ?></span><br>
                <span>CNPJ: <?= $oficina['cnpj'] ?></span><br>
                <span><?= $oficina['logradouro'] ?>, <?= $oficina['numero'] ?> - <?= $oficina['cidade'] ?>/<?= $oficina['estado'] ?></span><br>
                <strong>Fone: <?= $oficina['telefone'] ?></strong>
            </td>
            <td style="border:none; width: 30%;">
                <div class="score-box">
                    <small>CLASSIFICAÇÃO DE RISCO</small>
                    <span class="classificacao <?= strtolower($c['classificacao']) ?>">
                        CLIENTE <?= strtoupper($c['classificacao']) ?>
                    </span>
                    <strong><?= $c['score_total'] ?> PONTOS</strong>
                </div>
            </td>
        </tr>
    </table>

    <h3 style="text-align: center; margin: 10px 0;">FICHA CADASTRAL DO CLIENTE</h3>

    <table>
        <tr>
            <td class="bg-cinza">Cliente:</td>
            <td colspan="3"><strong><?= $c['nome_razao'] ?></strong></td>
        </tr>
        <tr>
            <td class="bg-cinza">Documento:</td>
            <td><?= $c['documento'] ?></td>
            <td class="bg-cinza">Celular/Whats:</td>
            <td><?= $c['celular'] ?></td>
        </tr>
        <tr>
            <td class="bg-cinza">E-mail:</td>
            <td colspan="3"><?= $c['email'] ?></td>
        </tr>
    </table>

    <div class="secao-titulo">VEÍCULOS VINCULADOS / FROTA</div>
    <table style="margin-top:0;">
        <thead>
            <tr style="background: #f2f2f2;">
                <th style="padding:5px; border:1px solid #eee;">Placa</th>
                <th style="padding:5px; border:1px solid #eee;">Marca/Modelo</th>
                <th style="padding:5px; border:1px solid #eee;">Ano/Cor</th>
                <th style="padding:5px; border:1px solid #eee;">Checklist de Entrada</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($veiculos as $v): ?>
            <tr>
                <td style="font-weight:bold; font-size:11px; text-align:center;"><?= $v['placa'] ?></td>
                <td><?= $v['marca'] ?> <?= $v['modelo'] ?></td>
                <td style="text-align:center;"><?= $v['ano'] ?> / <?= $v['cor'] ?></td>
                <td style="font-size: 9px;">
                    <strong>Lataria:</strong> <?= $v['condicao_lataria'] ?: 'N/A' ?><br>
                    <strong>Pintura:</strong> <?= $v['condicao_pintura'] ?: 'N/A' ?><br>
                    <strong>Obs:</strong> <?= $v['observacoes'] ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if($c['observacoes_financeiras']): ?>
        <div class="secao-titulo">OBSERVAÇÕES FINANCEIRAS</div>
        <div style="padding: 8px; border: 1px solid #eee; font-style: italic;">
            <?= nl2br($c['observacoes_financeiras']) ?>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px; text-align: center; font-size: 8px; color: #777;">
        Documento gerado em <?= date('d/m/Y H:i') ?> | Mestre Mecânico Software
    </div>

</body>
</html>