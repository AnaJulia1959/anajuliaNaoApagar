-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/06/2025 às 01:12
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
-- Banco de dados: `bd_vagas`
--
CREATE DATABASE IF NOT EXISTS `bd_vagas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bd_vagas`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `candidaturas`
--

DROP TABLE IF EXISTS `candidaturas`;
CREATE TABLE `candidaturas` (
  `id` int(11) NOT NULL,
  `vaga_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_candidatura` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tabela truncada antes do insert `candidaturas`
--

TRUNCATE TABLE `candidaturas`;
--
-- Despejando dados para a tabela `candidaturas`
--

INSERT INTO `candidaturas` (`id`, `vaga_id`, `usuario_id`, `data_candidatura`) VALUES
(1, 1, 2, '2023-10-15 09:30:00'),
(2, 1, 3, '2023-10-16 14:15:00'),
(3, 2, 2, '2023-10-17 11:20:00'),
(4, 3, 3, '2023-10-18 16:45:00'),
(5, 5, 2, '2023-10-19 10:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tabela truncada antes do insert `categorias`
--

TRUNCATE TABLE `categorias`;
--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`) VALUES
(4, 'Administração'),
(8, 'Atendimento ao Cliente'),
(1, 'Desenvolvimento Web'),
(2, 'Design Gráfico'),
(9, 'Finanças'),
(3, 'Marketing Digital'),
(5, 'Recursos Humanos'),
(10, 'Saúde'),
(6, 'Tecnologia da Informação'),
(7, 'Vendas');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `data_cadastro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tabela truncada antes do insert `usuarios`
--

TRUNCATE TABLE `usuarios`;
--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `foto`, `linkedin`, `tipo`, `data_cadastro`) VALUES
(1, 'Admin', 'admin@gmail.com', '1234', NULL, NULL, 'admin', '2025-06-03 19:43:03'),
(2, 'Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'admin', '2025-06-03 19:46:38'),
(3, 'Administrador', 'admin@empresa.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/admin.jpg', 'https://linkedin.com/in/admin', 'admin', '2025-06-03 19:49:59'),
(4, 'João Silva', 'joao@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/joao.jpg', 'https://linkedin.com/in/joaosilva', 'usuario', '2025-06-03 19:49:59'),
(5, 'Maria Santos', 'maria@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/maria.jpg', 'https://linkedin.com/in/mariasantos', 'usuario', '2025-06-03 19:49:59'),
(6, 'Carlos Oliveira', 'carlos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'usuario', '2025-06-03 19:49:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vagas`
--

DROP TABLE IF EXISTS `vagas`;
CREATE TABLE `vagas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `requisitos` text NOT NULL,
  `salario` varchar(50) DEFAULT NULL,
  `empresa` varchar(100) NOT NULL,
  `logo_empresa` varchar(255) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL,
  `contato_email` varchar(100) DEFAULT NULL,
  `contato_telefone` varchar(20) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `ativa` tinyint(1) DEFAULT 1,
  `data_publicacao` datetime DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tabela truncada antes do insert `vagas`
--

TRUNCATE TABLE `vagas`;
--
-- Despejando dados para a tabela `vagas`
--

INSERT INTO `vagas` (`id`, `titulo`, `descricao`, `requisitos`, `salario`, `empresa`, `logo_empresa`, `localizacao`, `contato_email`, `contato_telefone`, `categoria_id`, `ativa`, `data_publicacao`, `admin_id`) VALUES
(1, 'Desenvolvedor Front-end', 'Desenvolver interfaces web modernas utilizando React.js', 'Experiência com React, HTML5, CSS3, JavaScript. Conhecimento em TypeScript é diferencial.', 'R$ 6.500,00', 'Tech Solutions', 'uploads/techsolutions.png', 'São Paulo/SP - Remoto', 'rh@techsolutions.com', '(11) 98765-4321', 1, 1, '2025-06-03 19:49:59', 1),
(2, 'Designer UX/UI', 'Criar interfaces intuitivas e atraentes para produtos digitais', 'Domínio do Figma, Adobe XD. Experiência com pesquisa de usuários.', 'R$ 5.200,00', 'Digital Creative', 'uploads/digitalcreative.jpg', 'Rio de Janeiro/RJ - Híbrido', 'contato@digitalcreative.com.br', '(21) 99876-5432', 2, 1, '2025-06-03 19:49:59', 1),
(3, 'Analista de Marketing', 'Gerenciar campanhas digitais e métricas de performance', 'Conhecimento em Google Ads, Facebook Ads, Google Analytics.', 'R$ 4.800,00', 'MKT Experts', 'uploads/mktexperts.png', 'Belo Horizonte/MG', 'selecao@mktexperts.com', '(31) 98765-1234', 3, 1, '2025-06-03 19:49:59', 1),
(4, 'Assistente Administrativo', 'Suporte às operações administrativas da empresa', 'Pacote Office, organização, atendimento telefônico.', 'R$ 2.200,00', 'Office Total', 'uploads/officetotal.jpg', 'Curitiba/PR - Presencial', 'rh@officetotal.com', '(41) 3456-7890', 4, 0, '2025-06-03 19:49:59', 1),
(5, 'Gerente de Vendas', 'Liderar equipe comercial e atingir metas de vendas', 'Experiência comprovada em vendas, liderança de equipe.', 'R$ 8.000,00 + comissão', 'Vendas Forte', 'uploads/vendasforte.png', 'Porto Alegre/RS', 'gerencia@vendasforte.com.br', '(51) 98765-4321', 7, 1, '2025-06-03 19:49:59', 1),
(6, 'Analista de TI', 'Suporte técnico e manutenção de infraestrutura', 'Conhecimento em redes, Windows Server, virtualização.', 'R$ 5.500,00', 'IT Services', 'uploads/itservices.jpg', 'Salvador/BA', 'ti@itservices.com', '(71) 3344-5566', 6, 1, '2025-06-03 19:49:59', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `candidaturas`
--
ALTER TABLE `candidaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vaga_id` (`vaga_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `vagas`
--
ALTER TABLE `vagas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `candidaturas`
--
ALTER TABLE `candidaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `vagas`
--
ALTER TABLE `vagas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `candidaturas`
--
ALTER TABLE `candidaturas`
  ADD CONSTRAINT `candidaturas_ibfk_1` FOREIGN KEY (`vaga_id`) REFERENCES `vagas` (`id`),
  ADD CONSTRAINT `candidaturas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `vagas`
--
ALTER TABLE `vagas`
  ADD CONSTRAINT `vagas_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `vagas_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
