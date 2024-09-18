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
    <title>Voedselbank Maaskantje Voorraad</title>
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
                <th>User ID</th>
                <th>Gebruikersnaam</th>
                <th>User Type</th>      
            </tr>
                <?php 
                    $prevUser = null;
                    $sql = "SELECT iduser, gebruikersnaam, idusertype FROM user ORDER BY iduser";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["iduser"] != $prevUser) {
                                echo "<tr>";
                                echo "<td>" . $row["iduser"] . "</td>";
                                echo "<td>" . $row["gebruikersnaam"] . "</td>"; 
                                echo "<td>" . $row["idusertype"] . "</td>";
                                echo "</tr>";
                            }
                            $prevUser = $row["iduser"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<button class="open-button" onclick="openForm()">Open Form</button>
<div class="form-popup" id="myForm">
  <form class="form-container" method="post">
    <h1>Medewerker toevoegen</h1>

    <label for="user"><b>Gebruikersnaam</b></label>
    <input type="text" placeholder="Gebruikersnaam toevoegen" name="user" required>
    <label for="pass"><b>Wachtwoord</b></label>
    <input type="password" placeholder="Wachtwoord toevoegen" name="pass" required>
    <label for="repw"><b>Herhaal wachtwoord</b></label>
    <input type="password" placeholder="Wachtwoord toevoegen" name="repw" required>
    <label for="type"><b>Usertype</b></label>
    <select name="type" id="type">
        <option value="1">Admin</option>
        <option value="2">Magazijnmedewerker</option>
        <option value="3">Vrijwilliger</option>
    </select>

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
    }
?>
</body>
</html>