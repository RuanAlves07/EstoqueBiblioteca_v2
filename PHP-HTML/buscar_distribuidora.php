<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}


// Processar a busca
$fornecedores = [];
$busca = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busca = trim($_POST['busca']);
    if (!empty($busca)) {
        // Busca por ID ou nome
        $sql = "SELECT * FROM fornecedor 
                WHERE id_fornecedor = :busca 
                OR nome_empresa LIKE :nome_busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', $busca, PDO::PARAM_INT);
        $stmt->bindValue(':nome_busca', "$busca%", PDO::PARAM_STR);
    } else {
        // Se não houver busca, traz todos
        $sql = "SELECT * FROM fornecedor";
        $stmt = $pdo->prepare($sql);
    }
} else {
    // Se não for POST, traz todos os fornecedores
    $sql = "SELECT * FROM fornecedor";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Distribuidora</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Corrigido: removido espaços no final do URL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    
    <div class="container mt-4">
        <center><h2>Lista de Distribuidora</h2></center>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="busca" class="form-label">Digite o ID ou Nome do usuario (opcional)</label>
                <input type="text" class="form-control" id="busca" name="busca" value="<?= htmlspecialchars($busca) ?>">
            </div>
            <center><button type="submit" class="btn btn-primary">Pesquisar</button></center>
        </form>

        <br>

        <!-- Exibir resultados -->
        <?php if (!empty($fornecedores)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CNPJ</th>
                            <th>Endereço</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Contato</th>
                            <th>Ações</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['nome_empresa']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['cnpj']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" 
                                   href="alterar_distribuidora.php?id=<?= (int)$fornecedor['id_fornecedor'] ?>">
                                   Alterar
                                </a>
                                <a class="btn btn-sm btn-danger" 
                                   href="excluir_distribuidora.php?id=<?= (int)$fornecedor['id_fornecedor'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir esta distribuidora?')">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum distribuidor encontrado.</p></center>
        <?php endif; ?>

    </div>

    <!-- Scripts no final -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

</body>
</html>