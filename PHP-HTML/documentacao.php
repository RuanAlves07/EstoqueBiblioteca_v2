<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentação de Atualizações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
        <center><h1>Documentação de Atualizações</h1></center>

        <div class="container mt-4">
            <div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div >
                            <center><h5><u>Versão 1.1.0.0</u></h5></center>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Adicionado suporte ao novo layout da EFD-Reinf v3.0</li>
                                <li>Integração com o novo módulo de auditoria fiscal automática</li>
                                <li>Novo relatório de análise de crédito Pis/Cofins com gráficos interativos</li>
                                <li>Correção no cálculo de base de ICMS para operações interestaduais</li>
                                <li>Adicionado campo de observação em notas de entrada</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="dashboard.php" class="btn-back">Voltar</a>

</body>
</html>