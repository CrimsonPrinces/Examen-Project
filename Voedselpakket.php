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
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Voedselpakket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class = "p-8">
<div class="flex">
<?php

echo "Voedselbank Maaskantje";
?>
<div>
<a href='home.php' class= "mx-5"> Home </a>
<a href='Medewerker.php' class= "mx-5"> Medewerkers </a>
<a href='Voorraad.php' class= "mx-5"> Voorraad </a>
<a href='Leverancier.php' class= "mx-5"> Leveranciers </a>
<a href='Klanten.php' class= "mx-5"> Klanten </a>
<a href='index.php' class= "mx-5"> Uitloggen </a>
</div>
</div>

<form method="post" class="flexbox">
        <table>
            <tr>                
                <th>Voedselpakket ID</th>
                <th>Hoord bij klant</th>
                <th>Samensteldatum</th>
                <th>Uitgiftedatum</th>            
            </tr>
                <?php 
                    $prevVoedselpakket = null;
                    $sql = "SELECT * FROM voedselpakket JOIN klant ON voedselpakket.idklant = klant.idklant ORDER BY voedselpakket.idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idvoedselpakket"] != $prevVoedselpakket) {
                                echo "<tr>";
                                echo "<td>" . $row["idvoedselpakket"] . "</td>";
                                echo "<td>" . $row["naam"] . "</td>"; 
                                echo "<td>" . $row["samensteldatum"] . "</td>"; 
                                echo "<td>" . $row["uitgiftedatum"] . "</td>";
                                echo "</tr>";
                            }
                            $prevVoedselpakket = $row["idvoedselpakket"];
                        }
                    }
                ?>
        </table>
    </form>
</body>
</html>