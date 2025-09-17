<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica login
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['id_usuario'])) {
    die("<script>alert('Sessão inválida.'); window.location.href='dashboard.php';</script>");
}

// Recupera o perfil do usuário logado
$id_usuario_logado = $_SESSION['id_usuario'];
$id_perfil = $_SESSION['id_perfil'] ?? null; // <-- Adicionado para garantir que $id_perfil está definido

$email_digitado = $_POST['email'] ?? '';
$senha_digitada = $_POST['senha'] ?? '';
$opcao_emprestimo = $_POST['opcao_emprestimo'] ?? '';
$id_cliente = $_POST['id_cliente'] ?? null;
$id_produto = $_POST['id_produto'] ?? null;

$erro = '';
$sucesso = '';

// Processa o formulário para todos os perfis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o email digitado é o do usuário logado
    if ($email_digitado !== $_SESSION['email']) {
        $erro = "Email incorreto.";
    } else {
        // Verifica a senha no banco
        $stmt = $pdo->prepare("SELECT senha FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email_digitado);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($senha_digitada, $usuario['senha'])) {
            $erro = "Senha incorreta.";
        } else {
            if (empty($id_produto)) {
                $erro = "Selecione um livro.";
            } else {
                // Verifica se o produto existe e tem estoque
                $stmt = $pdo->prepare("SELECT quantidade_estoque, titulo FROM produto WHERE id_produto = :id");
                $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
                $stmt->execute();
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$produto) {
                    $erro = "Livro não encontrado.";
                } elseif ($produto['quantidade_estoque'] <= 0) {
                    $erro = "Livro '" . htmlspecialchars($produto['titulo']) . "' está sem estoque.";
                } else {
                    try {
                        $pdo->beginTransaction();

                        // Define para quem será o empréstimo e quem fez o empréstimo
                        $id_usuario_emprestimo = null;
                        $id_funcionario = null;

                        if ($id_perfil == 4) {
                            // Cliente só pode emprestar para si mesmo
                            $id_usuario_emprestimo = $id_usuario_logado;
                        } else {
                            // Perfis 1, 2, 3 podem escolher
                            if ($opcao_emprestimo === 'para_mim') {
                                $id_usuario_emprestimo = $id_usuario_logado;

                                // Verifica se o usuário logado é funcionário
                                $stmt_func = $pdo->prepare("SELECT id_funcionario FROM funcionario WHERE id_funcionario = :id_func");
                                $stmt_func->bindParam(':id_func', $id_usuario_logado, PDO::PARAM_INT);
                                $stmt_func->execute();
                                $funcionario = $stmt_func->fetch(PDO::FETCH_ASSOC);

                                if ($funcionario) {
                                    $id_funcionario = $funcionario['id_funcionario'];
                                } else {
                                    throw new Exception("Usuário não cadastrado como funcionário.");
                                }
                            } elseif ($opcao_emprestimo === 'para_cliente') {
                                if (empty($id_cliente)) {
                                    throw new Exception("Informe o ID do cliente.");
                                }

                                // Verifica se o cliente existe na tabela cliente
                                $stmt_cli = $pdo->prepare("SELECT id_cliente FROM cliente WHERE id_cliente = :id");
                                $stmt_cli->bindParam(':id', $id_cliente, PDO::PARAM_INT);
                                $stmt_cli->execute();
                                $cliente = $stmt_cli->fetch(PDO::FETCH_ASSOC);

                                if ($cliente) {
                                    $id_usuario_emprestimo = $id_cliente;
                                } else {
                                    throw new Exception("Cliente inválido ou não encontrado.");
                                }

                                // Verifica se o usuário logado é funcionário
                                $stmt_func = $pdo->prepare("SELECT id_funcionario FROM funcionario WHERE id_funcionario = :id_func");
                                $stmt_func->bindParam(':id_func', $id_usuario_logado, PDO::PARAM_INT);
                                $stmt_func->execute();
                                $funcionario = $stmt_func->fetch(PDO::FETCH_ASSOC);

                                if ($funcionario) {
                                    $id_funcionario = $funcionario['id_funcionario'];
                                } else {
                                    throw new Exception("Usuário não cadastrado como funcionário.");
                                }
                            } else {
                                throw new Exception("Opção inválida.");
                            }
                        }

                        // Define data de devolução (14 dias)
                        $data_devolucao = date('Y-m-d', strtotime('+14 days'));

                        // Insere o empréstimo com id_funcionario
                        $sql_emp = "INSERT INTO emprestimo (id_usuario, id_funcionario, data_devolucao_prevista, status) 
                                    VALUES (:id_usuario, :id_funcionario, :data_devolucao, 'emprestado')";
                        $stmt_emp = $pdo->prepare($sql_emp);
                        $stmt_emp->bindParam(':id_usuario', $id_usuario_emprestimo, PDO::PARAM_INT);
                        $stmt_emp->bindParam(':id_funcionario', $id_funcionario, PDO::PARAM_INT);
                        $stmt_emp->bindParam(':data_devolucao', $data_devolucao, PDO::PARAM_STR);
                        $stmt_emp->execute();
                        $id_emprestimo = $pdo->lastInsertId();

                        // Adiciona o item do empréstimo
                        $sql_item = "INSERT INTO item_emprestimo (id_emprestimo, id_produto, data_devolucao_prevista) 
                                     VALUES (:id_emprestimo, :id_produto, :data_devolucao)";
                        $stmt_item = $pdo->prepare($sql_item);
                        $stmt_item->bindParam(':id_emprestimo', $id_emprestimo, PDO::PARAM_INT);
                        $stmt_item->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
                        $stmt_item->bindParam(':data_devolucao', $data_devolucao, PDO::PARAM_STR);
                        $stmt_item->execute();

                        // Atualiza estoque
                        $sql_update = "UPDATE produto SET quantidade_estoque = quantidade_estoque - 1 WHERE id_produto = :id";
                        $stmt_update = $pdo->prepare($sql_update);
                        $stmt_update->bindParam(':id', $id_produto, PDO::PARAM_INT);
                        $stmt_update->execute();

                        $pdo->commit();

                        $sucesso = "Empréstimo realizado com sucesso! Devolução prevista para " . date('d/m/Y', strtotime($data_devolucao));

                    } catch (Exception $e) {
                        $pdo->rollback();
                        $erro = $e->getMessage();
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empréstimo de Livros</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <center><h2>Empréstimo de Livros</h2></center>

    <div class="container">

        <?php if ($erro): ?>
            <center><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div></center>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <center><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div></center>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Campos de segurança -->
            <div class="mb-3">
                <label for="email" class="form-label">Seu Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= htmlspecialchars($email_digitado) ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Sua Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <?php if ($id_perfil != 4): ?>
                <!-- Nova lógica para perfis 1, 2, 3 -->
                <div class="mb-3">
                    <label for="opcao_emprestimo" class="form-label">Para quem é o empréstimo?</label>
                    <select name="opcao_emprestimo" id="opcao_emprestimo" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="para_mim" <?= $opcao_emprestimo === 'para_mim' ? 'selected' : '' ?>>Para mim</option>
                        <option value="para_cliente" <?= $opcao_emprestimo === 'para_cliente' ? 'selected' : '' ?>>Para cliente</option>
                    </select>
                </div>

                <div class="mb-3" id="campo_id_cliente" style="display: none;">
                    <label for="id_cliente" class="form-label">ID do Cliente:</label>
                    <input type="number" name="id_cliente" id="id_cliente" class="form-control" 
                           value="<?= htmlspecialchars($id_cliente ?? '') ?>">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="id_produto" class="form-label">Livro:</label>
                <select name="id_produto" id="id_produto" class="form-select" required>
                    <option value="">Selecione um livro...</option>
                    <?php
                    $stmt = $pdo->query("SELECT id_produto, titulo, quantidade_estoque FROM produto ORDER BY titulo");
                    while ($row = $stmt->fetch()) {
                        $disabled = $row['quantidade_estoque'] <= 0 ? 'disabled' : '';
                        $estoque_info = $row['quantidade_estoque'] <= 0 ? ' (Sem estoque)' : ' (' . $row['quantidade_estoque'] . ' disponíveis)';
                        echo "<option value='{$row['id_produto']}' " . (($id_produto ?? '') == $row['id_produto'] ? 'selected' : '') . " $disabled>
                                {$row['titulo']}{$estoque_info}
                              </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Realizar Empréstimo</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
        </form>

        <?php if ($id_perfil != 4): ?>
            <script>
                document.getElementById('opcao_emprestimo').addEventListener('change', function() {
                    const campo = document.getElementById('campo_id_cliente');
                    if (this.value === 'para_cliente') {
                        campo.style.display = 'block';
                    } else {
                        campo.style.display = 'none';
                    }
                });

                // Mostra campo se já estava selecionado
                if (document.getElementById('opcao_emprestimo').value === 'para_cliente') {
                    document.getElementById('campo_id_cliente').style.display = 'block';
                }
            </script>
        <?php endif; ?>

    </div>

</body>
</html>