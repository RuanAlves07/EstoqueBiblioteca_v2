<?php
ob_start();
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica login
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$erro = $sucesso = '';

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_completo = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $cargo = trim($_POST['cargo']);
    $telefone = $_POST['telefone'];
    $data_admissao = $_POST['data_admissao'];

    // Validação de campos, caso tudo ok insert na tabela cliente
    if (empty($nome_completo) || empty($cpf) || empty($cargo) || empty($telefone) || empty($data_admissao)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        try {
            $sql = "INSERT INTO funcionario (nome_completo, cpf, cargo, telefone, data_admissao) VALUES (:nome_completo, :cpf, :cargo, :telefone, :data_admissao)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nome_completo' => $nome_completo,'cpf' => $cpf,'cargo' => $cargo, 'telefone' => $telefone,'data_admissao' => $data_admissao]);
            $sucesso = "Cliente cadastrado com sucesso!";
            $_POST = [];
        } catch (PDOException $e) {
            $erro = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de funcionarios</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <br>
        <center><h2>Cadastro de funcionarios</h2></center>

        <!-- Exibir mensagens -->
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form action="cadastro_funcionario.php" method="POST">

                <label for="nome_completo">Nome completo:</label>
                <input type="text" id="nome_completo" name="nome_completo"
                       value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>" required>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf"
                       value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>

                <label for="cargo" >cargo:</label>
                <input type="text"  id="cargo" name="cargo"
                        value="<?= htmlspecialchars($_POST['cargo'] ?? '') ?>" required>

                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone"
                       value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>

                <label for="data_admissao" >Data de Admissão:</label>
                <input type="date" id="data_admissao" name="data_admissao"
                       value="<?= htmlspecialchars($_POST['data_admissao'] ?? '') ?>" required>
            <br>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="reset" class="btn btn-secondary">Cancelar</button>
        </form>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

</body>
</html>