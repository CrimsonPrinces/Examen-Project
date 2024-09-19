<?php
    require_once("db_login.php");

  if (!isset($_SESSION["usertype"])) {
    header("Location: index.php");
  } // FIlter om te checken of je vrijwilligerbent zoja redirect
   else if ($_SESSION["usertype"] == 3) {
    header("Location: home.php");
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

<h2>Voedselbank Maaskantje</h2>;
   <div class="mb-20">
    <a href='home.php' class= "mx-5"> Home </a>
    <?php require_once("Switches.php");
     ?>
    </div>
</div>

<h2 class="text-lg border-b border-black mb-3"> Voorraad</h2>


<div class="bg-gray-200">
    <form method="post" class="bg-gray-200">
        <table class= "border-separate border-spacing-5 border">
            <thead>
                <?php                     
                    $prevProduct = null;
                    ?>
                <tr>
                    <th><a href="Voorraad.php?sort=streepjescode">Streepjescode</a></th>
                    <th><a href="Voorraad.php?sort=productnaam">Productnaam</a></th>
                    <th><a href="Voorraad.php?sort=categorie">Categorie</a></th>
                    <th><a href="Voorraad.php?sort=aantal">Aantal</a></th>
                    <th><a href="Voorraad.php?sort=verderfdatum">Verderfdatum</a></th>            
                </tr>
                <?php
                    $sort = array('streepjescode', 'productnaam', 'categorie', 'aantal', 'verderfdatum');
                    $order = 'streepjescode';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort)) {
                        $order = $_GET['sort'];
                    }

                    $sql = 'SELECT streepjescode, productnaam, categorie, aantal, verderfdatum FROM product ORDER BY '.$order;
                    $result = $conn->query($sql);
                ?>
            </thead>
            <tbody>
                <?php 
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
<button class="open-button" onclick="openForm()">Open Form</button>

<div class="form-popup" id="myForm">
  <form class="form-container" method="post">
    <h1>Product toevoegen</h1>

    <label for="stcd"><b>Streepjescode</b></label>
    <input type="number" placeholder="13 cijfige code hier" name="stcd" required>
    <label for="prnm"><b>Naam product</b></label>
    <input type="text" placeholder="Naam toevoegen" name="prnm" required>
    <label for="ctgr"><b>Categorie</b></label>
    <input type="text" placeholder="Categorie toevoegen" name="ctgr" required>
    <label for="amnt"><b>Aantal product</b></label>
    <input type="number" placeholder="0" name="amnt" required>
    <label for="date"><b>Verderfdatum</b></label>
    <input type="date" name="date" required>
    
    <button type="submit" class="btn">Toevoegen</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Sluiten</button>
  </form>
</div>
<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

closeForm();
</script>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = $conn->prepare("INSERT INTO product(streepjescode, productnaam, categorie, aantal, verderfdatum) VALUES(?, ?, ?, ?, ?)");
        
        $prdctCode = $_POST['stcd'];
        $prdctNaam = $_POST['prnm'];
        $prdctCtgr = $_POST['ctgr'];
        $prdctAantal = $_POST['amnt'];
        $prdctDatum = $_POST['date'];
        
        $sql->execute([$prdctCode, $prdctNaam, $prdctCtgr, $prdctAantal, $prdctDatum]);
        echo "Nieuw product toegevoegd.";

        header("Refresh: 3; url=Voorraad.php");
    }
?>
</body>
</html>