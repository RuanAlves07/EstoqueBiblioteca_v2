<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado');window.location.href='dashboard.php';</script>";
    exit;
}

// INICIALIZA AS VARIÁVEIS
$funcionario = null;
$busca = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_funcionario'])) {
        $busca = trim($_POST['busca_funcionario']);
    }

    // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
    if ($busca !== null && is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } elseif ($busca !== null) {
        $sql = "SELECT * FROM funcionario WHERE nome_completo LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O FUNCIONÁRIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if (!$funcionario) {
            echo "<script>alert('Funcionário não encontrado');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Funcionário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="validacoes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="scripts.js"></script>
    <style>
        .container { max-width: 800px; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>

    <center><h2 class="mb-4">Alterar Funcionário</h2></center>

    <div class="container mt-4">
        <!-- FORMULÁRIO DE BUSCA -->
        <form method="POST" action="alterar_funcionario.php">
            <div class="form-group">
                <label for="busca_funcionario">Buscar por ID ou Nome Completo:</label>
                <input type="text" 
                       id="busca_funcionario" 
                       name="busca_funcionario" 
                       value="<?= htmlspecialchars($busca ?? '') ?>" 
                       class="form-control" 
                       placeholder="Digite ID ou nome do funcionário"
                       required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Funcionário</button>
        </form>

        <!-- FORMULÁRIO DE ALTERAÇÃO (APARECE SE ENCONTRAR) -->
        <?php if ($funcionario): ?>
        <hr>
        <form action="processa_alteracao_funcionario.php" method="POST" onsubmit="return validarFuncionario();">
            <input type="hidden" name="id_funcionario" value="<?= htmlspecialchars($funcionario['id_funcionario']) ?>">

            <!-- Nome Completo -->
            <div class="form-group">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text" 
                       name="nome_completo" 
                       id="nome_completo" 
                       value="<?= htmlspecialchars($funcionario['nome_completo']) ?>" 
                       class="form-control" 
                       required>
            </div>

            <!-- CPF -->
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" 
                       name="cpf" 
                       id="cpf" 
                       value="<?= htmlspecialchars($funcionario['cpf']) ?>" 
                       class="form-control" 
                       maxlength="14" 
                       placeholder="000.000.000-00" 
                       required>
            </div>

            <!-- Cargo -->
            <div class="form-group">
                <label for="cargo">Cargo:</label>
                <input type="text" 
                       name="cargo" 
                       id="cargo" 
                       value="<?= htmlspecialchars($funcionario['cargo']) ?>" 
                       class="form-control">
            </div>

            <!-- Telefone -->
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" 
                       name="telefone" 
                       id="telefone" 
                       value="<?= htmlspecialchars($funcionario['telefone']) ?>" 
                       class="form-control"
                       maxlength="15"
                       placeholder="(00) 00000-0000">
            </div>

            <!-- Data de Admissão -->
            <div class="form-group">
                <label for="data_admissao">Data de Admissão:</label>
                <input type="date" 
                       name="data_admissao" 
                       id="data_admissao" 
                       value="<?= htmlspecialchars($funcionario['data_admissao']) ?>" 
                       class="form-control">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Funcionário</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </div>
        </form>
        <?php endif; ?>
    </div>



    <!-- BOTÃO DE LOGOUT -->
    <div class="logout text-center mt-3">
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <script src="../JS/validacoes.js"></script>

</body>
</html>