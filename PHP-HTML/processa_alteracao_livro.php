<?php
session_start();
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_produto = $_POST["id_produto"];
    $titulo = $_POST["titulo"];
    $id_categoria = $_POST["id_categoria"];
    $nome_autor = trim($_POST["nome_autor"]);
    $nome_editora = trim($_POST["nome_editora"]);
    $ano_publicacao = $_POST["ano_publicacao"];
    $edicao = $_POST["edicao"] ?? null;
    $quantidade_estoque = $_POST["quantidade_estoque"];

    try {
        $pdo->beginTransaction();

        // === BUSCAR OU INSERIR AUTOR ===
        $id_autor = null;
        if (!empty($nome_autor)) {
            $stmt = $pdo->prepare("SELECT id_autor FROM autor WHERE nome_autor = :nome");
            $stmt->execute([':nome' => $nome_autor]);
            $autor = $stmt->fetch();

            if ($autor) {
                $id_autor = $autor['id_autor'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO autor (nome_autor) VALUES (:nome)");
                $stmt->execute([':nome' => $nome_autor]);
                $id_autor = $pdo->lastInsertId();
            }
        }

        // === BUSCAR OU INSERIR EDITORA ===
        $id_editora = null;
        if (!empty($nome_editora)) {
            $stmt = $pdo->prepare("SELECT id_editora FROM editora WHERE nome_editora = :nome");
            $stmt->execute([':nome' => $nome_editora]);
            $editora = $stmt->fetch();

            if ($editora) {
                $id_editora = $editora['id_editora'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO editora (nome_editora) VALUES (:nome)");
                $stmt->execute([':nome' => $nome_editora]);
                $id_editora = $pdo->lastInsertId();
            }
        }

        // === ATUALIZAR PRODUTO ===
        $sql = "UPDATE produto SET
                    titulo = :titulo,
                    id_categoria = :id_categoria,
                    id_autor = :id_autor,
                    id_editora = :id_editora,
                    ano_publicacao = :ano_publicacao,
                    edicao = :edicao,
                    quantidade_estoque = :quantidade_estoque
                WHERE id_produto = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':id_autor', $id_autor);
        $stmt->bindParam(':id_editora', $id_editora);
        $stmt->bindParam(':ano_publicacao', $ano_publicacao);
        $stmt->bindParam(':edicao', $edicao);
        $stmt->bindParam(':quantidade_estoque', $quantidade_estoque);
        $stmt->bindParam(':id', $id_produto);

        if ($stmt->execute()) {
            $pdo->commit();
            echo "<script>alert('Livro atualizado com sucesso!');window.location.href='buscar_livro.php';</script>";
        } else {
            $pdo->rollback();
            echo "<script>alert('Erro ao atualizar o Livro.');window.location.href='alterar_livro.php';</script>";
        }

    } catch (Exception $e) {
        $pdo->rollback();
        error_log("Erro na alteração do livro: " . $e->getMessage());
        echo "<script>alert('Erro interno. Tente novamente.');window.location.href='alterar_livro.php';</script>";
    }
}
?>