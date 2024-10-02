<?php
    ob_start();
    require_once("db_login.php");

    if ($_SESSION["usertype"] == 3) {
        header("Location: Voedselpakket.php");
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
    <?php require_once("Switches.php");
     ?>
    </div>
</div>

<h2 class="text-lg border-b border-black mb-3">Leverancier</h2>

<div class="max-w-6xl">
<form method="post" class="flexbox bg-blue-200 ">
        <table class="border-separate border-spacing-5 border text-white">
            <tr>                
                <th class=" border border-slate-600 bg-gray-500">Leverancier ID</th>
                <th class=" border border-slate-600 bg-gray-500">Bedrijfsnaam</th>
                <th class=" border border-slate-600 bg-gray-500">Adres</th>
                <th class=" border border-slate-600 bg-gray-500">Naam contactpersoon</th>
                <th class=" border border-slate-600 bg-gray-500">E-mailadres</th>
                <th class=" border border-slate-600 bg-gray-500">Telefoonnummer</th>
                <th class=" border border-slate-600 bg-gray-500">Volgende Levering</th>      
            </tr>
                <?php 
                    $sql = "SELECT idleverancier, bedrijfsnaam, adres, naamcontact, emailadres, telefoonnummer, volgendelevering FROM leverancier ORDER BY idleverancier";
                    $result = $conn->query($sql);
                    
                    if ($result) {
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
                            $prevLeverancier = $row["idleverancier"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-yellow-500 hover:text-white" onclick="openLeverform()">Nieuwe Levering</button>

<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Leverancier toevoegen</h2>
    <div class="grid grid-cols-3">
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
    <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Leverancier verwijderen</h2>

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

    <button class="text-black bg-white border border-black mt-5 hover:bg-orange-300 hover:text-white " type="submit" class="btn" name="delete">Verwijderen</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-lever" id="Leverform">
    <form class="form-container-levering" method="post">
    <label for="levering"><b>Nieuwe Leverings Datum</b></label>
    <select name="leverSel" id="leverSel">
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
    <button class="text-black bg-white border border-black mt-5 hover:bg-orange-300 hover:text-white " type="submit" class="btn" name="Change">Verander</button>
    <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel delete" onclick="closeLeverform()">Sluiten</button>
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

function openLeverform(){
    document.getElementById("Leverform").style.display = "block";
}
function closeLeverform(){
    document.getElementById("Leverform").style.display = "none";
}
closeLeverform();
</script>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
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
            case isset($_POST['delete']): 
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    $leverDeletes = $_POST['leveranciers'];
        
                    foreach ($leverDeletes as $leverDelete) {
                        $sql = $conn->prepare("DELETE FROM leverancier WHERE idleverancier = ?");
                        $sql->execute([$leverDelete]);
                        echo "Leverancier verwijderd.";
                    }
                    header("Refresh: 3; url=Leverancier.php");
                } 
                break;
            case isset($_POST['Change']):
                if($_SERVER["REQUEST_METHOD"]=="POST"){
                    $newlever = $_POST['nextdells'];
                    $idlever = $_POST['leverSel'];
                    $leverchange = $conn->prepare("UPDATE leverancier SET volgendelevering = ? WHERE idleverancier = ?");
                    $leverchange->execute([$newlever, $idlever]);
        
                echo "Nieuwe Leverings Datum Genoteerd.";
                header("Refresh:3; url=Leverancier.php");
                }
                break;
        }
    }
?>
</body>
</html>