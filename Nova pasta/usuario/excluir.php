<?php
include_once "../class/usuario.class.php";
include_once "../class/usuarioDAO.class.php";

$id = $_GET["id"];
$objDAO = new usuarioDAO();
$retorno = $objDAO->delete($id);

if ($retorno)
    header("location:listar.php?deleteOk");
else
    header("location:listar.php?deleteN");
?>