<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$produtos = $pdo->query("SELECT titulo, quantidade_estoque FROM produto ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo de Livros</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
    

    <form action="processar_emprestimo.php" method="POST">
        <center><h2>Empréstimo de Livros</h2></center>

        <div class="container">
            <label for="titulo">Selecione o livro:</label>
            <select id="titulo" name="titulo" required>
                <option value="">Selecione um livro...</option>
                <?php foreach ($produtos as $pro): ?>
                    <option value="<?= htmlspecialchars($pro['titulo']) ?>" 
                            data-quantidade="<?= (int)$pro['quantidade_estoque'] ?>">
                        <?= htmlspecialchars($pro['titulo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantidade_estoque">Quantidade em Estoque:</label>
            <input type="number" name="quantidade_estoque" id="quantidade_estoque" readonly>

            <label for="cpf">CPF do Cliente:</label>
            <input type="text" id="cpf" name="cpf" required>

            <label for="senha">Repita sua senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Confirmar Reserva</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
        </div>
    </form>

    <!-- Inclui o script externo de validações -->
    <script src="../JS/validacoes.js"></script>
</body>
</html>