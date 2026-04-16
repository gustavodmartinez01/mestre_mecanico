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
        .titulo-laudo h1 { margin: 0; font-size: 16pt; color: #28a745; } /* Verde para compras */
        .titulo-secao { 
            background-color: #f4f4f4; 
            padding: 5px 10px; 
            font-weight: bold; 
            border-left: 4px solid #28a745; 
            margin: 15px 0 8px 0;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        /* Tabelas */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background-color: #f8f9fa; font-weight: bold; text-align: left; padding: 6px; border: 1px solid #ddd; font-size: 8pt; }
        td { padding: 6px; border: 1px solid #ddd; vertical-align: top; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
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
            <h1>REQUISIÇÃO</h1>
            <strong>NÚMERO #<?= str_pad($requisicao['id'], 5, '0', STR_PAD_LEFT) ?></strong><br>
            <small>Emissão: <?= date('d/m/Y H:i', strtotime($requisicao['created_at'])) ?></small>
        </td>
    </tr>
</table>

<div class="titulo-secao">Dados do Fornecedor e Status</div>
<table>
    <tr>
        <td width="60%"><strong>Fornecedor:</strong> <?= $requisicao['nome_fornecedor'] ?? 'Não informado' ?></td>
        <td width="40%"><strong>Status Atual:</strong> <?= strtoupper($requisicao['status']) ?></td>
    </tr>
    <tr>
        <td><strong>Data de Fechamento:</strong> <?= $requisicao['data_fechamento'] ? date('d/m/Y H:i', strtotime($requisicao['data_fechamento'])) : 'Pendente' ?></td>
        <td><strong>Condição:</strong> À combinar</td>
    </tr>
</table>

<div class="titulo-secao">Itens da Requisição</div>
<table>
    <thead>
        <tr>
            <th width="50%">Descrição do Produto / Serviço</th>
            <th width="10%" class="text-center">Qtd</th>
            <th width="20%" class="text-right">Vl. Unitário</th>
            <th width="20%" class="text-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($itens as $it): ?>
        <tr>
            <td>
                <?= $it['descricao_item'] ?>
                <?php if($it['situacao'] != 'pendente'): ?>
                    <br><small><i>Status: <?= strtoupper($it['situacao']) ?></i></small>
                <?php endif; ?>
            </td>
            <td class="text-center"><?= number_format($it['quantidade'], 2, ',', '.') ?></td>
            <td class="text-right">R$ <?= number_format($it['valor_unitario'], 2, ',', '.') ?></td>
            <td class="text-right font-bold">R$ <?= number_format($it['subtotal'], 2, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-right font-bold" style="background-color: #f8f9fa;">VALOR TOTAL DA REQUISIÇÃO:</td>
            <td class="text-right font-bold" style="background-color: #eee; font-size: 10pt;">
                R$ <?= number_format($requisicao['valor_total'], 2, ',', '.') ?>
            </td>
        </tr>
    </tfoot>
</table>

<div class="titulo-secao">Observações Internas</div>
<table>
    <tr>
        <td style="min-height: 60px;">
            <?= !empty($requisicao['observacoes']) ? $requisicao['observacoes'] : 'Nenhuma observação adicional registrada para esta requisição de compra.' ?>
        </td>
    </tr>
</table>

<table class="assinaturas-table">
    <tr>
        <td>
            <div class="linha-assinatura"></div>
            <strong>Departamento de Compras</strong><br>
            Responsável pela Emissão
        </td>
        <td>
            <div class="linha-assinatura"></div>
            <strong>Diretoria / Aprovação</strong><br>
            Autorização de Pagamento
        </td>
    </tr>
</table>

<div class="footer">
    <?= $empresa['nome_fantasia'] ?> - Documento gerado eletronicamente em <?= date('d/m/Y H:i:s') ?>
</div>

</body>
</html>