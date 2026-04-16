-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/03/2026 às 04:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

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

-- --------------------------------------------------------

--
-- Estrutura para tabela `checklists`
--

DROP TABLE IF EXISTS `checklists`;
CREATE TABLE `checklists` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `categoria` varchar(50) DEFAULT 'GERAL',
  `ordem_exibicao` int(11) DEFAULT 0,
  `obrigatorio` tinyint(1) DEFAULT 0,
  `tipo` enum('entrada','servico') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `checklists`
--

REPLACE INTO `checklists` (`id`, `descricao`, `categoria`, `ordem_exibicao`, `obrigatorio`, `tipo`) VALUES
(1, 'Nível de Combustível (marcado no painel)', 'INTERIOR', 1, 0, 'entrada'),
(2, 'Quilometragem Atual', 'GERAL', 0, 0, 'entrada'),
(3, 'Estado da Lataria (riscos, amassados)', 'GERAL', 0, 0, 'entrada'),
(4, 'Integridade dos Vidros e Parabrisa', 'GERAL', 0, 0, 'entrada'),
(5, 'Estado dos Pneus e Rodas (incluindo estepe)', 'GERAL', 0, 0, 'entrada'),
(6, 'Presença de Estepe, Macaco e Chave de Roda', 'GERAL', 0, 0, 'entrada'),
(7, 'Presença de Triângulo e Extintor', 'GERAL', 0, 0, 'entrada'),
(8, 'Funcionamento das Luzes Externas (Faróis/Lanternas)', 'GERAL', 0, 0, 'entrada'),
(9, 'Estado dos Estofados e Tapetes', 'GERAL', 0, 0, 'entrada'),
(10, 'Rádio/Central Multimídia e Som', 'GERAL', 0, 0, 'entrada'),
(11, 'Pertences Pessoais Deixados no Veículo', 'GERAL', 0, 0, 'entrada'),
(12, 'Funcionamento do Ar-Condicionado', 'GERAL', 0, 0, 'entrada'),
(13, 'Luzes de Advertência no Painel (ABS, Airbag, Injeção)', 'GERAL', 0, 0, 'entrada'),
(14, 'Nível e Condição do Óleo do Motor', 'GERAL', 0, 0, 'servico'),
(15, 'Nível e Aditivo do Sistema de Arrefecimento', 'GERAL', 0, 0, 'servico'),
(16, 'Estado das Pastilhas e Discos de Freio', 'GERAL', 0, 0, 'servico'),
(17, 'Fluido de Freio (Nível e Contaminação)', 'GERAL', 0, 0, 'servico'),
(18, 'Estado das Correias (Dentada e Acessórios)', 'GERAL', 0, 0, 'servico'),
(19, 'Condição das Velas e Cabos de Ignição', 'GERAL', 0, 0, 'servico'),
(20, 'Filtro de Ar do Motor', 'GERAL', 0, 0, 'servico'),
(21, 'Filtro de Cabine (Ar-Condicionado)', 'GERAL', 0, 0, 'servico'),
(22, 'Bateria (Teste de Carga e Terminais)', 'GERAL', 0, 0, 'servico'),
(23, 'Folgas em Suspensão (Pivôs, Buchas, Batentes)', 'GERAL', 0, 0, 'servico'),
(24, 'Estado dos Amortecedores', 'GERAL', 0, 0, 'servico'),
(25, 'Estado dos Coifas de Homocinética', 'GERAL', 0, 0, 'servico'),
(26, 'Escapamento e Suportes', 'GERAL', 0, 0, 'servico'),
(27, 'Vazamentos de Óleo ou Fluidos por Baixo', 'GERAL', 0, 0, 'servico'),
(28, 'Calibragem de Pneus e Reset de Monitoramento', 'GERAL', 0, 0, 'servico'),
(29, 'Torque das Rodas (Aperto final)', 'GERAL', 0, 0, 'servico'),
(30, 'Teste de Rodagem (Barulhos e Estabilidade)', 'GERAL', 0, 0, 'servico'),
(31, 'Curso e Peso do Pedal de Embreagem', 'GERAL', 0, 0, 'servico'),
(32, 'Nível/Estado do Óleo do Câmbio (Manual/Automático)', 'GERAL', 0, 0, 'servico'),
(33, 'Engate de Marchas e Ruídos de Transmissão', 'GERAL', 0, 0, 'servico'),
(34, 'Integridade das Juntas Homocinéticas e Coifas', 'GERAL', 0, 0, 'servico'),
(35, 'Leitura de Códigos de Falha (Scanner OBD2)', 'GERAL', 0, 0, 'servico'),
(36, 'Funcionamento de Vidros, Travas e Alarmes', 'GERAL', 0, 0, 'servico'),
(37, 'Estado das Palhetas do Limpador e Esguicho', 'GERAL', 0, 0, 'servico'),
(38, 'Limpeza de Polos e Teste de Alternador', 'GERAL', 0, 0, 'servico'),
(39, 'Funcionamento de Buzina e Luzes de Cortesia', 'GERAL', 0, 0, 'servico'),
(40, 'Limpeza do Corpo de Borboleta (TBI)', 'GERAL', 0, 0, 'servico'),
(41, 'Teste de Estanqueidade e Vazão dos Bicos', 'GERAL', 0, 0, 'servico'),
(42, 'Estado do Filtro de Combustível', 'GERAL', 0, 0, 'servico'),
(43, 'Integridade das Mangueiras de Combustível', 'GERAL', 0, 0, 'servico'),
(44, 'Folga em Caixas de Direção e Terminais', 'GERAL', 0, 0, 'servico'),
(45, 'Nível e Cor do Fluido de Direção Hidráulica', 'GERAL', 0, 0, 'servico'),
(46, 'Verificação de Alinhamento e Balanceamento', 'GERAL', 0, 0, 'servico'),
(47, 'Estado das Buchas de Eixo Traseiro', 'GERAL', 0, 0, 'servico'),
(48, 'Folga nos Rolamentos de Roda', 'GERAL', 0, 0, 'servico'),
(49, 'Funcionamento do Freio de Estacionamento', 'GERAL', 0, 0, 'servico'),
(50, 'Estado dos Cintos de Segurança', 'GERAL', 0, 0, 'servico'),
(51, 'Verificação de Data de Validade do Pneu (DOT)', 'GERAL', 0, 0, 'servico'),
(52, 'Lubrificação de Dobradiças e Fechaduras', 'GERAL', 0, 0, 'servico'),
(53, 'Lavagem de Motor e Higienização (Se contratado)', 'GERAL', 0, 0, 'servico');

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

