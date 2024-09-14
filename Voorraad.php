<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";

  try {
      $conn = new PDO("mysql:host=$servername;dbname=examenproject", $username, $password);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
      echo $sql . "<br>" . $e->getMessage();
  }

//   if (!isset($_SESSION["usertype"])) {
//     header("Location: index.php");
//   } else if (isset($_SESSION["usertype"]) == 3) {
//     header("Location: home.php");
//   } Wij moeten hier nog naar kijken of A usertype wordt niet mee gegeven of we blockeren het verkeerd
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
<div class="mb-20">
<a href='home.php' class= "mx-5"> Home </a>
<a href='Voedselpakket.php' class= "mx-5"> Voedselpakket </a>
<a href='Medewerker.php' class= "mx-5"> Medewerkers </a>
<a href='Leverancier.php' class= "mx-5"> Leveranciers </a>
<a href='Klanten.php' class= "mx-5"> Klanten </a>
<a href='index.php' class= "mx-5"> Uitloggen </a>
</div>
</div>

<h2 class="text-lg border-b border-black mb-3"> Voorraad</h2>


<div class="bg-gray-200">
    <form method="post" class="bg-gray-200">
        <table class= "border-separate border-spacing-5 border">
            <thead>
                <tr>                
                    <th>Streepjescode</th>
                    <th>Productnaam</th>
                    <th>Categorie</th>
                    <th>Aantal</th>
                    <th>Verderfdatum</th>            
                </tr>
            </thead>
            <tbody>
                <?php 
                    $prevProduct = null;
                    $sql = "SELECT * FROM product ORDER BY streepjescode";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["streepjescode"] != $prevProduct) {
                                echo "<tr>";
                                echo "<td>" . $row["streepjescode"] . "</td>";
                                echo "<td>" . $row["productnaam"] . "</td>"; 
                                echo "<td>" . $row["categorie"] . "</td>"; 
                                echo "<td>" . $row["aantal"] . "</td>";
                                echo "<td>" . $row["verderfdatum"] . "</td>";
                                echo "</tr>";
                            }
                            $prevProduct = $row["streepjescode"];
                        } 
                    }
                ?> <!--Fetches data from database and displays it-->
            </tbody>    
        </table>
    </form>
</div>
</body>
</html>