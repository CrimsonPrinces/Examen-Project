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
<h2>Voedselbank Maaskantje</h2>
<div class="mb-20">
    <a href='home.php' class= "mx-5"> Home </a>
    <?php require_once("Switches.php");
     ?>
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
                    $sql = "SELECT idleverancier, bedrijfsnaam, adres, naamcontact, emailadres, telefoonnummer, volgendelevering FROM leverancier ORDER BY idleverancier";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $prevLeverancier = null;
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
<button class="open-button" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button" onclick="openDeleteForm()">Verwijderen</button>

<div class="form-popup" id="myEnterForm">
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

    <button type="submit" class="btn" name="add">Toevoegen</button>
    <button type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h1>Leverancier verwijderen</h1>

    <?php 
        $sql2 = "SELECT idleverancier, bedrijfsnaam FROM leverancier";
        $result2 = $conn->query($sql2); 
        if ($result2) { 
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                echo "<input type='checkbox' name='leveranciers[]' value='" . $row["idleverancier"] . "'> " . $row["bedrijfsnaam"] . "<br>";
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
    if(isset($_POST['add'])) {
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
    } else if(isset($_POST['delete'])) {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $leverDeletes = $_POST['leveranciers'];

            foreach ($leverDeletes as $leverDelete) {
                $sql = $conn->prepare("DELETE FROM leverancier WHERE idleverancier = ?");
                $sql->execute([$leverDelete]);
                echo "Leverancier verwijderd.";
            }
            header("Refresh: 3; url=Leverancier.php");
        }
    }
?>
</body>
</html>