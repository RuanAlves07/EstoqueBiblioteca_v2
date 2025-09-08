<?php
session_start();
require_once 'conexao.php';


$id_usuario = $_SESSION['id_usuario'];
$email_digitado = $_POST['email'] ?? '';
$senha_digitada = $_POST['senha'] ?? '';
$id_produto = $_POST['id_produto'] ?? null;

// Verifica se o email digitado é o do usuário logado
if ($email_digitado !== $_SESSION['email']) {
    die("<script>alert('Email incorreto.'); window.location.href='emprestimo_de_livros.php';</script>");
}

// Verifica a senha no banco
$stmt = $pdo->prepare("SELECT senha FROM usuario WHERE email = :email");
$stmt->bindParam(':email', $email_digitado);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($senha_digitada, $usuario['senha'])) {
    die("<script>alert('Senha incorreta.'); window.location.href='emprestimo_de_livros.php';</script>");
}

// Verifica se o produto existe e tem estoque
$stmt = $pdo->prepare("SELECT quantidade_estoque FROM produto WHERE id_produto = :id");
$stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto || $produto['quantidade_estoque'] <= 0) {
    die("<script>alert('Livro indisponível no estoque.'); window.location.href='emprestimo_de_livros.php';</script>");
}

// Define data de devolução (14 dias depois)
$data_devolucao = date('Y-m-d', strtotime('+14 days'));

try {
    $pdo->beginTransaction();

    // Cria o empréstimo: id_funcionario é NULL (cliente fez sozinho)
    $sql_emp = "INSERT INTO emprestimo (id_usuario, id_funcionario, data_devolucao_prevista, status) 
                VALUES (:id_usuario, NULL, :data_devolucao, 'emprestado')";
    $stmt_emp = $pdo->prepare($sql_emp);
    $stmt_emp->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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

    // Atualiza o estoque: -1
    $sql_update = "UPDATE produto SET quantidade_estoque = quantidade_estoque - 1 WHERE id_produto = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(':id', $id_produto, PDO::PARAM_INT);
    $stmt_update->execute();

    $pdo->commit();

    echo "<script>
        alert('Empréstimo realizado com sucesso! Devolução prevista para $data_devolucao.');
        window.location.href = 'seus_emprestimos.php';
    </script>";

} catch (Exception $e) {
    $pdo->rollback();
    $msg = addslashes($e->getMessage()); 
    echo "<script>
        alert('Erro ao realizar empréstimo: $msg');
        window.location.href = 'emprestimo_de_livros.php';
    </script>";
}
?>