<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        
        <button type="submit">Entrar</button>

    </form>
    
    <p><a href="recuperar_senha.php">Esqueci minha senha</a></p>
</body>
</html>