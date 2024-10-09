<?php
    // Start output buffering
    ob_start();
    // Include database login information
    require_once("db_login.php");

    // Check if the user is not an admin (usertype != 1), if true redirect to home
    if ($_SESSION["usertype"] != 1) {
        header("Location: home.php");
    } 
    // Check if the session user type is not set, if true redirect to index
    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    } 
?>

<html lang="en">
<head>
    <!-- Set character encoding for the document -->
    <meta charset="UTF-8">
    <!-- Ensure compatibility with IE -->
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <!-- Set viewport for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Voorraad</title>
    <!-- Include Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class = "p-8">
<div class="flex">
<h2>Voedselbank Maaskantje</h2>
<div class="mb-20">
    <!-- Include Switches.php file -->
    <?php require_once("Switches.php"); ?>
</div>
</div>
<h2 class="text-lg border-b border-black mb-3"> Medewerkers</h2>
<div class="max-w-sm">
<form method="post" class="flexbox bg-blue-200">
        <table class="border-separate border-spacing-5 border text-white">
            <tr>                
                <!-- Table headers for user ID, username, and user type -->
                <th class=" border border-slate-600 bg-gray-500">User ID</th>
                <th class=" border border-slate-600 bg-gray-500">Gebruikersnaam</th>
                <th class=" border border-slate-600 bg-gray-500">User Type</th>      
            </tr>
                <?php 
                    // SQL query to select user information
                    $sql = "SELECT iduser, gebruikersnaam, idusertype FROM user ORDER BY iduser";
                    $result = $conn->query($sql);
                    
                    // Check if the query was successful
                    if ($result) {
                        $prevUser = null;
                        // Fetch each row from the result
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            // Prevent duplicate user rows
                            if ($row["iduser"] != $prevUser) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["iduser"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["gebruikersnaam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["idusertype"] . "</td>";
                                echo "</tr>";
                            }
                            // Set previous user ID to current for comparison
                            $prevUser = $row["iduser"];
                        }
                    }
                ?>
        </table>
    </form>
</div>
<!-- Buttons for adding, deleting, and changing user information -->
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" onclick="openEnterForm()">Toevoegen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" onclick="openDeleteForm()">Verwijderen</button>
<button class="open-button text-black bg-white border border-black mt-5 mb-5 hover:bg-yellow-500 hover:text-white" onclick="openChangeform()">Aanpassen</button>

<!-- Form for adding a new user -->
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
    <!-- Submit button for adding user -->
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-green-500 hover:text-white" type="submit" class="btn" name="add">Toevoegen</button>
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel" onclick="closeEnterForm()">Sluiten</button>
  </form>
</div>
<!-- Form for deleting a user -->
<div class="form-popup-delete" id="myDeleteForm">
  <form class="form-container-delete" method="post">
    <h2 class="text-lg border-b border-black mt-3 mb-3">Medewerker verwijderen</h2>

    <?php 
        // SQL query to select users for deletion
        $sql2 = "SELECT iduser, gebruikersnaam FROM user";
        $result2 = $conn->query($sql2); 
        if ($result2) { 
            // List users with checkboxes for selection
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) { 
                echo "<input type='checkbox' name='users[]' value='" . $row["iduser"] . "'> " . $row["gebruikersnaam"] . "<br>";
            } 
        }
        // Debugging output for the result
        print_r($result2->fetch(PDO::FETCH_ASSOC));
    ?>

    <!-- Submit button for deleting users -->
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-orange-300 hover:text-white" type="submit" class="btn" name="delete">Verwijderen</button>
    <button class="text-black bg-white border border-black mt-5 mb-5 hover:bg-red-500 hover:text-white" type="button" class="btn cancel delete" onclick="closeDeleteForm()">Sluiten</button>
  </form>
