<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
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
        
        <center><h2>Cadastrar Fornecedor</h2></center>
        <form action="cadastro_fornecedor.php" method="POST">

            <label for="nome_fornecedor">Nome Fornecedor:</label>
            <input type="text" id="nome_fornecedor" name="nome_fornecedor" required>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required onkeyup="ValidarTelefoneQntd()">
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="contato">Contato:</label>
            <input type="text" id="contato" name="contato" required>
            
            <br>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <br>
            <button type="reset" class="btn btn-primary">Cancelar</button>
        </form>

        <center><a href="principal.php" class="btn btn-primary" >Voltar</a></center>
    </body>
</html>