<?php
    ob_start();
    require_once("db_login.php");

    if ($_SESSION["usertype"] != 1) {
        header("Location: Voedselpakket.php");
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
    <h2>Voedselbank Maaskantje</h2>
    <div class="mb-20">
    <?php require_once("Switches.php");
     ?>
    </div>
    </div>
    <h2 class="text-lg border-b border-black mb-3"> Klanten</h2>


<div class="max-w-5xl">
<form method="post" class="flexbox bg-blue-200 text-white">
        <table class="border-separate border-spacing-5 border">
            <tr>                
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
                    $sql = "SELECT idklant, naam, adres, telefoonnummer, email, aantalvolwassen, aantalkind, aantalbaby, wensen FROM klant ORDER BY idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $prevKlant = null;
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
                                echo "<td class='border border-slate-600 text-black'    >" . $row["wensen"] . "</td>";
                                echo "</tr>";
                            }
                            $prevKlant = $row["idklant"];
                        }
                    }
                ?>
        </table>
    </form>
</div>

<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>

<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Klant toevoegen</h2>
    <div class=" grid grid-cols-3">
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
        <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="add">Toevoegen</button>
        <button class ="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Klant verwijderen</h2>

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

    <button class="text-black bg-white border border-black mt-5 hover:bg-orange-300 hover:text-white " type="submit" class="btn" name="delete">Verwijderen</button>
    <button class ="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
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
                $sql = $conn->prepare("DELETE FROM klant WHERE idklant = ?");
                $sql->execute([$klantDelete]);
                echo "Klant verwijderd.";
            }
            header("Refresh: 3; url=Klanten.php");
        }
    }
?>
</body>
</html>