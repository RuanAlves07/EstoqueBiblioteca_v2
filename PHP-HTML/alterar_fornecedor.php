<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtém o perfil do usuário
$id_perfil = $_SESSION['perfil'];

// Busca nome do perfil
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil, PDO::PARAM_INT);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'] ?? 'Perfil';

// Definição das permissões por perfil
$permissoes = [
    1 => [
        "Cadastrar" => ["cadastro_usuario.php", "cadastro_perfil.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php", "cadastro_funcionario.php"],
        "Buscar" => ["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar" => ["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir" => ["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"]
    ],
    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php", "alterar_fornecedor.php"]
    ],
    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php"]
    ],
    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"]
    ]
];

// Verifica se o perfil tem permissão para alterar fornecedores
if (!isset($permissoes[$id_perfil]['Alterar']) || !in_array('alterar_fornecedor.php', $permissoes[$id_perfil]['Alterar'])) {
    echo "<script>alert('Acesso negado.'); window.location.href='principal.php';</script>";
    exit();
}

// Menu do usuário
$opcoes_menu = $permissoes[$id_perfil] ?? [];

// Verifica se o ID foi passado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do fornecedor não informado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_fornecedor.php");
    exit();
}

$id_fornecedor = (int)$_GET['id'];

// Busca o fornecedor no banco
$sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
$stmt->execute();

$fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fornecedor) {
    $_SESSION['mensagem'] = "Fornecedor não encontrado.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: buscar_fornecedor.php");
    exit();
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_empresa']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contato = trim($_POST['contato']);

    // Validação básica
    if (empty($nome) || empty($endereco) || empty($telefone) || !$email || empty($contato)) {
        $erro = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
    } else {
        try {
            $sql_update = "UPDATE fornecedor 
                           SET nome_empresa = :nome, 
                               endereco = :endereco, 
                               telefone = :telefone, 
                               email = :email, 
                               contato = :contato 
                           WHERE id_fornecedor = :id";

            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                ':nome' => $nome,
                ':endereco' => $endereco,
                ':telefone' => $telefone,
                ':email' => $email,
                ':contato' => $contato,
                ':id' => $id_fornecedor
            ]);

            $_SESSION['mensagem'] = "Fornecedor <strong>" . htmlspecialchars($nome) . "</strong> atualizado com sucesso!";
            $_SESSION['msg_tipo'] = "success";

            header("Location: buscar_fornecedor.php");
            exit();

        } catch (PDOException $e) {
            $erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Fornecedor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Estilo personalizado -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background-color: #003366;
            color: white;
            padding: 0;
            margin-bottom: 20px;
        }
        .menu {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .menu li {
            position: relative;
            margin-right: 1rem;
        }
        .menu a {
            color: white;
            text-decoration: none;
            padding: 1rem 0.8rem;
            display: block;
            transition: background 0.3s;
        }
        .menu a:hover {
            background-color: #002b55;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #002b55;
            min-width: 200px;
            z-index: 1000;
            list-style: none;
            padding: 0;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu li a {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
        }
        .dropdown-menu li a:hover {
            background-color: #001f3f;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 {
            color: #003366;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .btn-primary {
            background-color: #003366;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
        }
        .btn {
            margin-right: 10px;
        }
        .alert {
            max-width: 600px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <!-- Menu Superior com Dropdown -->
    <nav class="navbar">
        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= htmlspecialchars($categoria) ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?= htmlspecialchars($arquivo) ?>">
                                    <?= ucfirst(str_replace(['_', '.php'], [' ', ''], basename($arquivo))) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- Mensagem de erro, se houver -->
    <?php if (isset($erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= htmlspecialchars($erro) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Formulário de Alteração -->
    <div class="container">
        <h2> Alterar Fornecedor</h2>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nome_empresa" class="form-label">Nome do Fornecedor</label>
                <input type="text" class="form-control" id="nome_empresa" name="nome_empresa"
                       value="<?= htmlspecialchars($fornecedor['nome_empresa']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" class="form-control" id="endereco" name="endereco"
                       value="<?= htmlspecialchars($fornecedor['endereco']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="telefone" name="telefone"
                       value="<?= htmlspecialchars($fornecedor['telefone']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($fornecedor['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="contato" class="form-label">Contato</label>
                <input type="text" class="form-control" id="contato" name="contato"
                       value="<?= htmlspecialchars($fornecedor['contato']) ?>" required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="buscar_fornecedor.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>

</body>
</html>