<?php

session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUARIO TEM PERMISSAO DE ADM
if ($_SESSION['perfil'] != 1){
    echo"<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

// INICIALIZA AS VARIAVEIS
$usuario = null;
$busca = null; 

if ($_SERVER["REQUEST_METHOD"]=="POST" ){
    if (!empty($_POST['busca_cliente'])) 
        $busca = trim($_POST['busca_cliente']);

    // VERIFICA SE A BUSCA É UM NUMERO (ID) OU UM NOME
    if($busca !== null && is_numeric($busca)){ 
       $sql =  "SELECT * FROM cliente WHERE id_cliente = :busca";
       $stmt =$pdo->prepare($sql);
       $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } elseif($busca !== null) { 
       $sql = "SELECT * FROM cliente WHERE nome_completo LIKE :busca_nome";
       $stmt =$pdo->prepare($sql);
       $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
    if (isset($stmt)) {
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O USUARIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA 
        if (!$usuario) {
            echo"<script>alert('cliente não encontrado');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
     <script src="scripts.js"></script>
     <link rel="stylesheet" href="../CSS/styles.css">
</head>
    <body>





        <center><h2>Alterar cliente</h2></center>

    <!-- FORMULARIO PARA ALTERAR FORNECEDOR -->
    <div class="container mt-4">
        <form method="POST" action="alterar_cliente.php">
            <label for="busca_cliente">Digite o ID ou Título do Produto:</label>
            <input type="text" id="busca_cliente" name="busca_cliente" value="<?= htmlspecialchars($busca ?? '') ?>" required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

    <?php if ($usuario): ?>
        <form action="processa_alteracao_cliente.php" method="POST" >

            <input type="hidden" name="id_cliente" value="<?=htmlspecialchars($usuario['id_cliente'])?>">

            <label for="nome_completo">Nome completo:</label>
            <input type="text" name="nome_completo" id="nome_completo" value="<?=htmlspecialchars($usuario['nome_completo'])?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" value="<?=htmlspecialchars($usuario['cpf'])?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="<?=htmlspecialchars($usuario['telefone'])?>" required>

            <label for="data_nascimento">Data de nascimento:</label>
            <input type="date" name="data_nascimento" id="data_nascimento" value="<?=htmlspecialchars($usuario['data_nascimento'])?>" required> 

            <button type="submit" class="btn btn-primary" >Alterar</button>
        </form>
        
            <?php endif; ?>
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
                </div>
            <center><a href="dashboard.php" class="btn btn-primary">Voltar</a></center>
    </body>
</html>