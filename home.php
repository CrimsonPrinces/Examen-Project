<?php
    require_once("db_login.php");

    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje</title>
</head>
<body>

<?php

echo "Voedselbank Maaskantje";
?>
<br>
<a href='Voedselpakket.php'> Voedselpakket </a>
<br>
<a href='Medewerker.php'> Medewerkers </a>
<br>
<a href='Voorraad.php'> Voorraad </a>
<br>
<a href='Leverancier.php'> Leveranciers </a>
<br>
<a href='Klanten.php'> Klanten </a>
<br>
<a href='logout.php'> Uitloggen </a>

</body>
</html>