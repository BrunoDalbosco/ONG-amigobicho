-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 24-Abr-2025 às 22:30
-- Versão do servidor: 9.2.0
-- versão do PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ong_amigo_bicho`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `adocoes`
--

CREATE TABLE IF NOT EXISTS `adocoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_animal` int NOT NULL,
  `id_usuario` int NOT NULL,
  `data_solicitacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pendente','aprovada','rejeitada') DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `id_animal` (`id_animal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `adocoes`
--

INSERT INTO `adocoes` (`id`, `id_animal`, `id_usuario`, `data_solicitacao`, `status`) VALUES
(1, 2, 1, '2025-04-24 15:47:16', 'pendente'),
(2, 3, 1, '2025-04-24 15:47:57', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `animais`
--

DROP TABLE IF EXISTS `animais`;
CREATE TABLE IF NOT EXISTS `animais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raça` varchar(100) DEFAULT NULL,
  `idade` int DEFAULT NULL,
  `descricao` text,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('disponivel','adotado') DEFAULT 'disponivel',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `animais`
--

INSERT INTO `animais` (`id`, `nome`, `especie`, `raça`, `idade`, `descricao`, `foto`, `status`, `data_cadastro`) VALUES
(2, 'spyke', 'Cachorro', 'sds', 11, 'dadasdaweqw', 'img_680aacb3b1e15.png', 'adotado', '2025-04-24 14:34:30'),
(3, 'jhon', 'Cachorro', 'Husky siberiano', 10, '', 'img_680aacf07385a.png', 'disponivel', '2025-04-24 14:35:11');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `tipo` enum('admin','comum') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'comum',
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `nome`, `email`, `telefone`, `tipo`, `data_cadastro`) VALUES
(1, 'qqq', '$2y$10$tctdK9fnQxmJcIdxbVTASOqhDBVDUee2y2Vu36y9naPvsc5acJlPu', 'bruno', 'aaa@hotmail.com', '(99) 99999-9999', 'comum', '2025-04-24 15:23:43'),
(2, 'admin', '$2y$10$fN7erNpxxTtZrgVaS0vYz.NpqfZtiIxuWi1HmO7fFvtYvfB/gE0/6', 'joao', 'admin@cod3r.com.br', '(xx) xxxxx-xxxx', 'admin', '2025-04-24 19:46:11'),
(5, 'comum', '$2y$10$pdmqzAJO48KvPaYoLqXf.em8DHJopS3soni/NGmiakLUNVMhtsbp2', 'bruno', 'de547f4369@emailabox.pro', '(99) 99999-9999', 'comum', '2025-04-24 21:58:00');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `adocoes`
--
ALTER TABLE `adocoes`
  ADD CONSTRAINT `adocoes_ibfk_1` FOREIGN KEY (`id_animal`) REFERENCES `animais` (`id`),
  ADD CONSTRAINT `adocoes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
