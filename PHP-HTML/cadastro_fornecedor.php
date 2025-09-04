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

$erro = $sucesso = '';

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_empresa = trim($_POST['nome_empresa']);
    $nome_fantasia = trim($_POST['nome_fantasia']);
    $cnpj = preg_replace('/\D/', '', $_POST['cnpj']); // Só números
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contato = trim($_POST['contato']);

    // Validação de campos obrigatórios
    if (empty($nome_empresa) || empty($nome_fantasia) || empty($cnpj) || empty($endereco) || empty($telefone) || !$email || empty($contato)) {
        $erro = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
    } elseif (strlen($cnpj) !== 14) {
        $erro = "CNPJ inválido. Deve ter 14 dígitos.";
    } else {
        try {
            // Verifica se o CNPJ já existe
            $check = $pdo->prepare("SELECT id_fornecedor FROM fornecedor WHERE cnpj = ?");
            $check->execute([$cnpj]);
            if ($check->rowCount() > 0) {
                $erro = "CNPJ já cadastrado.";
            } else {
                // Agora sim: insere todos os campos
                $sql = "INSERT INTO fornecedor 
                            (nome_empresa, nome_fantasia, cnpj, endereco, telefone, email, contato) 
                        VALUES 
                            (:nome_empresa, :nome_fantasia, :cnpj, :endereco, :telefone, :email, :contato)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome_empresa' => $nome_empresa,
                    ':nome_fantasia' => $nome_fantasia,
                    ':cnpj' => $cnpj,
                    ':endereco' => $endereco,
                    ':telefone' => $telefone,
                    ':email' => $email,
                    ':contato' => $contato
                ]);
                
                $sucesso = "Fornecedor cadastrado com sucesso!";
                // Limpa os campos após sucesso (opcional)
                $_POST = array();
            }
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
    <div class="container mt-4">
        <center><h2>Cadastro de Fornecedor</h2></center>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <?php if (!empty($sucesso)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome_empresa" class="form-label">Nome Empresarial (Razão Social):</label>
                <input type="text" class="form-control" id="nome_empresa" name="nome_empresa" 
                       value="<?= htmlspecialchars($_POST['nome_empresa'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="nome_fantasia" class="form-label">Nome Fantasia:</label>
                <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" 
                       value="<?= htmlspecialchars($_POST['nome_fantasia'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ:</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" 
                       value="<?= htmlspecialchars($_POST['cnpj'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco" 
                       value="<?= htmlspecialchars($_POST['endereco'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone:</label>
                <input type="text" class="form-control" id="telefone" name="telefone" 
                       value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="contato" class="form-label">Contato (Pessoa de contato):</label>
                <input type="text" class="form-control" id="contato" name="contato" 
                       value="<?= htmlspecialchars($_POST['contato'] ?? '') ?>" required>
            </div>

            <center><button type="submit" class="btn btn-primary">Cadastrar</button></center>
            <br>
            <center><button type="reset" class="btn btn-secondary">Limpar</button></center>
        </form>

        <div class="text-center mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script src="validacoes.js"></script>

    <!-- Máscara opcional para CNPJ (se quiser deixar bonitinho) -->
    <script>
        document.getElementById('cnpj').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            }
            e.target.value = value;
        });
    </script>
</body>
</html>