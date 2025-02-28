
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Chart</title>
        <link href="styles.css" rel="stylesheet">

    </head>
    <body>
        <div class="wrapper">
            <div class="container">
                <div class="top">
                    <h4>LOGIN</h4>
                </div>
                <div class="main">
                    <form action="auth.php" method="POST" id="loginForm">
                        <label>Email :</label>
                        <input type="email" placeholder="email" id="email">
                        <br>
                        <label>Password :</label>
                        <input type="password" placeholder="password" id="password">
                        <br>
                        <input type="submit" value="Login">
                    </form>
                    <p id="message" style="color: red;"></p>
                </div>
                
            </div>
        </div>

        <script>
            const form = document.getElementById("loginForm");

            form.addEventListener("submit", (event) => {

                const email = document.getElementById("email").value;
                const password = document.getElementById("password").value;

                if(email ==="" || password ==="") {
                    message.innerHTML = "Empty fields.";
                    event.preventDefault(); //Stop submit
                } else {
                    alert("Login Successful");
                    window.location.href="home.php";
                }
            } );


        </script>

    </body>
    
<html>