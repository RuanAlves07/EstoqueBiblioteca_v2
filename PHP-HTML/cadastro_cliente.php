<?php
ob_start();
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Recupera mensagens da sessão
$erro = $_SESSION['erro'] ?? null;
$sucesso = $_SESSION['sucesso'] ?? null;
unset($_SESSION['erro'], $_SESSION['sucesso']);

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);
    
    // Validação básica
    if (empty($nome_completo) || empty($cpf) || empty($telefone) || empty($endereco) || 
        empty($data_nascimento) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios.";
        header("Location: cadastro_cliente.php");
        ob_end_clean();
        exit();
    }

    try {
        $pdo->beginTransaction();
        
        // Primeiro cria o usuário com perfil 4 (Cliente)
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $sql_usuario = "INSERT INTO usuario (nome, email, senha, id_perfil, senha_temporaria) 
                        VALUES (:nome, :email, :senha, 4, 1)";
        $stmt_usuario = $pdo->prepare($sql_usuario);
        $stmt_usuario->bindParam(':nome', $nome_completo);
        $stmt_usuario->bindParam(':email', $email);
        $stmt_usuario->bindParam(':senha', $senhaHash);
        $stmt_usuario->execute();
        
        // Obtém o ID do usuário recém-criado
        $id_usuario = $pdo->lastInsertId();
        
        // Agora cria o cliente usando o mesmo ID
        $sql_cliente = "INSERT INTO cliente (id_cliente, nome_completo, cpf, telefone, endereco, data_nascimento) 
                        VALUES (:id_cliente, :nome_completo, :cpf, :telefone, :endereco, :data_nascimento)";
        $stmt_cliente = $pdo->prepare($sql_cliente);
        $stmt_cliente->bindParam(':id_cliente', $id_usuario);
        $stmt_cliente->bindParam(':nome_completo', $nome_completo);
        $stmt_cliente->bindParam(':cpf', $cpf);
        $stmt_cliente->bindParam(':telefone', $telefone);
        $stmt_cliente->bindParam(':endereco', $endereco);
        $stmt_cliente->bindParam(':data_nascimento', $data_nascimento);
        $stmt_cliente->execute();

        $pdo->commit();
        $_SESSION['sucesso'] = "Cliente cadastrado com sucesso!";
        header("Location: cadastro_cliente.php");
        ob_end_clean();
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        
        if ($e->getCode() == 23000 || strpos($e->getMessage(), 'uk_usuario_email') !== false) {
            $_SESSION['erro'] = "O e-mail <strong>" . htmlspecialchars($email) . "</strong> já está cadastrado.";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar cliente: " . htmlspecialchars($e->getMessage());
        }
        header("Location: cadastro_cliente.php");
        ob_end_clean();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <center><h2>Cadastro de Cliente</h2></center>

        <?php if ($erro): ?>
            <center><div class="alert alert-danger"><?= $erro ?></div></center>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <center><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div></center>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome_completo" class="form-label">Nome Completo:</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                       value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                       value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15"
                       value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco"
                       value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento:</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                       value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail de login:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="../JS/validacoes.js"></script>
</body>
</html>