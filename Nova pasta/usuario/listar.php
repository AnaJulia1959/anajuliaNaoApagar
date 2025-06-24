<?php
session_start();
if(!isset($_SESSION["login"])){
    header("location:login.php");
}
include_once "../class/usuario.class.php";
include_once "../class/usuarioDAO.class.php";

$objDAO = new usuarioDAO();
$retorno = $objDAO->listar();
?>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th colspan="2">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($retorno as $linha){
            echo "<tr>";
            echo "<td>".$linha["id"]."</td>";
            echo "<td>".$linha["nome"]."</td>";
            echo "<td>".$linha["email"]."</td>";
            echo "<td><a href='editar.php?id=".$linha["id"]."'>Editar</a></td>";
            echo "<td><a href='excluir.php?id=".$linha["id"]."'>Excluir</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
