<?php
    // Start output buffering to prevent headers from being sent prematurely
    ob_start();

    // Include database login credentials
    require_once("db_login.php");

    // Redirect to index.php if usertype is not set (user is not authenticated)
    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Voedselpakket</title>
    <!-- Load TailwindCSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
<div class="flex">

    <!-- Page Title -->
    <h2>Voedselbank Maaskantje</h2>
    
    <!-- Include external PHP file (Switches.php) for switches or UI components -->
    <div class="mb-20">
    <?php require_once("Switches.php"); ?>
    </div>
</div>

<!-- Section title for 'Voedselpakket' (food package) -->
<h2 class="text-lg border-b border-black mb-3">Voedselpakket</h2>

<!-- Wish selection form -->
<div>
    <form class='form-container' method='post'>
        <select name='klanten' id='klanten'>
            <?php 
                // Fetch customer details (id, name, wishes) from the database
                $sqlKlantDisplay = "SELECT idklant, naam, wensen FROM klant";
                $resKlantDisplay = $conn->query($sqlKlantDisplay);
                
                // Display customers as options in the select dropdown
                while ($row = $resKlantDisplay->fetch(PDO::FETCH_ASSOC)){
                    echo "<option class='border border-black hover:border-black' value='" . $row["idklant"] . "' name='" . $row["idklant"] . "'>" . $row["naam"] . "</option>";
                }
            ?>
        </select>
        <!-- Button to select the customer -->
        <button class="hover:bg-blue-400 hover:text-white text-black mb-5" type='submit' class='btn' onclick='openWens()' name="disWens">Deze klant selecteren</button>
    </form>
</div>

<!-- Table for displaying selected customer's wishes -->
<div>
    <table id="wensForm">
        <?php
            // Check if the 'disWens' button was clicked
            if (isset($_POST["disWens"])) {
                // Handle form submission via POST method
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $sqlWensID = $_POST["klanten"];
                    // Fetch selected customer's wishes based on their ID
                    $sqlWensDisplay = "SELECT idklant, naam, wensen FROM klant WHERE idklant = ?";
                    $resWensDisplay = $conn->prepare($sqlWensDisplay);
                    if ($resWensDisplay->execute([$sqlWensID])) { 
                        // Display the selected customer's wishes
                        while ($row = $resWensDisplay->fetch(PDO::FETCH_ASSOC)) { 
                            echo "<td value='" . $row["idklant"] . "'>Wens van " . $row["naam"] . ": " . $row["wensen"] . "</td>";
                        } 
                    }
                }
            }
        ?>
    </table>
</div>

