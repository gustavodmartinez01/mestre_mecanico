-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/04/2026 às 22:30
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `mestre_mecanico`
--
CREATE DATABASE IF NOT EXISTS `mestre_mecanico` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `mestre_mecanico`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklists`
--

DROP TABLE IF EXISTS `checklists`;
CREATE TABLE `checklists` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `categoria` varchar(50) DEFAULT 'Geral',
  `ordem_exibicao` int(11) DEFAULT 0,
  `tipo` enum('entrada','servico') NOT NULL,
  `obrigatorio` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `checklists`
--

INSERT INTO `checklists` (`id`, `descricao`, `categoria`, `ordem_exibicao`, `tipo`, `obrigatorio`) VALUES
(1, 'Nível de Combustível (marcado no painel)', 'Geral', 0, 'entrada', 0),
(2, 'Quilometragem Atual', 'Geral', 0, 'entrada', 0),
(3, 'Estado da Lataria (riscos, amassados)', 'Geral', 0, 'entrada', 0),
(4, 'Integridade dos Vidros e Parabrisa', 'Geral', 0, 'entrada', 0),
(5, 'Estado dos Pneus e Rodas (incluindo estepe)', 'Geral', 0, 'entrada', 0),
(6, 'Presença de Estepe, Macaco e Chave de Roda', 'Geral', 0, 'entrada', 0),
(7, 'Presença de Triângulo e Extintor', 'Geral', 0, 'entrada', 0),
(8, 'Funcionamento das Luzes Externas (Faróis/Lanternas)', 'Geral', 0, 'entrada', 0),
(9, 'Estado dos Estofados e Tapetes', 'Geral', 0, 'entrada', 0),
(10, 'Rádio/Central Multimídia e Som', 'Geral', 0, 'entrada', 0),
(11, 'Pertences Pessoais Deixados no Veículo', 'Geral', 0, 'entrada', 0),
(12, 'Funcionamento do Ar-Condicionado', 'Geral', 0, 'entrada', 0),
(13, 'Luzes de Advertência no Painel (ABS, Airbag, Injeção)', 'Geral', 0, 'entrada', 0),
(14, 'Nível e Condição do Óleo do Motor', 'Geral', 0, 'servico', 0),
(15, 'Nível e Aditivo do Sistema de Arrefecimento', 'Geral', 0, 'servico', 0),
(16, 'Estado das Pastilhas e Discos de Freio', 'Geral', 0, 'servico', 0),
(17, 'Fluido de Freio (Nível e Contaminação)', 'Geral', 0, 'servico', 0),
(18, 'Estado das Correias (Dentada e Acessórios)', 'Geral', 0, 'servico', 0),
(19, 'Condição das Velas e Cabos de Ignição', 'Geral', 0, 'servico', 0),
(20, 'Filtro de Ar do Motor', 'Geral', 0, 'servico', 0),
(21, 'Filtro de Cabine (Ar-Condicionado)', 'Geral', 0, 'servico', 0),
(22, 'Bateria (Teste de Carga e Terminais)', 'Geral', 0, 'servico', 0),
(23, 'Folgas em Suspensão (Pivôs, Buchas, Batentes)', 'Geral', 0, 'servico', 0),
(24, 'Estado dos Amortecedores', 'Geral', 0, 'servico', 0),
(25, 'Estado dos Coifas de Homocinética', 'Geral', 0, 'servico', 0),
(26, 'Escapamento e Suportes', 'Geral', 0, 'servico', 0),
(27, 'Vazamentos de Óleo ou Fluidos por Baixo', 'Geral', 0, 'servico', 0),
(28, 'Calibragem de Pneus e Reset de Monitoramento', 'Geral', 0, 'servico', 0),
(29, 'Torque das Rodas (Aperto final)', 'Geral', 0, 'servico', 0),
(30, 'Teste de Rodagem (Barulhos e Estabilidade)', 'Geral', 0, 'servico', 0),
(31, 'Curso e Peso do Pedal de Embreagem', 'Geral', 0, 'servico', 0),
(32, 'Nível/Estado do Óleo do Câmbio (Manual/Automático)', 'Geral', 0, 'servico', 0),
(33, 'Engate de Marchas e Ruídos de Transmissão', 'Geral', 0, 'servico', 0),
(34, 'Integridade das Juntas Homocinéticas e Coifas', 'Geral', 0, 'servico', 0),
(35, 'Leitura de Códigos de Falha (Scanner OBD2)', 'Geral', 0, 'servico', 0),
(36, 'Funcionamento de Vidros, Travas e Alarmes', 'Geral', 0, 'servico', 0),
(37, 'Estado das Palhetas do Limpador e Esguicho', 'Geral', 0, 'servico', 0),
(38, 'Limpeza de Polos e Teste de Alternador', 'Geral', 0, 'servico', 0),
(39, 'Funcionamento de Buzina e Luzes de Cortesia', 'Geral', 0, 'servico', 0),
(40, 'Limpeza do Corpo de Borboleta (TBI)', 'Geral', 0, 'servico', 0),
(41, 'Teste de Estanqueidade e Vazão dos Bicos', 'Geral', 0, 'servico', 0),
(42, 'Estado do Filtro de Combustível', 'Geral', 0, 'servico', 0),
(43, 'Integridade das Mangueiras de Combustível', 'Geral', 0, 'servico', 0),
(44, 'Folga em Caixas de Direção e Terminais', 'Geral', 0, 'servico', 0),
(45, 'Nível e Cor do Fluido de Direção Hidráulica', 'Geral', 0, 'servico', 0),
(46, 'Verificação de Alinhamento e Balanceamento', 'Geral', 0, 'servico', 0),
(47, 'Estado das Buchas de Eixo Traseiro', 'Geral', 0, 'servico', 0),
(48, 'Folga nos Rolamentos de Roda', 'Geral', 0, 'servico', 0),
(49, 'Funcionamento do Freio de Estacionamento', 'Geral', 0, 'servico', 0),
(50, 'Estado dos Cintos de Segurança', 'Geral', 0, 'servico', 0),
(51, 'Verificação de Data de Validade do Pneu (DOT)', 'Geral', 0, 'servico', 0),
(52, 'Lubrificação de Dobradiças e Fechaduras', 'Geral', 0, 'servico', 0),
(53, 'Lavagem de Motor e Higienização (Se contratado)', 'Geral', 0, 'servico', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklist_modelos`
--

DROP TABLE IF EXISTS `checklist_modelos`;
CREATE TABLE `checklist_modelos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `checklist_modelos`
--

INSERT INTO `checklist_modelos` (`id`, `empresa_id`, `nome`, `descricao`, `ativo`, `created_at`) VALUES
(1, 1, 'Checklist de Entrada Geral', 'Inspeção visual básica para recebimento do veículo', 1, '2026-02-25 21:20:00'),
(2, 1, 'Revisão Sistema de Freios', 'Inspeção detalhada de segurança do sistema de frenagem', 1, '2026-02-25 21:20:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklist_modelo_itens`
--