</div>
<!-- Form for changing user password -->
<div class="form-popup" id="Changeform">
    <form class="form-container-change" method="post">
        <h2 class="text-lg border-b border-black mt-3 mb-3">Medewerker Aanpassen</h2>
        <label for="Change"><b>Medewerker</b></label>
        <select name="Change" id="Change">
            <?php
            // SQL query to fetch users for password change
            $sqlChange = "SELECT iduser, gebruikersnaam from user";
            $changes = $conn->query($sqlChange);
            if($changes){
                // List users in dropdown for selection
                while($row = $changes->fetch(PDO::FETCH_ASSOC)){
                    echo"<option value='".$row["iduser"]."'>".$row["iduser"]."</option>";
                }
            }
             ?>
        </select>
        <br>

        <label for="cPass"><b>Nieuw Wachtwoord</b></label>
        <input class="border border-separate border-black" type="password" placeholder="Nieuw Watchwoord" name="cPass" required>
        <label for="RecPass"><b>Herhaal Nieuw Wachtwoord</b></label>
        <input class="border border-separate border-black" type="password" placeholder="Herhaal Nieuw Watchwoord" name="RecPass" required>
        <br>
        <!-- Submit button for changing password -->
        <button class="text-black bg-white border border-black mt-5 hover:bg-green-500 hover:text-white " type="submit" class="btn" name="Changepass">Aanpassen</button>
        <button class="text-black bg-white border border-black mt-5 hover:bg-red-500 hover:text-white " type="button" class="btn cancel" onclick="closeChangeform()">Sluiten</button>
    </form>
</div>
<script>
// Function to open the form for adding a new user
function openEnterForm() {
  document.getElementById("myEnterForm").style.display = "block";
}

// Function to close the form for adding a new user
function closeEnterForm() {
  document.getElementById("myEnterForm").style.display = "none";
}

// Call close function to ensure form is hidden initially
closeEnterForm();

// Function to open the form for deleting a user
function openDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "block";
}

// Function to close the form for deleting a user
function closeDeleteForm() {
  document.getElementById("myDeleteForm").style.display = "none";
}

// Call close function to ensure form is hidden initially
closeDeleteForm();

// Function to open the form for changing a user's password
function openChangeform(){
    document.getElementById("Changeform").style.display = "block";
}

// Function to close the form for changing a user's password
function closeChangeform(){
    document.getElementById("Changeform").style.display = "none";
}

// Call close function to ensure form is hidden initially
closeChangeform();
</script>

<?php
    // Check if the request method is POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Switch statement to handle different form submissions
        switch (true) {
            // Case for adding a new user
            case isset($_POST['add']):
                $sql = $conn->prepare("INSERT INTO user(gebruikersnaam, wachtwoord, idusertype) VALUES(?, ?, ?)");
                
                // Retrieve posted user information
                $userNaam = $_POST['user'];
                $userPass = $_POST['pass'];
                $userRePass = $_POST['repw'];
                $userType = $_POST['type'];

                // Check if the passwords match
                if ($userPass === $userRePass) {
                    // Execute insert query
                    $sql->execute([$userNaam, password_hash($userPass, PASSWORD_DEFAULT), $userType]);
                    echo "Nieuwe user toegevoegd.";
                    // Refresh the page after 3 seconds
                    header("Refresh: 3; url=Medewerker.php");
                } else {
                    echo "Wachtwoorden staan niet gelijk.";
                }
                break;
            // Case for deleting users
            case isset($_POST['delete']):
                $userDeletes = $_POST['users'];

                // Loop through selected users and delete each
                foreach ($userDeletes as $userDelete) {
                    $sql = $conn->prepare("DELETE FROM user WHERE iduser = ?");
                    $sql->execute([$userDelete]);
                    echo "Medewerker verwijderd.";
                }
                // Refresh the page after 3 seconds
                header("Refresh: 3; url=Medewerker.php");
                break;
            // Case for changing a user's password
            case isset($_POST['Changepass']):
                $Sqlchange = $conn->prepare("UPDATE user SET wachtwoord = ? Where iduser = ?");

                // Retrieve posted information for password change
                $userchange = $_POST['Change'];
                $cPass = $_POST['cPass'];
                $RecPass = $_POST['RecPass'];
                // Check if the new passwords match
                if($cPass === $RecPass){
                    // Execute update query
                    $Sqlchange->execute([password_hash($cPass, PASSWORD_DEFAULT), $userchange]);
                    echo "Wachtwoord is aangepast.";
                    // Refresh the page after 3 seconds
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