<!-- Display list of food packages and the customers they belong to -->
<div class="max-w-xl">
    <form method="post" class="flexbox bg-blue-200 text-white">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th class="border border-slate-600 bg-gray-500 text-sm">Voedselpakket ID</th>
                <th class="border border-slate-600 bg-gray-500 text-base">Hoord bij klant</th>
                <th class="border border-slate-600 bg-gray-500 text-base">Samensteldatum</th>
                <th class="border border-slate-600 bg-gray-500 text-base">Uitgiftedatum</th>            
            </tr>
            <?php
                // Fetch all food packages along with customer names and relevant dates
                $sql = "SELECT idvoedselpakket, naam, samensteldatum, uitgiftedatum FROM voedselpakket JOIN klant ON voedselpakket.idklant = klant.idklant ORDER BY idvoedselpakket";
                $result = $conn->query($sql);
                if ($result) {
                    // Display each food package in a table row
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<form method='post'>";
                            echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["idvoedselpakket"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["naam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["samensteldatum"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["uitgiftedatum"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'><button type='submit' value='" . $row["idvoedselpakket"] . "' name='". $row["idvoedselpakket"] . "'>Laat producten zien</button></td>";
                            echo "</tr>";

                            // Check if the 'Laat producten zien' button for this package was clicked
                            if (isset($_POST[$row["idvoedselpakket"]])) {
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    // Fetch and display the products in the selected food package
                                    $sqlDisplayProdInPakket = "SELECT idvoedselpakket, voedselpakket_has_product.streepjescode, productnaam, voedselpakket_has_product.aantal FROM voedselpakket_has_product JOIN product ON voedselpakket_has_product.streepjescode = product.streepjescode WHERE idvoedselpakket = ?";
                                    $resDisplayProdInPakket = $conn->prepare($sqlDisplayProdInPakket);

                                    if ($resDisplayProdInPakket->execute([$_POST[$row["idvoedselpakket"]]])) {
                                        while ($row = $resDisplayProdInPakket->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<p class='text-black'>Product: " . $row["productnaam"] . " Aantal: " . $row["aantal"] . "<br> </p>"; 
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

<!-- Buttons for adding or distributing food packages -->
<button class="open-button bg-green-500 text-white border border-black hover:bg-green-900 mt-5" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button bg-yellow-300 text-white border border-black hover:bg-yellow-500 mt-5" onclick="openUitgeef()">Uitgeven</button>

<!-- Form to add a new food package -->
<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Voedselpakket maken</h2>

    <label for="cust"><b>Klant</b></label>
    <select name="cust" id="cust">
    <?php 
        // Fetch and display all customers in the dropdown
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
        // Fetch and display all products with their categories and input fields to enter the quantity
        $sqlProd = "SELECT streepjescode, productnaam, product.idcategorie, beschrijving FROM product JOIN categorie ON product.idcategorie = categorie.idcategorie";
        $resProd = $conn->query($sqlProd);
        if ($resProd) {
            while ($row = $resProd->fetch(PDO::FETCH_ASSOC)) {
                echo "<input type='checkbox' name='producten[]' value='" . $row["streepjescode"] . "'> " . $row["productnaam"] . " ";
                echo "Categorie: ".$row["beschrijving"];
                echo "<label for='" . $row["streepjescode"] . "'> Aantal</label> ";
                echo "<input type='number' value='0' name='" . $row["streepjescode"] . "' required><br>";
            }
        }
    ?>

    <!-- Button to submit the form and add a new food package -->
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="add">Toevoegen</button>
    <!-- Button to close the form without submitting -->
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>

<!-- JavaScript functions to open and close the form -->
<script>
    function openEnterForm() {
    document.getElementById("myEnterForm").style.display = "block";
    }

    function closeEnterForm() {
    document.getElementById("myEnterForm").style.display = "none";
    }
</script>

<!-- Form for distributing an existing food package -->
<div class="form-popup-uitgeef" id="Uitgeef"> 
<form class="form-container-uitgeef" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Voedselpakket Uitgeven</h2>

    <label for="Uitsend"><b>Voedselpakket</b></label>
    <Select name="Uitsend" id="Uitsend">
    <?php
        // Fetch and display food packages that haven't been distributed yet
        $sqlUit = "SELECT idvoedselpakket, samensteldatum, idklant FROM voedselpakket WHERE uitgiftedatum IS NULL ORDER BY idvoedselpakket";
        $show = $conn->query($sqlUit);
        if ($show) {
            while ($row = $show->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='". $row["idvoedselpakket"]."'> ".$row["idvoedselpakket"]."</option>";
            }
        }
    ?> 
    </Select>
    <br>
    <!-- Button to distribute the selected food package -->
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="uitGeef">Uitgeven</button>
    <!-- Button to close the form without submitting -->
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeUitgeef()">Sluiten</button>
  </form>
</div>

<!-- JavaScript functions to open and close the distribution form -->
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
    // Handle POST form submission for adding a new food package or distributing a package
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            // Handle adding a new food package
            case isset($_POST['add']):
                $sqlKlantAanVoedselpakket = $conn->prepare("INSERT INTO voedselpakket(samensteldatum, idklant) VALUES(?, ?)");
                
                // Get the selected customer and current date for the food package
                $voedKlantID = $_POST['cust'];
                $voedDatum = date('Y/m/d');
                
                $sqlKlantAanVoedselpakket->execute([$voedDatum, $voedKlantID]);

                // Process the selected products and quantities for the new food package
                $voedProducten = $_POST['producten'];
                $last_id = $conn->lastInsertId();

                foreach ($voedProducten as $voedProduct) {
                    $voedAantal = $_POST[$voedProduct];

                    // Fetch the product details (like quantity) to ensure stock availability
                    $sqlProduct = "SELECT streepjescode, aantal FROM product WHERE streepjescode = ?";
                    $resProduct = $conn->prepare($sqlProduct);
                    if($resProduct->execute([$voedProduct])) {
                        while ($row = $resProduct->fetch(PDO::FETCH_ASSOC)) {
                            $aantalNaToevoeging = $row["aantal"] - $voedAantal;
                            // Ensure the stock is sufficient before adding the product to the food package
                            if ($aantalNaToevoeging >= 0) {
                                $sqlVoedselpakketAndProduct = $conn->prepare("INSERT INTO voedselpakket_has_product(idvoedselpakket, idklant, streepjescode, aantal) VALUES(?, ?, ?, ?)");
                                $sqlVoedselpakketAndProduct->execute([$last_id, $voedKlantID, $voedProduct, $voedAantal]);

                                // Update product stock in the database
                                $prodAantalVeranderen = $conn->prepare("UPDATE product SET aantal = ? WHERE streepjescode = ?");
                                $prodAantalVeranderen->execute([$aantalNaToevoeging, $voedProduct]);

                                // Notify the user that a new food package has been added and refresh the page
                                echo "Nieuw voedselpakket toegevoegd.";
                                header("Refresh: 3; url=Voedselpakket.php");
                            } else {
                                echo "Aantal van producten kan niet lager dan 0.";
                            }
                        }
                    }
                }
                break;

            // Handle distributing a food package
            case isset($_POST['uitGeef']):
                $idpakket = $_POST["Uitsend"];
                $SQLuitgeefpakket = $conn->prepare("UPDATE voedselpakket SET uitgiftedatum = ? where idvoedselpakket = ?");

                // Update the distribution date of the selected package
                $uitdatum = date('Y/m/d');
                $SQLuitgeefpakket->execute([$uitdatum, $idpakket]);
            
                // Notify the user that the food package has been distributed
                echo "Voedselpakket is uitgegeven.";
                header("Refresh: 3; url=Voedselpakket.php");
                break;
        }
    }
?>
</body>
</html>
