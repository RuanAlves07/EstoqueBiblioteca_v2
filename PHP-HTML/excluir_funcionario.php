<?php
ob_start(); // ← Bufferiza a saída
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Busca todos os funcionários
try {
    $sql = "SELECT id_funcionario, nome_completo, cpf, cargo, telefone, data_admissao FROM funcionario ORDER BY nome_completo ASC";
    $stmt = $pdo->query($sql);
    $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $funcionarios = [];
    $erro = "Erro ao carregar funcionários: " . $e->getMessage();
}

// Processa exclusão
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_funcionario = (int)$_GET['id'];

    // Verifica se o funcionário existe
    $sql_check = "SELECT nome_completo FROM funcionario WHERE id_funcionario = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':id', $id_funcionario, PDO::PARAM_INT);
    $stmt_check->execute();
    $funcionario = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$funcionario) {
        $_SESSION['mensagem'] = "Funcionário não encontrado.";
        $_SESSION['msg_tipo'] = "warning";
    } else {
        try {
            $sql_delete = "DELETE FROM funcionario WHERE id_funcionario = :id";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->bindParam(':id', $id_funcionario, PDO::PARAM_INT);

            if ($stmt_delete->execute()) {
                $_SESSION['mensagem'] = "Funcionário <strong>" . htmlspecialchars($funcionario['nome_completo']) . "</strong> excluído com sucesso!";
                $_SESSION['msg_tipo'] = "success";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir funcionário.";
                $_SESSION['msg_tipo'] = "danger";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['mensagem'] = "Não é possível excluir: este funcionário está vinculado a outros registros.";
            } else {
                $_SESSION['mensagem'] = "Erro ao excluir: " . $e->getMessage();
            }
            $_SESSION['msg_tipo'] = "danger";
        }
    }

    // Limpa o buffer e redireciona
    ob_clean(); // Remove tudo que foi gerado pelo Menu.php
    header("Location: excluir_funcionario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Funcionários</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
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
    <div class="container">
        <center><h2>Excluir Funcionários</h2></center>

        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!empty($funcionarios)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>CPF</th>
                            <th>Cargo</th>
                            <th>Telefone</th>
                            <th>Data de Admissão</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($funcionarios as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['id_funcionario']) ?></td>
                                <td><?= htmlspecialchars($f['nome_completo']) ?></td>
                                <td><?= htmlspecialchars($f['cpf']) ?></td>
                                <td><?= htmlspecialchars($f['cargo']) ?></td>
                                <td><?= htmlspecialchars($f['telefone']) ?></td>
                                <td><?= htmlspecialchars($f['data_admissao']) ?></td>
                                <td class="text-center">
                                    <a href="excluir_funcionario.php?id=<?= $f['id_funcionario'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Tem certeza que deseja excluir <?= addslashes($f['nome_completo']) ?>?');">
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
                <p>Nenhum funcionário encontrado.</p>
            </div>
        <?php endif; ?>


    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>