<?php
session_start();
require_once("db_conn.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EzBusLK Login</title>
    <link rel="stylesheet" href="Admin/styleshee.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .logo-login {
            height: 130px;
            position: absolute;
            left: 0;
            top: 0;
            margin-left: 10px;
        }

        .back-button {
            position: absolute;
            top: 0;
            right: 0;
            margin-right: 20px;
            margin-top: 20px;
            background-color: #000b3d;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            padding: 5px 15px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: rgb(0, 80, 150);
        }
    </style>
</head>
<body>
    <section class="form">
        <a href="index.php"><img class="logo-login" src="images/logo.png"></a>
        <a href="index.php"><button class="back-button">Back</button></a>
        <div class="inquire-form">
            <form id="form1" name="form1" action="login_db_model.php" method="post" onsubmit="return validateForm()">
                <div class="headings">
                    <p class="login-form-title">Login - LMS</p>
                </div>
                <div class="form-details">
                    <div class="inputs-login">
                        <div class="average">
                            <div class="col1">
                                <label class="labels" for="username">Username:</label><br>
                                <input class="average-input" placeholder="Username" type="text" name="username" id="username" />
                            </div>
                        </div>
                        <div class="average">
                            <div class="col1">
                                <label class="labels" for="password">Password:</label><br>
                                <input class="average-input" placeholder="Password" type="password" name="password" id="password" />
                            </div>
                        </div>

                        <div class="login-button">
                            <?php
                            if (isset($_SESSION["errors_login"])) {
                                $errors = $_SESSION["errors_login"];
                                foreach ($errors as $error) {
                                    echo '<p id="error" style="margin-bottom: 5px; margin-top: 5px; font-size: 13px; text-align: center;">' . $error . '</p>';
                                    break;
                                }
                                unset($_SESSION["errors_login"]);
                            } else {
                                echo '<p id="error" style="margin-bottom: 5px; margin-top: 5px; font-size: 13px; text-align: center;"></p>';
                            }
                            ?>
                            <button class="login" type="submit" name="login">Login</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</body>
</html>

<script>
    function validateForm() {
        var username = document.forms["form1"]["username"].value.trim();
        var password = document.forms["form1"]["password"].value.trim();

        if (username == "" || password == "") {
            document.getElementById("error").innerHTML = "Please fill in all required fields";
            return false;
        }
        return true;
    }
</script>