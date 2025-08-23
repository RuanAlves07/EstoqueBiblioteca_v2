<?php



?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
<body>
    <header>
        <div class="login">
            <h2>Bem-Vindo(a), <?php echo $_SESSION["usuario"];?>! Perfil de acesso: <?php echo $nome_perfil;?></h2>
        </div>
        </div>
    </header>
</body>
</html>