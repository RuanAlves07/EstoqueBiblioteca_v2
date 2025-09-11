<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado'); window.location.href='dashboard.php';</script>";
    exit;
}

// INICIALIZA AS VARIÁVEIS
$produto = null;
$busca = null;

// Verifica se foi passado ID via GET (do link do buscar_livro.php)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora, f.nome_fantasia AS nome_fornecedor, p.id_fornecedor 
            FROM produto p 
            LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
            LEFT JOIN autor a ON p.id_autor = a.id_autor 
            LEFT JOIN editora e ON p.id_editora = e.id_editora 
            LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor 
            WHERE p.id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "<script>alert('Livro não encontrado');</script>";
    }
} else {
    // BUSCA DE PRODUTO
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca_produto'])) {
        $busca = trim($_POST['busca_produto']);

        if (is_numeric($busca)) {
            $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora, f.nome_fantasia AS nome_fornecedor, p.id_fornecedor 
                    FROM produto p 
                    LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                    LEFT JOIN autor a ON p.id_autor = a.id_autor 
                    LEFT JOIN editora e ON p.id_editora = e.id_editora 
                    LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor 
                    WHERE p.id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT p.*, c.nome_categoria, a.nome_autor, e.nome_editora, f.nome_fantasia AS nome_fornecedor, p.id_fornecedor 
                    FROM produto p 
                    LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                    LEFT JOIN autor a ON p.id_autor = a.id_autor 
                    LEFT JOIN editora e ON p.id_editora = e.id_editora 
                    LEFT JOIN fornecedor f ON p.id_fornecedor = f.id_fornecedor 
                    WHERE p.titulo LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto) {
            echo "<script>alert('Livro não encontrado');</script>";
        }
    }
}

// CARREGA CATEGORIAS PARA O SELECT
$categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);

// CARREGA FORNECEDORES PARA O SELECT
$fornecedores = $pdo->query("SELECT id_fornecedor, nome_fantasia FROM fornecedor ORDER BY nome_fantasia")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar livros</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css    " rel="stylesheet">
    <style>
        .container { max-width: 800px; }
        .form-group { margin-bottom: 1rem; }
        .logout { margin-top: 20px; }
        .text-muted { font-size: 0.875em; }
    </style>
</head>
<body>

    <center><h2 class="mb-4">Alterar Livros</h2></center>

    <div class="container mt-4">
        <!-- FORMULÁRIO DE BUSCA -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="busca_produto">Buscar por ID ou Título:</label>
                <input type="text"
                       id="busca_produto"
                       name="busca_produto"
                       value="<?= htmlspecialchars($busca ?? '') ?>"
                       class="form-control"
                       placeholder="Digite o ID ou título do livro"
                       required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Livro</button>
        </form>

        <!-- FORMULÁRIO DE ALTERAÇÃO (APARECE SE ENCONTRAR) -->
        <?php if ($produto): ?>
        <hr>
        <form action="processa_alteracao_livro.php" method="POST" onsubmit="return validarProduto();">
            <input type="hidden" name="id_produto" value="<?= htmlspecialchars($produto['id_produto']) ?>">

            <!-- Título -->
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text"
                       name="titulo"
                       id="titulo"
                       value="<?= htmlspecialchars($produto['titulo']) ?>"
                       class="form-control"
                       required>
            </div>

            <!-- ISBN -->
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text"
                       name="isbn"
                       id="isbn"
                       value="<?= htmlspecialchars($produto['isbn'] ?? '') ?>"
                       class="form-control"
                       placeholder="978-0-123-45678-9">
            </div>

            <!-- Categoria -->
            <div class="form-group">
                <label for="id_categoria">Categoria:</label>
                <select name="id_categoria" id="id_categoria" class="form-control" required>
                    <option value="">Selecione uma categoria...</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id_categoria'] ?>"
                            <?= ($cat['id_categoria'] == $produto['id_categoria']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nome_categoria']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Autor -->
            <div class="form-group">
                <label for="nome_autor">Autor:</label>
                <input type="text"
                       name="nome_autor"
                       id="nome_autor"
                       value="<?= htmlspecialchars($produto['nome_autor'] ?? '') ?>"
                       class="form-control"
                       placeholder="Nome do autor"
                       required>
                <input type="hidden" name="id_autor" value="<?= htmlspecialchars($produto['id_autor'] ?? '') ?>">
            </div>

            <!-- Editora -->
            <div class="form-group">
                <label for="nome_editora">Editora:</label>
                <input type="text"
                       name="nome_editora"
                       id="nome_editora"
                       value="<?= htmlspecialchars($produto['nome_editora'] ?? '') ?>"
                       class="form-control"
                       placeholder="Nome da editora"
                       required>
                <input type="hidden" name="id_editora" value="<?= htmlspecialchars($produto['id_editora'] ?? '') ?>">
            </div>

            <!-- Ano de Publicação -->
            <div class="form-group">
                <label for="ano_publicacao">Ano de Publicação:</label>
                <input type="number"
                       name="ano_publicacao"
                       id="ano_publicacao"
                       value="<?= htmlspecialchars($produto['ano_publicacao'] ?? '') ?>"
                       class="form-control"
                       min="1000"
                       max="<?= date('Y') + 1 ?>"
                       required>
            </div>

            <!-- Edição -->
            <div class="form-group">
                <label for="edicao">Edição:</label>
                <input type="text"
                       name="edicao"
                       id="edicao"
                       value="<?= htmlspecialchars($produto['edicao'] ?? '') ?>"
                       class="form-control"
                       placeholder="1ª edição, 2ª edição...">
            </div>

            <!-- Quantidade em Estoque -->
            <div class="form-group">
                <label for="quantidade_estoque">Quantidade em Estoque:</label>
                <input type="number"
                       name="quantidade_estoque"
                       id="quantidade_estoque"
                       value="<?= htmlspecialchars($produto['quantidade_estoque'] ?? '') ?>"
                       class="form-control"
                       min="0"
                       required>
            </div>

            
            <div class="form-group">
                <label for="id_fornecedor">Fornecedor:</label>
                <select name="id_fornecedor" id="id_fornecedor" class="form-control" required>
                    <option value="">Selecione um fornecedor...</option>
                    <?php foreach ($fornecedores as $forn): ?>
                        <option value="<?= $forn['id_fornecedor'] ?>"
                            <?= ($forn['id_fornecedor'] == $produto['id_fornecedor']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($forn['nome_fantasia']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Livro</button>
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