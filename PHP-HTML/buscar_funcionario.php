<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Busca funcionarios
$busca = null;
$funcionarios = [];

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $busca = trim($_GET['busca']);
    
    // Se for número, busca por ID; senão, busca por nome
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM funcionario WHERE id_funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM funcionario WHERE nome_completo LIKE :nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', "%$busca%", PDO::PARAM_STR);
    }
    
    $stmt->execute();
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Busca todos os funcionários
    $sql = "SELECT * FROM funcionario ORDER BY nome_completo ASC";
    $stmt = $pdo->query($sql);
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Funcionários</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table th,
        .table td {
            white-space: normal; /* Permite quebra de linha */
            word-wrap: break-word; /* Quebra palavras longas */
            padding: 12px 15px;
        }
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        .table tbody tr:hover {
            background-color: #f1f3f5;
        }
        .form-control {
            width: 300px;
        }
    </style>
</head>
<body>

    <!-- Mensagem de feedback -->
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= $_SESSION['mensagem'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensagem'], $_SESSION['msg_tipo']); ?>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Lista de Funcionários</h2>

        <!-- Formulário de Busca -->
        <form method="GET" action="" class="mb-4">
            <div class="input-group">
                <input type="text" name="busca" class="form-control" placeholder="Digite o ID ou NOME do funcionário (opcional)" 
                       value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                <button class="btn btn-primary" type="submit">Pesquisar</button>
            </div>
        </form>

        <!-- Tabela -->
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Completo</th> <!-- Largura fixa para evitar corte -->
                        <th>CPF</th>
                        <th>Cargo</th>
                        <th>Telefone</th>
                        <th>Data de Admissão</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['id_funcionario']) ?></td>
                            <td><?= htmlspecialchars($f['nome_completo']) ?></td>
                            <td><?= htmlspecialchars($f['cpf']) ?></td>
                            <td><?= htmlspecialchars($f['cargo']) ?></td>
                            <td><?= htmlspecialchars($f['telefone']) ?></td>
                            <td><?= htmlspecialchars($f['data_admissao']) ?></td>
                            <td>
                                <a href="alterar_funcionario.php?id=<?= $f['id_funcionario'] ?>" class="btn btn-sm btn-warning">Alterar</a>
                                <a href="excluir_funcionario.php?id=<?= $f['id_funcionario'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Tem certeza que deseja excluir <?= addslashes($f['nome_completo']) ?>?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <div class="text-center mt-4">
            <a href="principal.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>