<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';


if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/estilos.css" />
</head>
    <body>
    <header>
        <div class="login">
            <h4>Bem-Vindo(a), <?php echo $_SESSION["usuario"];?>! Perfil de acesso: <?php echo $nome_perfil;?></h4>
        </div>

    </header>
    <div class="box-container">
        <div class="box-header">
            <h5>Atualização de Versão</h5>
        </div>
        <div class="box-body">
            <a href="documentacao.php" class="btn-documentacao">Ver documentação</a>
        </div>
    </div>

    <div class="box-container">
        <div class="box-header">
            <h5>Livros emprestados</h5>
        </div>
        <div class="box-body">
            
            <a href="emprestimo_de_livros.php" class="btn-documentacao">Ver livros emprestados</a>
        </div>
    </div>

        <div class="logout">
            <form action="logout.php" method="POST">
                <button type="submit">Logout</button>
            </form>
        </div>

    
</body>
</html>