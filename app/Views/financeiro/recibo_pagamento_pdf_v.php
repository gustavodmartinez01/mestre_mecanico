<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .titulo { font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .recibo-box { border: 1px solid #ccc; padding: 20px; margin-top: 20px; border-radius: 5px; }
        .valor-extenso { font-style: italic; margin-top: 10px; display: block; }
        .footer { margin-top: 50px; text-align: center; }
        .assinatura { border-top: 1px solid #333; width: 300px; margin: 0 auto; padding-top: 5px; }
        .bg-gray { background-color: #f2f2f2; padding: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%">
            <tr>
                <td width="50%">
                    <span style="font-weight: bold; font-size: 16px;">SUA EMPRESA LTDA</span><br>
                    CNPJ: 00.000.000/0001-00<br>
                    Contato: (54) 0000-0000
                </td>
                <td width="50%" align="right">
                    <span class="titulo">Recibo de Pagamento</span><br>
                    Nº Parcela: <?= $parcela['id'].' de '.$parcela['total_parcelas'] ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="recibo-box">
        <p align="right" class="bg-gray"><strong>VALOR: R$ <?= number_format($parcela['valor_pago'], 2, ',', '.') ?></strong></p>
        
        <p>Recebemos de <strong><?= $parcela['nome_razao'] ?></strong>, inscrito no CPF/CNPJ <strong><?= $parcela['documento'] ?></strong>, 
        a importância de <strong>R$ <?= number_format($parcela['valor_pago'], 2, ',', '.') ?></strong> referente ao pagamento da parcela 
        correspondente ao serviço/venda: <strong><?= $parcela['descricao'] ?></strong>.</p>

        <p>Pagamento realizado em: <strong><?= date('d/m/Y', strtotime($parcela['data_pagamento'])) ?></strong></p>
    </div>

    <div class="footer">
        <p><?= date('d') ?> de <?= strftime('%B', strtotime(date('Y-m-d'))) ?> de <?= date('Y') ?></p>
        <br><br><br>
        <div class="assinatura">Assinatura do Responsável</div>
    </div>

</body>
</html>