<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Cabeçalho igual ao modelo enviado */
        .header-table { width: 100%; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .logo { width: 120px; }
        .info-empresa { text-align: right; font-size: 10px; }
        
        /* Faixa de Título */
        .titulo { text-align: center; background: #eee; padding: 8px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; border: 1px solid #ddd; }
        
        /* Tabela de Dados */
        .tabela-dados { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tabela-dados th { background: #f4f4f4; border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
        .tabela-dados td { border: 1px solid #ddd; padding: 6px; font-size: 10px; }
        
        /* Classes de utilidade */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Cores de Fluxo */
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-primary { color: #007bff; }

        /* Rodapé de Totais (40% de largura à direita) */
        .footer-totais { width: 40%; margin-left: 60%; border-collapse: collapse; }
        .footer-totais td { border: 1px solid #ddd; padding: 6px; }
    </style>
</head>
<body>

    <!-- CABEÇALHO PADRONIZADO -->
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
                    <h2 style="color: #666; margin:0;">BUENO & MARTÍNEZ</h2>
                <?php endif; ?>
            </td>
            <td class="info-empresa">
                <strong><?= $empresa['nome_fantasia'] ?? 'Bueno & Martínez Advogados' ?></strong><br>
                <?= $empresa['logradouro'] ?? 'Rua Principal' ?>, <?= $empresa['numero'] ?? '123' ?> - <?= $empresa['cidade'] ?? 'Vacaria' ?>/<?= $empresa['estado'] ?? 'RS' ?><br>
                Fone: <?= $empresa['telefone'] ?? '(54) 0000-0000' ?> | CNPJ: <?= $empresa['cnpj'] ?? '00.000.000/0000-00' ?>
            </td>
        </tr>
    </table>

    <div class="titulo"><?= $titulo_relatorio ?></div>

    <!-- INFORMAÇÕES DO PERÍODO -->
    <table class="tabela-dados" style="margin-bottom: 10px;">
        <tr>
            <td style="background: #f9f9f9;"><strong>PERÍODO:</strong> <?= date('d/m/Y', strtotime($inicio)) ?> até <?= date('d/m/Y', strtotime($fim)) ?></td>
            <td style="background: #f9f9f9;"><strong>DATA DE EMISSÃO:</strong> <?= date('d/m/Y H:i') ?></td>
        </tr>
    </table>

    <!-- TABELA DE MOVIMENTAÇÕES -->
    <table class="tabela-dados">
        <thead>
            <tr>
                <th width="10%">DATA</th>
                <th width="40%">DESCRIÇÃO / REFERÊNCIA</th>
                <th width="15%" class="text-center">ORIGEM</th>
                <th width="15%" class="text-right">VALOR</th>
                <th width="20%" class="text-right">SALDO ACUMULADO</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $saldo_acumulado = 0; 
            if(!empty($movimentacoes)):
                foreach($movimentacoes as $m): 
                    $v = (float)$m['valor'];
                    if($m['tipo'] == 'entrada') {
                        $saldo_acumulado += $v;
                    } else {
                        $saldo_acumulado -= $v;
                    }
            ?>
            <tr>
                <td class="text-center"><?= date('d/m/Y', strtotime($m['data_movimentacao'])) ?></td>
                <td><?= esc($m['descricao']) ?></td>
                <td class="text-center" style="font-size: 9px; color: #666;">
                    <?= strtoupper(str_replace('_', ' ', $m['origem_tabela'])) ?>
                </td>
                <td class="text-right font-bold <?= $m['tipo'] == 'entrada' ? 'text-success' : 'text-danger' ?>">
                    <?= $m['tipo'] == 'saida' ? '-' : '' ?> R$ <?= number_format($v, 2, ',', '.') ?>
                </td>
                <td class="text-right font-bold <?= $saldo_acumulado >= 0 ? 'text-primary' : 'text-danger' ?>">
                    R$ <?= number_format($saldo_acumulado, 2, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="5" class="text-center py-4">Nenhuma movimentação registrada no período.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- RODAPÉ DE TOTAIS IGUAL AO MODELO -->
    <table class="footer-totais">
        <tr>
            <td style="background: #eee;"><strong>TOTAL ENTRADAS:</strong></td>
            <td class="text-right text-success"><strong>R$ <?= number_format($total_entradas, 2, ',', '.') ?></strong></td>
        </tr>
        <tr>
            <td style="background: #eee;"><strong>TOTAL SAÍDAS:</strong></td>
            <td class="text-right text-danger"><strong>R$ <?= number_format($total_saidas, 2, ',', '.') ?></strong></td>
        </tr>
        <tr>
            <td style="background: #eee;"><strong>SALDO FINAL:</strong></td>
            <td class="text-right font-bold <?= $saldo_periodo >= 0 ? 'text-primary' : 'text-danger' ?>">
                <strong>R$ <?= number_format($saldo_periodo, 2, ',', '.') ?></strong>
            </td>
        </tr>
    </table>

</body>
</html>