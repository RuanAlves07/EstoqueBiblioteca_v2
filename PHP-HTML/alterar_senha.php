<?php

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar senha</title>
    <link rel="stylesheet" href="styles.css">
</head>
    <body>
        <h2>Alterar senha</h2>
        <p>Olá, <strong><?php echo $_SESSION['usuario'];?></strong>. Digite sua nova senha abaixo:</p>

        <form action="alterar_senha.php" method="POST">
            <label for="nova_senha">Nova senha</label>
            <input type="password" id="nova_senha" name="nova_senha" required>
            
            <label for="confirmar_senha">Confirmar senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required>

            <label>
                <input type="checkbox" onclick="mostrarSenha()">Mostrar senha
            </label>

            <button type="submit">Salvar Nova Senha</button>
        </form>

        <script>
            function mostrarSenha(){
                var senha1 = document.getElementById("nova_senha");
                var senha2 = document.getElementById("confirmar_senha");
                var tipo = senha1.type === "password" ? "text": "password";
                senha1.type=tipo;
                senha2.type=tipo;
            }
        </script>
    </body>
</html>