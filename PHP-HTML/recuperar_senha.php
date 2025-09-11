<?php 
session_start(); 
require_once 'conexao.php'; 
require_once 'funcoes_email.php';

$mensagem = '';
$senha_exibida = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gera senha temporária
        $senha_temporaria = gerarSenhaTemporaria();
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // Atualiza a senha no banco
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = 1 WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Simula envio de email com senha
        simularEnvioEmail($email, $senha_temporaria);

        // Armazena a senha para exibir na tela
        $mensagem = "Nova senha temporária gerada!";
        $senha_exibida = $senha_temporaria;

    } else {
        $mensagem = "E-mail não encontrado.";
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

        <form method="POST" action="">
            <div class="form-group">
                <input type="email" id="email" name="email" class="input" placeholder="Digite seu email" required>
            </div>

            <button type="submit" class="button">
                Enviar Instruções
            </button>
        </form>

        <!-- Exibe a mensagem -->
        <?php if ($mensagem): ?>
            <div class="alert alert-info mt-3 text-center">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <!-- Exibe a senha temporária -->
        <?php if ($senha_exibida): ?>
            <div class="senha-display">
                <p><span class="senha-label">Sua nova senha temporária:</span></p>
                <p><strong><?php echo $senha_exibida; ?></strong></p>
                <small>Use esta senha para fazer login.</small>
            </div>
        <?php endif; ?>

        <div class="back-link mt-3">
            <a href="index.php">← Voltar para o login</a>
        </div>
    </div>
</body>
</html>