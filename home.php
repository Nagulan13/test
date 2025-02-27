<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if($email == "nagu1370@gmail.com" && $password == "nagu1370") {
        echo "Login Success";
    } else {
        echo "Invalid";
        window.location.href = "index.html";
    }
} else {
    echo "Error";
}


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width= device-width, initial-scale=1.0">
        <title>Dashboard</title>

        <link href="styles.css" rel="stylesheet">

    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="navbar">
                    <a href="#">Basic Column</a>
                    <a href="#">Stacked Column</a>
                    <a hreft="#">Multiple axes</a>
                </div>
                <div class="content">
                    <div class="chart">

                    
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>
