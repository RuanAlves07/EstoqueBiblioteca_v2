<?php
session_start();
require_once 'conexao.php';

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

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL
$permissoes = [
    1 => [
        "Cadastrar" => ["cadastro_produto.php", "cadastro_perfil.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_funcionario.php"],
        "Buscar" => ["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar" => ["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir" => ["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"],
        "Emprestimo" => ["emprestimo_de_livros.php"]
    ],
    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php", "alterar_fornecedor.php"],
        "Emprestimo" => ["emprestimo.php"]
    ],
    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php"],
        "Emprestimo" => ["emprestimo.php"]
    ],
    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"],
        "Emprestimo" => ["emprestimo.php"]
    ]
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_perfil_novo = trim($_POST['nome_perfil']);
    $descricao = trim($_POST['descricao']);
    
    try {
        // Verificar se o perfil já existe
        $stmt = $pdo->prepare("SELECT id_perfil FROM perfil WHERE nome_perfil = :nome_perfil LIMIT 1");
        $stmt->bindParam(':nome_perfil', $nome_perfil_novo);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $erro = "Este perfil já existe no sistema!";
        } else {
            // Inserir novo perfil
            $sql = "INSERT INTO perfil (nome_perfil, descricao) VALUES (:nome_perfil, :descricao)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_perfil', $nome_perfil_novo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->execute();
            
            $sucesso = "Perfil cadastrado com sucesso!";
        }
    } catch (Exception $e) {
        $erro = "Erro ao cadastrar perfil: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Perfil</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link rel="stylesheet" href="../CSS/MenuDropdown.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script>
        function validarNomePerfil() {
            const nome = document.getElementById('nome_perfil').value;
            const regex = /^[a-zA-ZÀ-ÿ\s]+$/;
            
            if (!regex.test(nome) && nome.length > 0) {
                document.getElementById('nome_perfil').style.borderColor = 'red';
                return false;
            } else {
                document.getElementById('nome_perfil').style.borderColor = '#ccc';
                return true;
            }
        }
        
        function validarFormulario() {
            return validarNomePerfil();
        }
    </script>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    
     Menu dropdown usando as classes do MenuDropdown.css 
    <nav class="menu-dropdown-container">
        <ul class="menu-dropdown">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
            <li class="has-dropdown">
                <a href="#"><?= $categoria ?></a>
                <ul class="dropdown-submenu">
                    <?php foreach($arquivos as $arquivo): ?>
                    <li>
                        <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <center><h2>Cadastro de Perfil</h2></center>

     Mensagens de erro e sucesso 
    <?php if (isset($erro)): ?>
        <div class="alert alert-danger" style="max-width: 600px; margin: 20px auto;">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($sucesso)): ?>
        <div class="alert alert-success" style="max-width: 600px; margin: 20px auto;">
            <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php endif; ?>

     Formulário de cadastro de perfil 
    <form method="POST" action="cadastro_perfil.php" onsubmit="return validarFormulario()">
        <label for="nome_perfil">Nome do Perfil:</label>
        <input type="text" id="nome_perfil" name="nome_perfil" required 
               onkeyup="validarNomePerfil()" 
               placeholder="Ex: Administrador, Bibliotecário, Usuário"
               maxlength="50">
        
        <label for="descricao">Descrição do Perfil:</label>
        <textarea id="descricao" name="descricao" rows="4" 
                  placeholder="Descreva as responsabilidades e características deste perfil..."
                  style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 15px; resize: vertical; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;"></textarea>

        <button type="submit" class="btn btn-primary">Cadastrar Perfil</button>
        <button type="reset" class="btn btn-danger">Cancelar</button>
    </form>
    
    <center><a href="principal.php" class="btn btn-primary">Voltar</a></center>
</body>
</html>
