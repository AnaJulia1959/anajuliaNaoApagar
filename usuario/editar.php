<?php
$id = $_GET["id"];
include_once "../class/usuario.class.php";
include_once "../class/usuarioDAO.class.php";

$objDAO = new usuarioDAO();
$retorno = $objDAO->retornaUnico($id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h2>Cadastro de Usuário</h2>
    <form action="editar_ok.php" method="post">
        Nome: <input type="text" name="nome" id="nome" value="<?=$retorno['nome']?>"><br>
        <br>Email: <input type="email" name="email" id="email" value="<?=$retorno['email']?>"><br>
        <br>Senha: <input type="password" name="senha" id="senha" value="<?=$retorno['senha']?>"><br>
        <input type="hidden" name="id" value="<?=$retorno['id']?>">
        <br><button type="submit">Enviar</button>
    </form>
</body>

</html>