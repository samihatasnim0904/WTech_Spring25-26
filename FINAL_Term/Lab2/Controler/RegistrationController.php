<?php
session_start();
$name = "";
$password = "";
$dataFile = "../data.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $password = $_POST["password"];

    if (!empty($name) && strlen($name) >= 5 && strlen($password) >= 4) {
        $_SESSION["name"] = $name;
        setcookie("name", $name, time() + 3600, "/");
        echo "Log in successful! <br>";

        $formdata = array(
            "name" => $name,
            "password" => $password
        );

        if (file_exists($dataFile)) {
            $existingdata = file_get_contents($dataFile);
            $tempJSONdata = json_decode($existingdata, true);
        } else {
            $tempJSONdata = array();
        }

        if (!is_array($tempJSONdata)) {
            $tempJSONdata = array();
        }

        $tempJSONdata[] = $formdata;
        $jsondata = json_encode($tempJSONdata, JSON_PRETTY_PRINT);

        if (file_put_contents($dataFile, $jsondata) !== false) {
            echo "Data successfully saved <br>";
        } else {
            echo "No data saved <br>";
        }

        $data = file_get_contents($dataFile);
        $mydata = json_decode($data, true);
    }
    else {
        echo "Please enter a valid name (at least 5 characters) and password (at least 4 characters).<br>";
    }

    if (isset($_SESSION["name"]) || isset($_COOKIE["name"])) {
        echo "Welcome Back";
    } else {
        echo "Log in again";
    }
}
?>