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
    <title>Voedselbank Maaskantje Voorraad</title>
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
<h2 class="text-lg border-b border-black mb-3"> Medewerkers</h2>
<div class="max-w-sm">
<form method="post" class="flexbox bg-blue-200">
        <table class="border-separate border-spacing-5 border text-white">
            <tr>                
                <th class=" border border-slate-600 bg-gray-500">User ID</th>
                <th class=" border border-slate-600 bg-gray-500">Gebruikersnaam</th>
                <th class=" border border-slate-600 bg-gray-500">User Type</th>      
            </tr>
                <?php 
                    $sql = "SELECT iduser, gebruikersnaam, idusertype FROM user ORDER BY iduser";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        $prevUser = null;
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["iduser"] != $prevUser) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["iduser"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["gebruikersnaam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["idusertype"] . "</td>";
                                echo "</tr>";
                            }
                            $prevUser = $row["iduser"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-yellow-500 hover:text-white" onclick="openChangeform()">Aanpassen</button>


<div class="form-popup" id="myEnterForm">
  <form class="form-container" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Medewerker toevoegen</h2>
    <div class="grid grid-cols-3">
        <div>
        <label for="user"><b>Gebruikersnaam</b></label>
        <input class="border border-separate border-black" type="text" placeholder="Gebruikersnaam toevoegen" name="user" required>
        </div>
        <div>
        <label for="pass"><b>Wachtwoord</b></label>
        <input class="border border-separate border-black" type="password" placeholder="Wachtwoord toevoegen" name="pass" required>
        </div>
        <div>
        <label for="repw"><b>Herhaal wachtwoord</b></label>
        <input class="border border-separate border-black" type="password" placeholder="Wachtwoord toevoegen" name="repw" required>
        </div>
        <div>
        <label for="type"><b>Usertype</b></label>
        <select class="border border-separate border-black" name="type" id="type">
            <option value="1">Admin</option>
            <option value="2">Magazijnmedewerker</option>
            <option value="3">Vrijwilliger</option>
        </select>
        </div>
        </div>
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Medewerker verwijderen</h2>

    <?php 
        $sql2 = "SELECT iduser, gebruikersnaam FROM user";
        $result2 = $conn->query($sql2); 
        if ($result2) { 
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                echo "<input type='checkbox' name='users[]' value='" . $row["iduser"] . "'> " . $row["gebruikersnaam"] . "<br>";
            } 
        }
        print_r($result2->fetch(PDO::FETCH_ASSOC));
    ?>

    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-orange-300 hover:text-white" type="submit" class="btn" name="delete">Verwijderen</button>
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
  </form>
</div>
<div class="form-popup" id="Changeform">
    <form class="form-container-change" method="post">
        <h2 class="text-lg border-b border-black mt-3 mb-3">Medewerker Aanpassen</h2>
        <label for="Change"><b>Medewerker</b></label>
        <select name="Change" id="Change">
            <?php
            $sqlChange = "SELECT iduser, gebruikersnaam from user";
            $changes = $conn->query($sqlChange);
            if($changes){
                while($row = $changes->fetch(PDO::FETCH_ASSOC)){
                    echo"<option value='".$row["iduser"]."'>".$row["iduser"]."</option>";
                }
            }
             ?>
        </select>
        <br>

        <label for="cPass"><b>Nieuw Wachtwoord</b></label>
        <input class="border border-separate border-black" type="password"placeholder="Nieuw Watchwoord" name="cPass" required>
        <label for="RecPass"><b>Herhaal Nieuw Wachtwoord</b></label>
        <input class="border border-separate border-black" type="password"placeholder="Herhaal Nieuw Watchwoord" name="RecPass" required>
        <br>
        <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="Changepass">Aanpassen</button>
        <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeChangeform()">Sluiten</button>
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

function openChangeform(){
    document.getElementById("Changeform").style.display = "block";
}
function closeChangeform(){
    document.getElementById("Changeform").style.display = "none";
}
closeChangeform();
</script>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        switch (true) {
            case isset($_POST['add']):
                $sql = $conn->prepare("INSERT INTO user(gebruikersnaam, wachtwoord, idusertype) VALUES(?, ?, ?)");
                
                $userNaam = $_POST['user'];
                $userPass = $_POST['pass'];
                $userRePass = $_POST['repw'];
                $userType = $_POST['type'];


                if ($userPass === $userRePass) {
                    $sql->execute([$userNaam, password_hash($userPass, PASSWORD_DEFAULT), $userType]);
                    echo "Nieuwe user toegevoegd.";
                    header("Refresh: 3; url=Medewerker.php");
                } else {
                    echo "Wachtwoorden staan niet gelijk.";
                }
                break;
            case isset($_POST['delete']):
                $userDeletes = $_POST['users'];

                foreach ($userDeletes as $userDelete) {
                    $sql = $conn->prepare("DELETE FROM user WHERE iduser = ?");
                    $sql->execute([$userDelete]);
                    echo "Medewerker verwijderd.";
                }
                header("Refresh: 3; url=Medewerker.php");
                break;
            case isset($_POST['Changepass']):
                $Sqlchange = $conn->prepare("UPDATE user SET wachtwoord = ? Where iduser = ?");

                $userchange = $_POST['Change'];
                $cPass = $_POST['cPass'];
                $RecPass = $_POST['RecPass'];
                if($cPass === $RecPass){
                    $Sqlchange->execute([password_hash($cPass, PASSWORD_DEFAULT), $userchange]);
                    echo "Wachtwoord is aangepast.";
                    header("Refresh:3; url=Medewerker.php");
                }else{
                    echo "Wachtwoorden staan niet gelijk.";
                }
                break;
        }
    } 
?>
</body>
</html>