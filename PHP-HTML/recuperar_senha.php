<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Minha Senha - Biblioteca</title>
    <link rel="stylesheet" href="../CSS/recuperar_senha.css">
</head>
<body>
    <!-- Elementos decorativos -->
    <div class="decoration decoration-1"></div>
    <div class="decoration decoration-2"></div>
    <div class="decoration decoration-3"></div>

    <div class="container">
        <div class="header">
            <h1 class="title">Esqueci Minha Senha</h1>
            <p class="subtitle">Digite seu email para receber as instruções de recuperação de senha</p>
        </div>

        <div class="success-message" id="successMessage">
            <strong>Email enviado com sucesso!</strong><br>
            Verifique sua caixa de entrada e siga as instruções para redefinir sua senha.
        </div>

        <form class="form-container" id="forgotForm">
            <div class="form-group">
                <label class="label" for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    class="input" 
                    placeholder="Digite seu email"
                    required
                >
            </div>

            <button type="submit" class="button">
                Enviar Instruções
            </button>
        </form>

        <div class="back-link">
            <a href="#" onclick="goBack()">← Voltar para o login</a>
        </div>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            
            if (email) {
                // Simula o envio do email
                document.getElementById('successMessage').classList.add('show');
                document.getElementById('forgotForm').classList.add('hide');
                
                // Opcional: redirecionar após alguns segundos
                setTimeout(function() {
                    // window.location.href = 'login.html';
                }, 3000);
            }
        });

        function goBack() {
            window.location.href = 'login.php';
            alert('Redirecionando para a página de login...');
        }
    </script>
</body>
</html>
