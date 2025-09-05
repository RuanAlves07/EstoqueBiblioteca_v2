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
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Funcionário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Corrigido: removido espaços no final do URL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <center><h2>Lista de Funcionários</h2></center>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="busca" class="form-label">Digite o ID ou Nome do funcionário (opcional)</label>
                <input type="text" class="form-control" id="busca" name="busca" value="<?= htmlspecialchars($busca) ?>">
            </div>
            <center><button type="submit" class="btn btn-primary">Pesquisar</button></center>
        </form>

        <br>

        <!-- Exibir resultados -->
        <?php if (!empty($funcionarios)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>CPF</th>
                            <th>Cargo</th>
                            <th>Telefone</th>
                            <th>Data de Admissão</th>
                            <th>Ações</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php foreach ($funcionarios as $funcionario): ?>
                        <tr>
                            <td><?= htmlspecialchars($funcionario['id_funcionario']) ?></td>
                            <td><?= htmlspecialchars($funcionario['nome_completo']) ?></td>
                            <td><?= htmlspecialchars($funcionario['cpf']) ?></td>
                            <td><?= htmlspecialchars($funcionario['cargo']) ?></td>
                            <td><?= htmlspecialchars($funcionario['telefone']) ?></td>
                            <td><?= htmlspecialchars($funcionario['data_admissao']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" 
                                   href="alterar_funcionario.php?id=<?= (int)$funcionario['id_funcionario'] ?>">
                                   Alterar
                                </a>
                                <a class="btn btn-sm btn-danger" 
                                   href="excluir_funcionario.php?id=<?= (int)$funcionario['id_funcionario'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir <?= addslashes($funcionario['nome_completo']) ?>?')">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum funcionário encontrado.</p></center>
        <?php endif; ?>


    </div>

    <!-- Scripts no final -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
</body>
</html>