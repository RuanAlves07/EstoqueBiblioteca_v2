<!-- menu_view.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Sidebar -->
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
                    <a href="<?= htmlspecialchars($arquivo) ?>" class="menu-item">
                        <i class="fas fa-circle"></i>
                        <?= htmlspecialchars(ucfirst(str_replace("_", " ", basename($arquivo, ".php")))) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
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