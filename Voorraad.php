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

<h2>Voedselbank Maaskantje</h2>
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
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=product.idcategorie">Categorie</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=aantal">Aantal</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=verderfdatum">Verderfdatum</a></th>            
                </tr>
                <?php
                    $sort = array('streepjescode', 'productnaam', 'product.idcategorie', 'aantal', 'verderfdatum');
                    $order = 'streepjescode';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort)) {
                        $order = $_GET['sort'];
                    }

                    $sql = 'SELECT streepjescode, productnaam, beschrijving, aantal, verderfdatum FROM product JOIN categorie ON product.idcategorie = categorie.idcategorie ORDER BY ' . $order;
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
                                echo "<td class='border border-slate-600 text-black'>" . $row["beschrijving"] . "</td>"; 
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
<button class="open-button bg-blue-500 text-white border border-black hover:bg-blue-900" onclick="openEnterForm()">Product toevoegen</button>
<button class="open-button bg-blue-500 text-white border border-black hover:bg-blue-900" onclick="openChangeForm()">Wijzigen</button>
<button class="open-button bg-blue-500 text-white border border-black hover:bg-blue-900" onclick="openEnterCateForm()">Categorie toevoegen</button>

<div class="form-popup" id="myEnterForm">
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
            <select name="ctgr" id="ctgr">
                <?php
                    $sqlCate = "SELECT idcategorie, beschrijving FROM categorie";
                    $resCate = $conn->query($sqlCate);
                    if ($resCate) {
                    while ($row = $resCate->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row["idcategorie"] . "'>" . $row["beschrijving"] . "</option>";
                        }
                    } 
                ?>
            </select>
            <!--<input class="border border-separate border-black" type="text" placeholder="Categorie toevoegen" name="ctgr" required> -->
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
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<?php
    if (isset($_POST["disWens"])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sqlWijzCode = $_POST["wijzCode"];

            $sqlWijzDisplay = "SELECT * FROM product WHERE streepjescode = ?";
            $resWijzDisplay = $conn->prepare($sqlWijzDisplay);
            
            if ($resWijzDisplay->execute([$sqlWijzCode])) {
                $rowWijzDisplay = $resWijzDisplay->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
?>
<div class="form-popup" id="myChangeForm">
    <div>
        <form class='form-container' method='post'>
            <select name='wijzCode' id='wijzCode'>
            <?php
                $sqlProdWijzig = "SELECT * FROM product";
                $resProdWijzig = $conn->query($sqlProdWijzig);
                
                while ($row = $resProdWijzig->fetch(PDO::FETCH_ASSOC)){
                    echo "<option class='border border-black hover:border-black' value='" . $row["streepjescode"] . "' name='" . $row["streepjescode"] . "'>" . $row["productnaam"] . "</option>";
                }
                ?>
            </select>
            <button class="hover:bg-blue-400 hover:text-white text-black mb-5" type='submit' class='btn' name="disWens">Dit product selecteren</button>
        </form>
    </div>
  <form class="form-container-change" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Product wijzigen</h2>
    <div class=" grid grid-cols-3">
        <div>
        <label for="wijzAantal"><b>Aantal toevoegen</b></label>
        <input class="border border-separate border-black" type="number" name="wijzAantal" value="0" required>
        </div>
        <div>
        <label for="wijzVerderf"><b>Verderfdatum</b></label>
        <input class="border border-separate border-black" type="date" name="wijzVerderf" value="<?= isset($rowWijzDisplay['verderfdatum']) ? $rowWijzDisplay['verderfdatum'] : '' ?>" required>
        </div>
        <div>
        <label for="wijzCode"></label>
        <input hidden="text" name="wijzCode" value="<?= isset($rowWijzDisplay['streepjescode']) ? $rowWijzDisplay['streepjescode'] : '' ?>" required>
        </div>
    </div>
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="change">Wijzigen</button>
    <button class ="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeChangeForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup" id="myEnterCateForm">
  <form class="form-container-enter-cate" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Categorie toevoegen</h2>
    <div class="grid grid-cols-3">
        <div>
            <label for="cate"><b>Categorie</b></label>
            <input class="border border-separate border-black" type="text" placeholder="Categorie naam hier" name="cate" required>
        </div>
    </div>
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="cateAdd">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterCateForm()">Sluiten</button>
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

function openChangeForm() {
    document.getElementById("myChangeForm").style.display = "block";
}

function closeChangeForm() {
    document.getElementById("myChangeForm").style.display = "none";
}

closeChangeForm();

function openEnterCateForm() {
    document.getElementById("myEnterCateForm").style.display = "block";
}

function closeEnterCateForm() {
    document.getElementById("myEnterCateForm").style.display = "none";
}

closeEnterCateForm();
</script>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            case isset($_POST['add']):
                $sql = $conn->prepare("INSERT INTO product(streepjescode, productnaam, idcategorie, aantal, verderfdatum) VALUES(?, ?, ?, ?, ?)");
            
                $prdctCode = $_POST['stcd'];
                $prdctNaam = $_POST['prnm'];
                $prdctCtgr = $_POST['ctgr'];
                $prdctAantal = $_POST['amnt'];
                $prdctDatum = $_POST['date'];
                
                $sql->execute([$prdctCode, $prdctNaam, $prdctCtgr, $prdctAantal, $prdctDatum]);
                echo "Nieuw product toegevoegd.";
    
                header("Refresh: 3; url=Voorraad.php");
                break;
            case isset($_POST['change']):
                $voedAantal = $_POST['wijzAantal'];
                $changeVerderf = $_POST['wijzVerderf'];
                $changeCode = $_POST['wijzCode'];
    
                $sqlPrevAantal = "SELECT streepjescode, aantal FROM product WHERE streepjescode = ?";
                $resPrevAantal = $conn->prepare($sqlPrevAantal);
    
                if($resPrevAantal->execute([$changeCode])) {
                    while ($row = $resPrevAantal->fetch(PDO::FETCH_ASSOC)) {
                        $aantalNaToevoeging = $row["aantal"] + $voedAantal;
                        if ($aantalNaToevoeging >= 0) {
                            $prodAantalVeranderen = $conn->prepare("UPDATE product SET aantal = ? WHERE streepjescode = ?");
                            $prodAantalVeranderen->execute([$aantalNaToevoeging, $changeCode]);
                            $changeProd = $conn->prepare("UPDATE product SET verderfdatum = ? WHERE streepjescode = ?");
    
                            $changeProd->execute([$changeVerderf, $changeCode]);
                
                            echo "Product gewijzigd.";
                            header("Refresh: 3; url=Voorraad.php");
                        } else {
                            echo "Aantal kan niet lager dan 0.";
                        }
                    }
                }
                break;
            case isset($_POST['cateAdd']):
                $sqlCateAdd = $conn->prepare("INSERT INTO categorie(beschrijving) VALUES(?)");
                $cateNaam = $_POST['cate'];
        
                $sqlCateAdd->execute([$cateNaam]);
                echo "Nieuwe categorie toegevoed.";
                header("Refresh: 3; url=Voorraad.php");
                break;
        }
    }    
?>
</body>
</html>