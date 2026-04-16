<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header-table { width: 100%; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 120px; }
        .info-empresa { text-align: right; }
        .titulo { text-align: center; background: #eee; padding: 5px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .tabela-dados { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabela-dados th { background: #f4f4f4; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }
        .tabela-dados td { border: 1px solid #ddd; padding: 8px; font-size: 11px; }
        .text-right { text-align: right; }
        .badge { padding: 3px 6px; border-radius: 3px; font-size: 9px; color: #fff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
        .footer-totais { width: 40%; margin-left: 60%; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="30%">
                <?php 
    $caminhoLogo = FCPATH . 'uploads/empresa/' . $empresa['logo']; 
    $caminhoLogoPadrao = FCPATH . 'uploads/empresa/mestre-mecanico.png';
    
    if(file_exists($caminhoLogo) && !empty($empresa['logo'])): 
?>
        <img src="<?= $caminhoLogo ?>" class="logo">
    <?php elseif(file_exists($caminhoLogoPadrao)): ?>
        <img src="<?= $caminhoLogoPadrao ?>" class="logo">
    <?php else: ?>
        <h2 style="color: #666;">MESTRE MECÂNICO</h2>
    <?php endif; ?>
            </td>
            <td class="info-empresa">
                <strong><?= $empresa['nome_fantasia'] ?></strong><br>
                <?= $empresa['logradouro'] ?>, <?= $empresa['numero'] ?> - <?= $empresa['cidade'] ?>/<?= $empresa['estado'] ?><br>
                Fone: <?= $empresa['telefone'] ?> | CNPJ: <?= $empresa['cnpj'] ?>
            </td>
        </tr>
    </table>

    <div class="titulo">Extrato Financeiro de Débitos</div>

    <table class="tabela-dados" style="margin-bottom: 10px;">
        <tr>
            <td style="background: #f9f9f9;"><strong>CLIENTE:</strong> <?= $cliente['nome_razao'] ?></td>
            <td style="background: #f9f9f9;"><strong>CPF/CNPJ:</strong> <?= $cliente['documento'] ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>DESCRIÇÃO DO GRUPO:</strong> <?= $parcelas[0]['descricao'] ?></td>
        </tr>
    </table>

    <table class="tabela-dados">
        <thead>
            <tr>
                <th>PARCELA</th>
                <th>VENCIMENTO</th>
                <th>VALOR ORIGINAL</th>
                <th>JUROS/MULTA</th>
                <th>PAGAMENTO</th>
                <th>VALOR PAGO</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalOriginal = 0;
            $totalPago = 0;
            foreach ($parcelas as $p): 
                $totalOriginal += $p['valor_original'];
                $totalPago += $p['valor_pago'];
                $acrescimos = $p['juros_mora'] + $p['multa_mora'];
            ?>
            <tr>
                <td align="center"><?= $p['parcela_atual'] ?>/<?= $p['total_parcelas'] ?></td>
                <td><?= date('d/m/Y', strtotime($p['data_vencimento'])) ?></td>
                <td class="text-right">R$ <?= number_format($p['valor_original'], 2, ',', '.') ?></td>
                <td class="text-right">R$ <?= number_format($acrescimos, 2, ',', '.') ?></td>
                <td><?= $p['data_pagamento'] ? date('d/m/Y', strtotime($p['data_pagamento'])) : '-' ?></td>
                <td class="text-right">R$ <?= number_format($p['valor_pago'], 2, ',', '.') ?></td>
                <td align="center">
                    <?php if($p['status'] == 'paga'): ?>
                        <span class="badge bg-success">PAGA</span>
                    <?php elseif($p['status'] == 'cancelada'): ?>
                        <span class="badge bg-danger">CANC.</span>
                    <?php else: ?>
                        <span class="badge bg-warning">PENDENTE</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="tabela-dados footer-totais">
        <tr>
            <td style="background: #eee;"><strong>TOTAL ORIGINAL:</strong></td>
            <td class="text-right"><strong>R$ <?= number_format($totalOriginal, 2, ',', '.') ?></strong></td>
        </tr>
        <tr>
            <td style="background: #eee;"><strong>TOTAL RECEBIDO:</strong></td>
            <td class="text-right" style="color: green;"><strong>R$ <?= number_format($totalPago, 2, ',', '.') ?></strong></td>
        </tr>
        <tr>
            <td style="background: #eee;"><strong>SALDO RESTANTE:</strong></td>
            <td class="text-right" style="color: red;"><strong>R$ <?= number_format($totalOriginal - $totalPago, 2, ',', '.') ?></strong></td>
        </tr>
    </table>

</body>
</html>