DROP TABLE IF EXISTS `checklist_modelo_itens`;
CREATE TABLE `checklist_modelo_itens` (
  `id` int(11) NOT NULL,
  `checklist_modelo_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `obrigatorio` tinyint(1) DEFAULT 0,
  `ordem` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `checklist_modelo_itens`
--

INSERT INTO `checklist_modelo_itens` (`id`, `checklist_modelo_id`, `descricao`, `obrigatorio`, `ordem`) VALUES
(1, 1, 'Verificar nível do óleo do motor', 1, 1),
(2, 1, 'Verificar luzes (faróis, setas, freio)', 1, 2),
(3, 1, 'Estado dos pneus e estepe', 0, 3),
(4, 1, 'Verificar palhetas do limpador', 0, 4),
(5, 1, 'Presença de triângulo, macaco e chave de roda', 1, 5),
(6, 2, 'Espessura das pastilhas dianteiras', 1, 1),
(7, 2, 'Estado dos discos de freio (sulcos/rebarbas)', 1, 2),
(8, 2, 'Nível e umidade do fluido de freio', 1, 3),
(9, 2, 'Vazamentos nos cilindros de roda/pinças', 1, 4),
(10, 2, 'Funcionamento do freio de estacionamento', 0, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `tipo_pessoa` enum('F','J') DEFAULT 'F',
  `nome_razao` varchar(255) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `score_historico` int(11) DEFAULT 0,
  `score_perfil` int(11) DEFAULT 0,
  `score_relacionamento` int(11) DEFAULT 0,
  `score_documentacao` int(11) DEFAULT 0,
  `score_total` int(11) DEFAULT 0,
  `classificacao` enum('Ouro','Prata','Bronze') DEFAULT 'Bronze',
  `observacoes_financeiras` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `empresa_id`, `tipo_pessoa`, `nome_razao`, `documento`, `email`, `telefone`, `celular`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `score_historico`, `score_perfil`, `score_relacionamento`, `score_documentacao`, `score_total`, `classificacao`, `observacoes_financeiras`, `ativo`, `created_at`) VALUES
(1, 1, 'F', 'Ricardo Almeida Santos', '55331858015', 'ricardo@email.com', '', '54996809855', '', '', '', '', '', '', '', 40, 20, 20, 20, 100, 'Ouro', 'Cliente antigo, sempre paga antecipado. Carro de passeio.', 1, '2026-02-23 23:05:55'),
(2, 1, 'F', 'Transportes Rapidez Ltda', '55331858015', 'contato@rapidez.com', '', '5432211000', '95200151', 'Rua Quinze de Novembro', '311', 'casa', 'Centro', 'Vacaria', 'RS', 30, 15, 15, 10, 70, 'Prata', 'Empresa de frota. Costuma parcelar em 3x no boleto.', 1, '2026-02-23 23:05:55'),
(3, 1, 'F', 'Marcos Oliveira da Silva', '98765432100', 'marcos@email.com', '', '54988776655', '95200151', 'Rua Quinze de Novembro', '001', 'casa', 'Centro', 'Vacaria', 'RS', 15, 5, 0, 10, 30, 'Bronze', 'Primeiro serviço. Já teve histórico de atraso em outras oficinas da região.', 1, '2026-02-23 23:05:55');

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras_requisicoes`
--

DROP TABLE IF EXISTS `compras_requisicoes`;
CREATE TABLE `compras_requisicoes` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL COMMENT 'Quem solicitou',
  `data_requisicao` date NOT NULL,
  `status` enum('aberta','cotacao','aprovada','rejeitada','finalizada') DEFAULT 'aberta',
  `valor_total` decimal(15,2) DEFAULT 0.00,
  `observacoes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_fechamento` datetime DEFAULT NULL,
  `pago_na_entrega` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras_requisicoes`
--

INSERT INTO `compras_requisicoes` (`id`, `empresa_id`, `fornecedor_id`, `usuario_id`, `data_requisicao`, `status`, `valor_total`, `observacoes`, `created_at`, `updated_at`, `data_fechamento`, `pago_na_entrega`) VALUES
(1, 1, 1, 1, '2026-04-14', 'finalizada', 10.00, '', '2026-04-14 09:46:10', '2026-04-14 17:19:17', NULL, 0),
(2, 1, 1, 1, '2026-04-14', 'finalizada', 280.00, '', '2026-04-14 10:44:59', '2026-04-15 10:01:16', NULL, 0),
(4, 1, 1, 1, '2026-04-15', 'aberta', 670.00, '', '2026-04-15 10:13:12', '2026-04-15 15:11:07', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `compras_requisicoes_itens`
--

DROP TABLE IF EXISTS `compras_requisicoes_itens`;
CREATE TABLE `compras_requisicoes_itens` (
  `id` int(11) NOT NULL,
  `requisicao_id` int(11) NOT NULL,
  `produto_id` int(11) DEFAULT NULL COMMENT 'NULL se for um serviço avulso',
  `descricao_item` varchar(255) NOT NULL COMMENT 'Nome do produto ou serviço',
  `quantidade` decimal(15,3) NOT NULL,
  `valor_unitario` decimal(15,2) DEFAULT 0.00,
  `situacao` enum('pendente','atendido','nao_atendido') DEFAULT 'pendente',
  `subtotal` decimal(15,2) GENERATED ALWAYS AS (`quantidade` * `valor_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `compras_requisicoes_itens`
--

INSERT INTO `compras_requisicoes_itens` (`id`, `requisicao_id`, `produto_id`, `descricao_item`, `quantidade`, `valor_unitario`, `situacao`) VALUES
(1, 1, 2, 'Filtro de Óleo PSL55 - Filtro de Óleo PSL55', 1.000, 10.00, 'nao_atendido'),
(2, 2, 1, 'Óleo 5W30 Sintético - Óleo 5W30 Sintético', 4.000, 70.00, 'atendido'),
(4, 2, 2, 'Filtro de Óleo PSL55 - Filtro de Óleo PSL55', 1.000, 12.50, 'nao_atendido'),
(5, 1, NULL, 'Troca de Óleo e Filtros', 1.000, 10.00, 'atendido'),
(6, 4, 2, 'Filtro de Óleo PSL55 - Filtro de Óleo PSL55', 4.000, 100.00, 'pendente'),
(7, 4, NULL, 'Troca de Óleo e Filtros', 2.000, 110.00, 'pendente'),
(8, 4, 1, 'Óleo 5W30 Sintético - Óleo 5W30 Sintético', 2.000, 25.00, 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_pagar`
--

DROP TABLE IF EXISTS `contas_pagar`;
CREATE TABLE `contas_pagar` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `fornecedor_id` int(11) DEFAULT NULL,
  `os_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `centro_custo_id` int(11) DEFAULT NULL,
  `nota_fiscal_id` varchar(50) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor_original` decimal(10,2) NOT NULL,
  `valor_pago` decimal(10,2) DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `juros_mora` decimal(10,2) DEFAULT 0.00,
  `multa_mora` decimal(10,2) DEFAULT 0.00,
  `atualizacao_monetaria` decimal(10,2) DEFAULT 0.00,
  `parcela_atual` int(11) DEFAULT 1,
  `total_parcelas` int(11) DEFAULT 1,
  `id_agrupador` varchar(50) DEFAULT NULL,
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status` enum('pendente','paga','vencida','cancelada') DEFAULT 'pendente',
  `is_recorrente` tinyint(1) DEFAULT 0,
  `completa` tinyint(1) DEFAULT 0,
  `conta_bancaria_id` int(11) DEFAULT NULL,
  `forma_pagamento` enum('dinheiro','cartao_credito','cartao_debito','pix','boleto','transferencia') NOT NULL,
  `arquivo_origem` varchar(255) DEFAULT NULL,
  `comprovante_arquivo` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contas_pagar`
--

INSERT INTO `contas_pagar` (`id`, `empresa_id`, `fornecedor_id`, `os_id`, `categoria_id`, `centro_custo_id`, `nota_fiscal_id`, `descricao`, `valor_original`, `valor_pago`, `desconto`, `juros_mora`, `multa_mora`, `atualizacao_monetaria`, `parcela_atual`, `total_parcelas`, `id_agrupador`, `data_vencimento`, `data_pagamento`, `status`, `is_recorrente`, `completa`, `conta_bancaria_id`, `forma_pagamento`, `arquivo_origem`, `comprovante_arquivo`, `observacoes`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 15, 4, NULL, 'Internet e telefone', 150.00, 150.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D027E87A01C', '2026-04-15', '2026-04-06', 'paga', 0, 0, NULL, '', NULL, NULL, '', '2026-04-03 17:49:44', '2026-04-06 16:34:41'),
(2, 1, NULL, NULL, 15, 4, NULL, 'Internet e Telefone Globals', 140.00, 140.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D0307D5C377', '2026-04-01', '2026-04-05', 'paga', 0, 0, NULL, '', NULL, NULL, '', '2026-04-03 18:26:21', '2026-04-05 20:23:53'),
(3, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 140.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-04-10', '2026-04-05', 'paga', 1, 0, NULL, '', NULL, NULL, '', '2026-04-03 19:07:07', '2026-04-05 20:28:58'),
(4, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-05-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(5, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-06-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(6, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-07-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(7, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-08-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(8, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-09-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(9, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-10-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(10, 1, NULL, NULL, 14, 2, NULL, 'conta de água -corsan', 140.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D03A0BBFB49', '2026-11-10', NULL, 'pendente', 1, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-03 19:07:07', '2026-04-04 16:29:50'),
(11, 1, NULL, NULL, 7, 2, NULL, 'Seguro focus - gustavo', 150.00, 150.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D5474067375', '2026-04-15', '2026-04-07', 'paga', 0, 0, NULL, '', NULL, NULL, '', '2026-04-07 15:04:48', '2026-04-07 15:06:47'),
(12, 1, NULL, NULL, 6, 2, NULL, 'Internet e telefone', 150.00, 150.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D5568E97B5D', '2026-04-07', '2026-04-07', 'paga', 0, 0, NULL, '', NULL, NULL, '', '2026-04-07 16:10:06', '2026-04-07 16:10:30'),
(13, 1, NULL, NULL, 6, 2, NULL, 'Internet e telefone', 150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D5568E97B5D', '2026-05-07', NULL, 'pendente', 0, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-07 16:10:06', '2026-04-07 16:10:06'),
(14, 1, NULL, NULL, 20, 3, NULL, 'Seguro focus - gustavo', 150.00, 150.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D55743D6224', '2026-04-08', '2026-04-09', 'paga', 0, 0, NULL, '', NULL, NULL, '', '2026-04-07 16:13:07', '2026-04-09 10:04:14'),
(15, 1, NULL, NULL, 20, 3, NULL, 'Seguro focus - gustavo', 150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D55743D6224', '2026-05-08', NULL, 'pendente', 0, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-07 16:13:07', '2026-04-07 16:13:07'),
(16, 1, NULL, NULL, 20, 3, NULL, 'Seguro focus - gustavo', 150.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D55743D6224', '2026-06-08', NULL, 'pendente', 0, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-07 16:13:07', '2026-04-07 16:13:07'),
(17, 1, NULL, NULL, 29, 2, NULL, 'calçados', 200.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D7A5BD8B956', '2026-04-09', NULL, 'pendente', 0, 0, NULL, 'cartao_credito', NULL, NULL, NULL, '2026-04-09 10:12:29', '2026-04-09 10:12:29'),
(18, 1, NULL, NULL, 6, 2, NULL, 'Internet e Telefone Globals, complemento', 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D7E3836D748', '2026-04-09', NULL, 'pendente', 0, 0, NULL, 'pix', NULL, NULL, NULL, '2026-04-09 14:36:03', '2026-04-09 14:36:03'),
(19, 1, NULL, NULL, 4, 2, NULL, 'aluguel maio', 1000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'PAG-69D7E4612C143', '2026-04-10', NULL, 'pendente', 0, 0, NULL, 'dinheiro', NULL, NULL, NULL, '2026-04-09 14:39:45', '2026-04-09 14:39:45'),
(20, 1, 1, NULL, NULL, NULL, NULL, 'Compra ref. Requisição #1', 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'REQ-1', '2026-04-14', '2026-04-14', 'paga', 0, 0, NULL, 'dinheiro', NULL, NULL, NULL, '2026-04-14 17:19:17', '2026-04-14 17:19:17'),
(21, 1, 1, NULL, NULL, NULL, NULL, 'Compra ref. Requisição #2', 280.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'REQ-2', '2026-04-15', '2026-04-15', 'paga', 0, 0, NULL, 'dinheiro', NULL, NULL, NULL, '2026-04-15 09:41:23', '2026-04-15 09:41:23'),
(22, 1, 1, NULL, NULL, NULL, NULL, 'Compra ref. Requisição #2', 280.00, 0.00, 0.00, 0.00, 0.00, 0.00, 1, 1, 'REQ-2', '2026-04-15', '2026-04-15', 'paga', 0, 0, NULL, 'dinheiro', NULL, NULL, NULL, '2026-04-15 10:01:16', '2026-04-15 10:01:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_receber`
--

DROP TABLE IF EXISTS `contas_receber`;
CREATE TABLE `contas_receber` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `os_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor_original` decimal(10,2) NOT NULL,
  `valor_pago` decimal(10,2) DEFAULT 0.00,
  `parcela_atual` int(11) DEFAULT 1,
  `total_parcelas` int(11) DEFAULT 1,
  `id_agrupador` varchar(50) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `juros_mora` decimal(10,2) DEFAULT 0.00,
  `multa_mora` decimal(10,2) DEFAULT 0.00,
  `atualizacao_monetaria` decimal(10,2) DEFAULT 0.00,
  `data_vencimento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status` enum('pendente','paga','vencida','cancelada') DEFAULT 'pendente',
  `completa` tinyint(1) DEFAULT 0,
  `forma_pagamento` enum('dinheiro','cartao_credito','cartao_debito','pix','boleto','duplicata','promissoria') DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contas_receber`
--

INSERT INTO `contas_receber` (`id`, `empresa_id`, `cliente_id`, `os_id`, `descricao`, `valor_original`, `valor_pago`, `parcela_atual`, `total_parcelas`, `id_agrupador`, `desconto`, `juros_mora`, `multa_mora`, `atualizacao_monetaria`, `data_vencimento`, `data_pagamento`, `status`, `completa`, `forma_pagamento`, `observacoes`, `created_at`, `updated_at`) VALUES
(16, 1, 3, 14, 'Referente a OS n°: 2026008 (1/3)', 150.00, 150.00, 1, 3, 'OS_14_1774896993_000008', 0.00, 0.00, 0.00, 0.00, '2026-03-30', '2026-04-01', 'paga', 1, 'dinheiro', NULL, '2026-03-30 16:39:18', '2026-04-27 11:15:21'),
(17, 1, 3, 14, 'Referente a OS n°: 2026008 (2/3)', 150.00, 150.00, 2, 3, 'OS_14_1774896993_000008', 0.00, 0.00, 0.00, 0.00, '2026-04-29', '2026-04-01', 'paga', 1, 'pix', NULL, '2026-03-30 16:39:18', '2026-04-27 11:15:21'),
(18, 1, 3, 14, 'Referente a OS n°: 2026008 (3/3)', 150.00, 150.00, 3, 3, 'OS_14_1774896993_000008', 0.00, 0.00, 0.00, 0.00, '2026-05-29', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-03-30 16:39:18', '2026-04-27 11:15:21'),
(19, 1, 1, 10, 'Referente a OS n°: 20260003 (1/5)', 101.00, 101.00, 1, 5, 'OS_10_1774896993_000006', 0.00, 0.00, 0.00, 0.00, '2026-03-31', '2026-03-31', 'paga', 1, 'dinheiro', NULL, '2026-03-31 09:59:24', '2026-04-27 11:15:21'),
(20, 1, 1, 10, 'Referente a OS n°: 20260003 (2/5)', 101.00, 0.00, 2, 5, 'OS_10_1774896993_000006', 0.00, 0.00, 0.00, 0.00, '2026-04-30', NULL, 'cancelada', 1, NULL, NULL, '2026-03-31 09:59:24', '2026-04-01 09:16:57'),
(21, 1, 1, 10, 'Referente a OS n°: 20260003 (3/5)', 101.00, 0.00, 3, 5, 'OS_10_1774896993_000006', 0.00, 0.00, 0.00, 0.00, '2026-05-30', NULL, 'cancelada', 1, NULL, NULL, '2026-03-31 09:59:24', '2026-04-01 09:16:57'),
(22, 1, 1, 10, 'Referente a OS n°: 20260003 (4/5)', 101.00, 0.00, 4, 5, 'OS_10_1774896993_000006', 0.00, 0.00, 0.00, 0.00, '2026-06-29', NULL, 'cancelada', 1, NULL, NULL, '2026-03-31 09:59:24', '2026-04-01 09:16:57'),
(23, 1, 1, 10, 'Referente a OS n°: 20260003 (5/5)', 101.00, 0.00, 5, 5, 'OS_10_1774896993_000006', 0.00, 0.00, 0.00, 0.00, '2026-07-29', NULL, 'cancelada', 1, NULL, NULL, '2026-03-31 09:59:24', '2026-04-01 09:16:57'),
(24, 1, 2, 3, 'Referente a OS n°: 2026004 (1/2)', 227.50, 227.50, 1, 2, 'OS_3_1774896993_000002', 0.00, 0.00, 0.00, 0.00, '2026-04-08', '2026-04-02', 'paga', 1, 'dinheiro', NULL, '2026-03-31 10:03:51', '2026-04-27 11:15:21'),
(25, 1, 2, 3, 'Referente a OS n°: 2026004 (2/2)', 227.50, 227.50, 2, 2, 'OS_3_1774896993_000002', 0.00, 0.00, 0.00, 0.00, '2026-05-08', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-03-31 10:03:51', '2026-04-27 11:15:21'),
(26, 1, 1, 8, 'Referente a OS n°: 20260002 (1/1)', 450.00, 450.00, 1, 1, 'OS_8_1774896993_000005', 0.00, 0.00, 0.00, 0.00, '2026-04-30', '2026-03-31', 'paga', 1, 'pix', NULL, '2026-03-31 10:08:22', '2026-04-27 11:15:21'),
(27, 1, 2, NULL, 'bateria de 24v 110A', 300.00, 300.00, 1, 2, 'bbdc3e6c89778f4c', 0.00, 0.00, 0.00, 0.00, '2026-04-01', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-04-01 10:23:12', '2026-04-27 11:15:21'),
(28, 1, 2, NULL, 'bateria de 24v 110A', 300.00, 300.00, 2, 2, 'bbdc3e6c89778f4c', 0.00, 0.00, 0.00, 0.00, '2026-05-01', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-04-01 10:23:12', '2026-04-27 11:15:21'),
(29, 1, 1, 1, 'Referente a OS n°: 20260001 (1/1)', 35.00, 35.00, 1, 1, 'OS_1_1774896993_000001', 0.00, 0.00, 0.00, 0.00, '2026-02-04', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-04-01 19:03:30', '2026-04-27 11:15:21'),
(30, 1, 2, 7, 'Referente a OS n°: 7 (1/1)', 0.00, 0.00, 1, 1, 'OS_7_1774896993_000004', 0.00, 0.00, 0.00, 0.00, '2026-04-02', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-04-02 23:44:27', '2026-04-27 11:15:21'),
(31, 1, 3, 11, 'Referente a OS n°: 2026005 (1/1)', 0.00, 0.00, 1, 1, 'OS_11_1774896993_000007', 0.00, 0.00, 0.00, 0.00, '2026-04-02', '2026-04-02', 'paga', 1, 'pix', NULL, '2026-04-02 23:47:08', '2026-04-27 11:15:21'),
(32, 1, 2, 5, 'Referente a OS n°: 5 (1/1)', 0.00, 0.00, 1, 1, 'OS_5_1774896993_000003', 0.00, 0.00, 0.00, 0.00, '2026-04-03', '2026-04-03', 'paga', 1, 'pix', NULL, '2026-04-03 00:14:20', '2026-04-27 11:15:21'),
(33, 1, 3, NULL, 'jantar', 100.00, 100.00, 1, 1, '27000fecca8afab3', 0.00, 0.00, 0.00, 0.00, '2026-04-07', '2026-04-01', 'paga', 1, NULL, NULL, '2026-04-06 15:47:58', '2026-04-27 11:15:21'),
(34, 1, 1, NULL, 'bateria de 24v 110A', 500.00, 500.00, 1, 1, 'bbd0a4bce3349271', 0.00, 0.00, 0.00, 0.00, '2026-04-08', '2026-04-07', 'paga', 1, 'pix', NULL, '2026-04-07 10:49:51', '2026-04-27 11:15:21'),
(35, 1, 3, NULL, 'pistoes focus', 200.00, 200.00, 1, 2, '3399120dc7dbed75', 0.00, 0.00, 0.00, 0.00, '2026-04-14', '2026-04-07', 'paga', 1, 'pix', NULL, '2026-04-07 15:19:19', '2026-04-27 11:15:21'),
(36, 1, 3, NULL, 'pistoes focus', 200.00, 200.00, 2, 2, '3399120dc7dbed75', 0.00, 0.00, 0.00, 0.00, '2026-05-14', '2026-04-07', 'paga', 1, 'pix', NULL, '2026-04-07 15:19:19', '2026-04-27 11:15:21'),
(37, 1, 2, NULL, 'bateria de 12 v  60A', 200.00, 200.00, 1, 1, '010da6f25f8e6850', 0.00, 0.00, 0.00, 0.00, '2026-04-08', '2026-04-07', 'paga', 1, 'pix', NULL, '2026-04-07 15:27:33', '2026-04-27 11:15:21'),
(38, 1, 3, NULL, 'oleo luibrificabte', 100.00, 0.00, 1, 1, '619d493346840f27', 0.00, 0.00, 0.00, 0.00, '2026-04-07', NULL, 'vencida', 1, NULL, NULL, '2026-04-07 15:37:01', '2026-04-09 14:36:03'),
(39, 1, 3, NULL, 'troca de aneis e pistoes', 100.00, 100.00, 1, 4, '20cd3cce402aa2c1', 0.00, 0.00, 0.00, 0.00, '2026-04-07', '2026-04-07', 'paga', 1, 'pix', NULL, '2026-04-07 15:47:52', '2026-04-27 11:15:21'),
(40, 1, 3, NULL, 'troca de aneis e pistoes', 100.00, 0.00, 2, 4, '20cd3cce402aa2c1', 0.00, 0.00, 0.00, 0.00, '2026-05-07', NULL, 'pendente', 1, NULL, NULL, '2026-04-07 15:47:52', '2026-04-07 15:47:52'),
(41, 1, 3, NULL, 'troca de aneis e pistoes', 100.00, 0.00, 3, 4, '20cd3cce402aa2c1', 0.00, 0.00, 0.00, 0.00, '2026-06-07', NULL, 'pendente', 1, NULL, NULL, '2026-04-07 15:47:52', '2026-04-07 15:47:52'),
(42, 1, 3, NULL, 'troca de aneis e pistoes', 100.00, 0.00, 4, 4, '20cd3cce402aa2c1', 0.00, 0.00, 0.00, 0.00, '2026-07-07', NULL, 'pendente', 1, NULL, NULL, '2026-04-07 15:47:52', '2026-04-07 15:47:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos_financeiros`
--

DROP TABLE IF EXISTS `documentos_financeiros`;
CREATE TABLE `documentos_financeiros` (
  `id` int(11) NOT NULL,
  `conta_receber_id` int(11) NOT NULL,
  `tipo` enum('recibo','duplicata','promissoria','carne','nfe') DEFAULT NULL,
  `caminho_arquivo` varchar(255) DEFAULT NULL,
  `conteudo_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`conteudo_json`)),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `ie` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `ie`, `email`, `telefone`, `cep`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `logo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Campos Auto Service - MEI', 'Campos Auto Service', '00.000.000/0001-00', NULL, NULL, '54996859785', NULL, 'Av. Presidente Juscelino Kubitscheck', '7417', 'Minuano', 'Vacaria', 'RS', 'mestre-mecanico.png', '2026-02-23 16:47:35', '2026-04-16 09:58:45', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque_movimentacao`
--

DROP TABLE IF EXISTS `estoque_movimentacao`;
CREATE TABLE `estoque_movimentacao` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `tipo` enum('E','S') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `origem` enum('Compra','Venda_OS','Ajuste_Manual','Devolucao') DEFAULT 'Compra',
  `data_movimento` datetime DEFAULT current_timestamp(),
  `observacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque_movimentacao`
--

INSERT INTO `estoque_movimentacao` (`id`, `produto_id`, `empresa_id`, `tipo`, `quantidade`, `origem`, `data_movimento`, `observacao`) VALUES
(1, 1, 1, 'E', 50, 'Compra', '2026-02-24 13:32:45', 'Nota Fiscal 1234 - Fornecedor Distribuidora X'),
(2, 2, 1, 'E', 10, 'Compra', '2026-02-24 13:32:45', 'Estoque inicial'),
(3, 3, 1, 'E', 4, 'Compra', '2026-02-24 13:32:45', 'Compra via Balcão'),
(4, 4, 1, 'E', 10, 'Compra', '2026-02-24 13:32:45', 'Reposição quinzenal'),
(5, 4, 1, 'S', 2, 'Ajuste_Manual', '2026-02-24 13:32:45', 'Lâmpada quebrada durante manuseio'),
(6, 2, 1, 'S', 1, '', '2026-02-25 14:08:08', 'Saída automática via Ordem de Serviço'),
(7, 2, 1, 'S', 1, '', '2026-02-25 14:12:13', 'Saída automática via Ordem de Serviço'),
(8, 2, 1, 'S', 1, '', '2026-02-25 14:13:23', 'Saída automática via Ordem de Serviço'),
(9, 2, 1, 'S', 1, '', '2026-02-25 14:14:43', 'Saída automática via Ordem de Serviço'),
(10, 2, 1, 'S', 1, '', '2026-03-03 10:42:42', 'Saída automática via Ordem de Serviço'),
(11, 2, 1, 'S', 1, '', '2026-03-07 14:55:59', 'Saída automática'),
(12, 3, 1, 'S', 4, '', '2026-03-18 23:29:47', 'Saída automática'),
(13, 3, 1, 'E', 4, '', '2026-03-18 23:36:14', 'Item removido da OS'),
(14, 1, 1, '', 4, 'Compra', '2026-04-15 09:41:23', NULL),
(15, 1, 1, '', 4, 'Compra', '2026-04-15 10:01:16', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_categorias`
--

DROP TABLE IF EXISTS `financeiro_categorias`;
CREATE TABLE `financeiro_categorias` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tipo` enum('receita','despesa') NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `financeiro_categorias`
--

INSERT INTO `financeiro_categorias` (`id`, `empresa_id`, `nome`, `tipo`, `status`) VALUES
(1, 1, 'Salários e Encargos', 'despesa', 1),
(2, 1, 'Pró-labore', 'despesa', 1),
(3, 1, 'Compra de Peças', 'despesa', 1),
(4, 1, 'Aluguel', 'despesa', 1),
(5, 1, 'Energia Elétrica', 'despesa', 1),
(6, 1, 'Internet e Software', 'despesa', 1),
(7, 1, 'Impostos e Taxas', 'despesa', 1),
(8, 1, 'Prestação de Serviços (Mão de Obra)', 'receita', 1),
(9, 1, 'Venda de Peças', 'receita', 1),
(10, 1, 'Venda de Acessórios', 'receita', 1),
(11, 1, 'Serviços Terceirizados (Retífica/Torno)', 'receita', 1),
(12, 1, 'Aluguel e Condomínio', 'despesa', 1),
(13, 1, 'Energia Elétrica', 'despesa', 1),
(14, 1, 'Água e Saneamento', 'despesa', 1),
(15, 1, 'Internet e Telefonia', 'despesa', 1),
(16, 1, 'Software de Gestão (SaaS)', 'despesa', 1),
(17, 1, 'Salários de Funcionários', 'despesa', 1),
(18, 1, 'Pró-labore (Sócios)', 'despesa', 1),
(19, 1, 'Comissões de Vendas', 'despesa', 1),
(20, 1, 'Vale Transporte / Refeição', 'despesa', 1),
(21, 1, 'FGTS / INSS / Encargos', 'despesa', 1),
(22, 1, 'Compra de Peças para Estoque', 'despesa', 1),
(23, 1, 'Compra de Peças para OS Específica', 'despesa', 1),
(24, 1, 'Ferramentas e Equipamentos', 'despesa', 1),
(25, 1, 'Insumos (Óleos, Estopas, Desengraxantes)', 'despesa', 1),
(26, 1, 'Marketing e Publicidade', 'despesa', 1),
(27, 1, 'Impostos (Simples Nacional / MEI)', 'despesa', 1),
(28, 1, 'Tarifas Bancárias', 'despesa', 1),
(29, 1, 'Limpeza e Conservação', 'despesa', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_centros_custo`
--

DROP TABLE IF EXISTS `financeiro_centros_custo`;
CREATE TABLE `financeiro_centros_custo` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `financeiro_centros_custo`
--

INSERT INTO `financeiro_centros_custo` (`id`, `empresa_id`, `nome`, `status`) VALUES
(1, 1, 'Oficina / Operacional', 1),
(2, 1, 'Administrativo', 1),
(3, 1, 'Oficina (Operacional)', 1),
(4, 1, 'Administrativo / Escritório', 1),
(5, 1, 'Comercial / Vendas', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro_movimentacao`
--

DROP TABLE IF EXISTS `financeiro_movimentacao`;
CREATE TABLE `financeiro_movimentacao` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL COMMENT 'entrada (receber) ou saida (pagar)',
  `categoria_id` int(11) DEFAULT NULL COMMENT 'FK para plano de contas/categorias',
  `conta_bancaria_id` int(11) DEFAULT NULL COMMENT 'Para controle de múltiplos caixas/bancos',
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(15,2) NOT NULL DEFAULT 0.00,
  `data_movimentacao` date NOT NULL COMMENT 'Data real do pagamento/recebimento',
  `origem_tabela` varchar(50) DEFAULT NULL COMMENT 'Ex: contas_pagar ou contas_receber',
  `origem_id` int(11) DEFAULT NULL COMMENT 'ID do registro na tabela de origem',
  `observacoes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `financeiro_movimentacao`
--

INSERT INTO `financeiro_movimentacao` (`id`, `empresa_id`, `tipo`, `categoria_id`, `conta_bancaria_id`, `descricao`, `valor`, `data_movimentacao`, `origem_tabela`, `origem_id`, `observacoes`, `created_at`, `updated_at`) VALUES
(1, 1, 'entrada', NULL, NULL, 'RECEBIMENTO: troca de aneis e pistoes', 100.00, '2026-04-07', 'contas_receber', 39, 'Baixa realizada com sucesso.', '2026-04-07 15:48:03', '2026-04-07 15:48:03'),
(2, 1, 'saida', 20, NULL, 'PAGAMENTO: Seguro focus - gustavo', 150.00, '2026-04-09', 'contas_pagar', 14, 'Baixa automática via módulo Contas a Pagar.', '2026-04-09 10:04:14', '2026-04-09 10:04:14'),
(3, 1, 'saida', NULL, NULL, 'PAGTO REQ #1', 10.00, '2026-04-14', 'contas_pagar', 20, NULL, '2026-04-14 17:19:17', '2026-04-14 17:19:17'),
(4, 1, 'saida', NULL, NULL, 'PAGTO REQ #2', 280.00, '2026-04-15', 'contas_pagar', 21, NULL, '2026-04-15 09:41:23', '2026-04-15 09:41:23'),
(5, 1, 'saida', NULL, NULL, 'PAGTO REQ #2', 280.00, '2026-04-15', 'contas_pagar', 22, NULL, '2026-04-15 10:01:16', '2026-04-15 10:01:16');

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
CREATE TABLE `fornecedores` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `tipo_pessoa` enum('F','J') DEFAULT 'J',
  `nome_razao` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) DEFAULT NULL,
  `documento` varchar(20) NOT NULL,
  `ie_rg` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `categoria` enum('Produtos','Serviços','Ambos') DEFAULT 'Produtos',
  `especialidade` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`id`, `empresa_id`, `tipo_pessoa`, `nome_razao`, `nome_fantasia`, `documento`, `ie_rg`, `email`, `telefone`, `celular`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `categoria`, `especialidade`, `observacoes`, `ativo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'F', 'RAFAEL DE CAMPOS', 'RAFAEL DE CAMPOS', '111.222.333-44', NULL, '', '', '5496859785', '95200-151', 'Rua Quinze de Novembro', '', '', 'Centro', 'Vacaria', 'RS', 'Ambos', 'MECANICA', '', 1, '2026-02-24 00:29:59', '2026-04-16 16:35:15', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
CREATE TABLE `funcionarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `matricula` varchar(20) DEFAULT NULL,
  `empresa_id` int(11) NOT NULL,
  `cargo` enum('Administrador','Caixa','Técnico','Gerente','Secretária','Balconista','Ajudante') NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `comissao_servico` decimal(5,2) DEFAULT 0.00,
  `comissao_produto` decimal(5,2) DEFAULT 0.00,
  `data_admissao` date DEFAULT NULL,
  `data_demissao` date DEFAULT NULL,
  `status` enum('trabalhando','ferias','afastado','desligado') DEFAULT 'trabalhando',
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `email`, `usuario_id`, `matricula`, `empresa_id`, `cargo`, `cpf`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `rg`, `data_nascimento`, `telefone`, `celular`, `comissao_servico`, `comissao_produto`, `data_admissao`, `data_demissao`, `status`, `observacoes`, `ativo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ricardo Oliveira', NULL, 1, NULL, 1, 'Administrador', '111.111.111-11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '2023-01-01', NULL, 'trabalhando', NULL, 1, '2026-02-23 16:47:35', '2026-02-23 16:47:35', NULL),
(2, 'João Silva da Graxa', NULL, NULL, NULL, 1, 'Ajudante', '222.222.222-22', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '2023-05-10', NULL, 'trabalhando', NULL, 1, '2026-02-23 16:47:35', '2026-02-23 16:47:35', NULL),
(3, 'GUSTAVO DORNELES MARTNEZ', 'gustavo.dmartinez@gmail.com', 6, '123', 1, 'Gerente', '55331858015', '95200-151', 'Rua Quinze de Novembro', '311', NULL, 'Centro', 'Vacaria', 'RS', '11111', '1966-09-05', '', '(54) 99680-9855', 20.00, 20.00, '2024-01-01', NULL, 'trabalhando', NULL, 1, '2026-03-02 12:41:38', '2026-03-27 09:06:50', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servicos`
--

DROP TABLE IF EXISTS `ordem_servicos`;
CREATE TABLE `ordem_servicos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `numero_os` varchar(20) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tecnico_id` int(11) DEFAULT NULL,
  `veiculo_id` int(11) NOT NULL,
  `status` enum('orcamento','aprovada','em_execucao','finalizada','cancelada') DEFAULT 'orcamento',
  `data_abertura` datetime DEFAULT NULL,
  `data_aprovacao` datetime DEFAULT NULL,
  `data_fechamento` datetime DEFAULT NULL,
  `km_entrada` int(11) DEFAULT NULL,
  `descricao_problema` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `valor_servicos` decimal(10,2) DEFAULT 0.00,
  `valor_produtos` decimal(10,2) DEFAULT 0.00,
  `desconto` decimal(10,2) DEFAULT 0.00,
  `acrescimo` decimal(10,2) DEFAULT 0.00,
  `valor_total` decimal(10,2) DEFAULT 0.00,
  `criado_por` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servicos`
--

INSERT INTO `ordem_servicos` (`id`, `empresa_id`, `numero_os`, `cliente_id`, `usuario_id`, `tecnico_id`, `veiculo_id`, `status`, `data_abertura`, `data_aprovacao`, `data_fechamento`, `km_entrada`, `descricao_problema`, `diagnostico`, `observacoes`, `valor_servicos`, `valor_produtos`, `desconto`, `acrescimo`, `valor_total`, `criado_por`, `updated_at`, `created_at`) VALUES
(1, 1, '20260001', 1, NULL, NULL, 1, 'finalizada', '2026-02-25 12:43:28', NULL, '2026-03-03 04:15:25', 0, 'Carro com soltando fumça azul pelo escapamento', NULL, NULL, 0.00, 35.00, 0.00, 0.00, 35.00, NULL, '2026-03-03 04:15:25', '2026-02-25 18:43:27'),
(2, 1, '', 1, NULL, NULL, 1, 'cancelada', '2026-03-02 23:37:10', NULL, NULL, NULL, NULL, NULL, NULL, 450.00, 0.00, 0.00, 0.00, 450.00, NULL, '2026-03-03 04:13:10', '2026-03-03 05:37:10'),
(3, 1, '2026004', 2, NULL, 3, 2, 'finalizada', '2026-03-03 10:38:38', NULL, '2026-03-03 18:01:50', NULL, NULL, NULL, NULL, 420.00, 35.00, 0.00, 0.00, 455.00, NULL, '2026-03-03 18:57:28', '2026-03-03 16:38:38'),
(4, 1, '', 3, NULL, NULL, 4, 'aprovada', '2026-03-03 11:09:28', NULL, NULL, NULL, NULL, NULL, NULL, 750.00, 0.00, 0.00, 0.00, 750.00, NULL, '2026-03-20 16:21:44', '2026-03-03 17:09:28'),
(5, 1, '', 2, NULL, NULL, 5, 'finalizada', '2026-03-03 11:14:11', NULL, '2026-03-03 20:55:36', NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-03 20:55:36', '2026-03-03 17:14:11'),
(6, 1, '', 2, NULL, NULL, 5, 'cancelada', '2026-03-03 11:14:59', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-03 14:19:08', '2026-03-03 17:14:59'),
(7, 1, '', 2, NULL, NULL, 2, 'finalizada', '2026-03-03 11:19:47', NULL, '2026-03-07 21:39:06', NULL, 'revisao dos freios', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-07 21:39:06', '2026-03-03 17:19:47'),
(8, 1, '20260002', 1, NULL, 3, 1, 'finalizada', '2026-03-03 16:48:58', NULL, '2026-03-03 20:56:45', NULL, '', NULL, NULL, 450.00, 0.00, 0.00, 0.00, 450.00, 1, '2026-03-03 20:56:45', '2026-03-03 22:48:58'),
(10, 1, '20260003', 1, NULL, 3, 4, 'finalizada', '2026-03-03 19:59:48', NULL, '2026-03-08 20:56:54', 90000, 'troca de oleo', NULL, NULL, 470.00, 35.00, 0.00, 0.00, 505.00, 1, '2026-03-08 20:57:45', '2026-03-03 22:59:48'),
(11, 1, '2026005', 3, NULL, 1, 1, 'finalizada', '2026-03-06 01:14:45', NULL, '2026-03-07 21:43:14', 0, 'troca de aneis', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-07 21:43:14', '2026-03-06 04:14:45'),
(12, 1, '2026006', 2, NULL, 2, 6, 'cancelada', '2026-03-18 10:02:11', '2026-03-18 23:36:51', NULL, 120000, 'trocar oleo e revisar correia dentada', NULL, NULL, 150.00, 0.00, 0.00, 0.00, 150.00, 1, '2026-03-19 00:44:22', '2026-03-18 13:02:11'),
(13, 1, '2026007', 3, NULL, 3, 1, 'em_execucao', '2026-03-20 09:58:38', '2026-03-20 13:20:41', NULL, 0, 'revisao', NULL, NULL, 150.00, 0.00, 0.00, 0.00, 150.00, 1, '2026-03-20 13:20:41', '2026-03-20 12:58:38'),
(14, 1, '2026008', 3, NULL, 2, 2, 'finalizada', '2026-03-20 13:20:14', '2026-03-24 14:13:20', '2026-03-27 15:40:08', 1000, 'revisar amortecedor', NULL, NULL, 450.00, 0.00, 0.00, 0.00, 450.00, 1, '2026-03-27 15:40:08', '2026-03-20 16:20:14');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico_checklists`
--

DROP TABLE IF EXISTS `ordem_servico_checklists`;
CREATE TABLE `ordem_servico_checklists` (
  `id` int(11) NOT NULL,
  `ordem_servico_id` int(11) NOT NULL,
  `checklist_modelo_id` int(11) DEFAULT NULL,
  `status` enum('pendente','em_execucao','concluido') DEFAULT 'pendente',
  `iniciado_em` datetime DEFAULT NULL,
  `concluido_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servico_checklists`
--

INSERT INTO `ordem_servico_checklists` (`id`, `ordem_servico_id`, `checklist_modelo_id`, `status`, `iniciado_em`, `concluido_em`) VALUES
(1, 1, 1, 'pendente', '2026-02-26 00:25:17', NULL),
(2, 2, 3, 'pendente', '2026-03-03 04:10:06', NULL),
(3, 3, 2, 'pendente', '2026-03-03 13:39:14', NULL),
(4, 8, 2, 'pendente', '2026-03-03 19:51:07', NULL),
(5, 11, 1, 'pendente', '2026-03-06 01:15:21', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico_checklist_itens`
--

DROP TABLE IF EXISTS `ordem_servico_checklist_itens`;
CREATE TABLE `ordem_servico_checklist_itens` (
  `id` int(11) NOT NULL,
  `os_checklist_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `obrigatorio` tinyint(1) DEFAULT 0,
  `status` enum('pendente','ok','nao_ok','nao_aplicavel') DEFAULT 'pendente',
  `observacao` text DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `executado_por` int(11) DEFAULT NULL,
  `executado_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servico_checklist_itens`
--

INSERT INTO `ordem_servico_checklist_itens` (`id`, `os_checklist_id`, `descricao`, `obrigatorio`, `status`, `observacao`, `foto_path`, `executado_por`, `executado_at`) VALUES
(1, 1, 'Verificar nível do óleo do motor', 1, 'ok', '', NULL, NULL, '2026-02-26 00:26:42'),
(2, 1, 'Verificar luzes (faróis, setas, freio)', 1, 'ok', '', NULL, NULL, '2026-03-03 04:15:16'),
(3, 1, 'Estado dos pneus e estepe', 0, 'ok', '', NULL, NULL, '2026-03-03 04:15:15'),
(4, 1, 'Verificar palhetas do limpador', 0, 'ok', '', NULL, NULL, '2026-02-26 00:26:59'),
(5, 1, 'Presença de triângulo, macaco e chave de roda', 1, 'ok', '', NULL, NULL, '2026-03-03 04:15:17'),
(6, 2, 'Nome completo do cliente', 1, 'pendente', NULL, NULL, NULL, NULL),
(7, 2, 'Telefone de contato do cliente', 1, 'pendente', NULL, NULL, NULL, NULL),
(8, 2, 'E-mail do cliente', 0, 'pendente', NULL, NULL, NULL, NULL),
(9, 2, 'Endereço do cliente', 0, 'pendente', NULL, NULL, NULL, NULL),
(10, 2, 'Marca e Modelo do veículo', 1, 'pendente', NULL, NULL, NULL, NULL),
(11, 2, 'Ano de fabricação do veículo', 1, 'pendente', NULL, NULL, NULL, NULL),
(12, 2, 'Placa do veículo', 1, 'pendente', NULL, NULL, NULL, NULL),
(13, 2, 'Quilometragem atual (hodômetro)', 1, 'pendente', NULL, NULL, NULL, NULL),
(14, 2, 'Cor do veículo', 1, 'pendente', NULL, NULL, NULL, NULL),
(15, 2, 'Número do Chassi (VIN)', 0, 'pendente', NULL, NULL, NULL, NULL),
(16, 2, 'Nível de combustível', 1, 'pendente', NULL, NULL, NULL, NULL),
(17, 2, 'Descrição detalhada dos problemas ou sintomas relatados pelo cliente', 1, 'pendente', NULL, NULL, NULL, NULL),
(18, 2, 'Serviços específicos solicitados pelo cliente', 1, 'pendente', NULL, NULL, NULL, NULL),
(19, 2, 'Observações adicionais do cliente sobre o histórico ou preferências', 0, 'pendente', NULL, NULL, NULL, NULL),
(20, 2, 'Para-choque dianteiro (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(21, 2, 'Para-choque traseiro (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(22, 2, 'Porta dianteira esquerda (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(23, 2, 'Porta dianteira direita (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(24, 2, 'Porta traseira esquerda (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(25, 2, 'Porta traseira direita (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(26, 2, 'Capô (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(27, 2, 'Tampa do porta-malas (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(28, 2, 'Para-lama dianteiro esquerdo (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(29, 2, 'Para-lama dianteiro direito (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(30, 2, 'Para-lama traseiro esquerdo (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(31, 2, 'Para-lama traseiro direito (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(32, 2, 'Teto (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(33, 2, 'Retrovisor esquerdo (estado e funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(34, 2, 'Retrovisor direito (estado e funcionamento)', 1, 'ok', NULL, NULL, NULL, '2026-03-08 13:26:45'),
(35, 2, 'Para-brisa (estado)', 1, 'nao_ok', NULL, NULL, NULL, '2026-03-08 14:42:59'),
(36, 2, 'Vidros laterais (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(37, 2, 'Vidro traseiro (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(38, 2, 'Faróis (alto e baixo, funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(39, 2, 'Lanternas traseiras (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(40, 2, 'Luzes de freio (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(41, 2, 'Setas dianteiras (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(42, 2, 'Setas traseiras (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(43, 2, 'Luz de ré (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(44, 2, 'Luz de placa (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(45, 2, 'Faróis de neblina (funcionamento, se houver)', 0, 'pendente', NULL, NULL, NULL, NULL),
(46, 2, 'Pneu dianteiro esquerdo (estado e desgaste)', 1, 'pendente', NULL, NULL, NULL, NULL),
(47, 2, 'Pneu dianteiro direito (estado e desgaste)', 1, 'pendente', NULL, NULL, NULL, NULL),
(48, 2, 'Pneu traseiro esquerdo (estado e desgaste)', 1, 'pendente', NULL, NULL, NULL, NULL),
(49, 2, 'Pneu traseiro direito (estado e desgaste)', 1, 'pendente', NULL, NULL, NULL, NULL),
(50, 2, 'Calibragem dos pneus (verificar)', 1, 'pendente', NULL, NULL, NULL, NULL),
(51, 2, 'Rodas (arranhões, amassados)', 1, 'pendente', NULL, NULL, NULL, NULL),
(52, 2, 'Estepe (localização e estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(53, 2, 'Ferramentas de troca de pneu (macaco, chave de roda)', 1, 'pendente', NULL, NULL, NULL, NULL),
(54, 2, 'Bancos (rasgos, manchas)', 1, 'pendente', NULL, NULL, NULL, NULL),
(55, 2, 'Forros de porta (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(56, 2, 'Painel (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(57, 2, 'Tapetes (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(58, 2, 'Rádio/Multimídia (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(59, 2, 'Ar-condicionado (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(60, 2, 'Vidros elétricos (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(61, 2, 'Travas elétricas (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(62, 2, 'Cintos de segurança (estado)', 1, 'pendente', NULL, NULL, NULL, NULL),
(63, 2, 'Buzina (funcionamento)', 1, 'pendente', NULL, NULL, NULL, NULL),
(64, 2, 'Limpador de para-brisa (funcionamento e estado das palhetas)', 1, 'pendente', NULL, NULL, NULL, NULL),
(65, 2, 'Objetos pessoais de valor deixados no veículo (registrar)', 0, 'pendente', NULL, NULL, NULL, NULL),
(66, 2, 'Número da Ordem de Serviço (OS)', 1, 'pendente', NULL, NULL, NULL, NULL),
(67, 2, 'Data e hora de entrada', 1, 'pendente', NULL, NULL, NULL, NULL),
(68, 2, 'Assinatura do cliente autorizando os serviços', 1, 'pendente', NULL, NULL, NULL, NULL),
(69, 2, 'Termo de responsabilidade assinado pelo cliente', 1, 'pendente', NULL, NULL, NULL, NULL),
(70, 3, 'Espessura das pastilhas dianteiras', 1, '', '', NULL, NULL, '2026-03-03 13:39:52'),
(71, 3, 'Estado dos discos de freio (sulcos/rebarbas)', 1, 'pendente', NULL, NULL, NULL, NULL),
(72, 3, 'Nível e umidade do fluido de freio', 1, 'pendente', NULL, NULL, NULL, NULL),
(73, 3, 'Vazamentos nos cilindros de roda/pinças', 1, 'pendente', NULL, NULL, NULL, NULL),
(74, 3, 'Funcionamento do freio de estacionamento', 0, 'pendente', NULL, NULL, NULL, NULL),
(75, 4, 'Espessura das pastilhas dianteiras', 1, 'pendente', NULL, NULL, NULL, NULL),
(76, 4, 'Estado dos discos de freio (sulcos/rebarbas)', 1, 'pendente', NULL, NULL, NULL, NULL),
(77, 4, 'Nível e umidade do fluido de freio', 1, 'pendente', NULL, NULL, NULL, NULL),
(78, 4, 'Vazamentos nos cilindros de roda/pinças', 1, 'pendente', NULL, NULL, NULL, NULL),
(79, 4, 'Funcionamento do freio de estacionamento', 0, 'pendente', NULL, NULL, NULL, NULL),
(80, 5, 'Verificar nível do óleo do motor', 1, 'ok', NULL, NULL, NULL, '2026-03-06 01:24:32'),
(81, 5, 'Verificar luzes (faróis, setas, freio)', 1, 'ok', NULL, NULL, NULL, '2026-03-06 01:24:33'),
(82, 5, 'Estado dos pneus e estepe', 0, 'ok', NULL, NULL, NULL, '2026-03-06 01:24:34'),
(83, 5, 'Verificar palhetas do limpador', 0, 'ok', NULL, NULL, NULL, '2026-03-06 01:24:35'),
(84, 5, 'Presença de triângulo, macaco e chave de roda', 1, 'ok', NULL, NULL, NULL, '2026-03-06 01:24:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico_fotos`
--

DROP TABLE IF EXISTS `ordem_servico_fotos`;
CREATE TABLE `ordem_servico_fotos` (
  `id` int(11) NOT NULL,
  `ordem_servico_id` int(11) NOT NULL,
  `checklist_item_id` int(11) DEFAULT NULL,
  `tipo` enum('antes','durante','depois','evidencia') DEFAULT 'evidencia',
  `caminho_arquivo` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tamanho_kb` int(11) DEFAULT NULL,
  `criado_por` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servico_fotos`
--

INSERT INTO `ordem_servico_fotos` (`id`, `ordem_servico_id`, `checklist_item_id`, `tipo`, `caminho_arquivo`, `descricao`, `tamanho_kb`, `criado_por`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'antes', 'uploads/os/1/1772066426_e675e669136132b721f4.jpg', 'teste', 30, NULL, '2026-02-26 00:40:26', '2026-02-26 03:40:26'),
(2, 2, NULL, 'antes', 'uploads/os/2/1772506568_4e7e568f88230ea29575.jpg', '', 71, NULL, '2026-03-03 02:56:08', '2026-03-03 05:56:08'),
(5, 10, NULL, 'evidencia', 'uploads/os/10/1772923537_c1375a8a2a9540c49a72.png', '', 968, NULL, '2026-03-07 19:45:37', '2026-03-07 22:45:37');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ordem_servico_itens`
--

DROP TABLE IF EXISTS `ordem_servico_itens`;
CREATE TABLE `ordem_servico_itens` (
  `id` int(11) NOT NULL,
  `ordem_servico_id` int(11) NOT NULL,
  `tipo` enum('servico','produto') NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT 1.00,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `custo_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `margem` decimal(10,2) DEFAULT NULL,
  `mecanico_id` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ordem_servico_itens`
--

INSERT INTO `ordem_servico_itens` (`id`, `ordem_servico_id`, `tipo`, `item_id`, `descricao`, `quantidade`, `valor_unitario`, `custo_unitario`, `subtotal`, `margem`, `mecanico_id`, `updated_at`, `created_at`) VALUES
(1, 1, 'produto', 2, 'Filtro de Óleo PSL55', 1.00, 35.00, 12.50, 35.00, 22.50, NULL, '2026-02-25 20:14:43', '2026-02-25 20:14:43'),
(4, 2, 'servico', 2, 'Revisão de Suspensão', 1.00, 450.00, 0.00, 450.00, 450.00, NULL, '2026-03-03 06:00:47', '2026-03-03 06:00:47'),
(5, 3, 'servico', 4, 'Diagnóstico e Reparo de Injeção', 1.00, 320.00, 50.00, 320.00, 270.00, NULL, '2026-03-03 16:40:28', '2026-03-03 16:40:28'),
(6, 3, 'servico', 6, 'Guincho Barney - Perímetro Urbano', 1.00, 100.00, 0.00, 100.00, 100.00, NULL, '2026-03-03 16:40:48', '2026-03-03 16:40:48'),
(7, 3, 'produto', 2, 'Filtro de Óleo PSL55', 1.00, 35.00, 12.50, 35.00, 22.50, NULL, '2026-03-03 16:42:42', '2026-03-03 16:42:42'),
(8, 4, 'servico', 2, 'Revisão de Suspensão', 1.00, 450.00, 0.00, 450.00, 450.00, NULL, '2026-03-03 17:09:50', '2026-03-03 17:09:50'),
(10, 8, 'servico', 2, 'Revisão de Suspensão', 1.00, 450.00, 0.00, 450.00, 450.00, NULL, '2026-03-03 22:51:49', '2026-03-03 22:51:49'),
(12, 10, 'produto', 2, 'Filtro de Óleo PSL55', 1.00, 35.00, 12.50, 35.00, NULL, NULL, '2026-03-07 17:55:59', '2026-03-07 17:55:59'),
(13, 10, 'servico', 4, 'Uso de Scanner e limpeza de bicos.', 1.00, 320.00, 50.00, 320.00, NULL, NULL, '2026-03-07 21:50:43', '2026-03-07 21:50:43'),
(14, 10, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-08 15:41:47', '2026-03-08 15:41:47'),
(15, 12, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-19 02:19:58', '2026-03-19 02:19:58'),
(17, 13, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-20 16:18:42', '2026-03-20 16:18:42'),
(18, 4, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-20 16:40:32', '2026-03-20 16:40:32'),
(19, 4, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-20 19:21:44', '2026-03-20 19:21:44'),
(20, 14, 'servico', 2, 'Revisão de Suspensão - Troca de amortecedores, batentes e buchas.', 1.00, 450.00, 0.00, 450.00, NULL, NULL, '2026-03-24 17:12:11', '2026-03-24 17:12:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `os_checklists`
--

DROP TABLE IF EXISTS `os_checklists`;
CREATE TABLE `os_checklists` (
  `id` int(11) NOT NULL,
  `ordem_servico_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `status` enum('pendente','ok','nao_ok') DEFAULT 'pendente',
  `observacao` varchar(255) DEFAULT NULL,
  `foto_evidencia` varchar(255) DEFAULT NULL,
  `status_anterior` enum('pendente','ok','nao_ok') DEFAULT NULL,
  `tipo` enum('entrada','servico') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `os_checklists`
--

INSERT INTO `os_checklists` (`id`, `ordem_servico_id`, `descricao`, `categoria`, `status`, `observacao`, `foto_evidencia`, `status_anterior`, `tipo`, `created_at`, `updated_at`) VALUES
(33, 5, 'teste', NULL, 'pendente', NULL, NULL, NULL, 'entrada', '2026-03-08 17:54:44', '2026-03-08 17:54:44'),
(34, 10, 'Nível de Combustível (marcado no painel)', NULL, 'nao_ok', 'meio tanque', NULL, 'ok', 'entrada', '2026-03-08 17:54:44', '2026-03-08 19:04:53'),
(35, 10, 'Nível e Condição do Óleo do Motor', NULL, 'ok', NULL, NULL, 'nao_ok', 'servico', '2026-03-08 17:54:44', '2026-03-08 18:32:06'),
(36, 10, 'Quilometragem Atual', NULL, 'ok', '', NULL, 'ok', 'entrada', '2026-03-08 18:08:19', '2026-03-08 22:12:46'),
(37, 10, 'Nível de Combustível (marcado no painel)', NULL, 'pendente', NULL, NULL, NULL, 'entrada', '2026-03-08 18:42:57', '2026-03-08 18:42:57'),
(38, 10, 'Quilometragem Atual', NULL, 'pendente', '100000', NULL, 'pendente', 'entrada', '2026-03-08 18:44:07', '2026-03-08 19:18:13'),
(39, 10, 'Estado das Pastilhas e Discos de Freio', NULL, 'nao_ok', 'troca dianteira', NULL, 'nao_ok', 'servico', '2026-03-08 19:05:37', '2026-03-08 19:17:26'),
(40, 12, 'Nível de Combustível (marcado no painel)', NULL, 'ok', 'tanque cheio', NULL, 'ok', 'entrada', '2026-03-18 13:02:58', '2026-03-18 13:19:37'),
(41, 12, 'Nível e Condição do Óleo do Motor', NULL, 'pendente', 'trocar', NULL, 'pendente', 'servico', '2026-03-18 13:03:13', '2026-03-18 13:05:38'),
(42, 12, 'Quilometragem Atual', NULL, 'ok', '100000', NULL, 'pendente', 'entrada', '2026-03-18 13:19:56', '2026-03-19 02:36:35'),
(43, 12, 'Quilometragem Atual', NULL, 'nao_ok', '', NULL, 'nao_ok', 'entrada', '2026-03-18 13:19:59', '2026-03-19 02:36:50'),
(44, 4, 'Nível de Combustível (marcado no painel)', NULL, 'pendente', '', NULL, 'pendente', 'entrada', '2026-03-20 19:37:28', '2026-03-20 19:40:53'),
(45, 4, 'Estado das Pastilhas e Discos de Freio', NULL, 'pendente', NULL, NULL, NULL, 'servico', '2026-03-20 19:43:35', '2026-03-20 19:43:35'),
(46, 4, 'Quilometragem Atual', NULL, 'pendente', NULL, NULL, NULL, 'entrada', '2026-03-20 19:53:37', '2026-03-20 19:53:37'),
(47, 14, 'Estado dos Estofados e Tapetes', NULL, 'pendente', 'manchas de ttintas', NULL, 'pendente', 'entrada', '2026-03-24 12:22:20', '2026-03-24 12:22:46'),
(48, 14, 'Estado das Pastilhas e Discos de Freio', NULL, 'ok', 'finalizaddo', NULL, 'pendente', 'servico', '2026-03-24 17:12:51', '2026-03-24 17:13:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `os_checklists_fotos`
--

DROP TABLE IF EXISTS `os_checklists_fotos`;
CREATE TABLE `os_checklists_fotos` (
  `id` int(11) NOT NULL,
  `os_checklist_id` int(11) NOT NULL,
  `caminho_arquivo` varchar(255) NOT NULL,
  `legenda` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `codigo_barras` varchar(50) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` varchar(100) DEFAULT 'produtos.nome',
  `marca` varchar(100) DEFAULT NULL,
  `preco_custo` decimal(10,2) DEFAULT 0.00,
  `preco_venda` decimal(10,2) NOT NULL,
  `estoque_minimo` int(11) DEFAULT 1,
  `estoque_atual` int(11) DEFAULT 0,
  `unidade_medida` enum('UN','LITRO','KG','PAR','CONJ') DEFAULT 'UN',
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `empresa_id`, `codigo_barras`, `nome`, `descricao`, `marca`, `preco_custo`, `preco_venda`, `estoque_minimo`, `estoque_atual`, `unidade_medida`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Óleo 5W30 Sintético', 'Óleo 5W30 Sintético - Óleo 5W30 Sintético', 'Lubrax', 25.00, 48.00, 20, 50, 'LITRO', 1, '2026-02-24 13:32:45', '2026-02-25 17:10:00'),
(2, 1, NULL, 'Filtro de Óleo PSL55', 'Filtro de Óleo PSL55 - Filtro de Óleo PSL55', 'Tecfil', 12.50, 35.00, 5, 4, 'UN', 1, '2026-02-24 13:32:45', '2026-03-07 17:55:59'),
(3, 1, NULL, 'Pastilha de Freio Dianteira', 'Pastilha de Freio Dianteira - Pastilha de Freio Dianteira', 'Fras-le', 85.00, 160.00, 2, 4, 'PAR', 1, '2026-02-24 13:32:45', '2026-03-19 02:36:14'),
(4, 1, NULL, 'Lâmpada H7 12V', 'Lâmpada H7 12V - Lâmpada H7 12V', 'Osram', 15.00, 45.00, 10, 8, 'UN', 1, '2026-02-24 13:32:45', '2026-02-25 17:10:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `recebimentos_historico`
--

DROP TABLE IF EXISTS `recebimentos_historico`;
CREATE TABLE `recebimentos_historico` (
  `id` int(11) NOT NULL,
  `conta_receber_id` int(11) DEFAULT NULL,
  `valor_recebido` decimal(10,2) DEFAULT NULL,
  `data_horario` datetime DEFAULT current_timestamp(),
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `comprovante_hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

DROP TABLE IF EXISTS `servicos`;
CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `unidade` enum('hora','fixo','Km') DEFAULT 'fixo',
  `preco_custo` decimal(10,2) DEFAULT 0.00,
  `preco_venda` decimal(10,2) NOT NULL,
  `tempo_dias` int(11) DEFAULT 0,
  `tempo_horas` int(11) DEFAULT 0,
  `tempo_minutos` int(11) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`id`, `empresa_id`, `nome`, `descricao`, `unidade`, `preco_custo`, `preco_venda`, `tempo_dias`, `tempo_horas`, `tempo_minutos`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Troca de Óleo e Filtros', 'Troca de Óleo e Filtros - Substituição de óleo lubrificante e filtros de ar/óleo.', 'fixo', 0.00, 150.00, 0, 1, 30, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(2, 1, 'Revisão de Suspensão', 'Revisão de Suspensão - Troca de amortecedores, batentes e buchas.', 'fixo', 0.00, 450.00, 0, 5, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(3, 1, 'Retífica de Cabeçote (Terceirizado)', 'Retífica de Cabeçote (Terceirizado) - Serviço enviado para retífica externa. Inclui desmonte e monte.', 'fixo', 600.00, 950.00, 2, 0, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(4, 1, 'Diagnóstico e Reparo de Injeção', 'Diagnóstico e Reparo de Injeção - Uso de Scanner e limpeza de bicos.', 'fixo', 50.00, 320.00, 0, 3, 45, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(5, 1, 'Pintura de Parachoque', 'Pintura de Parachoque - Serviço realizado em parceria com funilaria externa.', 'fixo', 250.00, 450.00, 3, 0, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(6, 1, 'Guincho Barney - Perímetro Urbano', 'Guincho Barney - Perímetro Urbano - Reboque de um veículo dentro do perímetro urbnao', 'Km', 0.00, 100.00, 0, 0, 0, 1, '2026-03-03 03:07:07', '2026-03-03 06:07:07');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel_acesso` enum('admin','gerente','funcionario') DEFAULT 'funcionario',
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ultimo_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `empresa_id`, `nome`, `email`, `senha`, `nivel_acesso`, `status`, `ultimo_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '', 'admin@mestre.com', '$2y$10$Orx8.drsVlBUEJEwCWmIHOxHiBQKQc5vjXp7VlvFm26iB5Nclsr9W', 'admin', 'ativo', NULL, '2026-02-23 16:47:35', '2026-02-23 17:48:06', NULL),
(6, 1, '', 'gustavo.dmartinez@gmail.com', '$2y$10$4xEO.liObCn20du5OLqZA.I/hDqBNJ7xbG9CrXojvPIaYPDhxgHhK', 'admin', 'ativo', NULL, '2026-03-24 14:16:24', '2026-03-27 09:06:49', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculos`
--

DROP TABLE IF EXISTS `veiculos`;
CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `proprietario` varchar(100) DEFAULT NULL,
  `placa` varchar(10) NOT NULL DEFAULT '0000000000',
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `cor` varchar(30) NOT NULL,
  `ano` int(11) NOT NULL,
  `renavam` varchar(20) DEFAULT NULL,
  `chassis` varchar(30) DEFAULT NULL,
  `condicao_lataria` varchar(100) DEFAULT NULL,
  `condicao_pintura` varchar(100) DEFAULT NULL,
  `condicao_vidros` varchar(100) DEFAULT NULL,
  `condicao_lanternas` varchar(100) DEFAULT NULL,
  `condicao_estofamento` varchar(100) DEFAULT NULL,
  `seguro_veicular` varchar(100) DEFAULT NULL,
  `valor_fipe` decimal(10,2) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculos`
--

INSERT INTO `veiculos` (`id`, `cliente_id`, `empresa_id`, `proprietario`, `placa`, `marca`, `modelo`, `cor`, `ano`, `renavam`, `chassis`, `condicao_lataria`, `condicao_pintura`, `condicao_vidros`, `condicao_lanternas`, `condicao_estofamento`, `seguro_veicular`, `valor_fipe`, `observacoes`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'ABC1D23', 'Toyota', 'Corolla', 'Prata', 2022, NULL, NULL, 'Impecável', NULL, 'Sem trincas', NULL, NULL, NULL, NULL, 'Revisão de 40k km', 1, '2026-02-23 23:05:55', '2026-02-24 02:28:59'),
(2, 2, 1, NULL, 'FRT-001_', 'Mercedes-Benz', 'Sprinter', 'Branco', 2019, '', '', 'Risco na porta lateral', 'Desgastada pelo sol', 'ok', 'trincado', 'maanchas de gordura', 'Porto Seguro', NULL, '', 1, '2026-02-23 23:05:55', '2026-02-24 05:45:49'),
(3, 2, 1, NULL, 'FRT0A02', 'Mercedes-Benz', 'Sprinter', 'Branco', 2020, NULL, NULL, 'Pequeno amassado traseiro', NULL, NULL, NULL, NULL, 'Porto Seguro', NULL, NULL, 1, '2026-02-23 23:05:55', '2026-02-24 02:28:59'),
(4, 3, 1, NULL, 'IJK-9876', 'Fiat', 'Uno Mille', 'Vermelho', 2010, '7540738278', '', '', '', '', '', 'Banco do motorista rasgado', '', NULL, 'Veículo utilizado para entregas (trabalho)', 1, '2026-02-23 23:05:55', '2026-03-25 13:06:27'),
(5, 2, 1, NULL, 'IFX-362_', 'Chevrolet', 'cruze', 'azul', 2026, '00578523515', '', 'nova', 'nova', 'novo', 'novo', 'novo', 'sem seguro', NULL, '', 1, '2026-02-24 02:29:18', '2026-02-24 05:29:18'),
(6, 1, 1, 'Cliente', 'FRT0011', 'Chevrolet', 'Sprinter', 'azul', 2026, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-03 01:49:45', '2026-03-03 05:01:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `checklist_modelos`
--
ALTER TABLE `checklist_modelos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `checklist_modelo_itens`
--
ALTER TABLE `checklist_modelo_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checklist_modelo_id` (`checklist_modelo_id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documento` (`documento`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `compras_requisicoes`
--
ALTER TABLE `compras_requisicoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fornecedor` (`fornecedor_id`),
  ADD KEY `idx_status` (`status`);

--
-- Índices de tabela `compras_requisicoes_itens`
--
ALTER TABLE `compras_requisicoes_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requisicao_id` (`requisicao_id`);

--
-- Índices de tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `documentos_financeiros`
--
ALTER TABLE `documentos_financeiros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conta_receber_id` (`conta_receber_id`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `estoque_movimentacao`
--
ALTER TABLE `estoque_movimentacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produto_id` (`produto_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `financeiro_centros_custo`
--
ALTER TABLE `financeiro_centros_custo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `financeiro_movimentacao`
--
ALTER TABLE `financeiro_movimentacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_empresa` (`empresa_id`),
  ADD KEY `idx_data` (`data_movimentacao`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_origem` (`origem_tabela`,`origem_id`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `ordem_servicos`
--
ALTER TABLE `ordem_servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `veiculo_id` (`veiculo_id`);

--
-- Índices de tabela `ordem_servico_checklists`
--
ALTER TABLE `ordem_servico_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_servico_id` (`ordem_servico_id`);

--
-- Índices de tabela `ordem_servico_checklist_itens`
--
ALTER TABLE `ordem_servico_checklist_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `os_checklist_id` (`os_checklist_id`);

--
-- Índices de tabela `ordem_servico_fotos`
--
ALTER TABLE `ordem_servico_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_servico_id` (`ordem_servico_id`),
  ADD KEY `checklist_item_id` (`checklist_item_id`);

--
-- Índices de tabela `ordem_servico_itens`
--
ALTER TABLE `ordem_servico_itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_servico_id` (`ordem_servico_id`);

--
-- Índices de tabela `os_checklists`
--
ALTER TABLE `os_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ordem_servico_id` (`ordem_servico_id`);

--
-- Índices de tabela `os_checklists_fotos`
--
ALTER TABLE `os_checklists_fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `os_checklist_id` (`os_checklist_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `recebimentos_historico`
--
ALTER TABLE `recebimentos_historico`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `checklists`
--
ALTER TABLE `checklists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `checklist_modelos`
--
ALTER TABLE `checklist_modelos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `checklist_modelo_itens`
--
ALTER TABLE `checklist_modelo_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `compras_requisicoes`
--
ALTER TABLE `compras_requisicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `compras_requisicoes_itens`
--
ALTER TABLE `compras_requisicoes_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `contas_pagar`
--
ALTER TABLE `contas_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `documentos_financeiros`
--
ALTER TABLE `documentos_financeiros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estoque_movimentacao`
--
ALTER TABLE `estoque_movimentacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `financeiro_categorias`
--
ALTER TABLE `financeiro_categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de tabela `financeiro_centros_custo`
--
ALTER TABLE `financeiro_centros_custo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `financeiro_movimentacao`
--
ALTER TABLE `financeiro_movimentacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `ordem_servicos`
--
ALTER TABLE `ordem_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `ordem_servico_checklists`
--
ALTER TABLE `ordem_servico_checklists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `ordem_servico_checklist_itens`
--
ALTER TABLE `ordem_servico_checklist_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de tabela `ordem_servico_fotos`
--
ALTER TABLE `ordem_servico_fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `ordem_servico_itens`
--
ALTER TABLE `ordem_servico_itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `os_checklists`
--
ALTER TABLE `os_checklists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de tabela `os_checklists_fotos`
--
ALTER TABLE `os_checklists_fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `recebimentos_historico`
--
ALTER TABLE `recebimentos_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `checklist_modelo_itens`
--
ALTER TABLE `checklist_modelo_itens`
  ADD CONSTRAINT `checklist_modelo_itens_ibfk_1` FOREIGN KEY (`checklist_modelo_id`) REFERENCES `checklist_modelos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `compras_requisicoes_itens`
--
ALTER TABLE `compras_requisicoes_itens`
  ADD CONSTRAINT `compras_requisicoes_itens_ibfk_1` FOREIGN KEY (`requisicao_id`) REFERENCES `compras_requisicoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `documentos_financeiros`
--
ALTER TABLE `documentos_financeiros`
  ADD CONSTRAINT `documentos_financeiros_ibfk_1` FOREIGN KEY (`conta_receber_id`) REFERENCES `contas_receber` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `estoque_movimentacao`
--
ALTER TABLE `estoque_movimentacao`
  ADD CONSTRAINT `estoque_movimentacao_ibfk_1` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estoque_movimentacao_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD CONSTRAINT `fornecedores_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `funcionarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `funcionarios_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `ordem_servicos`
--
ALTER TABLE `ordem_servicos`
  ADD CONSTRAINT `ordem_servicos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `ordem_servicos_ibfk_2` FOREIGN KEY (`veiculo_id`) REFERENCES `veiculos` (`id`);

--
-- Restrições para tabelas `ordem_servico_checklists`
--
ALTER TABLE `ordem_servico_checklists`
  ADD CONSTRAINT `ordem_servico_checklists_ibfk_1` FOREIGN KEY (`ordem_servico_id`) REFERENCES `ordem_servicos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ordem_servico_checklist_itens`
--
ALTER TABLE `ordem_servico_checklist_itens`
  ADD CONSTRAINT `ordem_servico_checklist_itens_ibfk_1` FOREIGN KEY (`os_checklist_id`) REFERENCES `ordem_servico_checklists` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ordem_servico_fotos`
--
ALTER TABLE `ordem_servico_fotos`
  ADD CONSTRAINT `ordem_servico_fotos_ibfk_1` FOREIGN KEY (`ordem_servico_id`) REFERENCES `ordem_servicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ordem_servico_fotos_ibfk_2` FOREIGN KEY (`checklist_item_id`) REFERENCES `ordem_servico_checklist_itens` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `ordem_servico_itens`
--
ALTER TABLE `ordem_servico_itens`
  ADD CONSTRAINT `ordem_servico_itens_ibfk_1` FOREIGN KEY (`ordem_servico_id`) REFERENCES `ordem_servicos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `os_checklists_fotos`
--
ALTER TABLE `os_checklists_fotos`
  ADD CONSTRAINT `os_checklists_fotos_ibfk_1` FOREIGN KEY (`os_checklist_id`) REFERENCES `os_checklists` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `servicos`
--
ALTER TABLE `servicos`
  ADD CONSTRAINT `servicos_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);

--
-- Restrições para tabelas `veiculos`
--
ALTER TABLE `veiculos`
  ADD CONSTRAINT `veiculos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `veiculos_ibfk_2` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
