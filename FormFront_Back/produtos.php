<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Produto</title>
    <script src="form.js"></script>
    <link rel="stylesheet" href="style_produto.css"> </script>
</head>
<body>
    <form method="post" action="backendform.php" id="formulario" onsubmit="return valida()"  >
        <!--DADOS PESSOAIS-->
        <fieldset>
            <div>
                <center><h1>ADICIONAR PRODUTO</h1></center>
            </div>
            <div id="linha">
                <hr>
            </div>
            <br>
            
            <!--Nome do produto-->

            <table border="0" cellspacing="5">
                    <tr>
                        <td align="right">
                            <label for="nome">Nome do produto: </label>
                        </td>
                        <td align="right">
                            <input type="text" onkeypress="mascara(this, nome)" maxlength="20" size="15">
                        </td>
                    </tr>

            <!--Descrição do produto-->


                    <tr>
                        <td align="right">
                            <label for="descricao">Descrição do produto: </label>
                        </td>
                        <td align="right">
                            <input class="tamanho" type="text" name="descricao" size="15"required>
                        </td>
                    </tr>


            <!--Categoria do produto-->
                <tr>
                    <td align="right">
                        <label for="categoria">Categoria do produto: </label>
                    </td>
                    <td align="right">
                        <input type="text" placeholder="Ex: Juvenil" onkeypress="mascara(this, categoria)" maxlength="20" size="15">
                    </td>
                </tr>

            <!--Quantidade do produto-->


                <tr>
                    <td align="right">
                        <label for="quantidade">Quantidade do produto: </label>
                    </td>
                    <td align="right">
                        <input type="text" placeholder="qntd.unitária" onkeypress="mascara(this, quantidade)" maxlength="13" size="15">
                    </td>
                </tr>

            <!--Preço do produto-->


                <tr>
                    <td align="right">
                        <label for="preco">Preço do produto: </label>
                    </td>
                    <td align="right">
                        <input type="text" placeholder="R$0,00" onkeypress="mascara(this, preco)" maxlength="13" size="15">
                    </td>
                </tr>

            <!--Fornecedor do produto-->

                <tr>
                    <td align="right">
                        <label for="fornecedor">Fornecedor do produto: </label>
                    </td>
                    <td align="right">
                        <input type="text" onkeypress="mascara(this, fornecedor)" maxlength="30" size="15">
                    </td>
                </tr>


            <br>
            <br>

            <tr>
                <td class="botao">
                    <input class="btn1" type="submit" value="Enviar" >
                    <input class="btn2" type="button" value="Cancelar">
                </td>
            </tr>
            </table>
            
        </fieldset>
    </form>
</body>
</html>