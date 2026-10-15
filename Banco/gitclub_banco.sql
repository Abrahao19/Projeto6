-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Out-2026 às 21:16
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

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
-- Estrutura da tabela `perfil`
--

CREATE TABLE `perfil` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `visibilidade` enum('publico','privado') DEFAULT 'publico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`id`, `usuario_id`, `foto`, `bio`, `github`, `visibilidade`) VALUES
(1, 9, '../uploads/perfis/121928877f748a8c02644de646aa1487.webp', 'sou um cara legal', '', 'publico'),
(11, 10, '../uploads/perfis/e454bb309335f173b2e165066432b408.jpg', '', '', 'publico'),
(12, 8, '../uploads/perfis/0b1d6a4b1957ee93d58f0e54ec0a4433.webp', '', '', 'publico');

-- --------------------------------------------------------

--
-- Estrutura da tabela `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `conteudo` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `post`
--

INSERT INTO `post` (`id`, `usuario_id`, `conteudo`, `data_criacao`) VALUES
(1, 10, 'asdsasd', '2026-10-15 18:19:15'),
(2, 10, 'oi', '2026-10-15 18:25:11'),
(3, 10, 'oi2', '2026-10-15 18:30:34'),
(4, 8, 'asd', '2026-10-15 18:56:25'),
(5, 8, 'oi sou eu dnv', '2026-10-15 19:02:19'),
(6, 10, 'asdad', '2026-10-15 19:05:48'),
(7, 10, 'alguma coisa', '2026-10-15 19:09:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'sda', 'sda@sda', '$2y$10$ksr5Y8aCOMrRRU/iYQYCYu09t7SeAwTV33qcGOe/oq0QzNmiEtnRi'),
(2, 'sda', 'sda@sda', '$2y$10$mfTnDknJoH/1VQPVXw.kDuS2K4Zxi.eTA/UefCo99d5DJJ2VtJ3Li'),
(3, 'sda', 'sda@sda', '$2y$10$ityDuRUfqgGZ8O8DxyezF.xAKfHuWBl.53jAaNOUsrtJpeRt1Khd.'),
(4, 'sda', 'sda@sda', '$2y$10$2IK6ECTP82ZRcVrIVBMY8eXe3edwWYD3hMsYgbRxWfIm5HBJUTDK.'),
(5, 'sda', 'sda@sda', '$2y$10$c1Ng5ifUkL3HHAQSplViyua.WyLSrSKUt4Manr2pWQOYE0iDm6CoG'),
(6, 'josney', 'josney@nsei', '$2y$10$iemzi1DnSKMMK03oWIg9IeLSAAqDwPfkuNiMkWvqSaA13hrgbZ8N6'),
(7, 'jvnq', 'jvnq@etc', '$2y$10$oMyddG.1wHwNxGjQskNIp.GNM3FjsJz5O5kM9ALBDBhvy/vcctjQ.'),
(8, 'JJ', 'JJ@etec', '$2y$10$XDmCCcaA9mvfn9MpuOHIcOCRtoJjIE.CzxXmr3TN5pwlKu48.78sS'),
(9, 'JJ', 'JJ@etec', '$2y$10$mlYcSvovmGe.QP07fUeQ7u9PFryA5n70798HrfYrcrt6ZJkeBSpe2'),
(10, 'josney', 'josney@etc.com', '$2y$10$qQIq0kMg2WQ81pKtH91tKuJXQ8WaFpyjRfz.pwJaDMjf9bvyozy4O'),
(11, 'eddi', 'edd@etc', '$2y$10$Uv5QsnCwoSujgL82/gmx2u4hxZvF.pQoQtvQsVPIAIG9j.cI72c/i');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `perfil`
--
ALTER TABLE `perfil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `perfil`
--
ALTER TABLE `perfil`
  ADD CONSTRAINT `perfil_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Limitadores para a tabela `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
