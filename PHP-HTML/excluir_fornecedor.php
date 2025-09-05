<?php
ob_start();
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Busca todos os fornecedores
try {
    $sql = "SELECT * FROM fornecedor ORDER BY nome_empresa ASC";
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

    // Redireciona para evitar reexclusão
    header("Location: excluir_fornecedor.php");
    ob_end_clean();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Fornecedor</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .table th, .table td {
            vertical-align: middle;
            padding: 12px 15px;
            text-align: left;
        }
        .table th {
            font-weight: bold;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .btn-excluir {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-align: center;
        }
        .btn-excluir:hover {
            background-color: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>

    <!-- Mensagem de feedback -->
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_tipo'] ?> alert-dismissible fade show mx-4 mt-3 text-center" role="alert">
            <?= $_SESSION['mensagem'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensagem'], $_SESSION['msg_tipo']); ?>
    <?php endif; ?>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">
        <h2 class="text-center mb-4">Excluir Fornecedor</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($fornecedores)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Empresa</th>
                            <th>Nome Fantasia</th>
                            <th>CNPJ</th>
                            <th>Contato</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Endereço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fornecedores as $fornecedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($fornecedor['id_fornecedor']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['nome_empresa']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['nome_fantasia']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['cnpj']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['contato']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['telefone']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['email']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($fornecedor['endereco']) ?></td>
                            <td class="text-center">
                                <a class="btn-excluir"
                                   href="excluir_fornecedor.php?id=<?= (int)$fornecedor['id_fornecedor'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <center><p class="text-muted">Nenhum fornecedor encontrado.</p></center>
        <?php endif; ?>


    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
</body>
</html>