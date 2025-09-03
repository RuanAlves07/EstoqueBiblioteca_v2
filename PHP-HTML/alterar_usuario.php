<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o perfil tem permissão para alterar fornecedores
if (!isset($permissoes[$id_perfil]['Alterar']) || !in_array('alterar_usuario.php', $permissoes[$id_perfil]['Alterar'])) {
    echo "<script>alert('Acesso negado.'); window.location.href='principal.php';</script>";
    exit();
}

// Menu do usuário
$opcoes_menu = $permissoes[$id_perfil] ?? [];

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do usuario não informado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_usuario.php");
    exit();
}

$id_usuario = (int)$_GET['id'];

// Busca o fornecedor no banco
$sql = "SELECT * FROM usuario WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['mensagem'] = "Usuario não encontrado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_usuario.php");
    exit();
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);

    // Validação básica
    if (empty($nome) || !$email || empty($senha)) {
        $erro = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
    } else {
        try {
            $sql_update = "UPDATE usuuario SET nome = :nome,  email = :email, senha = :senha WHERE id_usuario = :id";

            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([':nome' => $nome,':email' => $email,':contato' => $contato,':id' => $id_usuario]);

            $_SESSION['mensagem'] = "Usuario <strong>" . htmlspecialchars($nome) . "</strong> atualizado com sucesso!";
            $_SESSION['msg_tipo'] = "success";

            header("Location: buscar_usuario.php");
            exit();

        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>

    <!-- Mensagem de erro, se houver -->
    <?php if (isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= htmlspecialchars($erro) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Formulário de Alteração -->
    <div class="container">
        <h2> Alterar Usuario</h2>

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

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="buscar_usuario.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

</body>
</html>