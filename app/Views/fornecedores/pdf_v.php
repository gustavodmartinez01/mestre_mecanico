<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        .titulo { font-size: 18px; font-weight: bold; color: #007bff; }
        .secao { background: #f4f4f4; padding: 5px; font-weight: bold; margin: 15px 0 5px 0; border-left: 3px solid #007bff; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .label { font-weight: bold; width: 150px; color: #555; }
        .info { font-size: 14px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <span class="titulo">FICHA DO FORNECEDOR</span><br>
                    <strong>Empresa:</strong> <?= session()->get('empresa_nome') ?>
                </td>
                <td style="text-align: right;">
                    <strong>ID:</strong> #<?= $f['id'] ?> | <strong>Status:</strong> <?= $f['ativo'] ? 'ATIVO' : 'INATIVO' ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="secao">DADOS DO FORNECEDOR</div>
    <table>
        <tr>
            <td class="label">Razão Social/Nome:</td>
            <td class="info"><?= $f['nome_razao'] ?></td>
        </tr>
        <tr>
            <td class="label">Nome Fantasia:</td>
            <td><?= $f['nome_fantasia'] ?? '-' ?></td>
        </tr>
        <tr>
            <td class="label"><?= ($f['tipo_pessoa'] == 'J') ? 'CNPJ' : 'CPF' ?>:</td>
            <td><?= $f['documento'] ?></td>
        </tr>
        <tr>
            <td class="label">Categoria:</td>
            <td><strong><?= $f['categoria'] ?></strong> (<?= $f['especialidade'] ?>)</td>
        </tr>
    </table>

    <div class="secao">CONTATO</div>
    <table>
        <tr>
            <td class="label">E-mail:</td>
            <td><?= $f['email'] ?></td>
        </tr>
        <tr>
            <td class="label">Telefone:</td>
            <td><?= $f['telefone'] ?></td>
        </tr>
        <tr>
            <td class="label">Celular/Whats:</td>
            <td><strong><?= $f['celular'] ?></strong></td>
        </tr>
    </table>

    <div class="secao">ENDEREÇO</div>
    <table>
        <tr>
            <td class="label">Endereço:</td>
            <td><?= $f['logradouro'] ?>, <?= $f['numero'] ?> <?= $f['complemento'] ?></td>
        </tr>
        <tr>
            <td class="label">Bairro:</td>
            <td><?= $f['bairro'] ?> - <?= $f['cidade'] ?>/<?= $f['estado'] ?></td>
        </tr>
        <tr>
            <td class="label">CEP:</td>
            <td><?= $f['cep'] ?></td>
        </tr>
    </table>

    <?php if ($f['observacoes']): ?>
    <div class="secao">OBSERVAÇÕES</div>
    <div style="padding: 10px; border: 1px solid #eee;">
        <?= nl2br($f['observacoes']) ?>
    </div>
    <?php endif; ?>

    <div style="margin-top: 50px; text-align: center; color: #999; font-size: 10px;">
        Documento gerado pelo Sistema Mestre Mecânico em <?= date('d/m/Y H:i') ?>
    </div>

</body>
</html>