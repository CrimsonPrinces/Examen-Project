<?php
    // Start output buffering to capture any output
    ob_start();
    
    // Include database login credentials
    require_once("db_login.php");

    // Check if the user type is not equal to 1 (assuming 1 is an admin type)
    if ($_SESSION["usertype"] != 1) {
        // Redirect to Voedselpakket.php if user is not an admin
        header("Location: Voedselpakket.php");
    } 
    // Check if the user type session is not set
    if (!isset($_SESSION["usertype"])) {
        // Redirect to index.php if no user type is found (not logged in)
        header("Location: index.php");
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Character encoding for the document -->
    <meta charset="UTF-8">
    <!-- Compatibility with Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Responsive viewport settings -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the webpage -->
    <title>Voedselbank Maaskantje Klanten</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <!-- Main container for the page -->
    <div class="flex">
        <h2>Voedselbank Maaskantje</h2>
        <div class="mb-20">
            <!-- Include a switch component -->
            <?php require_once("Switches.php"); ?>
        </div>
    </div>
    <h2 class="text-lg border-b border-black mb-3"> Klanten</h2>

    <div class="max-w-5xl">
        <!-- Form to display customer data -->
        <form method="post" class="flexbox bg-blue-200 text-white">
            <table class="border-separate border-spacing-5 border">
                <tr>
                    <!-- Table headers for customer information -->
                    <th class="border border-slate-600 bg-gray-500 text-base">Klant ID</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Naam</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Adres</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Telefoonnummer</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">E-mailadres</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Aantal volwassenen</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Aantal kinderen</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Aantal baby's</th>
                    <th class="border border-slate-600 bg-gray-500 text-base">Wensen</th>            
                </tr>
                <?php 
                    // SQL query to fetch customer data from the database
                    $sql = "SELECT idklant, naam, adres, telefoonnummer, email, aantalvolwassen, aantalkind, aantalbaby, wensen FROM klant ORDER BY idklant";
                    $result = $conn->query($sql);
                    
                    // Check if the query was successful
                    if ($result) {
                        // Fetch and display each customer's data
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $prevKlant = null; // Initialize previous customer variable
                            if ($row["idklant"] != $prevKlant) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["idklant"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["naam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["adres"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["telefoonnummer"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["email"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["aantalvolwassen"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["aantalkind"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["aantalbaby"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["wensen"] . "</td>";
                                echo "</tr>";
                            }
                            $prevKlant = $row["idklant"]; // Update previous customer
                        }
                    }
                ?>
            </table>
        </form>
    </div>

    <!-- Buttons to open forms for adding, deleting, and changing customer data -->
    <button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
    <button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>
    <button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openChangeForm()">Wijzigen</button>

    <!-- Form for adding a new customer -->
    <div class="form-popup" id="myEnterForm">
        <form class="form-container" method="post">
            <h2 class="text-lg border-b border-black mt-3 mb-3">Klant toevoegen</h2>
            <div class="grid grid-cols-3">
                <div>
                    <label for="naam"><b>Naam</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Naam toevoegen" name="naam" required>
                </div>
                <div>
                    <label for="adres"><b>Adres</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Adres toevoegen" name="adres" required>
                </div>
                <div>
                    <label for="tel"><b>Telefoonnummer</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="Telefoonnummer toevoegen" name="tel" required>
                </div>
                <div>
                    <label for="email"><b>Emailadres</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Email toevoegen" name="email" required>
                </div>
                <div>
                    <label for="volwas"><b>Aantal volwassenen</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="volwas" required>
                </div>
                <div>
                    <label for="kind"><b>Aantal kinderen</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="kind">
                </div>
                <div>
                    <label for="baby"><b>Aantal baby's</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="baby">
                </div>
                <div>
                    <label for="wens"><b>Wensen</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Wensen/allergiën toevoegen" name="wens">
                </div>
            </div>
            <!-- Submit button for adding a new customer -->
            <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white" type="submit" name="add">Toevoegen</button>
            <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" onclick="closeEnterForm()">Sluiten</button>
        </form>
    </div>

    <!-- Form for deleting a customer -->
    <div class="form-popup-delete" id="myDeleteForm">
        <form class="form-container-delete" method="post">
            <h2 class="text-lg border-b border-black mt-3 mb-3">Klant verwijderen</h2>
            <?php 
                // SQL query to fetch customer IDs and names for deletion
                $sql2 = "SELECT idklant, naam FROM klant";
                $result2 = $conn->query($sql2); 
                
                // Check if the query was successful
                if ($result2) { 
                    // Generate checkboxes for each customer
                    while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                        echo "<input type='checkbox' name='klanten[]' value='" . $row["idklant"] . "'> " . $row["naam"] . "<br>";
                    } 
                }
            ?>
            <!-- Submit button for deleting selected customers -->
            <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="submit" name="delete">Verwijderen</button>
            <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" onclick="closeDeleteForm()">Sluiten</button>
        </form>
    </div>

    <!-- Form for changing customer data -->
    <div class="form-popup-change" id="myChangeForm">
        <form class="form-container-change" method="post">
            <h2 class="text-lg border-b border-black mt-3 mb-3">Klant wijzigen</h2>
            <?php 
                // SQL query to fetch customer IDs and names for modification
                $sql3 = "SELECT idklant, naam FROM klant";
                $result3 = $conn->query($sql3); 
                
                // Check if the query was successful
                if ($result3) { 
                    // Generate radio buttons for selecting a customer to modify
                    while ($row = $result3->fetch(PDO::FETCH_ASSOC)) { 
                        echo "<input type='radio' name='klant' value='" . $row["idklant"] . "'> " . $row["naam"] . "<br>";
                    } 
                }
            ?>
            <div class="grid grid-cols-3">
                <div>
                    <label for="naam"><b>Nieuwe naam</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Nieuwe naam toevoegen" name="naam" required>
                </div>
                <div>
                    <label for="adres"><b>Nieuw adres</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Nieuw adres toevoegen" name="adres" required>
                </div>
                <div>
                    <label for="tel"><b>Nieuw telefoonnummer</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="Nieuw telefoonnummer toevoegen" name="tel" required>
                </div>
                <div>
                    <label for="email"><b>Nieuw emailadres</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Nieuw email toevoegen" name="email" required>
                </div>
                <div>
                    <label for="volwas"><b>Nieuw aantal volwassenen</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="volwas" required>
                </div>
                <div>
                    <label for="kind"><b>Nieuw aantal kinderen</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="kind">
                </div>
                <div>
                    <label for="baby"><b>Nieuw aantal baby's</b></label>
                    <input class="border border-separate border-black" type="number" placeholder="0" name="baby">
                </div>
                <div>
                    <label for="wens"><b>Nieuwe wensen</b></label>
                    <input class="border border-separate border-black" type="text" placeholder="Nieuwe wensen/allergiën toevoegen" name="wens">
                </div>
            </div>
            <!-- Submit button for changing customer data -->
            <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white" type="submit" name="change">Wijzigen</button>
            <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" onclick="closeChangeForm()">Sluiten</button>
        </form>
    </div>

    <!-- JavaScript functions to open and close forms -->
    <script>
        // Function to open the "Add Customer" form
        function openEnterForm() {
            document.getElementById("myEnterForm").style.display = "block";
        }
        // Function to close the "Add Customer" form
        function closeEnterForm() {
            document.getElementById("myEnterForm").style.display = "none";
        }
        // Function to open the "Delete Customer" form
        function openDeleteForm() {
            document.getElementById("myDeleteForm").style.display = "block";
        }
        // Function to close the "Delete Customer" form
        function closeDeleteForm() {
            document.getElementById("myDeleteForm").style.display = "none";
        }
        // Function to open the "Change Customer" form
        function openChangeForm() {
            document.getElementById("myChangeForm").style.display = "block";
        }
        // Function to close the "Change Customer" form
        function closeChangeForm() {
            document.getElementById("myChangeForm").style.display = "none";
        }
    </script>
