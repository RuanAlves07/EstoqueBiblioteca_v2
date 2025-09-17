<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

$id_usuario = $_SESSION['id_usuario'];

// Busca os empréstimos, livros e o nome do usuário
$sql = "SELECT e.id_emprestimo, e.data_emprestimo, e.data_devolucao_prevista, e.data_devolucao_real, e.status, p.id_produto, p.titulo, u.nome AS nome_usuario
        FROM emprestimo e 
        INNER JOIN item_emprestimo i ON e.id_emprestimo = i.id_emprestimo 
        INNER JOIN produto p ON i.id_produto = p.id_produto 
        INNER JOIN usuario u ON e.id_usuario = u.id_usuario 
        WHERE e.id_usuario = :id_usuario 
        ORDER BY e.data_emprestimo DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Empréstimos</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        .btn-devolucao {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-devolucao:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <center><h2>Seus Empréstimos</h2></center>

        <?php if (empty($emprestimos)): ?>
            <center><div class="alert alert-info">Você ainda não fez nenhum empréstimo.</div></center>
        <?php else: ?>
            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>Usuário</th>
                        <th>ID do Livro</th>
                        <th>Livro</th>
                        <th>Empréstimo</th>
                        <th>Devolução Prevista</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprestimos as $emp): ?>
                        <tr id="emprestimo-<?= $emp['id_emprestimo'] ?>">
                            <td><?= htmlspecialchars($emp['nome_usuario']) ?></td>
                            <td><?= $emp['id_produto'] ?></td>
                            <td><?= htmlspecialchars($emp['titulo']) ?></td>
                            <td><?= date('d/m/Y', strtotime($emp['data_emprestimo'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($emp['data_devolucao_prevista'])) ?></td>
                            <td>
                                <span class="badge 
                                    <?= $emp['status'] == 'devolvido' ? 'bg-success' : 
                                       ($emp['status'] == 'atrasado' ? 'bg-danger' : 'bg-warning') ?>">
                                    <?= ucfirst($emp['status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($emp['status'] == 'emprestado' || $emp['status'] == 'atrasado'): ?>
                                    <button class="btn-devolucao" 
                                            data-emprestimo="<?= $emp['id_emprestimo'] ?>">
                                        Devolvido
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">Concluído</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona evento de clique para todos os botões de devolução
            const botoesDevolucao = document.querySelectorAll('.btn-devolucao');
            
            botoesDevolucao.forEach(botao => {
                botao.addEventListener('click', function() {
                    const idEmprestimo = this.getAttribute('data-emprestimo');
                    
                    // Confirmação da devolução
                    if (confirm('Tem certeza que foi realizado a devolução do produto?')) {
                        // Envia requisição para processar a devolução
                        fetch('processa_devolucao.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id_emprestimo=' + idEmprestimo
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove a linha da tabela
                                const linha = document.getElementById('emprestimo-' + idEmprestimo);
                                if (linha) {
                                    linha.style.backgroundColor = '#d4edda';
                                    setTimeout(() => {
                                        linha.remove();
                                    }, 500);
                                    
                                    // Se não houver mais empréstimos, mostra mensagem
                                    if (document.querySelectorAll('#emprestimo-\\[0-9\\]+').length === 0) {
                                        const container = document.querySelector('.container');
                                        container.innerHTML = '<center><div class="alert alert-info">Você não tem mais empréstimos ativos.</div></center>';
                                    }
                                    
                                    alert('Devolução registrada com sucesso!');
                                }
                            } else {
                                alert('Erro: ' + data.message);
                            }
                        })
                    }
                });
            });
        });
    </script>
</body>
</html>