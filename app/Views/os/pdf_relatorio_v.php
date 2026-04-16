<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #333; line-height: 1.4; }
        
        /* Cabeçalho da Empresa */
        .header-table { width: 100%; border-bottom: 2px solid #333; margin-bottom: 20px; padding-bottom: 10px; }
        .header-table td { border: none; vertical-align: middle; }
        .logo { width: 90px; }
        .empresa-dados { font-size: 8pt; color: #555; }
        .empresa-nome { font-size: 12pt; font-weight: bold; color: #000; text-transform: uppercase; }
        
        /* Títulos e Seções */
        .titulo-laudo { text-align: right; }
        .titulo-laudo h1 { margin: 0; font-size: 16pt; color: #007bff; }
        .titulo-secao { 
            background-color: #f4f4f4; 
            padding: 5px 10px; 
            font-weight: bold; 
            border-left: 4px solid #007bff; 
            margin: 15px 0 8px 0;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        /* Tabelas */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background-color: #f8f9fa; font-weight: bold; text-align: left; padding: 6px; border: 1px solid #ddd; font-size: 8pt; }
        td { padding: 6px; border: 1px solid #ddd; vertical-align: top; }

        .status-ok { color: #28a745; font-weight: bold; }
        .status-erro { color: #dc3545; font-weight: bold; }
        
        /* Grid de Fotos */
        .foto-container { margin-top: 10px; width: 100%; }
        .foto-box { float: left; width: 30%; margin: 1%; border: 1px solid #eee; padding: 4px; background: #fff; text-align: center; }
        .foto-img { width: 100%; height: 130px; object-fit: cover; }
        .foto-legenda { font-size: 7pt; color: #777; margin-top: 3px; display: block; height: 25px; overflow: hidden; }
        
        /* Rodapé e Assinaturas */
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7pt; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
        .assinaturas-table { width: 100%; margin-top: 40px; border: none; }
        .assinaturas-table td { border: none; text-align: center; width: 50%; padding-top: 40px; }
        .linha-assinatura { border-top: 1px solid #333; width: 80%; margin: 0 auto; }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td width="15%">
            <?php if(!empty($empresa['logo'])): ?>
                <img src="<?= FCPATH . $empresa['logo'] ?>" class="logo">
            <?php endif; ?>
        </td>
        <td width="55%">
            <span class="empresa-nome"><?= $empresa['nome_fantasia'] ?? $empresa['razao_social'] ?></span><br>
            <div class="empresa-dados">
                <?= $empresa['logradouro'] ?>, <?= $empresa['numero'] ?> - <?= $empresa['bairro'] ?><br>
                <?= $empresa['cidade'] ?>/<?= $empresa['estado'] ?> - CEP: <?= $empresa['cep'] ?><br>
                Fone: <?= $empresa['telefone'] ?> | CNPJ: <?= $empresa['cnpj'] ?>
            </div>
        </td>
        <td width="30%" class="titulo-laudo">
            <h1>LAUDO TÉCNICO</h1>
            <strong>ORDEM DE SERVIÇO #<?= $os['id'] ?></strong><br>
            <small>Emissão: <?= date('d/m/Y H:i') ?></small>
        </td>
    </tr>
</table>

<div class="titulo-secao">Identificação do Cliente e Veículo</div>
<table>
    <tr>
        <td width="60%"><strong>Cliente:</strong> <?= $os['cliente_nome'] ?? 'Não informado' ?></td>
        <td width="40%"><strong>Placa:</strong> <?= $os['veiculo_placa'] ?? '-' ?></td>
    </tr>
    <tr>
        <td><strong>Modelo:</strong> <?= $os['veiculo_modelo'] ?? 'N/A' ?></td>
        <td><strong>Técnico Responsável:</strong> <?= $os['tecnico_nome'] ?? 'Não atribuído' ?></td>
    </tr>
</table>

<div class="titulo-secao">1. Inspeção de Entrada (Check-in)</div>
<table>
    <thead>
        <tr>
            <th width="35%">Item Inspecionado</th>
            <th width="15%" style="text-align: center;">Condição</th>
            <th width="50%">Observações / Medições Técnicas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($checklists as $ck): if($ck['tipo'] == 'entrada'): ?>
        <tr>
            <td><?= $ck['descricao'] ?></td>
            <td style="text-align: center;">
                <span class="<?= $ck['status'] == 'ok' ? 'status-ok' : 'status-erro' ?>">
                    <?= $ck['status'] == 'ok' ? 'POSITIVO' : 'NEGATIVO' ?>
                </span>
            </td>
            <td><?= $ck['observacao'] ?: '-' ?></td>
        </tr>
        <?php endif; endforeach; ?>
    </tbody>
</table>

<div class="titulo-secao">2. Conferência de Qualidade (Check-out)</div>
<table>
    <thead>
        <tr>
            <th width="35%">Verificação Final</th>
            <th width="15%" style="text-align: center;">Resultado</th>
            <th width="50%">Observações do Técnico</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($checklists as $ck): if($ck['tipo'] == 'servico'): ?>
        <tr>
            <td><?= $ck['descricao'] ?></td>
            <td style="text-align: center;">
                <span class="<?= $ck['status'] == 'ok' ? 'status-ok' : 'status-erro' ?>">
                    <?= $ck['status'] == 'ok' ? 'APROVADO' : 'REPROVADO' ?>
                </span>
            </td>
            <td><?= $ck['observacao'] ?: '-' ?></td>
        </tr>
        <?php endif; endforeach; ?>
    </tbody>
</table>

<?php if(!empty($fotos)): ?>
    <div style="page-break-before: always;"></div>
    <div class="titulo-secao">3. Evidências Fotográficas</div>
    <div class="foto-container">
        <?php foreach($fotos as $f): ?>
            <div class="foto-box">
                <img src="<?= FCPATH . $f['caminho_arquivo'] ?>" class="foto-img">
                <span class="foto-legenda">
                    <strong><?= strtoupper($f['tipo']) ?>:</strong> <?= $f['descricao'] ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <div style="clear: both;"></div>
<?php endif; ?>

<table class="assinaturas-table">
    <tr>
        <td>
            <div class="linha-assinatura"></div>
            <strong><?= $os['tecnico_nome'] ?? 'Responsável Técnico' ?></strong><br>
            Oficina Mecânica
        </td>
        <td>
            <div class="linha-assinatura"></div>
            <strong>Assinatura do Cliente</strong><br>
            Concordo com os termos do laudo
        </td>
    </tr>
</table>

<div class="footer">
    <?= $empresa['nome_fantasia'] ?> - Gerado via Sistema de Gestão em <?= date('d/m/Y H:i:s') ?>
</div>

</body>
</html>