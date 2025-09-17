<?php
ob_start();
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

// Recupera mensagens da sessão
$erro = $_SESSION['erro'] ?? null;
$sucesso = $_SESSION['sucesso'] ?? null;
unset($_SESSION['erro'], $_SESSION['sucesso']);

// Processa o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $senha = trim($_POST['senha']);
    $id_perfil = $_POST['id_perfil'];
    
    // Validação básica
    if (empty($nome) || empty($email) || empty($senha) || empty($id_perfil)) {
        $_SESSION['erro'] = "Todos os campos são obrigatórios e o e-mail deve ser válido.";
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();
    }

    if ($id_perfil == 4) {
        $_SESSION['erro'] = "Não é possível cadastrar cliente aqui. Use o cadastro de cliente.";
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();
    }

    try {
        $pdo->beginTransaction();
        
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);
        $stmt->bindParam(':id_perfil', $id_perfil);

        $stmt->execute();
        $id_usuario = $pdo->lastInsertId();

        // Se for funcionário, insere na tabela funcionario também
        if ($id_perfil == 3) {
            $nome_completo = trim($_POST['nome_completo']);
            $cpf = trim($_POST['cpf']);
            $telefone = trim($_POST['telefone']);
            $cargo = trim($_POST['cargo']);
            $data_admissao = trim($_POST['data_admissao']);

            // Validação dos campos adicionais
            if (empty($nome_completo) || empty($cpf) || empty($telefone) || empty($cargo) || empty($data_admissao)) {
                throw new Exception("Todos os campos de funcionário são obrigatórios.");
            }

            $sql_func = "INSERT INTO funcionario (id_funcionario, nome_completo, cpf, cargo, telefone, data_admissao) 
                         VALUES (:id_funcionario, :nome_completo, :cpf, :cargo, :telefone, :data_admissao)";
            $stmt_func = $pdo->prepare($sql_func);
            $stmt_func->bindParam(':id_funcionario', $id_usuario);
            $stmt_func->bindParam(':nome_completo', $nome_completo);
            $stmt_func->bindParam(':cpf', $cpf);
            $stmt_func->bindParam(':cargo', $cargo);
            $stmt_func->bindParam(':telefone', $telefone);
            $stmt_func->bindParam(':data_admissao', $data_admissao);
            $stmt_func->execute();
        }

        $pdo->commit();
        $_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        
        if ($e->getCode() == 23000 || strpos($e->getMessage(), 'uk_usuario_email') !== false) {
            $_SESSION['erro'] = "O e-mail <strong>" . htmlspecialchars($email) . "</strong> já está cadastrado.";
        } else {
            $_SESSION['erro'] = "Erro ao cadastrar usuário: " . htmlspecialchars($e->getMessage());
        }
        header("Location: cadastro_usuario.php");
        ob_end_clean();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .funcionario-fields {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .funcionario-fields.show {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h2>Cadastro de Usuário</h2></center>

        <?php if ($erro): ?>
            <center><div class="alert alert-danger"><?= $erro ?></div></center>
        <?php endif; ?>
        <?php if ($sucesso): ?>
            <center><div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div></center>
        <?php endif; ?>

        <form method="POST" action="" id="cadastroForm">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Usuário:</label>
                <input type="text" class="form-control" id="nome" name="nome"
                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>

            <div class="mb-3">
                <label for="id_perfil" class="form-label">Perfil:</label>
                <select class="form-select" id="id_perfil" name="id_perfil" required>
                    <option value="">Selecione um perfil</option>
                    <option value="1" <?= ($_POST['id_perfil'] ?? '') == '1' ? 'selected' : '' ?>>Administrador</option>
                    <option value="2" <?= ($_POST['id_perfil'] ?? '') == '2' ? 'selected' : '' ?>>Superior</option>
                    <option value="3" <?= ($_POST['id_perfil'] ?? '') == '3' ? 'selected' : '' ?>>Funcionário</option>
                </select>
            </div>

            <!-- Campos adicionais para Funcionário -->
            <div class="funcionario-fields <?= ($_POST['id_perfil'] ?? '') == '3' ? 'show' : '' ?>" id="funcionarioFields">
                <h4>Dados do Funcionário</h4>
                <div class="mb-3">
                    <label for="nome_completo" class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" id="nome_completo" name="nome_completo"
                           value="<?= htmlspecialchars($_POST['nome_completo'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="cpf" class="form-label">CPF:</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="14"
                           value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone:</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" maxlength="15"
                           value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="cargo" class="form-label">Cargo:</label>
                    <input type="text" class="form-control" id="cargo" name="cargo"
                           value="<?= htmlspecialchars($_POST['cargo'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="data_admissao" class="form-label">Data de Admissão:</label>
                    <input type="date" class="form-control" id="data_admissao" name="data_admissao"
                           value="<?= htmlspecialchars($_POST['data_admissao'] ?? '') ?>">
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
                <button type="reset" class="btn btn-danger">Cancelar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
    <script>        // Mostrar/esconder campos de funcionário baseado no perfil selecionado
        document.getElementById('id_perfil').addEventListener('change', function() {
            const funcionarioFields = document.getElementById('funcionarioFields');
            if (this.value == '3') {
                funcionarioFields.classList.add('show');
            } else {
                funcionarioFields.classList.remove('show');
            }
        });

        // Validação do formulário antes de enviar
        document.getElementById('cadastroForm').addEventListener('submit', function(e) {
            const perfil = document.getElementById('id_perfil').value;
            if (perfil == '3') {
                const nomeCompleto = document.getElementById('nome_completo').value;
                const cpf = document.getElementById('cpf').value;
                const telefone = document.getElementById('telefone').value;
                const cargo = document.getElementById('cargo').value;
                const dataAdmissao = document.getElementById('data_admissao').value;
                
                if (!nomeCompleto || !cpf || !telefone || !cargo || !dataAdmissao) {
                    e.preventDefault();
                    alert('Todos os campos de funcionário são obrigatórios!');
                }
            }
        });

        // Executa ao carregar a página para mostrar campos se já tiver selecionado funcionário
        window.onload = function() {
            const perfil = document.getElementById('id_perfil').value;
            if (perfil == '3') {
                document.getElementById('funcionarioFields').classList.add('show');
            }
        };</script>
    <script src="../JS/validacoes.js"></script>
</body>
</html>