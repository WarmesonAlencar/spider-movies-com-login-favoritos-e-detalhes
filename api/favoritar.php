<?php
include('../includes/conexao.php'); // Essa linha importa o arquivo de conexão com o banco de dados. Isso é essencial para que $conn funcione mais abaixo.

if (!isset($_SESSION['usuario_id'])) {
    die('Não autorizado.');
}   // Explicação: verifica se a sessão contém o usuario_id.
//Se não tiver (ou seja, o usuário não está logado), o script termina imediatamente com a mensagem "Não autorizado."
//🔐 Segurança básica para evitar que alguém acesse a API diretamente.



$id_filme = $_POST['id_filme'];
$titulo = $_POST['titulo'];
$poster = $_POST['poster'];
$sinopse = $_POST['sinopse'];
$usuario_id = $_SESSION['usuario_id'];    // Explicação:
//Pega os dados que vieram via POST (geralmente de um formulário ou requisição via JS) e o usuario_id da sessão.
//Esses dados serão usados para:
//Inserir o filme (caso ainda não esteja na base).
//Relacionar o usuário com o filme na tabela de favoritos.

$stmt = $conn->prepare("INSERT IGNORE INTO filmes (id_filme, titulo, poster, sinopse) VALUES (?, ?, ?, ?)");//Explicação:
//Prepara um comando SQL para inserir o filme na tabela filmes.
//INSERT IGNORE é usado para não dar erro caso o id_filme já exista (ou seja, o filme já foi inserido antes).
//Boa prática quando você quer evitar duplicidade sem causar erro.




$stmt->bind_param("isss", $id_filme, $titulo, $poster, $sinopse);
$stmt->execute();

// Explicação:
//bind_param faz a ligação segura dos valores com a query (protege contra SQL Injection).
//"isss" significa:
//i → inteiro (id_filme)
//s → string (titulo, poster, sinopse)
//Depois, ele executa a query.



$stmt2 = $conn->prepare("INSERT INTO favoritos (usuario_id, id_filme) VALUES (?, ?)");


$stmt2->bind_param("ii", $usuario_id, $id_filme);
$stmt2->execute();

header("Location: ../index.php");
exit;
?>
