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

        <center><h2>Excluir produto</h2></center>

        <?php if(!empty($usuarios)):?>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <center><table border="1" class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Perfil</th>
                    <th>Ações</th>
                </tr>
                <?php foreach($usuarios as $usuario):?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_usuario'])?></td>
                        <td><?= htmlspecialchars($usuario['nome'])?></td>
                        <td><?= htmlspecialchars($usuario['email'])?></td>
                        <td><?= htmlspecialchars($usuario['id_perfil'])?></td>
                        <td>
                            <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario']) ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </table></center>
            
                <?php else: ?>
                    <p>Nenhum usuário encontrado!</p>
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

