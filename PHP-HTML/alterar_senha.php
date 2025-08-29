<?php
session_start();
require_once 'conexao.php';

// Garante que o usuário esteja logado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Acesso negado.');window.location.href='index.php';</script>";
    exit();
    header('Location: index.php');
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if($nova_senha !== $confirmar_senha){
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha < 8)) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres.');</script>";
    } elseif ($nova_senha === "temp123") {
        echo "<script>alert('Escolha uma senha diferente de temporaria.');</script>";
        } else {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            
            //ATUALIZA A SENHA E REMOVE O STATUS DE TEMPORARIA
            $sql = "UPDATE usuario SET senha = :senha, senha_temporaria = FALSE WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":senha",$senha_hash);
            $stmt->bindParam(":id",$id_usuario);

            if($stmt->execute()){
                session_destroy(); //FINALIZA A SESSÃO
                echo "<script>alert('Senha alterada com sucesso!');window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Erro ao alterar a senha!');</script>";
            }
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
            <h1 class="title">Trocar Senha</h1>
            <p class="subtitle">Escolha sua nova senha</p>
        </div>

        <form method="POST" action="alterar_senha.php">
        <div class="form-group">
            <label for="nova_senha" class="label">Nova Senha</label>
            <input type="password" id="nova_senha" name="nova_senha" class="input" required>

            <label for="confirmar_senha" class="label">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" class="input" required>
        </div>

        <label>
            <input type="checkbox" onclick="mostrarSenha()"> Mostrar Senha
        </label>

        <button type="submit" class="button">  
            Salvar nova senha
        </button>
    </form>

    <script>
    function mostrarSenha() {
        const senhaInput = document.getElementById("nova_senha");
        const confirmarInput = document.getElementById("confirmar_senha");
        
        if (senhaInput.type === "password") {
            senhaInput.type = "text";
            confirmarInput.type = "text";
        } else {
            senhaInput.type = "password";
            confirmarInput.type = "password";
        }
    }
    </script>

        <div class="back-link">
            <a href="index.php">← Voltar para o login</a>
        </div>
    </div>
</body>
</html>
