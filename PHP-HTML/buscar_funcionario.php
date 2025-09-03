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
    $sql =  "SELECT * FROM funcionario WHERE id_funcionario = :busca ORDER BY nome_completo ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
 } else {
    $sql = "SELECT * FROM funcionario WHERE nome_completo LIKE :busca_nome ORDER BY nome_completo ASC";
    $stmt =$pdo->prepare($sql);
    $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
 }
} else{
    $sql = "SELECT * FROM funcionario ORDER BY nome_completo ASC";
    $stmt =$pdo->prepare($sql);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar funcionario</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <center><h2>Lista de funcionarios</h2></center>

    <!-- FORMULARIO PARA BUSCAR FUNCIONARIO -->

    <form action="buscar_funcionario.php" method="POST">
        <label for="busca">Digite o ID ou NOME do funcionario (opcional)</label>
        <input type="text" id="busca" name="busca">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </form>

    <?php if(!empty($usuarios)):?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <center><table border="1" class="table table-bordered"> 
            <tr>
                <th>ID do funcionario</th>
                <th>Nome completo</th>
                <th>CPF</th>
                <th>Cargo</th>
                <th>Telefone</th>
                <th>Data de admissão</th>

            </tr>

            <?php foreach($usuarios as $usuario): ?>
                <tr>
                    <td><?=htmlspecialchars($usuario['id_funcionario']) ?></td>
                    <td><?=htmlspecialchars($usuario['nome_completo']) ?></td> 
                    <td><?=htmlspecialchars($usuario['cpf']) ?></td>
                    <td><?=htmlspecialchars($usuario['cargo']) ?></td>
                    <td><?=htmlspecialchars($usuario['telefone']) ?></td>
                    <td><?=htmlspecialchars($usuario['data_admissao']) ?></td>
                    <td>
                        <a href="alterar_funcionario.php?id=<?=htmlspecialchars($usuario['id_funcionario'])?>">Alterar</a>
                        <a href="excluir_funcionario.php?id=<?=htmlspecialchars($usuario['id_funcionario'])?>"onclick="return confirm('Tem certeza que deseja excluir esse funcionario?')">Excluir</a>
                    </td> 
                </tr>
            <?php endforeach; ?>
        </table></center>
    <?php else: ?>
        <center><p> Nenhum funcionario encontrado.</p></center>
    <?php endif; ?>
    <br>
    <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
                </div>
    <center><a href="dashboard.php" class="btn btn-primary" >Voltar</a></center>

</body>
</html>