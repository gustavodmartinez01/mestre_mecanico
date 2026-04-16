<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        /* O mPDF funciona melhor com estilos CSS 2.1 e tabelas */
        body { font-family: sans-serif; font-size: 11pt; color: #333; }
        .tabela-cheia { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabela-cheia th, .tabela-cheia td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .bg-cinza { background-color: #f2f2f2; font-weight: bold; }
        .titulo { font-size: 18pt; font-weight: bold; color: #007bff; }
        .subtitulo { font-size: 13pt; font-weight: bold; border-bottom: 2px solid #007bff; margin-bottom: 10px; margin-top: 20px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box { background-color: #eee; padding: 15px; font-size: 14pt; font-weight: bold; text-align: right; border: 1px solid #333; }
        .foto { width: 180px; height: 140px; margin: 5px; border: 1px solid #ddd; object-fit: cover; }
        .footer { font-size: 9pt; text-align: center; margin-top: 30px; color: #777; }
    </style>
</head>
<body>

    <table class="tabela-cheia" style="border: none;">
        <tr>
            <td style="border: none; width: 60%;">
                <span class="titulo"><?= mb_strtoupper($oficina['nome_fantasia']) ?></span><br>
                <?= $oficina['logradouro'] ?>, <?= $oficina['numero'] ?> - <?= $oficina['cidade'] ?>/<?= $oficina['estado'] ?><br>
                CNPJ: <?= $oficina['cnpj'] ?> | Tel: <?= $oficina['telefone'] ?>
            </td>
            <td style="border: none; width: 40%; text-align: right; vertical-align: top;">
                <span style="font-size: 14pt;">ORDEM DE SERVIÇO</span><br>
                <span style="font-size: 20pt; font-weight: bold;">#<?= $os['numero_os'] ?></span><br>
                Data: <?= date('d/m/Y', strtotime($os['data_abertura'])) ?>
            </td>
        </tr>
    </table>

    <table class="tabela-cheia">
        <tr>
            <td colspan="2" class="bg-cinza">DADOS DO CLIENTE</td>
            <td colspan="2" class="bg-cinza">DADOS DO VEÍCULO</td>
        </tr>
        <tr>
            <td width="15%"><strong>Nome:</strong></td>
            <td width="35%"><?= $os['cliente_nome'] ?></td>
            <td width="15%"><strong>Veículo:</strong></td>
            <td width="35%"><?= $os['veiculo_marca'] ?> <?= $os['veiculo_modelo'] ?></td>
        </tr>
        <tr>
            <td><strong>Celular:</strong></td>
            <td><?= $os['cliente_celular'] ?></td>
            <td><strong>Placa / Cor:</strong></td>
            <td><?= $os['veiculo_placa'] ?> / <?= $os['veiculo_cor'] ?></td>
        </tr>
    </table>

    <div class="subtitulo">RELATO DO CLIENTE / SINTOMAS</div>
    <div style="padding: 10px; border: 1px solid #ccc; background: #fafafa; min-height: 50px;">
        <?= nl2br($os['descricao_problema']) ?>
    </div>

    <div class="subtitulo">SERVIÇOS E PEÇAS APLICADAS</div>
    <table class="tabela-cheia">
        <thead>
            <tr class="bg-cinza">
                <th>Descrição</th>
                <th width="10%" class="text-center">Qtd</th>
                <th width="15%" class="text-right">Unitário</th>
                <th width="15%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($itens as $item): ?>
            <tr>
                <td><?= ($item['tipo'] == 'servico' ? '[S] ' : '[P] ') . $item['descricao'] ?></td>
                <td class="text-center"><?= number_format($item['quantidade'], 2, ',', '.') ?></td>
                <td class="text-right">R$ <?= number_format($item['valor_unitario'], 2, ',', '.') ?></td>
                <td class="text-right">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        TOTAL GERAL: R$ <?= number_format($os['valor_total'], 2, ',', '.') ?>
    </div>

    <?php if (!empty($itens_checklist)): ?>
        <div style="page-break-before: always;"></div> <div class="subtitulo">INSPEÇÃO TÉCNICA (CHECKLIST)</div>
        <table class="tabela-cheia">
            <thead>
                <tr class="bg-cinza">
                    <th>Item Verificado</th>
                    <th width="20%" class="text-center">Status</th>
                    <th>Observação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($itens_checklist as $check): ?>
                <tr>
                    <td><?= $check['descricao'] ?></td>
                    <td class="text-center"><strong><?= strtoupper($check['status']) ?></strong></td>
                    <td><?= $check['observacao'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (!empty($fotos)): ?>
        <div class="subtitulo">EVIDÊNCIAS FOTOGRÁFICAS</div>
        <div style="text-align: center;">
            <?php foreach($fotos as $foto): ?>
                <?php 
                    // Tenta usar o caminho físico para o mPDF não travar com URL
                    $caminho_foto = FCPATH . $foto['caminho_arquivo']; 
                    if(file_exists($caminho_foto)):
                ?>
                    <img src="<?= $caminho_foto ?>" class="foto">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <table width="100%" style="margin-top: 50px; border: none;">
        <tr>
            <td width="45%" style="border-top: 1px solid #000; text-align: center;">
                <br>Assinatura da Oficina
            </td>
            <td width="10%" style="border: none;"></td>
            <td width="45%" style="border-top: 1px solid #000; text-align: center;">
                <br>Assinatura do Cliente
            </td>
        </tr>
    </table>

</body>
</html>