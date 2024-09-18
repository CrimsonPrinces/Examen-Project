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
    <title>Voedselbank Maaskantje Voorraad</title>
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
<a href='Voorraad.php' class= "mx-5"> Voorraad </a>
<a href='Leverancier.php' class= "mx-5"> Leveranciers </a>
<a href='Klanten.php' class= "mx-5"> Klanten </a>
<a href='index.php' class= "mx-5"> Uitloggen </a>
</div>
</div>

<div class="bg-gray-200">
<form method="post" class="flexbox bg-gray-200">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th>User ID</th>
                <th>Gebruikersnaam</th>
                <th>User Type</th>      
            </tr>
                <?php 
                    $prevUser = null;
                    $sql = "SELECT iduser, gebruikersnaam, idusertype FROM user ORDER BY iduser";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["iduser"] != $prevUser) {
                                echo "<tr>";
                                echo "<td>" . $row["iduser"] . "</td>";
                                echo "<td>" . $row["gebruikersnaam"] . "</td>"; 
                                echo "<td>" . $row["idusertype"] . "</td>";
                                echo "</tr>";
                            }
                            $prevUser = $row["iduser"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
</body>
</html>