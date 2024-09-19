<?php
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
<h2>Voedselbank Maaskantje</h2>
    <div class="mb-20">
    <a href='home.php' class= "mx-5"> Home </a>
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
                    $prevKlant = null;
                    $sql = "SELECT idklant, naam, adres, telefoonnummer, email, aantalvolwassen, aantalkind, aantalbaby, wensen FROM klant ORDER BY idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
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

<button class="open-button" onclick="openForm()">Open Form</button>

<div class="form-popup" id="myForm">
  <form class="form-container" method="post">
    <h2>Klant toevoegen</h2>
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
        <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn">Toevoegen</button>
        <button class ="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeForm()">Sluiten</button>
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
?>
</body>
</html>