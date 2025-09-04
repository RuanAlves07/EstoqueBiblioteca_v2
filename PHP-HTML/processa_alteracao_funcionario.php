<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado');window.location.href='principal.php';</script>";
    exit;
}

$id = $_POST['id_funcionario'];
$nome = trim($_POST['nome_completo']);
$cpf = preg_replace('/\D/', '', $_POST['cpf']);
$cargo = trim($_POST['cargo']);
$telefone = trim($_POST['telefone']);
$data_admissao = $_POST['data_admissao'] ?? null;

try {
    $sql = "UPDATE funcionario SET 
                nome_completo = :nome, 
                cpf = :cpf, 
                cargo = :cargo, 
                telefone = :telefone, 
                data_admissao = :data_admissao 
            WHERE id_funcionario = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':cargo', $cargo);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_admissao', $data_admissao);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Funcionário atualizado com sucesso!');window.location.href='alterar_funcionario.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar.');</script>";
    }
} catch (Exception $e) {
    echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
}