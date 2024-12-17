

	<?php
$host = 'localhost'; 
$dbname = 'plushie_shop'; 
$username = 'root'; 
$password = ''; 

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

