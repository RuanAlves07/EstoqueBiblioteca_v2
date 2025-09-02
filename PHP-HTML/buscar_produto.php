<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

$usuarios = [];

// SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU NOME

if ($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])){
 $busca = trim($_POST['busca']);
 
 // VERIFICA SE A BUSCA É UM NUMERO (ID) OU UM NOME

 if(is_numeric($busca)){
    $sql =  "SELECT * FROM produto WHERE id_produto = :busca ORDER BY titulo ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
 } else {
    $sql = "SELECT * FROM produto WHERE titulo LIKE :busca_nome ORDER BY titulo ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
 }
} else{
    $sql = "SELECT * FROM produto ORDER BY titulo ASC";
    $stmt =$pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar produto</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

        <nav>
            <ul class="menu">
                <?php foreach($opcoes_menu as $categoria=>$arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= $categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach($arquivos as $arquivo): ?>
                        <li>
                            <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_"," ",basename($arquivo,".php")))?></a>
                        </li>
                            <?php endforeach; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>

    <center><h2>Lista de Usuários</h2></center>

    <!-- FORMULARIO PARA BUSCAR PRODUTO -->

    <form action="buscar_produto.php" method="POST">
        <label for="busca">Digite o ID ou NOME do produto (opcional)</label>
        <input type="text" id="busca" name="busca">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <?php if(!empty($usuarios)):?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <center><table border="1" class="table table-bordered"> 
            <tr>
                <th>ID</th>
                <th>Titulo do livro</th>
                <th>ISBN</th>
                <th>Categoria</th>
                <th>Autor</th>
                <th>Editora</th>
                <th>Ano de publicação</th>
                <th>Edição</th>
                <th>Quantidade</th>
            </tr>

            <?php foreach($usuarios as $usuario): ?>
                <tr>
                    <td><?=htmlspecialchars($usuario['id_produto']) ?></td>
                    <td><?=htmlspecialchars($usuario['titulo']) ?></td> 
                    <td><?=htmlspecialchars($usuario['isbn']) ?></td>
                    <td><?=htmlspecialchars($usuario['id_categoria']) ?></td>
                    <td><?=htmlspecialchars($usuario['id_autor']) ?></td>
                    <td><?=htmlspecialchars($usuario['id_editora']) ?></td>
                    <td><?=htmlspecialchars($usuario['ano_publicacao']) ?></td>
                    <td><?=htmlspecialchars($usuario['edicao']) ?></td>
                    <td><?=htmlspecialchars($usuario['quantidade_estoque']) ?></td>

                    <td>
                        <a href="alterar_produto.php?id=<?=htmlspecialchars($usuario['id_produto'])?>">Alterar</a>
                        <a href="excluir_produto.php?id=<?=htmlspecialchars($usuario['id_produto'])?>"onclick="return confirm('Tem certeza que deseja excluir esse usuario?')">Excluir</a>
                    </td> 
                </tr>
            <?php endforeach; ?>
        </table></center>
    <?php else: ?>
        <center><p> Nenhum produto encontrado.</p></center>
    <?php endif; ?>
    <br>
    <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
                </div>
    <center><a href="principal.php" class="btn btn-primary" >Voltar</a></center>

</body>
</html>