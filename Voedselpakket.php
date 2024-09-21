<?php
    ob_start();
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

<h2>Voedselbank Maaskantje</h2>
    <div class="mb-20">
    <a href='home.php' class= "mx-5"> Home </a>
    <?php require_once("Switches.php");
     ?>
    </div>
</div>
<h2 class="text-lg border-b border-black mb-3"> Voedselpakket</h2>
<div>
    <form class='form-container' method='post'>
        <select name='klanten' id='klanten'>
<?php 
    $sqlKlantDisplay = "SELECT idklant, naam FROM klant";
    $resKlantDisplay = $conn->query($sqlKlantDisplay);
    
    
    while ($row = $resKlantDisplay->fetch(PDO::FETCH_ASSOC)){
        echo "<option class='border border-black hover:border-black' value='" . $row["idklant"] . "'>" . $row["naam"] . "</option>";
    }
?>
        </select>
        <button type='submit' class='btn' onclick='openWens()'>Deze klant selecteren</button>
    </form>

<!--Alles hier onder werkt niet en ik haat alles in het leven.-->
<!-- <script>
function openWens() {
  document.getElementById("wensForm").style.display = "block";
}

</script> -->
<?php 
    /*$askedKlantID = $_POST["klanten"];
    $askedKlantWens = $conn->prepare("SELECT wens FROM klant WHERE idklant = ?");
    
    $askedKlantWens->execute([$askedKlantID]);
    while ($row = $askedKlantWens->fetch(PDO::FETCH_ASSOC)) {
        echo "<form id='wensForm'>";
        echo "<p>" . $row["wens"] . "</p>";
        echo "</form>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<form id='wensForm'>";
        echo "<table>";
        echo "<tr>" . $row["naam"] . "</tr>";
        echo "<tr>" . $row["wensen"] . "</tr>";
        echo "</table>";
        echo "</form>";
    }*/
?>
</div>
<div class="max-w-xl">
    <form method="post" class="flexbox bg-blue-200 text-white">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th class=" border border-slate-600 bg-gray-500 text-sm">Voedselpakket ID</th>
                <th class=" border border-slate-600 bg-gray-500 text-base">Hoord bij klant</th>
                <th class=" border border-slate-600 bg-gray-500 text-base">Samensteldatum</th>
                <th class=" border border-slate-600 bg-gray-500 text-base">Uitgiftedatum</th>            
            </tr>
                <?php
                    $sql = "SELECT idvoedselpakket, naam, samensteldatum, uitgiftedatum FROM voedselpakket JOIN klant ON voedselpakket.idklant = klant.idklant ORDER BY voedselpakket.idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            $prevVoedselpakket = null;
                            if ($row["idvoedselpakket"] != $prevVoedselpakket) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["idvoedselpakket"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["naam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["samensteldatum"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["uitgiftedatum"] . "</td>";
                                echo "</tr>";
                            }
                            $prevVoedselpakket = $row["idvoedselpakket"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button bg-green-500 text-white border border-black hover:bg-green-900 mt-5" onclick="openEnterForm()">Toevoegen</button>

<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h1>Voedselpakket maken</h1>

    <label for="cust"><b>Klant</b></label>
    <select name="cust" id="cust">
    <?php 
    
    $sqlCust = "SELECT idklant, naam FROM klant";
    $resCust = $conn->query($sqlCust);
    if ($resCust) {
        while ($row = $resCust->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row["idklant"] . "'>" . $row["naam"] . "</option>";
        }
    }
    ?>
    </select>
    <label for="prod"><b>Product</b></label>
    <?php 
    
    $sqlProd = "SELECT streepjescode, productnaam FROM product";
    $resProd = $conn->query($sqlProd);
    if ($resProd) {
        while ($row = $resProd->fetch(PDO::FETCH_ASSOC)) {
            echo "<input type='checkbox' name='producten[]' value='" . $row["streepjescode"] . "'>" . $row["productnaam"] . " ";
            echo "<label for='" . $row["streepjescode"] . "'>Aantal</label> ";
            echo "<input type='number' placeholder='0' name='" . $row["streepjescode"] . "' required><br>";
        }
    }
    ?>

    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<script>
function openEnterForm() {
  document.getElementById("myEnterForm").style.display = "block";
}

function closeEnterForm() {
  document.getElementById("myEnterForm").style.display = "none";
}

closeEnterForm();
</script>

<?php
    if(isset($_POST['add'])) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $sqlKlantAanVoedselpakket = $conn->prepare("INSERT INTO voedselpakket(samensteldatum, idklant) VALUES(?, ?)");
            
            $voedKlantID = $_POST['cust'];
            $voedDatum = date('Y/m/d');
            $sqlKlantAanVoedselpakket->execute([$voedDatum, $voedKlantID]);
            
            $voedProducten = $_POST['producten'];
            $last_id = $conn->lastInsertId();

            foreach ($voedProducten as $voedProduct) {
                $voedAantal = $_POST[$voedProduct];
                $sqlVoedselpakketAandProduct = $conn->prepare("INSERT INTO voedselpakket_has_product(idvoedselpakket, idklant, streepjescode, aantal) VALUES(?, ?, ?, ?)");
                $sqlVoedselpakketAandProduct->execute([$last_id, $voedKlantID, $voedProduct, $voedAantal]);

                $sqlProduct = "SELECT streepjescode, aantal FROM product WHERE streepjescode = $voedProduct";
                $resProduct = $conn->query($sqlProduct);
                if($resProduct) {
                    while ($row = $resProduct->fetch(PDO::FETCH_ASSOC)) {
                        $aantalNaToevoeging = $row["aantal"] - $voedAantal;
                        $prodAantalVeranderen = $conn->prepare("UPDATE product SET aantal = ? WHERE streepjescode = ?");
                        $prodAantalVeranderen->execute([$aantalNaToevoeging, $voedProduct]);
                    }
                }
            }
            echo "Nieuw voedselpakket toegevoegd.";
            header("Refresh: 3; url=Voedselpakket.php");
        }
    }
?>
</body>
</html>