<?php
    ob_start(); // Starts output buffering to manage header redirects later in the script
    require_once("db_login.php"); // Includes the database connection script

    // Check if the user is of type 3 (e.g., regular user), redirect them to Voedselpakket.php
    if ($_SESSION["usertype"] == 3) {
        header("Location: Voedselpakket.php");
    }

    // If there's no user session, redirect the user to the login page (index.php)
    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    }
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Leverancier</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Includes Tailwind CSS for styling -->
</head>
<body class = "p-8">
<div class="flex">
    <h2>Voedselbank Maaskantje</h2>
    <div class="mb-20">
        <!-- Include the Switches.php file for additional navigation or switch options -->
        <?php require_once("Switches.php"); ?>
    </div>
</div>

<h2 class="text-lg border-b border-black mb-3">Leverancier</h2> <!-- Section title for suppliers -->

<div class="max-w-6xl">
<!-- Form to display the supplier list -->
<form method="post" class="flexbox bg-blue-200 ">
        <table class="border-separate border-spacing-5 border text-white"> <!-- Table for displaying supplier information -->
            <tr>
                <!-- Table headers for supplier details -->
                <th class=" border border-slate-600 bg-gray-500">Leverancier ID</th>
                <th class=" border border-slate-600 bg-gray-500">Bedrijfsnaam</th>
                <th class=" border border-slate-600 bg-gray-500">Adres</th>
                <th class=" border border-slate-600 bg-gray-500">Naam contactpersoon</th>
                <th class=" border border-slate-600 bg-gray-500">E-mailadres</th>
                <th class=" border border-slate-600 bg-gray-500">Telefoonnummer</th>
                <th class=" border border-slate-600 bg-gray-500">Volgende Levering</th>      
            </tr>
            <?php 
                // Query to select supplier details
                $sql = "SELECT idleverancier, bedrijfsnaam, adres, naamcontact, emailadres, telefoonnummer, volgendelevering FROM leverancier ORDER BY idleverancier";
                $result = $conn->query($sql); // Executes the query
                
                if ($result) {
                    // Loop through each supplier and display their details in the table
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $prevLeverancier = null;
                        if ($row["idleverancier"] != $prevLeverancier) {
                            echo "<tr>";
                            echo "<td class='border border-slate-600 text-black'>" . $row["idleverancier"] . "</td>";
                            echo "<td class='border border-slate-600 text-black'>" . $row["bedrijfsnaam"] . "</td>"; 
                            echo "<td class='border border-slate-600 text-black'>" . $row["adres"] . "</td>";
                            echo "<td class='border border-slate-600 text-black'>" . $row["naamcontact"] . "</td>"; 
                            echo "<td class='border border-slate-600 text-black'>" . $row["emailadres"] . "</td>";
                            echo "<td class='border border-slate-600 text-black'>" . $row["telefoonnummer"] . "</td>";
                            echo "<td class='border border-slate-600 text-black'>" . $row["volgendelevering"] . "</td>";
                            echo "</tr>";
                        }
                        // Update the previous supplier ID to track changes
                        $prevLeverancier = $row["idleverancier"];
                    }
                }
            ?>
        </table>
    </form>
</div>

<!-- Buttons to trigger different form actions (add, delete, update delivery) -->
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-yellow-500 hover:text-white" onclick="openLeverform()">Nieuwe Levering</button>

<!-- Popup form to add a new supplier -->
<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Leverancier toevoegen</h2>
    <div class="grid grid-cols-3">
        <!-- Form fields to enter supplier details -->
        <div>
            <label for="leve"><b>Naam leverancier</b></label>
            <input type="text" placeholder="Leverancier naam toevoegen" name="leve" required>
        </div>
        <div>
            <label for="adres"><b>Adres</b></label>
            <input class="border border-separate border-black" type="text" placeholder="Adres toevoegen" name="adres" required>
        </div>
        <div>
            <label for="cont"><b>Naam contact</b></label>
            <input class="border border-separate border-black" type="text" placeholder="Contact naam toevoegen" name="cont" required>
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
            <label for="nextdel"><b>Volgende levering</b></label>
            <input class="border border-separate border-black" type="datetime-local" name="nextdel" required>
        </div>
    </div>
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white" type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>

