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
               
                <td width="50%" align="right">
                    <span class="titulo">Recibo de Pagamento</span><br>
                    Nº Parcela: <?= $parcela['parcela_atual'] .'|'.$parcela['total_parcelas']?>
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
        <p><?php
$data = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

$formatter = new IntlDateFormatter(
    'pt_BR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE,
    'America/Sao_Paulo',
    IntlDateFormatter::TRADITIONAL,
    "dd 'de' MMMM 'de' yyyy"
);

echo 'Vacaria/RS - ' . $formatter->format($data)  ;
?></p>
        <br><br><br>
        <div class="assinatura">Assinatura do Responsável</div>
    </div>