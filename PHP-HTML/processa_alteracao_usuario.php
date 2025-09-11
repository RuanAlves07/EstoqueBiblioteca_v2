<?php
session_start();
require_once 'conexao.php';

// Verifica se é POST e se tem ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_usuario'])) {
    echo "<script>alert('Acesso inválido.'); window.location.href='alterar_usuario.php';</script>";
    exit();
}

$id_usuario = (int)$_POST['id_usuario'];
$nome = trim($_POST['nome']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$id_perfil = (int)$_POST['id_perfil'];
$senha = trim($_POST['senha']);

// Validação básica
if (empty($nome) || !$email || !in_array($id_perfil, [1, 2, 3, 4])) {
    echo "<script>alert('Dados inválidos.'); window.location.href='alterar_usuario.php?id=" . $id_usuario . "';</script>";
    exit();
}

try {
    // Monta a query: se senha foi preenchida, atualiza; senão, mantém a antiga
    if (!empty($senha)) {
        $sql = "UPDATE usuario SET nome = :nome, email = :email, id_perfil = :id_perfil, senha = :senha WHERE id_usuario = :id";
        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':id_perfil' => $id_perfil,
            ':senha' => password_hash($senha, PASSWORD_DEFAULT),
            ':id' => $id_usuario
        ];
    } else {
        $sql = "UPDATE usuario SET nome = :nome, email = :email, id_perfil = :id_perfil WHERE id_usuario = :id";
        $params = [
            ':nome' => $nome,
            ':email' => $email,
            ':id_perfil' => $id_perfil,
            ':id' => $id_usuario
        ];
    }

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute($params)) { // 👈 Aqui estava faltando o $params!
        echo "<script>alert('Usuário atualizado com sucesso!'); window.location.href='buscar_usuario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o usuário.'); window.location.href='alterar_usuario.php?id=" . $id_usuario . "';</script>";
    }

} catch (PDOException $e) {
    echo "<script>alert('Erro ao atualizar o usuário: " . addslashes($e->getMessage()) . "'); window.location.href='alterar_usuario.php?id=" . $id_usuario . "';</script>";
}
exit();