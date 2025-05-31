<?php
include('includes/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha);

    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso. <a href='login.php'>Fazer login</a>";
    } else {
        echo "Erro ao registrar: " . $stmt->error;
    }
}
?>
<form method="post">
  <h2>Registrar</h2>
  Nome: <input type="text" name="nome" required><br>
  Email: <input type="email" name="email" required><br>
  Senha: <input type="password" name="senha" required><br>
  <button type="submit">Cadastrar</button>
</form>
