<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .header .titulo { font-size: 18px; font-weight: bold; text-transform: uppercase; text-align: right; }
        .header .empresa { font-size: 14px; font-weight: bold; }
        
        .secao-titulo { 
            background-color: #f2f2f2; 
            padding: 5px 10px; 
            font-weight: bold; 
            border-left: 4px solid #333; 
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table.dados { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.dados td { padding: 6px; border-bottom: 1px solid #eee; vertical-align: top; }
        .label { font-weight: bold; color: #555; width: 150px; }
        
        .status-badge { 
            padding: 3px 8px; 
            border-radius: 4px; 
            font-size: 10px; 
            text-transform: uppercase;
            font-weight: bold;
        }
        .ativo { background-color: #d4edda; color: #155724; }
        .inativo { background-color: #f8d7da; color: #721c24; }

        .footer-assinatura { margin-top: 60px; width: 100%; text-align: center; }
        .assinatura-box { display: inline-block; width: 45%; }
        .linha { border-top: 1px solid #333; width: 80%; margin: 0 auto; margin-top: 40px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="empresa">
                    <?= session()->get('empresa_nome') ?><br>
                    <small>CNPJ: <?= session()->get('empresa_cnpj') ?></small>
                </td>
                <td class="titulo">Ficha do Funcionário</td>
            </tr>
        </table>
    </div>

    <div class="secao-titulo">Dados de Identificação</div>
    <table class="dados">
        <tr>
            <td class="label">Matrícula:</td>
            <td><strong><?= $funcionario['matricula'] ?></strong></td>
            <td class="label">Status do Registro:</td>
            <td>
                <span class="status-badge <?= $funcionario['ativo'] ? 'ativo' : 'inativo' ?>">
                    <?= $funcionario['ativo'] ? 'Cadastro Ativo' : 'Cadastro Inativo' ?>
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Nome Completo:</td>
            <td colspan="3"><?= $funcionario['nome'] ?></td>
        </tr>
        <tr>
            <td class="label">CPF:</td>
            <td><?= $funcionario['cpf'] ?></td>
            <td class="label">RG:</td>
            <td><?= $funcionario['rg'] ?? 'Não informado' ?></td>
        </tr>
        <tr>
            <td class="label">Data de Nascimento:</td>
            <td colspan="3"><?= ($funcionario['data_nascimento']) ? date('d/m/Y', strtotime($funcionario['data_nascimento'])) : '--/--/----' ?></td>
        </tr>
    </table>

    <div class="secao-titulo">Localização e Contato</div>
    <table class="dados">
        <tr>
            <td class="label">Endereço:</td>
            <td colspan="3">
                <?= $funcionario['logradouro'] ?>, <?= $funcionario['numero'] ?> - <?= $funcionario['bairro'] ?>
            </td>
        </tr>
        <tr>
            <td class="label">Cidade/UF:</td>
            <td><?= $funcionario['cidade'] ?> / <?= $funcionario['estado'] ?></td>
            <td class="label">CEP:</td>
            <td><?= $funcionario['cep'] ?></td>
        </tr>
        <tr>
            <td class="label">Telefone:</td>
            <td><?= $funcionario['telefone'] ?></td>
            <td class="label">Celular:</td>
            <td><?= $funcionario['celular'] ?></td>
        </tr>
    </table>

    <div class="secao-titulo">Informações Contratuais</div>
    <table class="dados">
        <tr>
            <td class="label">Cargo:</td>
            <td><?= $funcionario['cargo'] ?></td>
            <td class="label">Data de Admissão:</td>
            <td><?= date('d/m/Y', strtotime($funcionario['data_admissao'])) ?></td>
        </tr>
        <tr>
            <td class="label">Situação:</td>
            <td><?= strtoupper($funcionario['status']) ?></td>
            <td class="label">Comissão (S/P):</td>
            <td><?= $funcionario['comissao_servico'] ?>% / <?= $funcionario['comissao_produto'] ?? '0.00' ?>%</td>
        </tr>
        <tr>
            <td class="label">Observações:</td>
            <td colspan="3"><?= nl2br($funcionario['observacoes'] ?? 'Nenhuma observação registrada.') ?></td>
        </tr>
    </table>

    <div class="footer-assinatura">
        <div class="assinatura-box">
            <div class="linha"></div>
            <p>Responsável Administrativo</p>
        </div>
        <div class="assinatura-box">
            <div class="linha"></div>
            <p><?= $funcionario['nome'] ?></p>
        </div>
    </div>

</body>
</html>