<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST["id_cliente"];
    $nome_completo = $_POST["nome_completo"];
    $cpf = $_POST["cpf"];
    $telefone = trim($_POST["telefone"]);
    $data_nascimento = trim($_POST["data_nascimento"]);

    // ATUALIZA OS DADOS DO USUÁRIO

    if ($id_cliente) {
        $sql = "UPDATE cliente SET nome_completo = :nome_completo, cpf = :cpf, telefone = :telefone, data_nascimento = :data_nascimento WHERE id_cliente = :id_cliente";
        $stmt = $pdo->prepare($sql);
    } 

    $stmt->bindParam(':id_cliente',$id_cliente);
    $stmt->bindParam(':nome_completo',$nome_completo);
    $stmt->bindParam(':cpf',$cpf);
    $stmt->bindParam(':telefone',$telefone);
    $stmt->bindParam(':data_nascimento',$data_nascimento);

    if($stmt->execute()) {
        echo"<script>alert('Cliente atualizado com sucesso!');window.location.href='buscar_cliente.php';</script>";
    } else {
        echo"<script>alert('Erro ao atualizar o cliente!');window.location.href='alterar_cliente.php?id=$usuario';</script>";
    }

}
    
?>