</body>
</html>

<?php
// Manages the adding of customers
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            case isset($_POST['add']): 
                $sql = $conn->prepare("INSERT INTO klant(naam, adres, telefoonnummer, email, aantalvolwassen, aantalkind, aantalbaby, wensen) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                
                $klantNaam = $_POST['naam'];
                $klantAdres = $_POST['adres'];
                $klantTel = $_POST['tel'];
                $klantEmail = $_POST['email'];
                $klantVolwas = $_POST['volwas'];
                $klantKind = $_POST['kind'];
                $klantBaby = $_POST['baby'];
                $klantWens = $_POST['wens'];
                
                $sql->execute([$klantNaam, $klantAdres, $klantTel, $klantEmail, $klantVolwas, $klantKind, $klantBaby, $klantWens]);
                echo "Nieuwe klant toegevoegd.";

                header("Refresh: 3; url=Klanten.php");
                break;
            case isset($_POST['delete']):
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    $klantDeletes = $_POST['klanten'];
        
                    foreach ($klantDeletes as $klantDelete) {
                        $sql = $conn->prepare("DELETE FROM klant WHERE idklant = ?");
                        $sql->execute([$klantDelete]);
                        echo "Klant verwijderd.";
                    }
                    header("Refresh: 3; url=Klanten.php");
                }
                break;
                //manges the changes of customer details
            case isset($_POST['change']):
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    $changeKlant = $conn->prepare("UPDATE klant SET naam = ?, adres = ?, telefoonnummer = ?, email = ?, aantalvolwassen = ?, aantalkind = ?, aantalbaby = ?, wensen = ? WHERE idklant = ?");
                    $changeNaam = $_POST['wijzNaam'];
                    $changeAdres = $_POST['wijzAdres'];
                    $changeTel = $_POST['wijzTel'];
                    $changeEmail = $_POST['wijzEmail'];
                    $changeVolwas = $_POST['wijzVolwas'];
                    $changeKind = $_POST['wijzKind'];
                    $changeBaby = $_POST['wijzBaby'];
                    $changeWens = $_POST['wijzWens'];
        
                    $changeId = $_POST['wijzId'];
        
                    $changeKlant->execute([$changeNaam, $changeAdres, $changeTel, $changeEmail, $changeVolwas, $changeKind, $changeBaby, $changeWens, $changeId]);
        
                    echo "Klant gewijzigd.";
                    header("Refresh: 3; url=Klanten.php");
                }
                break;
        }
    }
?>
</body>
</html>