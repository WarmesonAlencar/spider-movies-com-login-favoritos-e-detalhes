<?php
$host = 'localhost:3307';
$usuario = 'root';
$senha = '';
$banco = 'filmes';

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die('Erro na conexão: ' . $conn->connect_error);
}
session_start();
?>