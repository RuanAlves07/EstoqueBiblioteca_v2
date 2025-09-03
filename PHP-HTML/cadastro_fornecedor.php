<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_empresa']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contato = trim($_POST['contato']);

    if (empty($nome) || empty($endereco) || empty($telefone) || !$email || empty($contato)) {
        $erro = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
    } else {
        try {
            $sql = "INSERT INTO fornecedor (nome_empresa, endereco, telefone, email, contato) 
                    VALUES (:nome, :endereco, :telefone, :email, :contato)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(compact('nome', 'endereco', 'telefone', 'email', 'contato'));
            $sucesso = "Fornecedor cadastrado com sucesso!";
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
    <title>Cadastro de Fornecedor</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <ul class="menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
            <li class="dropdown">
                <a href="#"><?= htmlspecialchars($categoria) ?></a>
                <ul class="dropdown-menu">
                    <?php foreach($arquivos as $arquivo): ?>
                    <li>
                        <a href="<?= htmlspecialchars($arquivo) ?>">
                            <?= ucfirst(str_replace(['_', '.php'], [' ', ''], basename($arquivo))) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="container mt-4">
        <center><h2>Cadastro de Fornecedor</h2></center>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome_empresa" class="form-label">Nome Fornecedor:</label>
                <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" required onkeyup="validarNomeFornecedor()">
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" required onkeyup="validarTelefone()">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="contato" class="form-label">Contato:</label>
                <input type="text" class="form-control" id="contato" name="contato" required>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
        </form>

        <div class="text-center mt-3">
            <a href="principal.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="validacoes.js"></script>
</body>
</html>