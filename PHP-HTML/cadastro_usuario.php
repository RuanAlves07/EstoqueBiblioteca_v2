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

// Recupera mensagens da sessão (para mostrar após redirecionamento)
$erro = $_SESSION['erro'] ?? null;
$sucesso = $_SESSION['sucesso'] ?? null;

// Limpa as mensagens da sessão após exibir
unset($_SESSION['erro'], $_SESSION['sucesso']);

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);
    $id_perfil = $_POST['id_perfil'];

    // Validação básica
    if (empty($nome) || empty($email) || empty($senha) || empty($id_perfil)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();
    }

    try {
        // Criptografa a senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere no banco
        $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':id_perfil', $id_perfil);

        $stmt->execute();

        // Define mensagem de sucesso e redireciona
        $_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();

    } catch (PDOException $e) {
        // Verifica se o erro é por e-mail duplicado
        if ($e->getCode() == 23000 || strpos($e->getMessage(), 'uk_usuario_email') !== false) {
            $_SESSION['erro'] = "O e-mail <strong>" . htmlspecialchars($email) . "</strong> já está cadastrado.";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar usuário: " . htmlspecialchars($e->getMessage());
        }
        header("Location: cadastro_usuario.php");
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
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <center><h2>Cadastro de Usuário</h2></center>

        <!-- Exibe mensagens de erro ou sucesso -->
        <?php if ($erro): ?>
            <center><div class="alert alert-danger"><?= $erro ?></div></center>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <center><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div></center>
        <?php endif; ?>

        <!-- Formulário de cadastro -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Usuário:</label>
                <input type="text" class="form-control" id="nome" name="nome"
                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="mb-3">
                <label for="id_perfil" class="form-label">Perfil:</label>
                <select class="form-select" id="id_perfil" name="id_perfil" required>
                    <option value="">Selecione um perfil</option>
                    <option value="1" <?= ($_POST['id_perfil'] ?? '') == '1' ? 'selected' : '' ?>>Administrador</option>
                    <option value="2" <?= ($_POST['id_perfil'] ?? '') == '2' ? 'selected' : '' ?>>Secretaria</option>
                    <option value="3" <?= ($_POST['id_perfil'] ?? '') == '3' ? 'selected' : '' ?>>Almoxarife</option>
                    <option value="4" <?= ($_POST['id_perfil'] ?? '') == '4' ? 'selected' : '' ?>>Cliente</option>
                </select>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
        </form>


    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="../JS/validacoes.js"></script>
</body>
</html>