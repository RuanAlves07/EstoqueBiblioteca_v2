<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM (perfil 1)
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado'); window.location.href='dashboard.php';</script>";
    exit;
}

// INICIALIZA AS VARIÁVEIS
$cliente = null;
$busca = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_cliente'])) {
        $busca = trim($_POST['busca_cliente']);
    }

    // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
    if ($busca !== null && is_numeric($busca)) {
        $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } elseif ($busca !== null) {
        $sql = "SELECT * FROM cliente WHERE nome_completo LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE O CLIENTE NÃO FOR ENCONTRADO, EXIBE UM ALERTA
        if (!$cliente) {
            echo "<script>alert('Cliente não encontrado');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="scripts.js"></script>
    <style>
        .container { max-width: 800px; }
        .form-group { margin-bottom: 1rem; }
        .logout { margin-top: 20px; }
        .text-muted { font-size: 0.875em; }
    </style>
</head>
<body>

    <center><h2 class="mb-4">Alterar Cliente</h2></center>

    <div class="container mt-4">
        <!-- FORMULÁRIO DE BUSCA -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="busca_cliente">Buscar por ID ou Nome:</label>
                <input type="text"
                       id="busca_cliente"
                       name="busca_cliente"
                       value="<?= htmlspecialchars($busca ?? '') ?>"
                       class="form-control"
                       placeholder="Digite o ID ou nome completo do cliente"
                       required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Cliente</button>
        </form>

        <!-- FORMULÁRIO DE ALTERAÇÃO (APARECE SE ENCONTRAR) -->
        <?php if ($cliente): ?>
        <hr>
        <form action="processa_alteracao_cliente.php" method="POST" onsubmit="return validarCliente();">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">

            <!-- Nome Completo -->
            <div class="form-group">
                <label for="nome_completo">Nome Completo:</label>
                <input type="text"
                       name="nome_completo"
                       id="nome_completo"
                       value="<?= htmlspecialchars($cliente['nome_completo']) ?>"
                       class="form-control"
                       required>
            </div>

            <!-- CPF -->
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text"
                       name="cpf"
                       id="cpf"
                       value="<?= htmlspecialchars($cliente['cpf']) ?>"
                       class="form-control"
                       placeholder="000.000.000-00"
                       required>
            </div>

            <!-- Telefone -->
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text"
                       name="telefone"
                       id="telefone"
                       value="<?= htmlspecialchars($cliente['telefone']) ?>"
                       class="form-control"
                       placeholder="(00) 00000-0000"
                       required>
            </div>

            <!-- Data de Nascimento -->
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="date"
                       name="data_nascimento"
                       id="data_nascimento"
                       value="<?= htmlspecialchars($cliente['data_nascimento']) ?>"
                       class="form-control"
                       required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Cliente</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </div>
        </form>
        <?php endif; ?>
    </div>



    <!-- BOTÃO DE LOGOUT -->
    <div class="logout text-center mt-3">
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- VALIDAÇÃO JS -->
    <script>
        function validarCliente() {
            const nome = document.getElementById('nome_completo').value.trim();
            const cpf = document.getElementById('cpf').value.trim();
            const telefone = document.getElementById('telefone').value.trim();
            const dataNasc = document.getElementById('data_nascimento').value;

            if (nome === '') {
                alert('O nome completo é obrigatório.');
                return false;
            }
            if (cpf === '' || !validarCPF(cpf)) {
                alert('CPF inválido.');
                return false;
            }
            if (telefone === '') {
                alert('O telefone é obrigatório.');
                return false;
            }
            if (dataNasc === '') {
                alert('A data de nascimento é obrigatória.');
                return false;
            }

            return true;
        }

        // Função simples de validação de CPF (opcional)
        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

            let soma = 0, resto;
            for (let i = 1; i <= 9; i++) {
                soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
            }
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) resto = 0;
            if (resto !== parseInt(cpf.substring(9, 10))) return false;

            soma = 0;
            for (let i = 1; i <= 10; i++) {
                soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
            }
            resto = (soma * 10) % 11;
            if ((resto === 10) || (resto === 11)) resto = 0;
            if (resto !== parseInt(cpf.substring(10, 11))) return false;

            return true;
        }
    </script>

</body>
</html>