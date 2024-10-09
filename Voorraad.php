<?php
    // Start output buffering
    ob_start();
    // Include the database connection
    require_once("db_login.php");

    // Check if the user is logged in and has the appropriate user type
    if (!isset($_SESSION["usertype"])) {
        // If no user type is set, redirect to the homepage (index.php)
        header("Location: index.php");
    } 
    // If the user is a volunteer (usertype 3), redirect to the Voedselpakket page
    else if ($_SESSION["usertype"] == 3) {
        header("Location: Voedselpakket.php");
    }
?>


<html lang="en">
<head>
    <!-- Sets the character encoding and metadata for browser compatibility -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Voorraad</title>
    <!-- Load Tailwind CSS from a CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">

<div class="flex">
    <!-- Heading for the food bank -->
    <h2>Voedselbank Maaskantje</h2>
    <div class="mb-20">
        <!-- Include Switches.php for additional functionality (possibly user interface switches) -->
        <?php require_once("Switches.php"); ?>
    </div>
</div>

<!-- Section for inventory management -->
<h2 class="text-lg border-b border-black mb-3">Voorraad</h2>

<!-- A form to display the product inventory table -->
<div class="max-w-3xl">
    <form method="post" class="flexbox bg-blue-200">
        <table class="border-separate border-spacing-5 border">
            <thead>
                <tr>
                    <!-- Table headers with sortable links for product attributes (e.g., barcode, name, category) -->
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=streepjescode">Streepjescode</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=productnaam">Productnaam</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=product.idcategorie">Categorie</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=aantal">Aantal</a></th>
                    <th class="border border-slate-600 bg-gray-500 text-base"><a class="text-white" href="Voorraad.php?sort=verderfdatum">Verderfdatum</a></th>            
                </tr>

                <!-- PHP section to fetch and display product inventory from the database -->
                <?php
                    // Array of sortable columns
                    $sort = array('streepjescode', 'productnaam', 'product.idcategorie', 'aantal', 'verderfdatum');
                    $order = 'streepjescode';  // Default sort order
                    // Check if a valid sort parameter is set in the URL and update the order
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sort)) {
                        $order = $_GET['sort'];
                    }

                    // SQL query to fetch product data joined with category information, ordered by the selected column
                    $sql = 'SELECT streepjescode, productnaam, beschrijving, aantal, verderfdatum FROM product JOIN categorie ON product.idcategorie = categorie.idcategorie ORDER BY ' . $order;
                    $result = $conn->query($sql);
                ?>
            </thead>

            <!-- Table body to display the fetched product data -->
            <tbody>
                <?php 
                    // If query result is valid, loop through each product and display in a table row
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $prevProduct = null;  // Variable to track previous product
                            // Only display a new row if the product is different from the previous one
                            if ($row["streepjescode"] != $prevProduct) {
                                echo "<tr>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["streepjescode"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["productnaam"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["beschrijving"] . "</td>"; 
                                echo "<td class='border border-slate-600 text-black'>" . $row["aantal"] . "</td>";
                                echo "<td class='border border-slate-600 text-black'>" . $row["verderfdatum"] . "</td>";
                                echo "</tr>";
                            }
                            $prevProduct = $row["streepjescode"];
                        } 
                    }
                ?> 
            </tbody>    
        </table>
    </form>
</div>
<script>
    // Function to show the "Add Product" form
    function openEnterForm() {
        document.getElementById("myEnterForm").style.display = "block";
    }
    // Function to hide the "Add Product" form
    function closeEnterForm() {
        document.getElementById("myEnterForm").style.display = "none";
    }
    closeEnterForm();

    // Function to show the "Change Product" form
    function openChangeForm() {
        document.getElementById("myChangeForm").style.display = "block";
    }
    // Function to hide the "Change Product" form
    function closeChangeForm() {
        document.getElementById("myChangeForm").style.display = "none";
    }
    closeChangeForm();

    // Function to show the "Add Category" form
    function openEnterCateForm() {
        document.getElementById("myEnterCateForm").style.display = "block";
    }
    // Function to hide the "Add Category" form
    function closeEnterCateForm() {
        document.getElementById("myEnterCateForm").style.display = "none";
    }
    closeEnterCateForm();
