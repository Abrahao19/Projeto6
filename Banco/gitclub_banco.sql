-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/10/2026 às 15:01
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
-- Banco de dados: `gitclub_banco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios_post`
--

CREATE TABLE `comentarios_post` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas_post`
--

CREATE TABLE `curtidas_post` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil`
--

CREATE TABLE `perfil` (
  `usuario_id` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `visibilidade` varchar(20) DEFAULT 'publico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil`
--

INSERT INTO `perfil` (`usuario_id`, `foto`, `bio`, `github`, `visibilidade`) VALUES
(4, '', '', '', 'privado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'wgwgwr', 'sgwgwgwg@bebdvhwbv', '$2y$10$ZPBWfdJAZ/qmNrYd7s3g4elbxpchrwAFFkHSvnTEWAgQL5Zw6BH.O'),
(2, 'dadasdasdas', 'dacavav@bshvdbvhs', '$2y$10$6AbffYpw3WYqWHb3Pqvl7uUkafQB2d6byHFSYoAUv3EGz5D0jf7pa'),
(3, 'sbsb', 'sdvsd@bwibv', '$2y$10$pKZADidY71ef3Y0j9SQXj.iZ94vtWB93N0XhoE3K6wKxyaLU/96w2'),
(4, 'jeff', 'jeff@etc.com', '$2y$10$Z0oCz0EI/v7Ym7ptTB/NFeoEM4bsclQMH9aby79ZkOIDrQduEsCtC'),
(5, 'jj', 'jj@etc.com', '$2y$10$7ffOp7pW/sbw.Om7QW82iuhS90p7k5P5EcbfFpHpLv1C5RAzrNuFW'),
(6, 'cj', 'cj@etc.com', '$2y$10$XHcUiujhNtGyFAzqeooObuK2JhHs7IzFvBivzDb/hntBv/SjeACAy');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios_post`
--
ALTER TABLE `comentarios_post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Índices de tabela `curtidas_post`
--
ALTER TABLE `curtidas_post`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `curtida_unica` (`post_id`,`usuario_id`);

--
-- Índices de tabela `perfil`
--.

ALTER TABLE `perfil`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Índices de tabela `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios_post`
--
ALTER TABLE `comentarios_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curtidas_post`
--
ALTER TABLE `curtidas_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios_post`
--
ALTER TABLE `comentarios_post`
  ADD CONSTRAINT `comentarios_post_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `curtidas_post`
--
ALTER TABLE `curtidas_post`
  ADD CONSTRAINT `curtidas_post_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Restrições para tabelas `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);
COMMIT;


-- Dia 20/10 paramos aqui no server linux na etec


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
