<?php

require_once "db.php";

$database = new Database();

$conn = $database->getConnection();

if($conn){
    echo "Database Connected Successfully";
}
?>