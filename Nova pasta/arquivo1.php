<html>

<head></head>

<body>
    <h1>Site 1</h1>
    <?php
    echo "<h2>Olá mundo!</h2>";
    ?>
    <p><?= "Simplificação do echo" ?></p>
    <?php
    $um = "Everton";
    $dois = ["Everton", "Rebeca", "Gustavo", "Marcela"];
    echo $um;
    echo "<pre>";
    var_dump(value: $dois);


    //imprmir array em lista
    echo "<ul>";
    foreach ($dois as $linha) {
        echo "<li>$linha</li>";
    }
    echo "</ul>";


    $numero = 10;
    if ($numero == 10)
        echo "Parabéns!";
    else if ($numero > 6)
        echo "Aprovado!";
    else
        echo "Em Re.";
    ?>

</body>

</html>