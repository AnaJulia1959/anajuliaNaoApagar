-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24/06/2025 às 03:16
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
-- Banco de dados: `sistema_veiculos`
--
CREATE DATABASE IF NOT EXISTS `sistema_veiculos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_veiculos`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'Caminhões'),
(2, 'Camionetes'),
(5, 'Motos'),
(3, 'SUV'),
(4, 'Veículos de Passeio');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `created_at`) VALUES
(1, 'Admin', 'admin@teste.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-06-23 23:31:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `veiculo`
--

DROP TABLE IF EXISTS `veiculo`;
CREATE TABLE IF NOT EXISTS `veiculo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placa` varchar(10) NOT NULL,
  `cor` varchar(30) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `ano` int(11) NOT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `quilometragem` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  KEY `id_categoria` (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `veiculo`
--

INSERT INTO `veiculo` (`id`, `placa`, `cor`, `modelo`, `marca`, `ano`, `preco`, `quilometragem`, `descricao`, `imagem`, `id_categoria`, `created_at`) VALUES
(1, 'ABC1D23', 'Prata', 'Toro', 'Fiat', 2022, 120000.00, 15000, 'Camionete completa, 4x4, diesel', 'https://conteudo.imguol.com.br/c/entretenimento/49/2021/09/21/fiat-toro-2022-chrome-edition-1632247547848_v2_4x3.jpg', 2, '2025-06-23 23:31:50'),
(2, 'DEF4G56', 'Branco', 'Onix', 'Chevrolet', 2021, 65000.00, 25000, 'Carro econômico, completo, 1.0 turbo', 'https://www.webmotors.com.br/imagens/prod/348336/CHEVROLET_ONIX_1.0_FLEX_PLUS_LT_MANUAL_34833617240721725.webp?s=fill&w=170&h=125&t=true', 4, '2025-06-23 23:31:50'),
(3, 'GHI7J89', 'Preto', 'X5', 'BMW', 2023, 450000.00, 5000, 'SUV de luxo, motor 3.0, teto solar', 'https://s3.ecompletocarros.dev/images/lojas/285/veiculos/81833/veiculoInfoVeiculoImagesMobile/vehicle_image_1637110825_d41d8cd98f00b204e9800998ecf8427e.jpeg', 3, '2025-06-23 23:31:50'),
(4, 'JKL0M12', 'Vermelho', 'Biz', 'Honda', 2022, 15000.00, 8000, 'Moto econômica, baixo consumo', 'https://www.honda.com.br/motos/sites/hda/files/2024-08/imagem-home-honda-biz-125-es-lateral-vermelho.webp', 5, '2025-06-23 23:31:50'),
(5, 'MNO3P45', 'Azul', 'Accord', 'Honda', 2020, 180000.00, 30000, 'Sedan de luxo, completo', 'https://s3.amazonaws.com/di-honda-enrollment/2020-accord-sedan/model-image-2020-accord-sedan-front.png', 4, '2025-06-23 23:31:50');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `veiculo`
--
ALTER TABLE `veiculo`
  ADD CONSTRAINT `veiculo_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
