<?php
    require_once("db_login.php");

    if ($_SESSION["usertype"] != 1) {
        header("Location: home.php");
    } 
    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    } 
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Leverancier</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class = "p-8">
<div class="flex">
<?php

echo "Voedselbank Maaskantje";
?>
<div>
<a href='home.php' class= "mx-5"> Home </a>
<a href='Voedselpakket.php' class= "mx-5"> Voedselpakket </a>
<a href='Medewerker.php' class= "mx-5"> Medewerkers </a>
<a href='Voorraad.php' class= "mx-5"> Voorraad </a>
<a href='Klanten.php' class= "mx-5"> Klanten </a>
<a href='index.php' class= "mx-5"> Uitloggen </a>
</div>
</div>

<div class="bg-gray-200">
<form method="post" class="flexbox bg-gray-200">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th>Leverancier ID</th>
                <th>Bedrijfsnaam</th>
                <th>Adres</th>
                <th>Naam contactpersoon</th>
                <th>E-mailadres</th>
                <th>Telefoonnummer</th>
                <th>Volgende Levering</th>      
            </tr>
                <?php 
                    $prevKlant = null;
                    $sql = "SELECT * FROM leverancier ORDER BY idleverancier";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idleverancier"] != $prevKlant) {
                                echo "<tr>";
                                echo "<td>" . $row["idleverancier"] . "</td>";
                                echo "<td>" . $row["bedrijfsnaam"] . "</td>"; 
                                echo "<td>" . $row["adres"] . "</td>";
                                echo "<td>" . $row["naamcontact"] . "</td>"; 
                                echo "<td>" . $row["emailadres"] . "</td>";
                                echo "<td>" . $row["telefoonnummer"] . "</td>";
                                echo "<td>" . $row["volgendelevering"] . "</td>";
                                echo "</tr>";
                            }
                            $prevKlant = $row["idleverancier"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
</body>
</html>