</script>


<?php
    // Check if the request method is POST
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Use a switch to handle different cases based on POST values
        switch (true) {
            
            // Case: Adding a new product (if 'add' button is set)
            case isset($_POST['add']):
                // Prepare SQL statement to insert a new product
                $sql = $conn->prepare("INSERT INTO product(streepjescode, productnaam, idcategorie, aantal, verderfdatum) VALUES(?, ?, ?, ?, ?)");
                
                // Get product details from the POST request
                $prdctCode = $_POST['stcd']; // Product barcode
                $prdctNaam = $_POST['prnm']; // Product name
                $prdctCtgr = $_POST['ctgr']; // Product category
                $prdctAantal = $_POST['amnt']; // Product amount
                $prdctDatum = $_POST['date']; // Product expiration date
                
                // Execute the SQL query to insert the new product
                $sql->execute([$prdctCode, $prdctNaam, $prdctCtgr, $prdctAantal, $prdctDatum]);
                
                // Display a message and refresh to Voorraad.php after 3 seconds
                echo "Nieuw product toegevoegd.";
                header("Refresh: 3; url=Voorraad.php");
                break;
            
            // Case: Modifying an existing product (if 'change' button is set)
            case isset($_POST['change']):
                // Get modification values from the POST request
                $voedAantal = $_POST['wijzAantal']; // Quantity to modify
                $changeVerderf = $_POST['wijzVerderf']; // New expiration date
                $changeCode = $_POST['wijzCode']; // Product barcode
                
                // Prepare SQL to get the current product quantity by barcode
                $sqlPrevAantal = "SELECT streepjescode, aantal FROM product WHERE streepjescode = ?";
                $resPrevAantal = $conn->prepare($sqlPrevAantal);
    
                // Execute query and check if a product with the given barcode exists
                if($resPrevAantal->execute([$changeCode])) {
                    while ($row = $resPrevAantal->fetch(PDO::FETCH_ASSOC)) {
                        // Calculate the new product quantity after the modification
                        $aantalNaToevoeging = $row["aantal"] + $voedAantal;
                        
                        // Check if the new quantity is non-negative
                        if ($aantalNaToevoeging >= 0) {
                            // Prepare SQL to update product quantity
                            $prodAantalVeranderen = $conn->prepare("UPDATE product SET aantal = ? WHERE streepjescode = ?");
                            $prodAantalVeranderen->execute([$aantalNaToevoeging, $changeCode]);
                            
                            // Prepare SQL to update product expiration date
                            $changeProd = $conn->prepare("UPDATE product SET verderfdatum = ? WHERE streepjescode = ?");
                            $changeProd->execute([$changeVerderf, $changeCode]);
                
                            // Display a success message and refresh to Voorraad.php
                            echo "Product gewijzigd.";
                            header("Refresh: 3; url=Voorraad.php");
                        } else {
                            // Display error if new quantity would be negative
                            echo "Aantal kan niet lager dan 0.";
                        }
                    }
                }
                break;
            
            // Case: Adding a new category (if 'cateAdd' button is set)
            case isset($_POST['cateAdd']):
                // Prepare SQL statement to insert a new category
                $sqlCateAdd = $conn->prepare("INSERT INTO categorie(beschrijving) VALUES(?)");
                $cateNaam = $_POST['cate']; // Category name
                
                // Execute the SQL query to insert the new category
                $sqlCateAdd->execute([$cateNaam]);
                
                // Display a message and refresh to Voorraad.php after 3 seconds
                echo "Nieuwe categorie toegevoed.";
                header("Refresh: 3; url=Voorraad.php");
                break;
        }
    }
?> 
</body>
</html>