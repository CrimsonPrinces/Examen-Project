<?php
    require_once("db_login.php");

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
    <title>Voedselbank Maaskantje Voedselpakket</title>
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
<h2 class="text-lg border-b border-black mb-3"> Voedselpakket</h2>


<div class="max-w-xl">
    <form method="post" class="flexbox bg-blue-200 w-auto">
        <table class="border-separate border-spacing-5 border border-slate-500 ">
            <tr>                
                <th class=" border border-slate-600">Voedselpakket ID</th>
                <th class="border-slate-600">Hoord bij klant</th>
                <th class="border-slate-600">Samensteldatum</th>
                <th class="border-slate-600">Uitgiftedatum</th>            
            </tr>
                <?php 
                    $prevVoedselpakket = null;
                    $sql = "SELECT idvoedselpakket, naam, samensteldatum, uitgiftedatum FROM voedselpakket JOIN klant ON voedselpakket.idklant = klant.idklant ORDER BY voedselpakket.idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idvoedselpakket"] != $prevVoedselpakket) {
                                echo "<tr>";
                                echo "<td class='border-slate-700'>" . $row["idvoedselpakket"] . "</td>";
                                echo "<td>" . $row["naam"] . "</td>"; 
                                echo "<td>" . $row["samensteldatum"] . "</td>"; 
                                echo "<td>" . $row["uitgiftedatum"] . "</td>";
                                echo "</tr>";
                            }
                            $prevVoedselpakket = $row["idvoedselpakket"];
                        }
                    }
                ?>
        </table>
    </form>
</div>    
</body>
</html>