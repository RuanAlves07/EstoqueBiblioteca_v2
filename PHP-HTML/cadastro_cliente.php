<?php
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
    $nome = trim($_POST['nome_completo']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data_nascimento'];

    // Validação de campos, caso tudo ok insert na tabela cliente
    if (empty($nome) || empty($cpf) || empty($telefone) || empty($data_nascimento)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        try {
            $sql = "INSERT INTO cliente (nome_completo, cpf, telefone, data_nascimento) VALUES (:nome_completo, :cpf, :telefone, :data_nascimento)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nome_completo' => $nome,'cpf' => $cpf,'telefone' => $telefone,'data_nascimento' => $data_nascimento]);
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
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


        <center><h2>Cadastro de Cliente</h2></center>

        <!-- Exibir mensagens -->
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form action="cadastro_cliente.php" method="POST">

                <label for="nome_completo" class="form-label">Nome completo:</label>
                <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                       value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>" required>

                <label for="cpf" class="form-label">CPF (apenas números):</label>
                <input type="text" class="form-control" id="cpf" name="cpf"
                       value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>" required>

                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone"
                       value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>


                <label for="data_nascimento" class="form-label">Data de nascimento:</label>
                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                       value="<?= htmlspecialchars($_POST['data_nascimento'] ?? '') ?>" required>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
        </form>



    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

</body>
</html>