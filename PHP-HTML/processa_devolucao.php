<?php
session_start();
require_once 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Sessão inválida']);
    exit();
}

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit();
}

// Obtém o ID do empréstimo
$id_emprestimo = filter_input(INPUT_POST, 'id_emprestimo', FILTER_VALIDATE_INT);

if (!$id_emprestimo) {
    echo json_encode(['success' => false, 'message' => 'ID de empréstimo inválido']);
    exit();
}

try {
    $pdo->beginTransaction();

    // Verifica se o empréstimo existe e pertence ao usuário
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $pdo->prepare("SELECT * FROM emprestimo WHERE id_emprestimo = :id AND id_usuario = :id_usuario AND status IN ('emprestado', 'atrasado')");
    $stmt->bindParam(':id', $id_emprestimo, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$emprestimo) {
        throw new Exception("Empréstimo não encontrado ou já devolvido");
    }

    // Atualiza o status do empréstimo
    $data_devolucao = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("UPDATE emprestimo SET status = 'devolvido', data_devolucao_real = :data_devolucao WHERE id_emprestimo = :id");
    $stmt->bindParam(':data_devolucao', $data_devolucao);
    $stmt->bindParam(':id', $id_emprestimo, PDO::PARAM_INT);
    $stmt->execute();

    // Atualiza o estoque do produto
    $stmt = $pdo->prepare("SELECT id_produto FROM item_emprestimo WHERE id_emprestimo = :id");
    $stmt->bindParam(':id', $id_emprestimo, PDO::PARAM_INT);
    $stmt->execute();
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itens as $item) {
        $stmt = $pdo->prepare("UPDATE produto SET quantidade_estoque = quantidade_estoque + 1 WHERE id_produto = :id");
        $stmt->bindParam(':id', $item['id_produto'], PDO::PARAM_INT);
        $stmt->execute();
    }

    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Devolução registrada com sucesso']);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}