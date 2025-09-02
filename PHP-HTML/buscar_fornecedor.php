<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtém o nome do perfil do usuário
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'] ?? 'Perfil Desconhecido';

// Definição das permissões por perfil
$permissoes = [
    1 => [
        "Cadastrar" => ["cadastro_produto.php", "cadastro_perfil.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_funcionario.php"],
        "Buscar" => ["buscar_usuario.php", "buscar_perfil.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar" => ["alterar_usuario.php", "alterar_perfil.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir" => ["excluir_usuario.php", "excluir_perfil.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"],
        "Emprestimo" => ["emprestimo_de_livros.php"],
    ],
    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php", "alterar_fornecedor.php"],
        "Emprestimo" => ["emprestimo.php"],
    ],
    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php"],
        "Emprestimo" => ["emprestimo.php"],
    ],
    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"],
        "Emprestimo" => ["emprestimo.php"],
    ],
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];

// Processar a busca
$fornecedores = [];
$busca = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $busca = trim($_POST['busca']);
    if (!empty($busca)) {
        // Busca por ID ou nome
        $sql = "SELECT * FROM fornecedor 
                WHERE id_fornecedor = :busca 
                OR nome_empresa LIKE :nome_busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca', $busca, PDO::PARAM_INT);
        $stmt->bindValue(':nome_busca', "%$busca%", PDO::PARAM_STR);
    } else {
        // Se não houver busca, traz todos
        $sql = "SELECT * FROM fornecedor";
        $stmt = $pdo->prepare($sql);
    }
} else {
    // Se não for POST, traz todos os fornecedores
    $sql = "SELECT * FROM fornecedor";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Fornecedor</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Corrigido: removido espaços no final do URL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Menu de navegação -->
    <nav>
        <ul class="menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
            <li class="dropdown">
                <a href="#"><?= htmlspecialchars($categoria) ?></a>
                <ul class="dropdown-menu">
                    <?php foreach($arquivos as $arquivo): ?>
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

    <div class="container mt-4">
        <center><h2>Lista de Fornecedores</h2></center>

        <!-- Formulário de busca -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="busca" class="form-label">Digite o ID ou Nome do fornecedor (opcional)</label>
                <input type="text" class="form-control" id="busca" name="busca" value="<?= htmlspecialchars($busca) ?>">
            </div>
            <center><button type="submit" class="btn btn-primary">Pesquisar</button></center>
        </form>

        <br>

        <!-- Exibir resultados -->
        <?php if (!empty($fornecedores)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Endereço</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Contato</th>
                            <th>Ações</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['nome_empresa']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['email']) ?></td>
                            <td><?= htmlspecialchars($fornecedor['contato']) ?></td>
                            <td>
                                <a class="btn btn-sm btn-warning" 
                                   href="alterar_fornecedor.php?id=<?= (int)$fornecedor['id_fornecedor'] ?>">
                                   Alterar
                                </a>
                                <a class="btn btn-sm btn-danger" 
                                   href="excluir_fornecedor.php?id=<?= (int)$fornecedor['id_fornecedor'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum fornecedor encontrado.</p></center>
        <?php endif; ?>

        <br>
        <center>
            <a href="principal.php" class="btn btn-secondary">Voltar</a>
        </center>
    </div>

    <!-- Scripts no final -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>

</body>
</html>