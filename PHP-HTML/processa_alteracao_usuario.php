<?php
session_start();
require_once 'conexao.php';

// Verifica se é POST e se tem ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_usuario'])) {
    $_SESSION['mensagem'] = "Acesso inválido.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: alterar_usuario.php");
    exit();
}

$id_usuario = (int)$_POST['id_usuario'];
$nome = trim($_POST['nome']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$id_perfil = (int)$_POST['id_perfil'];
$senha = trim($_POST['senha']);

// Validação básica
if (empty($nome) || !$email || !in_array($id_perfil, [1, 2, 3, 4])) {
    $_SESSION['mensagem'] = "Dados inválidos.";
    $_SESSION['msg_tipo'] = "danger";
    header("Location: alterar_usuario.php?busca_usuario=" . urlencode($_POST['nome']));
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
    $stmt->execute($params);

    $_SESSION['mensagem'] = "Usuário <strong>$nome</strong> atualizado com sucesso!";
    $_SESSION['msg_tipo'] = "success";

} catch (PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao atualizar: " . $e->getMessage();
    $_SESSION['msg_tipo'] = "danger";
}

header("Location: alterar_usuario.php");
exit();