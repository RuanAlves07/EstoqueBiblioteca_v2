<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if (isset($_GET['nome']) && isset($_GET['descricao']) && isset($_GET['categoria']) && isset($_GET['quantidade']) && isset($_GET['preco']) && isset($_GET['fornecedor']) )
        {
            echo "Nome do produto: ".$_GET['nome'];
            echo "Descrição do produto: ".$_GET['descricao']." Anos";
            echo "Categoria do produto: ".$_GET['categoria'];
            echo "Quantidade do produto: ".$_GET['quantidade'];
            echo "Preço do produto: ".$_GET['preco'];
            echo "Fornecedor do produto: : ".$_GET['fornecedor'];
        } 

        ?>
</body>
</html>