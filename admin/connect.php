

	<?php
$host = 'localhost'; 
$dbname = 'u143688490_plushie_shop'; 
$username = 'u143688490_bluesteel'; 
$password = 'Fujiwara000!'; 

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

