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

// Busca todos os clientes ordenados por nome
try {
    $sql = "SELECT id_cliente, nome_completo, cpf, telefone, data_nascimento FROM cliente ORDER BY nome_completo ASC";
    $stmt = $pdo->query($sql);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $clientes = [];
    $erro = "Erro ao carregar clientes: " . $e->getMessage();
}

// Processa exclusão
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_cliente = (int)$_GET['id'];

    // Verifica se o cliente existe
    $sql_check = "SELECT nome_completo FROM cliente WHERE id_cliente = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_cliente, PDO::PARAM_INT);
    $stmt_check->execute();
    $cliente = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        $_SESSION['mensagem'] = "Cliente não encontrado.";
        $_SESSION['msg_tipo'] = "warning";
    } else {
        try {
            $sql_delete = "DELETE FROM cliente WHERE id_cliente = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id_cliente, PDO::PARAM_INT);

            if ($stmt_delete->execute()) {
                $_SESSION['mensagem'] = "Cliente <strong>" . htmlspecialchars($cliente['nome_completo']) . "</strong> excluído com sucesso!";
                $_SESSION['msg_tipo'] = "success";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir o cliente.";
                $_SESSION['msg_tipo'] = "danger";
            }
        } catch (PDOException $e) {
            // Trata erro de chave estrangeira (ex: cliente em uma venda)
            if ($e->getCode() == 23000) {
                $_SESSION['mensagem'] = "Não é possível excluir: este cliente está vinculado a uma venda ou outro registro.";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir: " . $e->getMessage();
            }
            $_SESSION['msg_tipo'] = "danger";
        }
    }

    // Redireciona para evitar reexclusão ao atualizar a página
    header("Location: excluir_cliente.php");
    ob_end_clean();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .container { max-width: 900px; }
        .table th { text-align: center; vertical-align: middle; }
        .table td { vertical-align: middle; }
        .btn-danger { font-size: 0.875rem; }
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
        <h2 class="text-center mb-4">Excluir Cliente</h2>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($clientes)): ?>
            <center>
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Data de Nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
                            <td><?= htmlspecialchars($cliente['nome_completo']) ?></td>
                            <td><?= htmlspecialchars($cliente['cpf']) ?></td>
                            <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                            <td><?= date('d/m/Y', strtotime($cliente['data_nascimento'])) ?></td>
                            <td>
                                <a class="btn btn-sm btn-danger"
                                   href="excluir_cliente.php?id=<?= (int)$cliente['id_cliente'] ?>"
                                   onclick="return confirm('Tem certeza que deseja excluir o cliente \"<?= addslashes($cliente['nome_completo']) ?>\"?');">
                                   Excluir
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </center>
        <?php else: ?>
            <center><p class="text-muted">Nenhum cliente encontrado.</p></center>
        <?php endif; ?>

 
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