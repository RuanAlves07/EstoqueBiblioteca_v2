<?php
// Only start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// OBTENDO O NOME DO PERFIL DO USUARIO LOGADO 
$id_perfil = $_SESSION['perfil'];
$sqlPerfil = "SELECT nome_perfil FROM perfil WHERE id_perfil = :id_perfil";
$stmtPerfil = $pdo->prepare($sqlPerfil);
$stmtPerfil->bindParam(':id_perfil', $id_perfil);
$stmtPerfil->execute();
$perfil = $stmtPerfil->fetch(PDO::FETCH_ASSOC);
$nome_perfil = $perfil['nome_perfil'];

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL
$permissoes = [
    1 => [
        "Cadastrar"   => ["cadastro_usuario.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php", "cadastro_funcionario.php"],
        "Buscar"      => ["buscar_usuario.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php", "buscar_funcionario.php"],
        "Alterar"     => ["alterar_usuario.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php", "alterar_funcionario.php"],
        "Excluir"     => ["excluir_usuario.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php", "excluir_funcionario.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php"]
    ],
    2 => [
        "Cadastrar"   => ["cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar"     => ["alterar_cliente.php", "alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir"     => ["excluir_cliente.php", "excluir_fornecedor.php", "excluir_produto.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php"]
    ],
    3 => [
        "Cadastrar"   => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar"     => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir"     => ["excluir_produto.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php"]
    ],
    4 => [
        "Buscar"      => ["buscar_produto.php"],
        "Alterar"     => ["alterar_cliente.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php"]
    ],
];

$opcoes_menu = $permissoes[$id_perfil];
?>