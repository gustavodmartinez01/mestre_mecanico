<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .oficina-nome { font-size: 16px; font-weight: bold; color: #007bff; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <table style="border:none;">
            <tr>
                <td style="border:none; width: 60%;">
                    <span class="oficina-nome"><?= $oficina['nome_fantasia'] ?></span><br>
                    <?= $oficina['logradouro'] ?>, <?= $oficina['numero'] ?> - <?= $oficina['cidade'] ?>/<?= $oficina['estado'] ?><br>
                    Fone: <?= $oficina['telefone'] ?>
                </td>
                <td style="border:none; width: 40%; text-align: right;">
                    <h2 style="margin:0; color: #444;">CATÁLOGO DE SERVIÇOS</h2>
                    <span>Emissão: <?= date('d/m/Y H:i') ?></span>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40%;">Descrição do Serviço</th>
                <th class="text-center" style="width: 15%;">Tempo Est.</th>
                <th class="text-right" style="width: 20%;">Preço Venda</th>
                <th style="width: 25%;">Observações</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $model = new \App\Models\ServicoModel();
            foreach($servicos as $s): 
            ?>
            <tr>
                <td class="text-bold"><?= $s['nome'] ?></td>
                <td class="text-center"><?= $model->getTempoFormatado($s) ?></td>
                <td class="text-right text-bold">R$ <?= number_format($s['preco_venda'], 2, ',', '.') ?></td>
                <td style="font-size: 9px; color: #666;"><?= $s['descricao'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        Gerado por Mestre Mecânico Software - A tecnologia que move sua oficina.
    </div>

</body>
</html>