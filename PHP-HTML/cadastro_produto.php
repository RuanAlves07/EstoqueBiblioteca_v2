<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php'

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Obter perfil do usuário
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];


// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $isbn = trim($_POST['isbn']);
    $id_categoria = $_POST['id_categoria'];
    $nome_autor = trim($_POST['nome_autor']);
    $nome_editora = trim($_POST['nome_editora']);
    $ano_publicacao = $_POST['ano_publicacao'] ?: null;
    $edicao = trim($_POST['edicao']);
    $quantidade_estoque = (int)$_POST['quantidade_estoque'];

    try {
        $pdo->beginTransaction();

        // Inserts para a tabela Autor
        $stmt = $pdo->prepare("SELECT id_autor FROM autor WHERE nome_autor = :nome_autor LIMIT 1");
        $stmt->bindParam(':nome_autor', $nome_autor);
        $stmt->execute();
        $autor = $stmt->fetch();

        if ($autor) {
            $id_autor = $autor['id_autor'];
        } else {
            $insert = $pdo->prepare("INSERT INTO autor (nome_autor) VALUES (:nome_autor)");
            $insert->bindParam(':nome_autor', $nome_autor);
            $insert->execute();
            $id_autor = $pdo->lastInsertId();
        }

        // Inserts para a tabela editora
        $stmt = $pdo->prepare("SELECT id_editora FROM editora WHERE nome_editora = :nome_editora LIMIT 1");
        $stmt->bindParam(':nome_editora', $nome_editora);
        $stmt->execute();
        $editora = $stmt->fetch();

        if ($editora) {
            $id_editora = $editora['id_editora'];
        } else {
            $insert = $pdo->prepare("INSERT INTO editora (nome_editora) VALUES (:nome_editora)");
            $insert->bindParam(':nome_editora', $nome_editora);
            $insert->execute();
            $id_editora = $pdo->lastInsertId();
        }

        // Inserir produto
        $sql = "INSERT INTO produto (titulo, isbn, id_categoria, id_autor, id_editora, ano_publicacao, edicao, quantidade_estoque) 
                VALUES (:titulo, :isbn, :id_categoria, :id_autor, :id_editora, :ano_publicacao, :edicao, :quantidade_estoque)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':isbn', $isbn);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':id_autor', $id_autor);
        $stmt->bindParam(':id_editora', $id_editora);
        $stmt->bindParam(':ano_publicacao', $ano_publicacao);
        $stmt->bindParam(':edicao', $edicao);
        $stmt->bindParam(':quantidade_estoque', $quantidade_estoque);
        $stmt->execute();

        $pdo->commit();
        $sucesso = "Produto cadastrado com sucesso!";
    } catch (Exception $e) {
        $pdo->rollback();
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}

// Carregar categorias para o select
$categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
<body>

    <nav>
        <ul class="menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
            <li class="dropdown">
                <a href="#"><?= $categoria ?></a>
                <ul class="dropdown-menu">
                    <?php foreach($arquivos as $arquivo): ?>
                    <li>
                        <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <center><h2>Cadastrar Produto</h2></center>

    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?= $erro ?></div>
    <?php endif; ?>
    <?php if (isset($sucesso)): ?>
        <div class="alert alert-success"><?= $sucesso ?></div>
    <?php endif; ?>

    <form action="cadastro_produto.php" method="POST">
        <label for="titulo">Título do Livro:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" required>

        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">Selecione uma categoria...</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>">
                    <?= htmlspecialchars($cat['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nome_autor">Nome do Autor:</label>
        <input type="text" id="nome_autor" name="nome_autor" required placeholder="Ex: Machado de Assis">

        <label for="nome_editora">Nome da Editora:</label>
        <input type="text" id="nome_editora" name="nome_editora" required placeholder="Ex: Editora Abril">

        <label for="edicao">Edição:</label>
        <input type="text" id="edicao" name="edicao" placeholder="Ex: 2ª edição">

        <label for="ano_publicacao">Ano de Publicação:</label>
        <input type="number" id="ano_publicacao" name="ano_publicacao" min="1000" max="2100" placeholder="Ex: 2020">

        <label for="quantidade_estoque">Quantidade em Estoque:</label>
        <input type="number" id="quantidade_estoque" name="quantidade_estoque" value="1" min="1" required>

        <br>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <br>
        <button type="reset" class="btn btn-secondary">Cancelar</button>
    </form>

    <center><a href="../PHP-HTML/principal.php" class="btn btn-primary">Voltar</a></center>

</body>
</html>