<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>Document</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <form action="inserir_ok.php" method="post" enctype="multipart/form-data">
        Nome: <input type="text" name="nome" id="nome"><br>
        <br>Email: <input type="email" name="email" id="email"><br>
        <br>Senha: <input type="password" name="senha" id="senha"><br><br>
        Imagem:
        <input type="file" name="imagem"/><br>
        <br><button type="submit">Enviar</button>
    </form>
</body>
</html>