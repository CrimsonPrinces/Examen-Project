<?php
    require_once("db_login.php");

    if ($_SESSION["usertype"] != 1) {
        header("Location: home.php");
    } 
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class = "p-8">
<div class="flex">
<?php

echo "Voedselbank Maaskantje";
?>
<div class="mb-20">
    <a href='home.php' class= "mx-5"> Home </a>
    <?php require_once("Switches.php");
     ?>
    <a href='Voedselpakket.php' class="mx-5"> Voedselpakket </a>
    <a href='index.php' class= "mx-5"> Uitloggen </a>
    </div>
</div>

<div class="bg-gray-200">
<form method="post" class="flexbox bg-gray-200">
        <table class="border-separate border-spacing-5 border">
            <tr>                
                <th>Leverancier ID</th>
                <th>Bedrijfsnaam</th>
                <th>Adres</th>
                <th>Naam contactpersoon</th>
                <th>E-mailadres</th>
                <th>Telefoonnummer</th>
                <th>Volgende Levering</th>      
            </tr>
                <?php 
                    $prevLeverancier = null;
                    $sql = "SELECT idleverancier, bedrijfsnaam, adres, naamcontact, emailadres, telefoonnummer, volgendelevering FROM leverancier ORDER BY idleverancier";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idleverancier"] != $prevLeverancier) {
                                echo "<tr>";
                                echo "<td>" . $row["idleverancier"] . "</td>";
                                echo "<td>" . $row["bedrijfsnaam"] . "</td>"; 
                                echo "<td>" . $row["adres"] . "</td>";
                                echo "<td>" . $row["naamcontact"] . "</td>"; 
                                echo "<td>" . $row["emailadres"] . "</td>";
                                echo "<td>" . $row["telefoonnummer"] . "</td>";
                                echo "<td>" . $row["volgendelevering"] . "</td>";
                                echo "</tr>";
                            }
                            $prevLeverancier = $row["idleverancier"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button" onclick="openForm()">Open Form</button>

<div class="form-popup" id="myForm">
  <form class="form-container" method="post">
    <h1>Leverancier toevoegen</h1>

    <label for="leve"><b>Naam leverancier</b></label>
    <input type="text" placeholder="Leverancier naam toevoegen" name="leve" required>
    <label for="adres"><b>Adres</b></label>
    <input type="text" placeholder="Adres toevoegen" name="adres" required>
    <label for="cont"><b>Naam contact</b></label>
    <input type="text" placeholder="Contact naam toevoegen" name="cont" required>
    <label for="tel"><b>Telefoonnummer</b></label>
    <input type="number" placeholder="Telefoonnummer toevoegen" name="tel" required>
    <label for="email"><b>Emailadres</b></label>
    <input type="text" placeholder="Email toevoegen" name="email" required>
    <label for="nextdel"><b>Volgende levering</b></label>
    <input type="datetime-local" name="nextdel" required>

    <button type="submit" class="btn">Toevoegen</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Sluiten</button>
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
    }
?>
</body>
</html>