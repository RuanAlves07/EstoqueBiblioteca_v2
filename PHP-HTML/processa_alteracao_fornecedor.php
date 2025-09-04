<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado');window.location.href='principal.php';</script>";
    exit;
}

$id = $_POST['id_fornecedor'];
$nome_empresa = trim($_POST['nome_empresa']);
$nome_fantasia = trim($_POST['nome_fantasia']);
$cnpj = preg_replace('/\D/', '', $_POST['cnpj']);
$contato = trim($_POST['contato']);
$telefone = trim($_POST['telefone']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
$endereco = trim($_POST['endereco']);

// Verifica se o CNPJ já existe (exceto para o próprio)
$stmt = $pdo->prepare("SELECT id_fornecedor FROM fornecedor WHERE cnpj = :cnpj AND id_fornecedor != :id");
$stmt->bindParam(':cnpj', $cnpj);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "<script>alert('CNPJ já cadastrado para outro fornecedor.');window.location.href='alterar_fornecedor.php';</script>";
    exit;
}

try {
    $sql = "UPDATE fornecedor SET 
                nome_empresa = :nome_empresa,
                nome_fantasia = :nome_fantasia,
                cnpj = :cnpj,
                contato = :contato,
                telefone = :telefone,
                email = :email,
                endereco = :endereco
            WHERE id_fornecedor = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_empresa', $nome_empresa);
    $stmt->bindParam(':nome_fantasia', $nome_fantasia);
    $stmt->bindParam(':cnpj', $cnpj);
    $stmt->bindParam(':contato', $contato);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Fornecedor atualizado com sucesso!');window.location.href='alterar_fornecedor.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar fornecedor.');</script>";
    }
} catch (Exception $e) {
    echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
}