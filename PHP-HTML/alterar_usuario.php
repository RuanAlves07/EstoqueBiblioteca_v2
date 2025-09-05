<?php
session_start();
require_once 'conexao.php';
require_once 'Menu.php';

// VERIFICA SE O USUÁRIO ESTÁ LOGADO
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['perfil'])) { 
    echo "<script>alert('Você precisa estar logado.'); window.location.href='index.php';</script>";
    exit;
}

$usuario = null;
$busca = null;
$usuario_logado_id = $_SESSION['id_usuario'];
$perfil_logado = $_SESSION['perfil']; 

// SE O USUÁRIO FOR PERFIL 4 (CLIENTE), FORÇA O CARREGAMENTO DO PRÓPRIO USUÁRIO
if ($perfil_logado == 4) {
    $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $usuario_logado_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo "<script>alert('Erro: Usuário não encontrado.'); window.location.href='dashboard.php';</script>";
        exit;
    }
} 
// CASO CONTRÁRIO, PERMITE A BUSCA NORMAL
else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['busca_usuario'])) {
            $busca = trim($_POST['busca_usuario']);
        }

        // VERIFICA SE A BUSCA É POR ID OU NOME
        if ($busca !== null && is_numeric($busca)) {
            $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } elseif ($busca !== null) {
            $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "%$busca%", PDO::PARAM_STR);
        }

        if (isset($stmt)) {
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$usuario) {
                echo "<script>alert('Usuário não encontrado');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="../CSS/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="scripts.js"></script>
    <style>
        .container { max-width: 800px; }
        .form-group { margin-bottom: 1rem; }
        .logout { margin-top: 20px; }
    </style>
</head>
<body>

    <center><h2 class="mb-4">Alterar Usuário</h2></center>

    <div class="container mt-4">

        <!-- FORMULÁRIO DE BUSCA (SÓ MOSTRA SE NÃO FOR PERFIL 4) -->
        <?php if ($perfil_logado != 4): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="busca_usuario">Buscar por ID ou Nome:</label>
                <input type="text"
                       id="busca_usuario"
                       name="busca_usuario"
                       value="<?= htmlspecialchars($busca ?? '') ?>"
                       class="form-control"
                       placeholder="Digite o ID ou nome do usuário"
                       required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar Usuário</button>
        </form>
        <hr>
        <?php endif; ?>

        <!-- FORMULÁRIO DE ALTERAÇÃO -->
        <?php if ($usuario): ?>
        <form action="processa_alteracao_usuario.php" method="POST" onsubmit="return validarUsuario();">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

            <!-- Nome -->
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text"
                       name="nome"
                       id="nome"
                       value="<?= htmlspecialchars($usuario['nome']) ?>"
                       class="form-control"
                       required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email"
                       name="email"
                       id="email"
                       value="<?= htmlspecialchars($usuario['email']) ?>"
                       class="form-control"
                       required>
            </div>

            <!-- Perfil (BLOQUEADO PARA PERFIL 4) -->
            <div class="form-group">
                <label for="id_perfil">Perfil:</label>
                <select name="id_perfil" id="id_perfil" class="form-control" 
                    <?= $perfil_logado == 4 ? 'disabled' : '' ?> required>
                    <option value="1" <?= $usuario['id_perfil'] == 1 ? 'selected' : '' ?>>Administrador</option>
                    <option value="2" <?= $usuario['id_perfil'] == 2 ? 'selected' : '' ?>>Gerente</option>
                    <option value="3" <?= $usuario['id_perfil'] == 3 ? 'selected' : '' ?>>Operador</option>
                    <option value="4" <?= $usuario['id_perfil'] == 4 ? 'selected' : '' ?>>Cliente</option>
                </select>
                <?php if ($perfil_logado == 4): ?>
                    <input type="hidden" name="id_perfil" value="4">
                <?php endif; ?>
            </div>

            <!-- Nova Senha -->
            <div class="form-group">
                <label for="senha">Nova Senha (opcional):</label>
                <input type="password"
                       name="senha"
                       id="senha"
                       class="form-control"
                       placeholder="Deixe em branco para manter a senha atual">
                <small class="text-muted">Se preenchida, a senha será criptografada.</small>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success">Atualizar Usuário</button>
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
        function validarUsuario() {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            if (nome === '' || email === '') {
                alert('Nome e e-mail são obrigatórios!');
                return false;
            }
            return true;
        }
    </script>

</body>
</html>