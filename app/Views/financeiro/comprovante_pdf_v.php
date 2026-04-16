<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; color: #333; font-size: 12pt; }
        .recibo-container { border: 2px solid #000; padding: 20px; position: relative; }
        .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .titulo { font-size: 22pt; font-weight: bold; text-align: center; margin-bottom: 5px; }
        .numero { position: absolute; top: 20px; right: 20px; font-size: 14pt; color: red; font-weight: bold; }
        
        .oficina-dados { float: left; width: 60%; }
        .valor-caixa { float: right; width: 35%; border: 2px solid #000; text-align: center; padding: 10px; font-size: 18pt; font-weight: bold; background: #eee; }
        .clearfix { clear: both; }

        .corpo { margin-top: 30px; line-height: 1.8; text-align: justify; }
        .detalhes { margin-top: 20px; border-collapse: collapse; width: 100%; }
        .detalhes td { padding: 5px; border-bottom: 1px dotted #ccc; }

        .assinatura { margin-top: 60px; text-align: center; }
        .linha { border-top: 1px solid #000; width: 300px; margin: 0 auto; }
        
        .via { text-align: right; font-size: 8pt; font-style: italic; margin-top: 10px; }
    </style>
</head>
<body>

<div class="recibo-container">
    <div class="numero">Nº <?= str_pad($p['id'], 6, '0', STR_PAD_LEFT) ?></div>
    
    <div class="header">
        <div class="oficina-dados">
            <strong><?= $empresa['nome_fantasia'] ?></strong><br>
            <?= $empresa['logradouro'] ?>, <?= $empresa['numero'] ?> - <?= $empresa['bairro'] ?><br>
            <?= $empresa['cidade'] ?>/<?= $empresa['estado'] ?><br>
            CNPJ: <?= $empresa['cnpj'] ?> | Fone: <?= $empresa['telefone'] ?>
        </div>
        <div class="valor-caixa">
            R$ <?= number_format($p['valor_pago'], 2, ',', '.') ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="titulo">RECIBO</div>

    <div class="corpo">
        Recebemos de <strong><?= $p['nome_razao'] ?></strong>, inscrito no CPF/CNPJ <strong><?= $p['documento'] ?></strong>, 
        a importância de <strong>R$ <?= number_format($p['valor_pago'], 2, ',', '.') ?></strong> 
        (<?= ucfirst(trim(number_format($p['valor_pago'], 2, ',', '.'))) ?> *), 
        referente a: <strong><?= $p['descricao'] ?></strong> 
        <?php if(!empty($p['placa'])): ?>
            do veículo <strong><?= $p['modelo'] ?> (Placa: <?= $p['placa'] ?>)</strong>.
        <?php endif; ?>
    </div>

    <table class="detalhes">
        <tr>
            <td><strong>Forma de Pagamento:</strong> <?= ucfirst($p['forma_pagamento']) ?></td>
            <td><strong>Data:</strong> <?= date('d/m/Y', strtotime($p['data_pagamento'])) ?></td>
        </tr>
        <tr>
            <td><strong>Valor Original:</strong> R$ <?= number_format($p['valor_original'], 2, ',', '.') ?></td>
            <td><strong>Desconto:</strong> R$ <?= number_format($p['desconto'], 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td><strong>Juros/Multa:</strong> R$ <?= number_format($p['juros_mora'], 2, ',', '.') ?></td>
            <td><strong>Status:</strong> Confirmado / Pago</td>
        </tr>
    </table>

    <div class="assinatura">
        <div class="linha"></div>
        <?= $empresa['nome_fantasia'] ?><br>
        <small>Assinatura do Responsável</small>
    </div>

    <div class="via">1ª Via - Cliente</div>
</div>

</body>
</html>