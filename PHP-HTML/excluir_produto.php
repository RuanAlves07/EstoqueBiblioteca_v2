<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';


if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// OBTENDO O NOME DO PERFIL DO USUARIO LOGADO 

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];


// INICIALIZA AS VARIAVEIS
$usuario = null;

// BUSCA TODOS OS USUARIOS CADASTRADOS EM ORDEM ALFABETICA

$sql = "SELECT * FROM produto ORDER BY titulo ASC";
$stmt = $pdo->prepare($sql);
$stmt -> execute();
$usuarios = $stmt ->fetchAll(PDO::FETCH_ASSOC);

// SE UM ID FOR PASSADO VIA GET, EXCLUI O USUARIO 

if (isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];

    // EXCLUI O PRODUTO DO BANCO DE DADOS

    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt ->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo"<script>alert('Produto excluido com sucesso!');window.location.href='excluir_produto.php';</script>";
    } else {
        echo"<script>alert('Erro ao excluir o Produto!');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão de produtos</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
    <body>

        <center><h2>Excluir produto</h2></center>

        <?php if(!empty($usuarios)):?>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <center><table border="1" class="table table-bordered">
                <tr>
                    <th>ID Produto</th>
                    <th>Titulo do livro</th>
                    <th>ISBN</th>
                    <th>Categoria</th>
                    <th>Autor</th>
                    <th>Editora</th>
                    <th>Ano de publicação</th>
                    <th>Edição</th>
                    <th>Quantidade no estoque</th>
                </tr>
                <?php foreach($usuarios as $usuario):?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_produto'])?></td>
                        <td><?= htmlspecialchars($usuario['titulo'])?></td>
                        <td><?= htmlspecialchars($usuario['isbn'])?></td>
                        <td><?= htmlspecialchars($usuario['id_categoria'])?></td>
                        <td><?= htmlspecialchars($usuario['id_autor'])?></td>
                        <td><?= htmlspecialchars($usuario['id_editora'])?></td>
                        <td><?= htmlspecialchars($usuario['ano_publicacao'])?></td>
                        <td><?= htmlspecialchars($usuario['edicao'])?></td>
                        <td><?= htmlspecialchars($usuario['quantidade_estoque'])?></td>
                        <td>
                            <a href="excluir_produto.php?id=<?= htmlspecialchars($usuario['id_produto']) ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </table></center>
            
                <?php else: ?>
                    <center><p>Nenhum usuário encontrado!</p></center>
                <?php endif; ?>
                <br>

                <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
                </div>
                <center><a class="btn btn-primary" href="principal.php">Voltar</a></center>
    </body>
</html>

