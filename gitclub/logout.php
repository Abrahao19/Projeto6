<?php

session_start();

// apaga todos os dados da sessao
$_SESSION = [];

// Destroi a sessao
session_destroy();

// Volta para a página inicial
header("Location: inicio.php");
exit();

?>