<!-- Popup form to delete a supplier -->
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Leverancier verwijderen</h2>

    <!-- Displays a checkbox list of suppliers for deletion -->
    <?php 
        $sql2 = "SELECT idleverancier, bedrijfsnaam FROM leverancier";
        $result2 = $conn->query($sql2); 
        if ($result2) { 
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                echo "<input type='checkbox' name='leveranciers[]' value='" . $row["idleverancier"] . "'> " . $row["bedrijfsnaam"] . "<br>";
            } 
        }
    ?>

    <button class="text-black bg-white border border-black mt-5 hover:bg-orange-300 hover:text-white" type="submit" class="btn" name="delete">Verwijderen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
  </form>
</div>

<!-- Popup form to add a new delivery date for a supplier -->
<div class="form-popup-lever" id="Leverform">
    <form class="form-container-levering" method="post">
    <label for="levering"><b>Nieuwe Leverings Datum</b></label>
    <select name="leverSel" id="leverSel">
        <!-- Dropdown to select supplier for delivery date update -->
        <?php
        $sqlLevering ="SELECT idleverancier, bedrijfsnaam FROM leverancier";
        $levering = $conn->query($sqlLevering);
        if($levering){
            while($row = $levering->fetch(PDO::FETCH_ASSOC)){
                echo "<option value='".$row["idleverancier"]."'> ".$row["bedrijfsnaam"]."</option>";
            }
        }
        ?>
    </select>
    <label for="nextdells"><b>Volgende levering</b></label>
    <input class="border border-separate border-black" type="datetime-local" name="nextdells" required>
    <br>
    <button class="text-black bg-white border border-black mt-5 hover:bg-orange-300 hover:text-white" type="submit" class="btn" name="Change">Verander</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel delete" onclick="closeLeverform()">Sluiten</button>
    </form>
</div>

<!-- JavaScript functions to open and close the popup forms -->
<script>
function openEnterForm() {
  document.getElementById("myEnterForm").style.display = "block";
}

function closeEnterForm() {
  document.getElementById("myEnterForm").style.display = "none";
}
closeEnterForm();

function openDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "block";
}

function closeDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "none";
}
closeDeleteForm();

function openLeverform(){
    document.getElementById("Leverform").style.display = "block";
}
function closeLeverform(){
    document.getElementById("Leverform").style.display = "none";
}
closeLeverform();
</script>

<?php
    // Handle form submissions
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            // Add a new supplier
            case isset($_POST['add']):
                $sql = $conn->prepare("INSERT INTO leverancier(bedrijfsnaam, adres, naamcontact, telefoonnummer, emailadres, volgendelevering) VALUES(?, ?, ?, ?, ?, ?)");
                $leverNaam = $_POST['leve'];
                $leverAdres = $_POST['adres'];
                $leverCont = $_POST['cont'];
                $leverTele = $_POST['tel'];
                $leverEmail = $_POST['email'];
                $leverDeli = $_POST['nextdel'];
                
                $sql->execute([$leverNaam, $leverAdres, $leverCont, $leverTele, $leverEmail, $leverDeli]);
                echo "Nieuwe leverancier toegevoegd.";
                header("Refresh: 3; url=Leverancier.php");
                break;

            // Delete selected suppliers
            case isset($_POST['delete']): 
                $leverDeletes = $_POST['leveranciers'];
                foreach ($leverDeletes as $leverDelete) {
                    $sql = $conn->prepare("DELETE FROM leverancier WHERE idleverancier = ?");
                    $sql->execute([$leverDelete]);
                    echo "Leverancier verwijderd.";
                }
                header("Refresh: 3; url=Leverancier.php");
                break;

            // Update the delivery date for a selected supplier
            case isset($_POST['Change']):
                $newlever = $_POST['nextdells'];
                $idlever = $_POST['leverSel'];
                $leverchange = $conn->prepare("UPDATE leverancier SET volgendelevering = ? WHERE idleverancier = ?");
                $leverchange->execute([$newlever, $idlever]);
                echo "Nieuwe Leverings Datum Genoteerd.";
                header("Refresh:3; url=Leverancier.php");
                break;
        }
    }
?>
