<?php
require_once("db_login.php");


switch($_SESSION["usertype"]){
    case 1:
    echo "<a href='Medewerker.php' class= 'mx-5'> Medewerkers </a>";
    echo "<a href='Leverancier.php' class= 'mx-5'> Leveranciers </a>";
    echo "<a href='Klanten.php' class='mx-5'> Klanten </a>";
    echo "<a href='Voorraad.php' class='mx-5'> Voorraad </a>";
    break;
    case 2:
    echo "<a href='Voorraad.php' class='mx-5'> Voorraad </a>";  
}