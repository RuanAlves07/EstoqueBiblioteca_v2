<?php
session_start();
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

// ESTATÍSTICAS DO DASHBOARD
try {
    // Total de produtos/livros
    $sqlProdutos = "SELECT COUNT(*) as total FROM produto";
    $stmtProdutos = $pdo->prepare($sqlProdutos);
    $stmtProdutos->execute();
    $totalProdutos = $stmtProdutos->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // Total de fornecedores
    $sqlFornecedores = "SELECT COUNT(*) as total FROM fornecedor";
    $stmtFornecedores = $pdo->prepare($sqlFornecedores);
    $stmtFornecedores->execute();
    $totalFornecedores = $stmtFornecedores->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // Total de usuários
    $sqlUsuarios = "SELECT COUNT(*) as total FROM usuario";
    $stmtUsuarios = $pdo->prepare($sqlUsuarios);
    $stmtUsuarios->execute();
    $totalUsuarios = $stmtUsuarios->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

    // Produtos com estoque baixo (menos de 5)
    $sqlEstoqueBaixo = "SELECT COUNT(*) as total FROM produto WHERE quantidade_estoque < 5";
    $stmtEstoqueBaixo = $pdo->prepare($sqlEstoqueBaixo);
    $stmtEstoqueBaixo->execute();
    $estoqueBaixo = $stmtEstoqueBaixo->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

} catch (PDOException $e) {
    error_log("Erro ao buscar estatísticas: " . $e->getMessage());
    $totalProdutos = 0;
    $totalFornecedores = 0;
    $totalUsuarios = 0;
    $estoqueBaixo = 0;
}

// DEFINIÇÃO DAS PERMISSÕES POR PERFIL
$permissoes = [
    1 => [
        "Cadastrar"   => ["cadastro_usuario.php", "cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_livro.php", "cadastro_funcionario.php"],
        "Buscar"      => ["buscar_usuario.php", "buscar_cliente.php", "buscar_fornecedor.php", "buscar_livro.php", "buscar_funcionario.php"],
        "Alterar"     => ["alterar_usuario.php", "alterar_cliente.php", "alterar_fornecedor.php", "alterar_livro.php", "alterar_funcionario.php"],
        "Excluir"     => ["excluir_usuario.php", "excluir_cliente.php", "excluir_fornecedor.php", "excluir_livro.php", "excluir_funcionario.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    2 => [
        "Cadastrar"   => ["cadastro_cliente.php", "cadastro_fornecedor.php", "cadastro_livro.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_livro.php"],
        "Alterar"     => ["alterar_cliente.php", "alterar_fornecedor.php", "alterar_livro.php"],
        "Excluir"     => ["excluir_cliente.php", "excluir_fornecedor.php", "excluir_livro.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    3 => [
        "Cadastrar"   => ["cadastro_fornecedor.php", "cadastro_livro.php"],
        "Buscar"      => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_livro.php"],
        "Alterar"     => ["alterar_fornecedor.php", "alterar_livro.php"],
        "Excluir"     => ["excluir_livro.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
    4 => [
        "Buscar"      => ["buscar_livro.php"],
        "Alterar"     => ["alterar_usuario.php"],
        "Emprestimo"  => ["emprestimo_de_livros.php", "seus_emprestimos.php"]
    ],
];

$opcoes_menu = $permissoes[$id_perfil];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>
    <!-- Menu lateral -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-book"></i> Biblioteca</h4>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <?php foreach($opcoes_menu as $categoria => $arquivos): ?>
            <div class="menu-category">
                <div class="category-header" onclick="toggleCategory(this)">
                    <i class="fas fa-<?= getCategoryIcon($categoria) ?>"></i>
                    <span><?= $categoria ?></span>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div class="category-items">
                    <?php foreach($arquivos as $arquivo): ?>
                    <a href="<?= $arquivo ?>" class="menu-item">
                        <i class="fas fa-circle"></i>
                        <?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Conteudo principal -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="sidebar-toggle mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>Dashboard</h2>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-details">
                        <span class="user-name"><?php echo $_SESSION["usuario"]; ?></span>
                        <span class="user-role"><?php echo $nome_perfil; ?></span>
                    </div>
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <form action="logout.php" method="POST" class="logout-form">
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </header>

        <!-- Conteudo da dashboard -->
        <div class="dashboard-content">
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon books">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalProdutos ?></h3>
                        <p>Total de Livros</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon suppliers">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalFornecedores ?></h3>
                        <p>Fornecedores</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalUsuarios ?></h3>
                        <p>Usuários</p>
                    </div>
                </div>
                
                <div class="stat-card alert">
                    <div class="stat-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $estoqueBaixo ?></h3>
                        <p>Estoque Baixo</p>
                    </div>
                </div>
            </div>

            <!-- Atalhos da dashboard -->
            <div class="quick-actions">
                <h3>Ações Rápidas</h3>
                <div class="actions-grid">
                    <?php if (in_array("cadastro_livro.php", array_merge(...array_values($opcoes_menu)))): ?>
                    <a href="cadastro_livro.php" class="action-card">
                        <i class="fas fa-plus"></i>
                        <span>Cadastrar Livro</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array("buscar_livro.php", array_merge(...array_values($opcoes_menu)))): ?>
                    <a href="buscar_livro.php" class="action-card">
                        <i class="fas fa-search"></i>
                        <span>Buscar Livros</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (in_array("emprestimo_de_livros.php", array_merge(...array_values($opcoes_menu)))): ?>
                    <a href="emprestimo_de_livros.php" class="action-card">
                        <i class="fas fa-handshake"></i>
                        <span>Empréstimos</span>
                    </a>
                    <?php endif; ?>
                    
                    <a href="documentacao.php" class="action-card">
                        <i class="fas fa-file-alt"></i>
                        <span>Documentação</span>
                    </a>
                </div>
            </div>

          

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
                icon.style.transform = 'rotate(180deg)';
            } else {
                items.style.maxHeight = '0';
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Auto-collapse sidebar on mobile
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    </script>
</body>
</html>

<?php
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
