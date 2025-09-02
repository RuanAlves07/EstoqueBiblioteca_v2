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
    if (!empty($_POST['busca_produto'])) 
        $busca = trim($_POST['busca_produto']);

    // VERIFICA SE A BUSCA É UM NUMERO (ID) OU UM NOME
    if($busca !== null && is_numeric($busca)){ 
       $sql =  "SELECT * FROM produto WHERE id_produto = :busca";
       $stmt =$pdo->prepare($sql);
       $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } elseif($busca !== null) { 
       $sql = "SELECT * FROM produto WHERE titulo LIKE :busca_nome";
       $stmt =$pdo->prepare($sql);
       $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }
    if (isset($stmt)) {
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O USUARIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA 
        if (!$usuario) {
            echo"<script>alert('Usuário não encontrado');</script>";
        }
    }
}

$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// Carregar categorias para o select
$categorias = $pdo->query("SELECT id_categoria, nome_categoria FROM categoria ORDER BY nome_categoria")->fetchAll(PDO::FETCH_ASSOC);
$autores = $pdo->query("SELECT id_autor, nome_autor FROM autor ORDER BY nome_autor")->fetchAll(PDO::FETCH_ASSOC);
$editoras = $pdo->query("SELECT id_editora, nome_editora FROM editora ORDER BY nome_editora")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar produto</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <script src="validacoes.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- CERTIFIQUE-SE DE QUE O JAVASCRIPT ESTÁ SENDO CARREGADO CORRETAMENTE  -->
     <script src="scripts.js"></script>
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

        <center><h2>Alterar produto</h2></center>

    <!-- FORMULARIO PARA ALTERAR PRODUTOS -->

    <form action="alterar_produto.php" method="POST">
        <label for="busca_produto">Digite o ID ou NOME do usuário:</label>
        <input type="text" id="busca_produto" name="busca_produto" required onkeyup="buscarSugestoes()">
        <div id="sugestoes"></div>
        <button type="submit" class="btn btn-primary" >Buscar</button>
    </form>


            <?php if ($usuario): ?>
    <form action="processa_alteracao_produto.php" method="POST" onsubmit="return validarUsuario();">
        <input type="hidden" name="id_produto" value="<?= htmlspecialchars($usuario['id_produto']) ?>">

        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($usuario['titulo']) ?>" required>

        <?php if (!empty($usuario['isbn'])): ?>
            <label for="isbn">ISBN:</label>
            <input type="text" name="isbn" id="isbn" value="<?= htmlspecialchars($usuario['isbn']) ?>" required>
        <?php endif; ?>

        <label for="id_categoria">Categoria:</label>
        <select id="id_categoria" name="id_categoria" required>
            <option value="">Selecione uma categoria...</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria'] ?>" <?= $cat['id_categoria'] == $usuario['id_categoria'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome_categoria']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="nome_autor">Autor:</label>
        <input type="text" id="nome_autor" name="nome_autor" required>
            <?php foreach ($autores as $aut): ?> value="<?= $aut['id_autor'] ?>" <?= $aut['id_autor'] == $usuario['id_autor'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($aut['nome_autor']) ?>
            <?php endforeach; ?>

        <!-- Editora -->
        <label for="nome_editora">Editora:</label>
        <input type="text" name="nome_editora" id="nome_editora" 
            value="<?= htmlspecialchars($usuario['nome_editora'] ?? '') ?>" required>

        <!-- Ano de Publicação -->
        <label for="ano_publicacao">Ano de Publicação:</label>
        <input type="number" name="ano_publicacao" id="ano_publicacao" 
            value="<?= htmlspecialchars($usuario['ano_publicacao'] ?? '') ?>" required>

        <!-- Quantidade em Estoque -->
        <label for="quantidade_estoque">Quantidade em Estoque:</label>
        <input type="number" name="quantidade_estoque" id="quantidade_estoque" 
            value="<?= htmlspecialchars($usuario['quantidade_estoque'] ?? '') ?>" required>

        <br>
        <button type="submit" class="btn btn-primary">Alterar</button>
        <br>
        <button type="reset" class="btn btn-secondary">Cancelar</button>       
    </form>
    <?php endif; ?>
            <div class="logout">
                <form action="logout.php" method="POST">
                    <button type="submit">Logout</button>
                </form>
                </div>
            <center><a href="principal.php" class="btn btn-primary">Voltar</a></center>
    </body>
</html>

