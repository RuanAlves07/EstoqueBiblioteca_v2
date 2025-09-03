<?php
session_start();
require 'conexao.php';

// Verifica se os dados foram enviados pelo formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Consulta o banco de dados para verificar o usuário
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        //LOGIN BEM SUCEDIDO, DEFINE VARIAVEIS DE SESÃO
        $_SESSION['usuario'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['id_perfil'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];

        // VERIFIVA SE A SENHA É TEMPORARIA
        if ($usuario['senha_temporaria']) {
            // Redireciona para a página de troca de senha
            header("Location: alterar_senha.php");
            exit();
        } else {
            //REDIRECIONA PARA A PAGINA PRINCIPAL   
            header("Location: dashboard.php");
            exit();
        }
    } else {
        //LOGIN INVALIDO
        echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../CSS/login.css" />
</head>
<body>

  <!-- Formas aleatorias para o fundo -->
  <div class="shape yellow"></div>
  <div class="shape pink"></div>
  <div class="shape small-yellow"></div>
  <div class="shape small-pink"></div>

  <!-- Container principal -->
  <div class="container">
    <!-- Lado esquerdo: formulário -->
    <div class="login-form">
      <div class="logo"></div>
      <h2>Bem-Vindo a nossa biblioteca</h2>
      <p class="subtitle">Bem vindo de volta. Por favor faça login na sua conta.</p>

      <form action="index.php" method="POST">
        <div class="input-group">
        <input type="email" name="email" id="email" placeholder="Email" required/>
      </div>
      <div class="input-group">
        <input type="password" name="senha" id="senha" placeholder="Senha" required/>
        <br>
        <br>
        <div class="esqueci-Senha"> <a href="recuperar_senha.php">Esqueci minha senha</a> </div>
        

      </div>

      <div class="buttons">
        <button class="btn btn-login" href="dashboard.php">Login</button>
      </div>
    </div>
    </form>
     
    <!-- Lado direito: imagem e logo -->
    <div class="image-side">
       <img class="image" src="logoB.png" alt="Imagem de um gato feio" />
    </div>
  </div>

</body>
</html>