<?php 
session_start(); 
require_once 'conexao.php'; 
require_once 'funcoes_email.php'; // Arquivo com funções que geram a senha e silulam o envio 
 
// Verifica se o usuário existe 
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $email = $_POST['email']; 
 
    // Verifica se o usuário existe 
    $sql ="SELECT * FROM usuario WHERE email = :email"; 
    $stmt = $pdo->prepare($sql); 
    $stmt->bindParam(':email', $email); 
    $stmt->execute(); 
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); 
 
    if ($usuario) {
        // Gera uma nova senha temporária
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);
        
        // Atualiza a senha do usuário no banco de dados
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Envia a nova senha para o e-mail do usuário
        simularEnvioEmail($email, $senha_temporaria);
        echo "<script>alert('Uma nova senha temporaria foi gerada e enviada (simulação). Verifique o arquivo emails_simulados.txt');window.location.href='login.php';</script>";

    } else {
        echo "<script>alert('E-mail não encontrado.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Minha Senha - Biblioteca</title>
    <link rel="stylesheet" href="../CSS/recuperar_senha.css">
</head>
<body>
    <!-- Elementos decorativos -->
    <div class="decoration decoration-1"></div>
    <div class="decoration decoration-2"></div>
    <div class="decoration decoration-3"></div>

    <div class="container">
        <div class="header">
            <h1 class="title">Esqueci Minha Senha</h1>
            <p class="subtitle">Digite seu email para receber as instruções de recuperação de senha</p>
        </div>

        <form method="POST" action="alterar_senha.php">
            <div class="form-group">
                <input type="email" id="email" name="email" class="input" placeholder="Digite seu email" required>
            </div>

            <button type="submit" class="button">
                Enviar Instruções
            </button>
        </form>

        <div class="back-link">
            <a href="login.php">← Voltar para o login</a>
        </div>
    </div>
</body>
</html>