REPLACE INTO `checklist_modelos` (`id`, `empresa_id`, `nome`, `descricao`, `ativo`, `created_at`) VALUES
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

REPLACE INTO `checklist_modelo_itens` (`id`, `checklist_modelo_id`, `descricao`, `obrigatorio`, `ordem`) VALUES
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

REPLACE INTO `clientes` (`id`, `empresa_id`, `tipo_pessoa`, `nome_razao`, `documento`, `email`, `telefone`, `celular`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `score_historico`, `score_perfil`, `score_relacionamento`, `score_documentacao`, `score_total`, `classificacao`, `observacoes_financeiras`, `ativo`, `created_at`) VALUES
(1, 1, 'F', 'Ricardo Almeida Santos', '55331858015', 'ricardo@email.com', '', '54996809855', '', '', '', '', '', '', '', 40, 20, 20, 20, 100, 'Ouro', 'Cliente antigo, sempre paga antecipado. Carro de passeio.', 1, '2026-02-23 23:05:55'),
(2, 1, 'F', 'Transportes Rapidez Ltda', '55331858015', 'contato@rapidez.com', '', '5432211000', '95200151', 'Rua Quinze de Novembro', '311', 'casa', 'Centro', 'Vacaria', 'RS', 30, 15, 15, 10, 70, 'Prata', 'Empresa de frota. Costuma parcelar em 3x no boleto.', 1, '2026-02-23 23:05:55'),
(3, 1, 'F', 'Marcos Oliveira da Silva', '98765432100', 'marcos@email.com', '', '54988776655', '95200151', 'Rua Quinze de Novembro', '001', 'casa', 'Centro', 'Vacaria', 'RS', 15, 5, 0, 10, 30, 'Bronze', 'Primeiro serviço. Já teve histórico de atraso em outras oficinas da região.', 1, '2026-02-23 23:05:55');

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
  `data_vencimento` date NOT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status` enum('pendente','paga','vencida','cancelada') DEFAULT 'pendente',
  `forma_pagamento` enum('dinheiro','cartao_credito','cartao_debito','pix','boleto','duplicata','promissoria') NOT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

REPLACE INTO `empresas` (`id`, `razao_social`, `nome_fantasia`, `cnpj`, `ie`, `email`, `telefone`, `cep`, `logradouro`, `numero`, `bairro`, `cidade`, `estado`, `logo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Campos Auto Service - MEI', 'Campos Auto Service', '00.000.000/0001-00', NULL, NULL, NULL, NULL, 'Av. Presidente Juscelino Kubitscheck', '7417', 'Minuano', 'Vacaria', 'RS', NULL, '2026-02-23 16:47:35', '2026-03-08 17:10:10', NULL);

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

