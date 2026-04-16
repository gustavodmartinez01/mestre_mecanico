<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #333; }
        .header { border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 15px; }
        .oficina-nome { font-size: 14px; font-weight: bold; color: #28a745; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 6px; text-align: left; }
        td { border: 1px solid #ddd; padding: 5px; vertical-align: middle; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .baixo-estoque { color: #dc3545; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7px; color: #999; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <table style="border:none;">
            <tr>
                <td style="border:none; width: 60%;">
                    <span class="oficina-nome"><?= $oficina['nome_fantasia'] ?></span><br>
                    CNPJ: <?= $oficina['cnpj'] ?> | Fone: <?= $oficina['telefone'] ?>
                </td>
                <td style="border:none; width: 40%; text-align: right;">
                    <h2 style="margin:0; color: #444;">INVENTÁRIO DE ESTOQUE</h2>
                    <span>Gerado em: <?= date('d/m/Y H:i') ?></span>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35%;">Descrição da Peça / Produto</th>
                <th style="width: 15%;">Marca</th>
                <th class="text-center" style="width: 10%;">Mín.</th>
                <th class="text-center" style="width: 10%;">Atual</th>
                <th class="text-right" style="width: 15%;">Preço Venda</th>
                <th class="text-right" style="width: 15%;">Subtotal (Estoque)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalGeral = 0;
            foreach($produtos as $p): 
                $subtotal = $p['estoque_atual'] * $p['preco_venda'];
                $totalGeral += $subtotal;
                $alerta = ($p['estoque_atual'] <= $p['estoque_minimo']) ? 'baixo-estoque' : '';
            ?>
            <tr>
                <td><?= $p['nome'] ?><br><small><?= $p['codigo_barras'] ?></small></td>
                <td><?= $p['marca'] ?></td>
                <td class="text-center"><?= $p['estoque_minimo'] ?></td>
                <td class="text-center <?= $alerta ?>"><?= $p['estoque_atual'] ?> <?= $p['unidade_medida'] ?></td>
                <td class="text-right">R$ <?= number_format($p['preco_venda'], 2, ',', '.') ?></td>
                <td class="text-right text-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #eee;">
                <td colspan="5" class="text-right text-bold">VALOR TOTAL DO ESTOQUE (PREÇO DE VENDA):</td>
                <td class="text-right text-bold" style="font-size: 11px; color: #28a745;">
                    R$ <?= number_format($totalGeral, 2, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Mestre Mecânico Software - Controle Total de Inventário
    </div>

</body>
</html>