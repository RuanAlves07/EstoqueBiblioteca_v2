<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Processar a busca
$usuarios = [];
$busca = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busca = trim($_POST['busca']);
    if (!empty($busca)) {
        // Busca por ID ou nome
        $sql = "SELECT * FROM cliente 
                WHERE id_cliente = :busca 
                OR nome_completo LIKE :nome_busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', $busca, PDO::PARAM_INT);
        $stmt->bindValue(':nome_busca', "$busca%", PDO::PARAM_STR);
    } else {
        // Se não houver busca, traz todos
        $sql = "SELECT * FROM cliente ORDER BY nome_completo ASC";
        $stmt = $pdo->prepare($sql);
    }
} else {
    // Se não for POST, traz todos os clientes
    $sql = "SELECT * FROM cliente ORDER BY nome_completo ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Corrigido: removido espaços extras no URL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <center><h2>Lista de Clientes</h2></center>

        <!-- Formulário de busca -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="busca" class="form-label">Digite o ID ou Nome do cliente (opcional)</label>
                <input type="text" class="form-control" id="busca" name="busca" value="<?= htmlspecialchars($busca) ?>">
            </div>
            <center><button type="submit" class="btn btn-primary">Pesquisar</button></center>
        </form>

        <br>

        <!-- Exibir resultados -->
        <?php if (!empty($usuarios)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID do cliente</th>
                            <th>Nome completo</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Data de nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario['id_cliente']) ?></td>
                                <td><?= htmlspecialchars($usuario['nome_completo']) ?></td>
                                <td><?= htmlspecialchars($usuario['cpf']) ?></td>
                                <td><?= htmlspecialchars($usuario['telefone']) ?></td>
                                <td><?= htmlspecialchars($usuario['data_nascimento']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-warning" 
                                       href="alterar_cliente.php?id=<?= (int)$usuario['id_cliente'] ?>">
                                       Alterar
                                    </a>
                                    <a class="btn btn-sm btn-danger" 
                                       href="excluir_cliente.php?id=<?= (int)$usuario['id_cliente'] ?>"
                                       onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
                                       Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum cliente encontrado.</p></center>
        <?php endif; ?>

        <br>
        <center>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </center>
    </div>

    <!-- Scripts no final -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>