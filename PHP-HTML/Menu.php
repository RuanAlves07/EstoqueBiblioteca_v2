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
$nome_perfil = $perfil['nome_perfil'] ?? 'Desconhecido';

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL
$permissoes = [
    1 => [
        "Cadastrar"   => ["cadastro_usuario.php", "cadastro_cliente.php", "cadastro_distribuidora.php", "cadastro_livro.php", "cadastro_funcionario.php"],
        "Buscar"      => ["buscar_usuario.php", "buscar_cliente.php", "buscar_distribuidora.php", "buscar_livro.php", "buscar_funcionario.php"],
        "Alterar"     => ["alterar_usuario.php", "alterar_cliente.php", "alterar_distribuidora.php", "alterar_livro.php", "alterar_funcionario.php"],
        "Excluir"     => ["excluir_usuario.php", "excluir_cliente.php", "excluir_distribuidora.php", "excluir_livro.php", "excluir_funcionario.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    2 => [
        "Cadastrar"   => ["cadastro_cliente.php", "cadastro_distribuidora.php", "cadastro_livro.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_distribuidora.php", "buscar_livro.php"],
        "Alterar"     => ["alterar_cliente.php", "alterar_distribuidora.php", "alterar_livro.php"],
        "Excluir"     => ["excluir_cliente.php", "excluir_distribuidora.php", "excluir_livro.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    3 => [
        "Cadastrar"   => ["cadastro_distribuidora.php", "cadastro_livro.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_distribuidora.php", "buscar_livro.php"],
        "Alterar"     => ["alterar_distribuidora.php", "alterar_livro.php"],
        "Excluir"     => ["excluir_livro.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    4 => [
        "Buscar"      => ["buscar_livro.php"],
        "Alterar"     => ["alterar_usuario.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
];

$opcoes_menu = $permissoes[$id_perfil] ?? [];

// Função MOVIDA para ANTES do uso no HTML
function getCategoryIcon($categoria) {
    switch($categoria) {
        case 'Cadastrar': return 'plus';
        case 'Buscar': return 'search';
        case 'Alterar': return 'edit';
        case 'Excluir': return 'trash';
        case 'Emprestimo': return 'handshake';
        default: return 'cog';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Biblioteca</title>
    <!-- Corrigido: removidos espaços nas URLs -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <style>
        .white-link {
            color: white;
            text-decoration: none;
        }
        .white-link:hover {
            color: #f8f9fa; /* Cor mais clara ao passar o mouse */
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="white-link">
                <h4><i class="fas fa-book"></i> <span class="sidebar-title">Biblioteca</span></h4>
            </a>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <?php if (!empty($opcoes_menu)): ?>
                <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
                    <div class="menu-category">
                        <div class="category-header" onclick="toggleCategory(this)">
                            <i class="fas fa-<?= getCategoryIcon($categoria) ?>"></i>
                            <span class="category-text"><?= $categoria ?></span>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                        <div class="category-items">
                            <?php foreach($arquivos as $arquivo): ?>
                                <a href="<?= htmlspecialchars($arquivo) ?>" class="menu-item">
                                    <i class="fas fa-circle"></i>
                                    <?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-3 text-center text-muted">Sem permissões</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content -->


        <!-- Área de conteúdo principal -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleCategory(element) {
            const category = element.parentElement;
            const items = category.querySelector('.category-items');
            const icon = element.querySelector('.toggle-icon');
            
            category.classList.toggle('active');
            
            if (category.classList.contains('active')) {
                items.style.maxHeight = items.scrollHeight + 'px';
            } else {
                items.style.maxHeight = '0';
            }
        }

        // Auto-collapse sidebar on mobile
        window.addEventListener('resize', function () {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
        });

        // Verifica no carregamento
        window.onload = function () {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            }
        };
    </script>

</body>
</html>