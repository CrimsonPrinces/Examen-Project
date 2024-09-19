<?php
    ob_start();
    require_once("db_login.php");

    if ($_SESSION["usertype"] != 1) {
        header("Location: home.php");
    } 
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
    <title>Voedselbank Maaskantje Klanten</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
<div class="flex">
<?php

echo "Voedselbank Maaskantje";
?>
<div class="mb-20">
    <a href='home.php' class="mx-5"> Home </a>
    <a href='Voedselpakket.php' class="mx-5"> Voedselpakket </a>
    <a href='Medewerker.php' class="mx-5"> Medewerkers </a>
    <a href='Voorraad.php' class="mx-5"> Voorraad </a>
    <a href='Leverancier.php' class="mx-5"> Leveranciers </a>
    <a href='index.php' class="mx-5"> Uitloggen </a>
</div>
</div>

<h2 class="text-lg border-b border-black mb-3"> Klanten</h2>

<div class="bg-gray-200">
<form method="post" class="flexbox bg-gray-200">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th>Klant ID</th>
                <th>Naam</th>
                <th>Adres</th>
                <th>Telefoonnummer</th>
                <th>E-mailadres</th>
                <th>Aantal volwassenen</th>
                <th>Aantal kinderen</th>
                <th>Aantal baby's</th>
                <th>Wensen</th>            
            </tr>
                <?php 
                    $prevKlant = null;
                    $sql = "SELECT idklant, naam, adres, telefoonnummer, email, aantalvolwassen, aantalkind, aantalbaby, wensen FROM klant ORDER BY idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idklant"] != $prevKlant) {
                                echo "<tr>";
                                echo "<td>" . $row["idklant"] . "</td>";
                                echo "<td>" . $row["naam"] . "</td>"; 
                                echo "<td>" . $row["adres"] . "</td>"; 
                                echo "<td>" . $row["telefoonnummer"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["aantalvolwassen"] . "</td>";
                                echo "<td>" . $row["aantalkind"] . "</td>";
                                echo "<td>" . $row["aantalbaby"] . "</td>";
                                echo "<td>" . $row["wensen"] . "</td>";
                                echo "</tr>";
                            }
                            $prevKlant = $row["idklant"];
                        }
                    }
                ?>
        </table>
    </form>
</div>

<button class="open-button" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button" onclick="openDeleteForm()">Verwijderen</button>

<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h1>Klant toevoegen</h1>

    <label for="naam"><b>Naam</b></label>
    <input type="text" placeholder="Naam toevoegen" name="naam" required>
    <label for="adres"><b>Adres</b></label>
    <input type="text" placeholder="Adres toevoegen" name="adres" required>
    <label for="tel"><b>Telefoonnummer</b></label>
    <input type="number" placeholder="Telefoonnummer toevoegen" name="tel" required>
    <label for="email"><b>Emailadres</b></label>
    <input type="text" placeholder="Email toevoegen" name="email" required>
    <label for="volwas"><b>Aantal volwassenen</b></label>
    <input type="number" placeholder="0" name="volwas" required>
    <label for="kind"><b>Aantal kinderen</b></label>
    <input type="number" placeholder="0" name="kind">
    <label for="baby"><b>Aantal baby's</b></label>
    <input type="number" placeholder="0" name="baby">
    <label for="wens"><b>Wensen</b></label>
    <input type="text" placeholder="Wensen/allergiën toevoegen" name="wens">

    <button type="submit" class="btn" name="add">Toevoegen</button>
    <button type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h1>Klanten verwijderen</h1>

    <?php 
        $sql2 = "SELECT idklant, naam FROM klant";
        $result2 = $conn->query($sql2); 
        if ($result2) { 
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                echo "<input type='checkbox' name='klanten[]' value='" . $row["idklant"] . "'> " . $row["naam"] . "<br>";
            } 
        }
        print_r($result2->fetch(PDO::FETCH_ASSOC));
    ?>

    <button type="submit" class="btn" name="delete">Verwijderen</button>
    <button type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
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

function openDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "block";
}

function closeDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "none";
}

closeDeleteForm();
</script>

<?php
    if(isset($_POST['add'])){
        if($_SERVER["REQUEST_METHOD"] == "POST") {
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
        }
    } else if(isset($_POST['delete'])) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $klantDeletes = $_POST['klanten'];

            foreach ($klantDeletes as $klantDelete) {
                $sql = "DELETE FROM klant WHERE idklant = $klantDelete";
                $conn->exec($sql);
                echo "Klant verwijderd.";
            }
            header("Refresh: 3; url=Klanten.php");
        }
    }
?>
</body>
</html>