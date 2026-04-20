<?php
session_start();

$datafile = __DIR__ . "/../data.json";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // NAME
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    // EMAIL
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    // WEBSITE
    if (!empty($_POST["website"])) {
        $website = htmlspecialchars($_POST["website"]);
    }

    // COMMENT
    if (!empty($_POST["comment"])) {
        $comment = htmlspecialchars($_POST["comment"]);
    }

    // GENDER
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = $_POST["gender"];
    }

    // If no errors → save
    if ($nameErr == "" && $emailErr == "" && $genderErr == "") {

        $formdata = array(
            "name" => $name,
            "email" => $email,
            "website" => $website,
            "comment" => $comment,
            "gender" => $gender
        );

        // Read existing data
        if (file_exists($datafile)) {
            $current_data = file_get_contents($datafile);
            $array_data = json_decode($current_data, true);
        } else {
            $array_data = array();
        }

        if (!is_array($array_data)) {
            $array_data = array();
        }

        // Add new data
        $array_data[] = $formdata;

        // Save to JSON
        if (file_put_contents($datafile, json_encode($array_data, JSON_PRETTY_PRINT))) {
            echo "<p style='color:green;'>Data saved successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error saving data!</p>";
        }

        // Optional: set cookie
        setcookie("UserName", $name, time()+3600, "/");
    }
}
?>