<?php
include_once "../class/usuario.class.php";
include_once "../class/usuarioDAO.class.php";

// echo "<pres>";
// print_r(value: $_POST);
// echo "<br>", $_POST["nome"], "<br>";

// echo "Olá ".$_POST["nome"]."!";
$obj = new usuario();
$obj->setNome($_POST["nome"]);
$obj->setEmail($_POST["email"]);
$obj->setSenha($_POST["senha"]);

$nomeImagem = $_FILES["imagem"]["name"];
$nomeTmp = $_FILES["imagem"]["tmp_name"];
$diretorio = "../img/".$nomeImagem;
if(move_uploaded_file($nomeTmp, $diretorio)){
    $obj->setImagem($nomeImagem);
}


$objDAO = new usuarioDAO();
$retorno = $objDAO->inserir($obj);
if ($retorno)
    echo "Cadastra com sucesso!";
else
    echo "Erro ao cadastrar";

?>