REPLACE INTO `estoque_movimentacao` (`id`, `produto_id`, `empresa_id`, `tipo`, `quantidade`, `origem`, `data_movimento`, `observacao`) VALUES
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
(14, 2, 1, 'E', 4, 'Ajuste_Manual', '2026-03-25 22:22:05', 'ajuste por doação');

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

REPLACE INTO `fornecedores` (`id`, `empresa_id`, `tipo_pessoa`, `nome_razao`, `nome_fantasia`, `documento`, `ie_rg`, `email`, `telefone`, `celular`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `categoria`, `especialidade`, `observacoes`, `ativo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'F', 'RAFAEL DE CAMPOS', '', '111.222.333-44', NULL, '', '', '', '95200-151', 'Rua Quinze de Novembro', '', '', 'Centro', 'Vacaria', 'RS', 'Ambos', 'MECANICA', '', 1, '2026-02-24 00:29:59', '2026-02-24 00:29:59', NULL);

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

REPLACE INTO `funcionarios` (`id`, `nome`, `email`, `usuario_id`, `matricula`, `empresa_id`, `cargo`, `cpf`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `rg`, `data_nascimento`, `telefone`, `celular`, `comissao_servico`, `comissao_produto`, `data_admissao`, `data_demissao`, `status`, `observacoes`, `ativo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ricardo Oliveira', 'admin@mestre.com', 1, '', 1, 'Administrador', '11111111111', '', '', '', NULL, '', '', '', '', NULL, '', '', 0.00, 0.00, '2023-01-01', NULL, 'trabalhando', NULL, 1, '2026-02-23 16:47:35', '2026-03-24 22:51:19', NULL),
(2, 'João Silva da Graxa', 'admin@mestre.com', NULL, '', 1, 'Caixa', '22222222222', '', '', '', NULL, '', '', '', '', NULL, '', '', 0.00, 0.00, '2023-05-10', NULL, 'trabalhando', NULL, 1, '2026-02-23 16:47:35', '2026-03-24 22:42:28', NULL),
(3, 'GUSTAVO DORNELES MARTNEZ', 'gustavo.dmartinez@gmail.com', 6, '123', 1, 'Administrador', '55331858015', '95200-151', 'Rua Quinze de Novembro', '311', NULL, 'Centro', 'Vacaria', 'RS', '11111', NULL, '', '(54) 99680-9855', 20.00, 20.00, '2024-01-01', NULL, 'trabalhando', NULL, 1, '2026-03-02 12:41:38', '2026-03-24 23:19:38', NULL);

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

REPLACE INTO `ordem_servicos` (`id`, `empresa_id`, `numero_os`, `cliente_id`, `usuario_id`, `tecnico_id`, `veiculo_id`, `status`, `data_abertura`, `data_aprovacao`, `data_fechamento`, `km_entrada`, `descricao_problema`, `diagnostico`, `observacoes`, `valor_servicos`, `valor_produtos`, `desconto`, `acrescimo`, `valor_total`, `criado_por`, `updated_at`, `created_at`) VALUES
(1, 1, '20260001', 1, NULL, NULL, 1, 'finalizada', '2026-02-25 12:43:28', NULL, '2026-03-03 04:15:25', 0, 'Carro com soltando fumça azul pelo escapamento', NULL, NULL, 0.00, 35.00, 0.00, 0.00, 35.00, NULL, '2026-03-03 04:15:25', '2026-02-25 18:43:27'),
(2, 1, '', 1, NULL, NULL, 1, 'cancelada', '2026-03-02 23:37:10', NULL, NULL, NULL, NULL, NULL, NULL, 450.00, 0.00, 0.00, 0.00, 450.00, NULL, '2026-03-03 04:13:10', '2026-03-03 05:37:10'),
(3, 1, '2026004', 2, NULL, 3, 2, 'finalizada', '2026-03-03 10:38:38', NULL, '2026-03-03 18:01:50', NULL, NULL, NULL, NULL, 420.00, 35.00, 0.00, 0.00, 455.00, NULL, '2026-03-03 18:57:28', '2026-03-03 16:38:38'),
(4, 1, '', 3, NULL, NULL, 4, 'aprovada', '2026-03-03 11:09:28', NULL, NULL, NULL, NULL, NULL, NULL, 600.00, 0.00, 0.00, 0.00, 600.00, NULL, '2026-03-25 22:49:17', '2026-03-03 17:09:28'),
(5, 1, '', 2, NULL, NULL, 5, 'finalizada', '2026-03-03 11:14:11', NULL, '2026-03-03 20:55:36', NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-03 20:55:36', '2026-03-03 17:14:11'),
(6, 1, '', 2, NULL, NULL, 5, 'cancelada', '2026-03-03 11:14:59', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-03 14:19:08', '2026-03-03 17:14:59'),
(7, 1, '', 2, NULL, NULL, 2, 'finalizada', '2026-03-03 11:19:47', NULL, '2026-03-07 21:39:06', NULL, 'revisao dos freios', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-03-07 21:39:06', '2026-03-03 17:19:47'),
(8, 1, '20260002', 1, NULL, 3, 1, 'finalizada', '2026-03-03 16:48:58', NULL, '2026-03-03 20:56:45', NULL, '', NULL, NULL, 450.00, 0.00, 0.00, 0.00, 450.00, 1, '2026-03-03 20:56:45', '2026-03-03 22:48:58'),
(10, 1, '20260003', 1, NULL, 3, 4, 'finalizada', '2026-03-03 19:59:48', NULL, '2026-03-08 20:56:54', 90000, 'troca de oleo', NULL, NULL, 470.00, 35.00, 0.00, 0.00, 505.00, 1, '2026-03-08 20:57:45', '2026-03-03 22:59:48'),
(11, 1, '2026005', 3, NULL, 1, 1, 'finalizada', '2026-03-06 01:14:45', NULL, '2026-03-07 21:43:14', 0, 'troca de aneis', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-07 21:43:14', '2026-03-06 04:14:45'),
(12, 1, '2026006', 2, NULL, 2, 6, 'cancelada', '2026-03-18 10:02:11', '2026-03-18 23:36:51', NULL, 120000, 'trocar oleo e revisar correia dentada', NULL, NULL, 150.00, 0.00, 0.00, 0.00, 150.00, 1, '2026-03-19 00:44:22', '2026-03-18 13:02:11'),
(13, 1, '2026007', 3, NULL, 3, 1, 'em_execucao', '2026-03-20 09:58:38', '2026-03-20 13:20:41', NULL, 0, 'revisao', NULL, NULL, 150.00, 0.00, 0.00, 0.00, 150.00, 1, '2026-03-20 13:20:41', '2026-03-20 12:58:38'),
(14, 1, '2026008', 3, NULL, 2, 2, 'orcamento', '2026-03-20 13:20:14', NULL, NULL, 1000, 'revisar amortecedor', NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 1, '2026-03-20 13:20:14', '2026-03-20 16:20:14');

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

REPLACE INTO `ordem_servico_checklists` (`id`, `ordem_servico_id`, `checklist_modelo_id`, `status`, `iniciado_em`, `concluido_em`) VALUES
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

REPLACE INTO `ordem_servico_checklist_itens` (`id`, `os_checklist_id`, `descricao`, `obrigatorio`, `status`, `observacao`, `foto_path`, `executado_por`, `executado_at`) VALUES
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

REPLACE INTO `ordem_servico_fotos` (`id`, `ordem_servico_id`, `checklist_item_id`, `tipo`, `caminho_arquivo`, `descricao`, `tamanho_kb`, `criado_por`, `created_at`, `updated_at`) VALUES
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

REPLACE INTO `ordem_servico_itens` (`id`, `ordem_servico_id`, `tipo`, `item_id`, `descricao`, `quantidade`, `valor_unitario`, `custo_unitario`, `subtotal`, `margem`, `mecanico_id`, `updated_at`, `created_at`) VALUES
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
(18, 4, 'servico', 1, 'Substituição de óleo lubrificante e filtros de ar/óleo.', 1.00, 150.00, 0.00, 150.00, NULL, NULL, '2026-03-20 16:40:32', '2026-03-20 16:40:32');

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

REPLACE INTO `os_checklists` (`id`, `ordem_servico_id`, `descricao`, `categoria`, `status`, `observacao`, `foto_evidencia`, `status_anterior`, `tipo`, `created_at`, `updated_at`) VALUES
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
(46, 4, 'Quilometragem Atual', NULL, 'pendente', '', NULL, 'pendente', 'entrada', '2026-03-20 19:53:37', '2026-03-26 02:16:10');

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

REPLACE INTO `produtos` (`id`, `empresa_id`, `codigo_barras`, `nome`, `descricao`, `marca`, `preco_custo`, `preco_venda`, `estoque_minimo`, `estoque_atual`, `unidade_medida`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Óleo 5W30 Sintético', 'Óleo 5W30 Sintético', 'Lubrax', 25.00, 48.00, 20, 50, 'LITRO', 1, '2026-02-24 13:32:45', '2026-02-25 17:10:00'),
(2, 1, NULL, 'Filtro de Óleo PSL55', 'Filtro de Óleo PSL55', 'Tecfil', 12.50, 35.00, 5, 8, 'UN', 1, '2026-02-24 13:32:45', '2026-03-26 01:22:05'),
(3, 1, NULL, 'Pastilha de Freio Dianteira', 'Pastilha de Freio Dianteira', 'Fras-le', 85.00, 160.00, 2, 4, 'PAR', 1, '2026-02-24 13:32:45', '2026-03-19 02:36:14'),
(4, 1, NULL, 'Lâmpada H7 12V', 'Lâmpada H7 12V', 'Osram', 15.00, 45.00, 10, 8, 'UN', 1, '2026-02-24 13:32:45', '2026-02-25 17:10:00');

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

REPLACE INTO `servicos` (`id`, `empresa_id`, `nome`, `descricao`, `unidade`, `preco_custo`, `preco_venda`, `tempo_dias`, `tempo_horas`, `tempo_minutos`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Troca de Óleo e Filtros', 'Substituição de óleo lubrificante e filtros de ar/óleo.', 'fixo', 0.00, 150.00, 0, 1, 30, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(2, 1, 'Revisão de Suspensão', 'Troca de amortecedores, batentes e buchas.', 'fixo', 0.00, 450.00, 0, 5, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(3, 1, 'Retífica de Cabeçote (Terceirizado)', 'Serviço enviado para retífica externa. Inclui desmonte e monte.', 'fixo', 600.00, 950.00, 2, 0, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(4, 1, 'Diagnóstico e Reparo de Injeção', 'Uso de Scanner e limpeza de bicos.', 'fixo', 50.00, 320.00, 0, 3, 45, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(5, 1, 'Pintura de Parachoque', 'Serviço realizado em parceria com funilaria externa.', 'fixo', 250.00, 450.00, 3, 0, 0, 1, '2026-02-24 12:15:45', '2026-02-25 17:11:32'),
(6, 1, 'Guincho Barney - Perímetro Urbano', 'Reboque de um veículo dentro do perímetro urbnao', 'Km', 0.00, 100.00, 0, 0, 0, 1, '2026-03-03 03:07:07', '2026-03-03 06:07:07');

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

REPLACE INTO `usuarios` (`id`, `empresa_id`, `nome`, `email`, `senha`, `nivel_acesso`, `status`, `ultimo_login`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '', 'admin@mestre.com', '$2y$10$mwjsRzLcCHo4MLrAGarM4.ertYiw/n.mXVAzfMxWLpcRzg.149U0e', 'admin', 'ativo', NULL, '2026-02-23 16:47:35', '2026-03-24 22:51:19', NULL),
(6, 1, '', 'gustavo.dmartinez@gmail.com', '$2y$10$wK.VSDkp7T.EELslWPglV.TJSKvzS/bxswZw.M0vjMUnXm42AVity', 'admin', 'ativo', NULL, '2026-03-24 21:21:45', '2026-03-24 23:19:38', NULL);

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

REPLACE INTO `veiculos` (`id`, `cliente_id`, `empresa_id`, `proprietario`, `placa`, `marca`, `modelo`, `cor`, `ano`, `renavam`, `chassis`, `condicao_lataria`, `condicao_pintura`, `condicao_vidros`, `condicao_lanternas`, `condicao_estofamento`, `seguro_veicular`, `valor_fipe`, `observacoes`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'ABC1D23', 'Toyota', 'Corolla', 'Prata', 2022, NULL, NULL, 'Impecável', NULL, 'Sem trincas', NULL, NULL, NULL, NULL, 'Revisão de 40k km', 1, '2026-02-23 23:05:55', '2026-02-24 02:28:59'),
(2, 2, 1, NULL, 'FRT-001_', 'Mercedes-Benz', 'Sprinter', 'Branco', 2019, '', '', 'Risco na porta lateral', 'Desgastada pelo sol', 'ok', 'trincado', 'maanchas de gordura', 'Porto Seguro', NULL, '', 1, '2026-02-23 23:05:55', '2026-02-24 05:45:49'),
(3, 2, 1, NULL, 'FRT0A02', 'Mercedes-Benz', 'Sprinter', 'Branco', 2020, NULL, NULL, 'Pequeno amassado traseiro', NULL, NULL, NULL, NULL, 'Porto Seguro', NULL, NULL, 1, '2026-02-23 23:05:55', '2026-02-24 02:28:59'),
(4, 3, 1, NULL, 'IJK9876', 'Fiat', 'Uno Mille', 'Vermelho', 2010, NULL, NULL, NULL, NULL, NULL, NULL, 'Banco do motorista rasgado', NULL, NULL, 'Veículo utilizado para entregas (trabalho)', 1, '2026-02-23 23:05:55', '2026-02-24 02:28:59'),
(5, 2, 1, NULL, 'IFX-362_', 'Chevrolet', 'cruze', 'azul', 2026, '00578523515', '', 'nova', 'nova', 'novo', 'novo', 'novo', 'sem seguro', NULL, '', 1, '2026-02-24 02:29:18', '2026-02-24 05:29:18'),
(6, 1, 1, 'Cliente', 'FRT0011', 'Chevrolet', 'Sprinter', 'azul', 2026, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-03-03 01:49:45', '2026-03-03 05:01:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `checklists`
--
ALTER TABLE `checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_checklist_categoria` (`categoria`);

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
-- AUTO_INCREMENT de tabela `contas_receber`
--
ALTER TABLE `contas_receber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `os_checklists`
--
ALTER TABLE `os_checklists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
