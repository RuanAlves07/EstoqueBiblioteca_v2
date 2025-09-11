<?php
ob_start();
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Processar a busca
$produtos = [];
$busca = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busca = trim($_POST['busca']);
    if (!empty($busca)) {
        // Busca por ID ou título
        $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora FROM produto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                LEFT JOIN autor a ON p.id_autor = a.id_autor
                LEFT JOIN editora e ON p.id_editora = e.id_editora
                WHERE p.id_produto = :busca OR p.titulo LIKE :titulo_busca 
                ORDER BY p.titulo ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', $busca, PDO::PARAM_INT);
        $stmt->bindValue(':titulo_busca', "%$busca%", PDO::PARAM_STR);
    } else {
        // Se não houver busca, traz todos
        $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora FROM produto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                LEFT JOIN autor a ON p.id_autor = a.id_autor
                LEFT JOIN editora e ON p.id_editora = e.id_editora
                ORDER BY p.titulo ASC";
        $stmt = $pdo->prepare($sql);
    }
} else {
    // Se não for POST, traz todos os produtos
    $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora FROM produto p
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
            LEFT JOIN autor a ON p.id_autor = a.id_autor
            LEFT JOIN editora e ON p.id_editora = e.id_editora
            ORDER BY p.titulo ASC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Produtos</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Corrigido: removido espaços no final do URL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css  " rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <center><h2>Lista de livros</h2></center>

        <!-- Formulário de busca -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="busca" class="form-label">Digite o ID ou Nome do produto (opcional)</label>
                <input type="text" class="form-control" id="busca" name="busca" value="<?= htmlspecialchars($busca) ?>">
            </div>
            <center><button type="submit" class="btn btn-primary">Pesquisar</button></center>
        </form>

        <br>

        <!-- Exibir resultados -->
        <?php if (!empty($produtos)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título do Livro</th>
                            <th>ISBN</th>
                            <th>Categoria</th>
                            <th>Autor</th>
                            <th>Editora</th>
                            <th>Ano de Publicação</th>
                            <th>Edição</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?= htmlspecialchars($produto['id_produto']) ?></td>
                            <td><?= htmlspecialchars($produto['titulo']) ?></td>
                            <td><?= htmlspecialchars($produto['isbn']) ?></td>
                            <td><?= htmlspecialchars($produto['nome_categoria'] ?? $produto['id_categoria']) ?></td>
                            <td><?= htmlspecialchars($produto['nome_autor'] ?? $produto['id_autor']) ?></td>
                            <td><?= htmlspecialchars($produto['nome_editora'] ?? $produto['id_editora']) ?></td>
                            <td><?= htmlspecialchars($produto['ano_publicacao']) ?></td>
                            <td><?= htmlspecialchars($produto['edicao']) ?></td>
                            <td><?= htmlspecialchars($produto['quantidade_estoque']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning"
                                   href="alterar_produto.php?id=<?= (int)$produto['id_produto'] ?>">
                                   Alterar
                                </a>
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

        <br>
        <center>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </center>
    </div>

    <!-- Scripts no final -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js  "></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js  "></script>
 
</body>
</html>