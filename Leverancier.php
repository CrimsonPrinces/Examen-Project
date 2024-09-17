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
</body>
</html>