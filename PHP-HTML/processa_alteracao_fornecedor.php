<?php

session_start();
require_once 'conexao.php';

// VERIFICA SE O USUARIO TEM PERMISSAO DE ADM

if ($_SESSION['perfil'] != 1){
    echo"<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] =="POST"){
    $id_fornecedor = $_POST["id_fornecedor"];
    $nome = $_POST["nome_fornecedor"];
    $nome_fantasia = $_POST["nome_fantasia"];
    $cnpj = $_POST["cnpj"];
    $endereco = $_POST["endereco"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $contato = $_POST["contato"];

    // ATUALIZA OS DADOS DO USUÁRIO

    if ($nova_senha) {
        $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, endereco = :endereco, telefone = :telefone, email = :email, contato = :contato WHERE id_fornecedor = :id_fornecedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $nova_senha);
    } else {
        $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, nome_fornecedor = :nome_fantasia, endereco = :endereco, telefone = :telefone, email = :email, contato = :contato WHERE id_fornecedor = :id_fornecedor";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindParam(':nome_fornecedor',$nome);
    $stmt->bindParam(':nome_fantasia',$nome_fantasia);
    $stmt->bindParam(':cnpj',$cnpj);
    $stmt->bindParam(':endereco',$endereco);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':email',$email);
    $stmt->bindParam(':contato',$contato);
    $stmt->bindParam(':id_fornecedor',$id_fornecedor);

    if($stmt->execute()) {
        echo"<script>alert('Usuário atualizado com sucesso!');window.location.href='buscar_fornecedor.php';</script>";
    } else {
        echo"<script>alert('Erro ao atualizar o usuário!');window.location.href='alterar_fornecedor.php?id=$usuario';</script>";
    }

}
    

?>