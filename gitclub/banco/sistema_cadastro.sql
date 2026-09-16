-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Set-2026 às 07:31
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
-- Banco de dados: `sistema_cadastro`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(67) NOT NULL,
  `email` varchar(67) NOT NULL,
  `senha` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'asdasd', 'asdasd@asdasd', 'sadad'),
(2, 'asdasd', 'asdasd@asdasd', 'asdad'),
(3, 'asdasd', 'asdasd@asdasd', 'asdad'),
(4, 'sadad', 'asdasd@ADSD', 'asdad'),
(5, 'asdasd', 'asda@dada', 'adasdasd'),
(6, 'Josney', 'Josney@ETEC.JK', '123'),
(7, 'jj', 'sdad@aad', 'asdad'),
(8, 'Murillo', 'murillomatiazzo497@gmail.com', 'asdasd'),
(9, 'josney', 'murillomatiazzo497@gmail.com', 'ss'),
(10, 'Murillo', 'murillomatiazzo497@gmail.com', 'sadasdad'),
(11, 'asdad', 'asda@Asdad', 'asdasd'),
(12, 'Murillo', 'murillomatiazzo497@gmail.com', 'adasd'),
(13, 'asdad', 'adsd@asd', 'asdad'),
(14, 'asdad', 'adsd@asd', 'asdad'),
(15, 'josney', 'Josney@ETEC.JK', 'asdasd'),
(16, 'josney', 'Josney@ETEC.JK', 'asdasd'),
(17, 'Murillo', 'murillomatiazzo497@gmail.com', '123'),
(18, 'Murillo', 'murillomatiazzo497@gmail.com', 'w'),
(19, 'Murillo', 'murillomatiazzo497@gmail.com', '2'),
(20, 'Murillo', 'murillomatiazzo497@gmail.com', 's'),
(21, 'Murillo', 'murillomatiazzo497@gmail.com', 's'),
(22, 'Murillo', 'murillomatiazzo497@gmail.com', 's'),
(23, 'Murillo', 'murillomatiazzo497@gmail.com', 's'),
(24, 's', 'S@S', 's'),
(26, 'ss', 'ss@Ss', 'ss'),
(27, '', '', ''),
(28, '', '', ''),
(29, '', '', ''),
(30, 'sss', 'ss@sss', 'sss'),
(31, 'Murillo', 'murillomatiazzo497@gmail.com', '123'),
(32, 'josney', 'Josney@ETEC.JK', 's'),
(33, 'josney', 'Josney@ETEC.JK', 's'),
(34, 'Murillo', 'murillomatiazzo497@gmail.com', '123'),
(35, 'jeff', 'jeff@etec', '123'),
(36, 'thiago', 'thiago@gmail.com', '123'),
(37, 'andre', 'andre@gmail.com', '123'),
(38, 'numsei2', 'numsei@gmail.com', '123'),
(39, 'teste', 'teste@gmail.com', '123');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
