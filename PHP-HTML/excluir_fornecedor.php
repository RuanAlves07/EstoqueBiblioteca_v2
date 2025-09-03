<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Verifica se o perfil tem permissão para excluir fornecedores
if (!isset($permissoes[$id_perfil]['Excluir']) || !in_array('excluir_fornecedor.php', $permissoes[$id_perfil]['Excluir'])) {
    echo "<script>alert('Acesso negado.'); window.location.href='principal.php';</script>";
    exit();
}

// Opções do menu
$opcoes_menu = $permissoes[$id_perfil] ?? [];

// Busca todos os fornecedores
try {
    $sql = "SELECT id_fornecedor, nome_empresa, endereco, telefone, email, contato 
            FROM fornecedor 
            ORDER BY nome_empresa ASC";
    $stmt = $pdo->query($sql);
    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $fornecedores = [];
    $erro = "Erro ao carregar fornecedores: " . $e->getMessage();
}

// Processa exclusão
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_fornecedor = (int)$_GET['id'];

    // Verifica se o fornecedor existe
    $sql_check = "SELECT nome_empresa FROM fornecedor WHERE id_fornecedor = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
    $stmt_check->execute();
    $fornecedor = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$fornecedor) {
        $_SESSION['mensagem'] = "Fornecedor não encontrado.";
        $_SESSION['msg_tipo'] = "warning";
    } else {
        try {
            $sql_delete = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);

            if ($stmt_delete->execute()) {
                $_SESSION['mensagem'] = "Fornecedor <strong>" . htmlspecialchars($fornecedor['nome_empresa']) . "</strong> excluído com sucesso!";
                $_SESSION['msg_tipo'] = "success";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir fornecedor.";
                $_SESSION['msg_tipo'] = "danger";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['mensagem'] = "Não é possível excluir: este fornecedor está vinculado a produtos ou outros registros.";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir: " . $e->getMessage();
            }
            $_SESSION['msg_tipo'] = "danger";
        }
    }

    // Redireciona para evitar reexclusão ao atualizar
    header("Location: excluir_fornecedor.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Fornecedor</title>
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
            max-width: 1000px;
            margin: 20px auto;
        }
        h2 {
            color:#001f3f;
            text-align: center;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #003366;
            color: white;
            font-weight: 600;
        }
        .table td {
            padding: 12px;
        }
        .btn-danger {
            background-color: #d9534f;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
        }
        .btn-danger:hover {
            background-color: #c9302c;
        }
        .btn-primary {
            background-color: #003366;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
        }
        .text-center {
            text-align: center;
        }
        .alert {
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
    </style>
</head>
<body>
    

    <!-- Menu Superior com Dropdown -->


    <!-- Mensagem de feedback -->
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= $_SESSION['mensagem'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensagem'], $_SESSION['msg_tipo']); ?>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="container">
        <h2>Excluir Fornecedor</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($fornecedores)): ?>
            <div class="table-responsive">
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
                        <?php foreach ($fornecedores as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['id_fornecedor']) ?></td>
                                <td><?= htmlspecialchars($f['nome_empresa']) ?></td>
                                <td><?= htmlspecialchars($f['endereco']) ?></td>
                                <td><?= htmlspecialchars($f['telefone']) ?></td>
                                <td><?= htmlspecialchars($f['email']) ?></td>
                                <td><?= htmlspecialchars($f['contato']) ?></td>
                                <td class="text-center">
                                    <a href="excluir_fornecedor.php?id=<?= $f['id_fornecedor'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Tem certeza que deseja excluir <?= addslashes($f['nome_empresa']) ?>?');">
                                        Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center text-muted mt-4">
                <p>Nenhum fornecedor encontrado.</p>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-primary">Voltar</a>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
</body>
</html>