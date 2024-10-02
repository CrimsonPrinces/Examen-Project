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
    <?php require_once("Switches.php");
     ?>
    </div>
</div>
<h2 class="text-lg border-b border-black mb-3"> Voedselpakket</h2>
<!--Wens form-->
<div>
    <form class='form-container' method='post'>
        <select name='klanten' id='klanten'>
            <?php 
                $sqlKlantDisplay = "SELECT idklant, naam, wensen FROM klant";
                $resKlantDisplay = $conn->query($sqlKlantDisplay);
                
                while ($row = $resKlantDisplay->fetch(PDO::FETCH_ASSOC)){
                    echo "<option class='border border-black hover:border-black' value='" . $row["idklant"] . "' name='" . $row["idklant"] . "'>" . $row["naam"] . "</option>";
                }
            ?>
        </select>
        <button class="hover:bg-blue-400 hover:text-white text-black mb-5" type='submit' class='btn' onclick='openWens()' name="disWens">Deze klant selecteren</button>
    </form>
</div>
<div>
    <table id="wensForm">
        <?php
            if (isset($_POST["disWens"])) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $sqlWensID = $_POST["klanten"];
                    $sqlWensDisplay = "SELECT idklant, naam, wensen FROM klant WHERE idklant = ?";
                    $resWensDisplay = $conn->prepare($sqlWensDisplay);
                    if ($resWensDisplay->execute([$sqlWensID])) { 
                        while ($row = $resWensDisplay->fetch(PDO::FETCH_ASSOC)) { 
                            echo "<td value='" . $row["idklant"] . "'>Wens van " . $row["naam"] . ": " . $row["wensen"] . "</td>";
                        } 
                    }
                }
            }
        ?>
    </table>
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
                    $sql = "SELECT idvoedselpakket, naam, samensteldatum, uitgiftedatum FROM voedselpakket JOIN klant ON voedselpakket.idklant = klant.idklant ORDER BY idvoedselpakket";
                    $result = $conn->query($sql);
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<form method='post'>";
                                echo "<tr>";
                                    echo "<td class='border border-slate-600 text-black'>" . $row["idvoedselpakket"] . "</td>";
                                    echo "<td class='border border-slate-600 text-black'>" . $row["naam"] . "</td>"; 
                                    echo "<td class='border border-slate-600 text-black'>" . $row["samensteldatum"] . "</td>"; 
                                    echo "<td class='border border-slate-600 text-black'>" . $row["uitgiftedatum"] . "</td>";
                                    echo "<td class='border border-slate-600 text-black'><button type='submit' value='" . $row["idvoedselpakket"] . "' name='". $row["idvoedselpakket"] . "'>Laat producten zien</button></td>";
                                echo "</tr>";

                                if (isset($_POST[$row["idvoedselpakket"]])) {
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $sqlDisplayProdInPakket = "SELECT idvoedselpakket, voedselpakket_has_product.streepjescode, productnaam, voedselpakket_has_product.aantal FROM voedselpakket_has_product JOIN product ON voedselpakket_has_product.streepjescode = product.streepjescode WHERE idvoedselpakket = ?";
                                        $resDisplayProdInPakket = $conn->prepare($sqlDisplayProdInPakket);

                                        if ($resDisplayProdInPakket->execute([$_POST[$row["idvoedselpakket"]]])) {
                                            while ($row = $resDisplayProdInPakket->fetch(PDO::FETCH_ASSOC)) {
                                                echo "Product: " . $row["productnaam"] . " Aantal: " . $row["aantal"] . "<br>"; 
                                            }
                                        }
                                    }
                                }
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button bg-green-500 text-white border border-black hover:bg-green-900 mt-5" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button bg-yellow-300 text-white border border-black hover:bg-yellow-500 mt-5" onclick="openUitgeef()">Uitgeven</button>
<!-- toevoegen damian --> 
<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Voedselpakket maken</h2>

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
    <br>
    <label for="prod"><b>Product:</b></label>
    <br>
    <?php 
    
    $sqlProd = "SELECT streepjescode, productnaam FROM product";
    $resProd = $conn->query($sqlProd);
    if ($resProd) {
        while ($row = $resProd->fetch(PDO::FETCH_ASSOC)) {
            echo "<input type='checkbox' name='producten[]' value='" . $row["streepjescode"] . "'> " . $row["productnaam"] . " ";
            echo "<label for='" . $row["streepjescode"] . "'>Aantal</label> ";
            echo "<input type='number' value='0' name='" . $row["streepjescode"] . "' required><br>";
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
</script>
<!-- jeff -->
<div class="form-popup-uitgeef" id="Uitgeef"> 
<form class="form-container-uitgeef" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Voedselpakket Uitgeven</h2>

    <label for="Uitsend"><b>Voedselpakket</b></label>
    <Select name="Uitsend" id="Uitsend">
    <?php
    
    $sqlUit = "SELECT idvoedselpakket, samensteldatum, idklant FROM voedselpakket WHERE uitgiftedatum IS NULL ORDER BY idvoedselpakket";
    $show = $conn->query($sqlUit);
        if($show){
            while($row = $show->fetch(PDO::FETCH_ASSOC)) {
                echo"<option value='". $row["idvoedselpakket"]."'> ".$row["idvoedselpakket"]."</option>";
            }
        }
    ?> 
    </Select>
    <br>
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="uitGeef">Uitgeven</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeUitgeef()">Sluiten</button>
  </form>
</div>
<script>
    closeEnterForm();
    function openUitgeef(){
        document.getElementById("Uitgeef").style.display = "block";
    }
    function closeUitgeef(){
        document.getElementById("Uitgeef").style.display = "none";
    }
    closeUitgeef();
</script>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            case isset($_POST['add']):
                $sqlKlantAanVoedselpakket = $conn->prepare("INSERT INTO voedselpakket(samensteldatum, idklant) VALUES(?, ?)");
                $sqlKlantAanVoedselpakket->execute([$voedDatum, $voedKlantID]);
                
                $voedKlantID = $_POST['cust'];
                $voedDatum = date('Y/m/d');
                
                $voedProducten = $_POST['producten'];
                $last_id = $conn->lastInsertId();

                foreach ($voedProducten as $voedProduct) {
                    $voedAantal = $_POST[$voedProduct];

                    $sqlProduct = "SELECT streepjescode, aantal FROM product WHERE streepjescode = ?";
                    $resProduct = $conn->prepare($sqlProduct);
                    if($resProduct->execute([$voedProduct])) {
                        while ($row = $resProduct->fetch(PDO::FETCH_ASSOC)) {
                            $aantalNaToevoeging = $row["aantal"] - $voedAantal;
                            if ($aantalNaToevoeging >= 0) {
                                $sqlVoedselpakketAndProduct = $conn->prepare("INSERT INTO voedselpakket_has_product(idvoedselpakket, idklant, streepjescode, aantal) VALUES(?, ?, ?, ?)");
                                $sqlVoedselpakketAndProduct->execute([$last_id, $voedKlantID, $voedProduct, $voedAantal]);

                                $prodAantalVeranderen = $conn->prepare("UPDATE product SET aantal = ? WHERE streepjescode = ?");
                                $prodAantalVeranderen->execute([$aantalNaToevoeging, $voedProduct]);

                                echo "Nieuw voedselpakket toegevoegd.";
                                header("Refresh: 3; url=Voedselpakket.php");
                            } else {
                                echo "Aantal van producten kan niet lager dan 0.";
                            }
                        }
                    }
                }
                break;
            case isset($_POST['uitGeef']):
                $idpakket = $_POST["Uitsend"];
                $SQLuitgeefpakket = $conn->prepare("UPDATE voedselpakket SET uitgiftedatum = ? where idvoedselpakket = ?");

                $uitdatum = date('Y/m/d');
                $SQLuitgeefpakket->execute([$uitdatum,$idpakket]);
            
            
                echo"Voedselpakket is uitgegeven.";
                header("Refresh: 3; url=Voedselpakket.php");
                break;
        }
    }
?>
</body>
</html>