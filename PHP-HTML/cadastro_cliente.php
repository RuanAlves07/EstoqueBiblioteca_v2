<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';



$erro = $sucesso = '';

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data_nascimento'];
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);

    // Validação de campos obrigatórios
    if (empty($nome_completo) || empty($cpf) || empty($telefone) || empty($data_nascimento) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (!$email) {
        $erro = "E-mail inválido.";
    } elseif (strlen($senha) < 8) {
        $erro = "A senha deve ter pelo menos 8 caracteres.";
    } else {
        try {
            $pdo->beginTransaction();

            // Insere na tabela cliente
            $sql_cliente = "INSERT INTO cliente (nome_completo, cpf, telefone, data_nascimento) VALUES (:nome_completo, :cpf, :telefone, :data_nascimento)";
            $stmt_cliente = $pdo->prepare($sql_cliente);
            $stmt_cliente->execute([
                ':nome_completo' => $nome_completo,
                ':cpf' => $cpf,
                ':telefone' => $telefone,
                ':data_nascimento' => $data_nascimento
            ]);

            $id_cliente = $pdo->lastInsertId();

            // Cria o usuário associado com id_perfil = 4 (Cliente)
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $sql_usuario = "INSERT INTO usuario (nome, email, senha, id_perfil, senha_temporaria) VALUES (:nome, :email, :senha, 4, 0)";
            $stmt_usuario = $pdo->prepare($sql_usuario);
            $stmt_usuario->execute([
                ':nome' => $nome_completo,
                ':email' => $email,
                ':senha' => $senhaHash
            ]);

            $id_usuario = $pdo->lastInsertId();

            // Vincula cliente ao usuário (1:1)
            $sql_vinculo = "UPDATE cliente SET id_cliente = :id_usuario WHERE id_cliente = :id_cliente";
            $stmt_vinculo = $pdo->prepare($sql_vinculo);
            $stmt_vinculo->execute([':id_usuario' => $id_usuario, ':id_cliente' => $id_cliente]);

            $pdo->commit();

            $sucesso = "Cliente e acesso de login criados com sucesso!";
            $_POST = [];

        } catch (PDOException $e) {
            $pdo->rollback();
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'uk_usuario_email')) {
                $erro = "O e-mail informado já está cadastrado como usuário.";
            } else {
                $erro = "Erro ao cadastrar: " . $e->getMessage();
            }
        }
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

    <center><h2>Cadastro de Cliente</h2></center>

    <?php if ($erro): ?>
        <center><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div></center>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <center><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div></center>
    <?php endif; ?>

    <form action="cadastro_cliente.php" method="POST">

        <label for="nome_completo" class="form-label">Nome completo:</label>
        <input type="text" class="form-control" id="nome_completo" name="nome_completo"
               value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>" required>

        <label for="cpf" class="form-label">CPF:</label>
        <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
               value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>

        <label for="telefone" class="form-label">Telefone:</label>
        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15"
               value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>

        <label for="data_nascimento" class="form-label">Data de nascimento:</label>
        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
               value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" required>

        <label for="email" class="form-label">E-mail de Login:</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required
               placeholder="exemplo@biblioteca.com">

        <label for="senha" class="form-label">Senha de Login (mínimo 8 caracteres):</label>
        <input type="password" class="form-control" id="senha" name="senha" required>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Cadastrar Cliente e Criar Acesso</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="../JS/validacoes.js"></script>
</body>
</html>