<?php
// Inicia buffer para evitar "headers already sent"
ob_start();

session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    ob_end_clean();
    exit();
}

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do usuário não informado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_usuario.php");
    ob_end_clean();
    exit();
}

$id_usuario = (int)$_GET['id'];

// Busca o usuário no banco
$sql = "SELECT * FROM usuario WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['mensagem'] = "Usuário não encontrado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_usuario.php");
    ob_end_clean();
    exit();
}

// Variável para armazenar erro
$erro = null;

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);

    // Validação
    if (empty($nome) || !$email || empty($senha)) {
        $erro = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
    } else {
        try {
            // Atualiza o usuário com senha criptografada
            $sql_update = "UPDATE usuario SET nome = :nome, email = :email, senha = :senha WHERE id_usuario = :id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => password_hash($senha, PASSWORD_DEFAULT), // ✅ Segurança
                ':id' => $id_usuario
            ]);

            $_SESSION['mensagem'] = "Usuário <strong>" . htmlspecialchars($nome) . "</strong> atualizado com sucesso!";
            $_SESSION['msg_tipo'] = "success";

            header("Location: buscar_usuario.php");
            ob_end_flush();
            exit();

        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}

// Libera o buffer para exibir a página
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>

    <!-- Mensagem de erro -->
    <?php if ($erro): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= htmlspecialchars($erro) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Alterar Usuário</h2>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Usuário:</label>
                <input type="text" class="form-control" id="nome" name="nome"
                       value="<?= htmlspecialchars($_POST['nome'] ?? $usuario['nome']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? $usuario['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
                <small class="text-muted">Digite uma nova senha (será criptografada).</small>
            </div>

            <div class="text-center mt-4">
                <center><button type="submit" class="btn btn-primary">Salvar Alterações</button></center>
            </div>
        </form>
        <center><a href="buscar_usuario.php" class="btn btn-secondary">Cancelar</a></center>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
</body>
</html>