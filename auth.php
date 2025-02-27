<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email == "nagu1370@gmail.com" && $password == "nagu1370") {
        echo "Login Success";
        header("Location: home.php"); // Redirect to home
        exit();
    } else {
        echo "Invalid Login";
        header("Location: index.html"); // Back to Login Page
        exit();
    }
} else {
    echo "405 Not Allowed";
}

?>
