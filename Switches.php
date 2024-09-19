<?php
require_once("db_login.php");


switch($_SESSION["usertype"]){
    case 1:
    echo "<a href='Medewerker.php' class= 'mx-5 hover:bg-blue-200'> Medewerkers </a>";
    echo "<a href='Leverancier.php' class= 'mx-5 hover:bg-blue-200'> Leveranciers </a>";
    echo "<a href='Klanten.php' class='mx-5 hover:bg-blue-200'> Klanten </a>";
    echo "<a href='Voorraad.php' class='mx-5 hover:bg-blue-200'> Voorraad </a>";
    echo "<a href='Voedselpakket.php' class='mx-5 hover:bg-blue-200'> Voedselpakket </a>";
    echo "<a href='index.php' class= 'mx-5 hover:bg-blue-200'> Uitloggen </a>";
    break;
    case 2:
    echo "<a href='Voorraad.php' class='mx-5 hover:bg-blue-200'> Voorraad </a>";
    echo "<a href='Voedselpakket.php' class='mx-5 hover:bg-blue-200'> Voedselpakket </a>";
    echo "<a href='index.php' class= 'mx-5 hover:bg-blue-200'> Uitloggen </a>";
    break;
    default:
    echo "<a href='Voedselpakket.php' class='mx-5 hover:bg-blue-200'> Voedselpakket </a>";
    echo "<a href='index.php' class= 'mx-5 hover:bg-blue-200'> Uitloggen </a>";
}