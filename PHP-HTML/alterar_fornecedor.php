<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO DE ADM
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit;
}

// INICIALIZA AS VARIÁVEIS
$fornecedor = null;
$busca = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['busca_fornecedor'])) {
        $busca = trim($_POST['busca_fornecedor']);
    }

    // VERIFICA SE A BUSCA É POR ID (numérico) OU POR NOME (empresa ou fantasia)
    if ($busca !== null && is_numeric($busca)) {
        $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } elseif ($busca !== null) {
        $sql = "SELECT * FROM fornecedor WHERE nome_empresa LIKE :busca_nome OR nome_fantasia LIKE :busca_nome";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
    }

    if (isset($stmt)) {
        $stmt->execute();
        $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

        // SE NÃO ENCONTRAR
        if (!$fornecedor) {
            echo "<script>alert('Fornecedor não encontrado');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Fornecedor</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="scripts.js"></script>
    <style>
        .container { max-width: 800px; }
        .form-group { margin-bottom: 1rem; }
    </style>
</head>
<body>

    <center><h2 class="mb-4">Alterar Fornecedor</h2></center>

    <div class="container mt-4">
        <!-- FORMULÁRIO DE BUSCA -->
        <form method="POST" action="alterar_fornecedor.php">
            <div class="form-group">
                <label for="busca_fornecedor">Buscar por ID, Nome Empresarial ou Fantasia:</label>
                <input type="text" 
                       id="busca_fornecedor" 
                       name="busca_fornecedor" 
                       value="<?= htmlspecialchars($busca ?? '') ?>" 
                       class="form-control" 
                       placeholder="Digite ID, empresa ou nome fantasia"
                       required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Fornecedor</button>
        </form>

        <!-- FORMULÁRIO DE ALTERAÇÃO -->
        <?php if ($fornecedor): ?>
        <hr>
        <form action="processa_alteracao_fornecedor.php" method="POST" onsubmit="return validarFornecedor();">
            <input type="hidden" name="id_fornecedor" value="<?= htmlspecialchars($fornecedor['id_fornecedor']) ?>">

            <!-- Nome Empresarial -->
            <div class="form-group">
                <label for="nome_empresa">Nome Empresarial (Razão Social):</label>
                <input type="text" 
                       name="nome_empresa" 
                       id="nome_empresa" 
                       value="<?= htmlspecialchars($fornecedor['nome_empresa']) ?>" 
                       class="form-control" 
                       required>
            </div>

            <!-- Nome Fantasia -->
            <div class="form-group">
                <label for="nome_fantasia">Nome Fantasia:</label>
                <input type="text" 
                       name="nome_fantasia" 
                       id="nome_fantasia" 
                       value="<?= htmlspecialchars($fornecedor['nome_fantasia']) ?>" 
                       class="form-control" 
                       required>
            </div>

            <!-- CNPJ -->
            <div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" 
                       name="cnpj" 
                       id="cnpj" 
                       value="<?= htmlspecialchars($fornecedor['cnpj']) ?>" 
                       class="form-control" 
                       maxlength="18" 
                       placeholder="00.000.000/0000-00" 
                       required>
            </div>

            <!-- Contato -->
            <div class="form-group">
                <label for="contato">Pessoa de Contato:</label>
                <input type="text" 
                       name="contato" 
                       id="contato" 
                       value="<?= htmlspecialchars($fornecedor['contato']) ?>" 
                       class="form-control">
            </div>

            <!-- Telefone -->
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" 
                       name="telefone" 
                       id="telefone" 
                       value="<?= htmlspecialchars($fornecedor['telefone']) ?>" 
                       class="form-control" 
                       placeholder="(00) 00000-0000">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="<?= htmlspecialchars($fornecedor['email']) ?>" 
                       class="form-control">
            </div>

            <!-- Endereço -->
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <textarea name="endereco" 
                          id="endereco" 
                          class="form-control" 
                          rows="3" 
                          placeholder="Logradouro, número, bairro, cidade, estado"><?= htmlspecialchars($fornecedor['endereco']) ?></textarea>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Fornecedor</button>
                <button type="reset" class="btn btn-secondary">Limpar</button>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <!-- BOTÃO DE VOLTAR -->
    <div class="text-center mt-4">
        <a href="principal.php" class="btn btn-secondary">Voltar para o Início</a>
    </div>

    <!-- BOTÃO DE LOGOUT -->
    <div class="logout text-center mt-3">
        <form action="logout.php" method="POST">
            <button type="submit" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>

    <!-- VALIDAÇÃO JS -->
    <script>
        function validarFornecedor() {
            const nomeEmp = document.getElementById('nome_empresa').value.trim();
            const nomeFant = document.getElementById('nome_fantasia').value.trim();
            const cnpj = document.getElementById('cnpj').value.replace(/\D/g, '');
            if (nomeEmp === '' || nomeFant === '' || cnpj.length !== 14) {
                alert('Nome empresarial, nome fantasia e CNPJ (14 dígitos) são obrigatórios!');
                return false;
            }
            return true;
        }

        // Máscara de CNPJ
        document.getElementById('cnpj').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
            }
            e.target.value = value;
        });
    </script>

</body>
</html>