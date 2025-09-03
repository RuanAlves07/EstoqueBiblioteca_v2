<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}


// Carregar categorias para o select
$produtos = $pdo->query("SELECT titulo FROM produto ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emprestimo de livro</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
    <body>
        <center><h2>Emprestimo de livros</h2></center>
        <div class="container">
        <label for="titulo">Selecione o livro:</label>
        <select id="titulo" name="titulo" required>
            <option value="">Selecione um livro...</option>
            <?php foreach ($produtos as $pro): ?>
                <option value="<?= $pro['titulo'] ?>">
                    <?= htmlspecialchars($pro['titulo']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        </div>
    </body>
</html>