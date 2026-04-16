<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #444; color: white; padding: 8px; text-align: left; }
        td { border-bottom: 1px solid #ddd; padding: 8px; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .status { font-weight: bold; text-transform: uppercase; font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="border:none;">
            <tr>
                <td style="border:none; width: 70%;">
                    <strong style="font-size: 14px;"><?= $oficina['nome_fantasia'] ?></strong><br>
                    Relatório Geral de Ordens de Serviço
                </td>
                <td style="border:none; width: 30%; text-align: right;">
                    Emissão: <?= date('d/m/Y H:i') ?>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nº OS</th>
                <th>Cliente</th>
                <th>Veículo (Placa)</th>
                <th class="text-center">Abertura</th>
                <th class="text-center">Status</th>
                <th class="text-right">Total (R$)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalGeral = 0;
            foreach($ordens as $o): 
                $totalGeral += $o['valor_total'];
            ?>
            <tr>
                <td><strong><?= $o['numero_os'] ?></strong></td>
                <td><?= $o['cliente_nome'] ?></td>
                <td><?= $o['modelo'] ?> (<?= $o['placa'] ?>)</td>
                <td class="text-center"><?= date('d/m/Y', strtotime($o['data_abertura'])) ?></td>
                <td class="text-center"><span class="status"><?= $o['status'] ?></span></td>
                <td class="text-right">R$ <?= number_format($o['valor_total'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="5" class="text-right"><strong>SOMA TOTAL:</strong></td>
                <td class="text-right"><strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>