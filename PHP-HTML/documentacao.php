<?php
session_start();
require_once 'Menu.php';
require_once 'conexao.php';

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
        <!-- Linha 1 -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.1.4</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Adicionado filtro por status (Disponível/Emprestado)</li>
                            <li>Correção no envio de e-mail de devolução</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.1.3</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Exportação de relatório de empréstimos em PDF</li>
                            <li>Validação de data de devolução não anterior à data de empréstimo</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Linha 2 -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.1.2</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Adicionado campo "categoria" nos livros (Ficção, Ciência, etc.)</li>
                            <li>Filtro de livros por categoria na listagem</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.1.1</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Atualização da interface de empréstimo com confirmação de dados</li>
                            <li>Log de ações do usuário (quem emprestou/devolveu)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Linha 3 -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.1.0</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Adicionado cadastro de editoras</li>
                            <li>Integração do campo "ISBN" no cadastro de livros</li>
                            <li>Busca por título e ISBN na lista de livros</li>
                            <li>Correção no cálculo de multa por atraso</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.0.5</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Adicionado histórico de empréstimos por usuário</li>
                            <li>Restrição de empréstimo para usuários com pendências</li>
                            <li>Atualização automática de status de livro para "Emprestado"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Linha 4 -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.0.3</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Adicionado botão de devolução direta na listagem de empréstimos</li>
                            <li>Notificação visual de livros atrasados no dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Versão 1.0.0</strong>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Sistema inicial de cadastro de livros e usuários</li>
                            <li>Funcionalidade básica de empréstimo e devolução</li>
                            <li>Banco de dados MySQL estruturado para biblioteca</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <center><a href="dashboard.php" class="btn btn-primary">Voltar</a></center>
</body>
</html>