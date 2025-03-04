<?php

include 'db_conn.php';

if (isset($_POST["submit"])) {

    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password == $user['password']) {

            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;

            echo "<script>alert('Login Successful!'); window.location.href = 'home.php';</script>";
        } else {
            echo "Invalid Email or Password, Try <a href = 'index.php'>Login</a> again.";
        }
    } else {
        echo "No Record.";
    }
    mysqli_close($conn);
}

?>
