<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica login
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Busca os empréstimos, livros, usuário e funcionário
$sql = "SELECT 
            e.id_emprestimo, 
            e.data_emprestimo, 
            e.data_devolucao_prevista, 
            e.data_devolucao_real, 
            e.status, 
            p.id_produto, 
            p.titulo, 
            u.nome AS nome_usuario,
            f.nome_completo AS nome_funcionario
        FROM emprestimo e 
        INNER JOIN item_emprestimo i ON e.id_emprestimo = i.id_emprestimo 
        INNER JOIN produto p ON i.id_produto = p.id_produto 
        INNER JOIN usuario u ON e.id_usuario = u.id_usuario 
        LEFT JOIN funcionario f ON e.id_funcionario = f.id_funcionario
        ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos os Empréstimos</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <center><h2>Todos os Empréstimos</h2></center>

    <div class="container">
        <?php if (empty($emprestimos)): ?>
            <center><p>Não há nenhum empréstimo realizado.</p></center>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Funcionário</th>
                        <th>ID do Livro</th>
                        <th>Livro</th>
                        <th>Empréstimo</th>
                        <th>Devolução Prevista</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprestimos as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['nome_usuario']) ?></td>
                            <td><?= htmlspecialchars($emp['nome_funcionario'] ?? 'Não') ?></td>
                            <td><?= $emp['id_produto'] ?></td>
                            <td><?= htmlspecialchars($emp['titulo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($emp['data_devolucao_prevista'])) ?></td>
                            <td>
                                <span class="badge 
                                    <?= $emp['status'] == 'devolvido' ? 'bg-success' : 
                                       ($emp['status'] == 'atrasado' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= ucfirst($emp['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html>