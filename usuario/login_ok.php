<?php
include_once "../class/usuario.class.php";
include_once "../class/usuarioDAO.class.php";

$obj = new usuario();
$obj->setEmail($_POST["email"]);
$obj->setSenha($_POST["senha"]);

$objDAO = new usuarioDAO();
$retorno = $objDAO->login($obj);
if ($retorno == 0) {
    echo "Email não cadastrado!";
} else if ($retorno == 1) {
    echo "Senha incorreta";
} else {
    session_start();
    $_SESSION["id"] = $retorno["id"];
    $_SESSION["login"] = true;
    header("location:listar.php");
}


?>