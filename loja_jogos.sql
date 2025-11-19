-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13/10/2025 às 23:31
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_jogos`
--
CREATE DATABASE IF NOT EXISTS `loja_jogos` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `loja_jogos`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `jogos`
--

DROP TABLE IF EXISTS `jogos`;
CREATE TABLE `jogos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `jogos`
--

INSERT INTO `jogos` (`id`, `nome`, `preco`, `imagem`, `descricao`) VALUES
(1, 'Celeste', 49.90, 'celeste.jpg', 'Um jogo de plataforma desafiador e emocionante.'),
(2, 'Elden Ring', 249.90, 'elden.jpg', 'Um RPG de ação em um vasto mundo aberto.'),
(3, 'FC 25', 349.90, 'fc25.jpg', 'O mais novo simulador de futebol da EA Sports.'),
(4, 'God of War', 199.90, 'god.jpg', 'Kratos e Atreus embarcam em uma jornada épica.'),
(5, 'GTA V', 99.90, 'gta5.png', 'Um clássico de mundo aberto cheio de ação e liberdade.'),
(6, 'Hollow Knight', 39.90, 'hollow.jpg', 'Explore o misterioso reino de Hallownest.'),
(7, 'Minecraft', 79.90, 'minecraft.png', 'Construa, explore e sobreviva em um mundo de blocos.'),
(8, 'Spider-Man', 229.90, 'spider.jpg', 'Aventure-se por Nova York como o herói aracnídeo.'),
(9, 'Stardew Valley', 37.90, 'stardew.jpg', 'Construa sua fazenda e viva uma vida tranquila.'),
(10, 'Undertale', 24.90, 'undertale.jpg', 'Um RPG inovador com escolhas morais únicas.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Felipe Cavalcante da Costa', 'a@a', '$2y$10$aN9X5ZTUTrtzd0lnxSNidOunWgrTVCRUJK6N65k7bU5.lQjA64Leq');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `jogos`
--
ALTER TABLE `jogos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `jogos`
--
ALTER TABLE `jogos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
