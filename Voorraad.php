<?php
    ob_start();
    require_once("db_login.php");

    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    } // FIlter om te checken of je vrijwilliger bent zoja redirect
    else if ($_SESSION["usertype"] == 3) {
        header("Location: Voedselpakket.php");
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
    <?php require_once("Switches.php");
     ?>
    </div>
</div>

<h2 class="text-lg border-b border-black mb-3"> Voorraad</h2>


<div class="max-w-3xl">
    <form method="post" class="flexbox bg-blue-200">
        <table class= "border-separate border-spacing-5 border">
            <thead>
                <tr>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=streepjescode">Streepjescode</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=productnaam">Productnaam</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=categorie">Categorie</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=aantal">Aantal</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=verderfdatum">Verderfdatum</a></th>            
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
                            $prevProduct = null; 
                            if ($row["streepjescode"] != $prevProduct) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["streepjescode"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["productnaam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["categorie"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["aantal"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["verderfdatum"] . "</td>";
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
<button class="open-button bg-blue-500 text-white border border-black hover:bg-blue-900" onclick="openForm()">Open Form</button>

<div class="form-popup" id="myForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Product toevoegen</h2>
    <div class="grid grid-cols-3">
        <div>
            <label for="stcd"><b>Streepjescode</b></label>
            <input class="border border-separate border-black" type="number" placeholder="13 cijfige code hier" name="stcd" required>
        </div>
        <div>
            <label for="prnm"><b>Naam product</b></label>
            <input class="border border-separate border-black" type="text" placeholder="Naam toevoegen" name="prnm" required>
        </div>
        <div>
            <label for="ctgr"><b>Categorie</b></label>
            <!-- <select>
                <option value="Aardappels, groente, fruit">Aardappels, groente, fruit</option>
                <option value="Kaas, vleeswaren">Kaas, vleeswaren</option>
                <option value="Zuivel, plantaardig en eieren">Zuivel, plantaardig en eieren</option>
                <option value="Bakkerij en banket">Bakkerij en banket</option>
                <option value="Frisdrank, sappen, koffie en thee">Frisdrank, sappen, koffie en thee</option>
                <option value="Pasta, rijst en wereldkeuken">Pasta, rijst en wereldkeuken</option>
        </select> -->
            <input class="border border-separate border-black" type="text" placeholder="Categorie toevoegen" name="ctgr" required> 
        </div>
        <div>
            <label for="amnt"><b>Aantal product</b></label>
            <input class="border border-separate border-black" type="number" placeholder="0" name="amnt" required>
        </div>
        <div>
            <label for="date"><b>Verderfdatum</b></label>
            <input class="border border-separate border-black" type="date" name="date" required>
        </div>
        </div>
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeForm()">Sluiten</button>
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