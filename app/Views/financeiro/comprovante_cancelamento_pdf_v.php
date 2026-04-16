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
    <div class="header">
        <table width="100%">
            <tr>
                <td width="60%">
                    <strong>SUA EMPRESA LTDA</strong><br>
                    Comprovante de Distrato / Cancelamento Financeiro
                </td>
                <td width="40%" align="right">
                    <span class="titulo-cancel">CANCELAMENTO</span><br>
                    Agrupador: #<?= $cliente['id_agrupador'] ?>
                </td>
            </tr>
        </table>
    </div>

    <p>Declaramos para os devidos fins que as parcelas abaixo relacionadas, devidas por <strong><?= $cliente['nome_razao'] ?></strong>, 
    referentes a <strong><?= $cliente['descricao'] ?></strong>, foram <strong>CANCELADAS</strong> nesta data, não restando débitos sobre as mesmas.</p>

    <table>
        <thead>
            <tr>
                <th>Vencimento Original</th>
                <th>Descrição da Parcela</th>
                <th>Valor Cancelado</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalCancelado = 0; foreach($parcelas as $p): $totalCancelado += $p['valor_original']; ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($p['data_vencimento'])) ?></td>
                <td>Parcela Pendente</td>
                <td>R$ <?= number_format($p['valor_original'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total-box">
        TOTAL CANCELADO: R$ <?= number_format($totalCancelado, 2, ',', '.') ?>
    </div>

    <div style="margin-top: 40px; font-size: 10px; color: #666;">
        * Este documento comprova apenas o cancelamento das parcelas listadas. Pagamentos anteriores permanecem válidos mediante seus respectivos recibos.
    </div>

</body>
</html>