<!-- emprestimos_de_livros.php -->
<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';


// Busca produtos
$stmt = $pdo->query("SELECT id_produto, titulo, quantidade_estoque FROM produto ORDER BY titulo");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo de Livros</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="../JS/validacoes.js"></script>
</head>
<body>

    <form action="processa_emprestimo.php" method="POST">
        <center><h2>Empréstimo de Livros</h2></center>

        <div class="container">
            <label for="id_produto">Selecione o livro:</label>
            <select id="id_produto" name="id_produto" required onchange="atualizaEstoque(this)">
                <option value="">Selecione um livro...</option>
                <?php foreach ($produtos as $pro): ?>
                    <option value="<?= $pro['id_produto'] ?>" 
                            data-quantidade="<?= (int)$pro['quantidade_estoque'] ?>">
                        <?= htmlspecialchars($pro['titulo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantidade_estoque">Quantidade em Estoque:</label>
            <input type="number" name="quantidade_estoque" id="quantidade_estoque" readonly>

            <label for="email">Seu E-mail:</label>
            <input type="email" id="email" name="email" required 
                   value="<?= htmlspecialchars($_SESSION['email']) ?>">

            <label for="senha">Repita sua senha:</label>
            <input type="password" id="senha" name="senha" required>

            <label>
                <input type="checkbox" onclick="mostrarSenha()"> Mostrar Senha
            </label>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Confirmar Empréstimo</button>
            <button type="reset" class="btn btn-danger">Cancelar</button>
        </div>
    </form>

</body>
</html>