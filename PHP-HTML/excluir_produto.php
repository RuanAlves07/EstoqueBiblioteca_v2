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

// Busca todos os produtos com informações de categoria, autor e editora
try {
    $sql = "SELECT p.id_produto, p.titulo, p.isbn, c.nome_categoria, a.nome_autor, e.nome_editora, p.ano_publicacao, p.edicao, p.quantidade_estoque 
            FROM produto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            LEFT JOIN autor a ON p.id_autor = a.id_autor
            LEFT JOIN editora e ON p.id_editora = e.id_editora
            ORDER BY p.titulo ASC";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produtos = [];
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}

// Processa exclusão
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = (int)$_GET['id'];

    // Verifica se o produto existe
    $sql_check = "SELECT titulo FROM produto WHERE id_produto = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_produto, PDO::PARAM_INT);
    $stmt_check->execute();
    $produto = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        $_SESSION['mensagem'] = "Produto não encontrado.";
        $_SESSION['msg_tipo'] = "warning";
    } else {
        try {
            $sql_delete = "DELETE FROM produto WHERE id_produto = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id_produto, PDO::PARAM_INT);

            if ($stmt_delete->execute()) {
                $_SESSION['mensagem'] = "Produto <strong>" . htmlspecialchars($produto['titulo']) . "</strong> excluído com sucesso!";
                $_SESSION['msg_tipo'] = "success";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir o produto.";
                $_SESSION['msg_tipo'] = "danger";
            }
        } catch (PDOException $e) {
            // Trata erro de chave estrangeira (ex: produto vinculado a uma venda)
            if ($e->getCode() == 23000) {
                $_SESSION['mensagem'] = "Não é possível excluir: este produto está vinculado a uma venda ou registro.";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir: " . $e->getMessage();
            }
            $_SESSION['msg_tipo'] = "danger";
        }
    }

    // Redireciona para evitar reexclusão ao atualizar a página
    header("Location: excluir_produto.php");
    ob_end_clean();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir livros</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .container { max-width: 1000px; }
        .table th { text-align: center; vertical-align: middle; }
        .table td { vertical-align: middle; }
        .btn-danger { font-size: 0.875rem; }
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
        <h2 class="text-center mb-4">Excluir Produto</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($produtos)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>ISBN</th>
                            <th>Categoria</th>
                            <th>Autor</th>
                            <th>Editora</th>
                            <th>Ano</th>
                            <th>Edição</th>
                            <th>Estoque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                            <td><?= htmlspecialchars($produto['titulo']) ?></td>
                            <td><?= htmlspecialchars($produto['isbn'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['nome_categoria'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['nome_autor'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['nome_editora'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['ano_publicacao'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['edicao'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($produto['quantidade_estoque']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-danger" 
                                   href="excluir_produto.php?id=<?= (int)$produto['id_produto'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum produto encontrado.</p></center>
        <?php endif; ?>


    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

</body>
</html>