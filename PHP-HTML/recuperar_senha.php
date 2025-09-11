<?php 
session_start(); 
require_once 'conexao.php'; 
require_once 'funcoes_email.php';

$mensagem = '';
$codigo_exibido = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $sql = "SELECT * FROM usuario WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Gera senha temporária e código de recuperação
        $senha_temporaria = gerarSenhaTemporaria();
        $codigo_recuperacao = gerarCodigoRecuperacao();
        
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // Atualiza a senha e marca como temporária
        $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Envia email simulado com senha e código
        simularEnvioEmail($email, $senha_temporaria, $codigo_recuperacao);

        $mensagem = "Instruções enviadas! Verifique o arquivo emails_simulados.txt";
        $codigo_exibido = $codigo_recuperacao;

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

        <form method="POST" action="recuperar_senha.php">
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

        <!-- Exibe o código de recuperação -->
        <?php if ($codigo_exibido): ?>
            <div class="code-display mt-3 text-center" style="background-color: #f8f9fa; border: 1px dashed #3a66ff; padding: 15px; border-radius: 8px;">
                <p><strong>Código de Recuperação:</strong> <span style="font-size: 1.2em; color: #3a66ff;"><?php echo $codigo_exibido; ?></span></p>
                <small>Use este código para redefinir sua senha.</small>
            </div>
        <?php endif; ?>

        <div class="back-link">
            <a href="index.php">← Voltar para o login</a>
        </div>
    </div>
</body>
</html>