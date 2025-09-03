<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';


if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}


// INICIALIZA AS VARIAVEIS
$usuario = null;

// BUSCA TODOS OS USUARIOS CADASTRADOS EM ORDEM ALFABETICA

$sql = "SELECT * FROM cliente ORDER BY nome_completo ASC";
$stmt = $pdo->prepare($sql);
$stmt -> execute();
$usuarios = $stmt ->fetchAll(PDO::FETCH_ASSOC);

// SE UM ID FOR PASSADO VIA GET, EXCLUI O USUARIO 

if (isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];

    // EXCLUI O PRODUTO DO BANCO DE DADOS

    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $pdo->prepare($sql);
    $stmt ->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo"<script>alert('Cliente excluido com sucesso!');window.location.href='excluir_cliente.php';</script>";
    } else {
        echo"<script>alert('Erro ao excluir o Cliente!');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusão de cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>
    <body>

        <center><h2>Excluir cliente</h2></center>

        <?php if(!empty($usuarios)):?>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <center><table border="1" class="table table-bordered">
                <tr>
                    <th>ID Cliente</th>
                    <th>Nome completo</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Data de nascimento</th>

                </tr>
                <?php foreach($usuarios as $usuario):?>
                    <tr>
                        <td><?= htmlspecialchars($usuario['id_cliente'])?></td>
                        <td><?= htmlspecialchars($usuario['nome_completo'])?></td>
                        <td><?= htmlspecialchars($usuario['cpf'])?></td>
                        <td><?= htmlspecialchars($usuario['telefone'])?></td>
                        <td><?= htmlspecialchars($usuario['data_nascimento'])?></td>
                        <td>
                            <a href="excluir_cliente.php?id=<?= htmlspecialchars($usuario['id_cliente']) ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </table></center>
            
                <?php else: ?>
                    <center><p>Nenhum cliente encontrado!</p></center>
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

