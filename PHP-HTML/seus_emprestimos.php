<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

$id_usuario = $_SESSION['id_usuario'];

// Busca os empréstimos, livros e o nome do usuário
$sql = "SELECT e.id_emprestimo, e.data_emprestimo, e.data_devolucao_prevista, e.data_devolucao_real, e.status, p.id_produto, p.titulo, u.nome AS nome_usuario
        FROM emprestimo e 
        INNER JOIN item_emprestimo i ON e.id_emprestimo = i.id_emprestimo 
        INNER JOIN produto p ON i.id_produto = p.id_produto 
        INNER JOIN usuario u ON e.id_usuario = u.id_usuario 
        WHERE e.id_usuario = :id_usuario 
        ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Empréstimos</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>

    <center><h2>Seus Empréstimos</h2></center>

    <div class="container">
        <?php if (empty($emprestimos)): ?>
            <center><p>Você ainda não fez nenhum empréstimo.</p></center>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>ID do Livro</th>
                        <th>Título</th>
                        <th>Empréstimo</th>
                        <th>Devolução Prevista</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprestimos as $emp): ?>
                        <tr>
                            <td><?= htmlspecialchars($emp['nome_usuario']) ?></td>
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