<?php
    session_start();
    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "False") {
            header("Location: home.php");
            die();
        }
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "True") {
            header("Location: dashboard.php");
            die();
        }
    }

    require '../includes/validation.php';

    $path = "../db/users.json";
    $jsonData = file_get_contents($path);
    $users = json_decode($jsonData, true);

    if(isset($_POST["login-btn"])){
        $email = $_POST["email"];
        $password = $_POST["password"];

        $error = "";
        $found = FALSE;

        foreach($users as $user){
            if($user["email"] === $email && password_verify($password, $user["heslo"])){
                $found = TRUE;
                $_SESSION['username'] = $user['jmeno'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['loggedin'] = true;
                if ($user['admin'] === "True") {
                    $_SESSION['admin'] = $user['admin'];
                    break;
                }
                $_SESSION['admin'] = $user['admin'];
                break;
            }
        }
        if($found){
            if($_SESSION['admin'] == "True"){
                header("Location: dashboard.php");
                die();
            }
            header("Location: home.php");
            die();
        }
        if (!$found) {
            $error = "<div class='alert-php'><p>Invalid email or password!</p></div>";
        }

    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">
    <!--<link rel="stylesheet" href="#" media="print">-->

    <title>My recipes</title>
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <p class="title">Welcome Back! </p>
            
            <div class="line"></div>
            
            <?php 
                if (!empty($error)) {
                    echo $error;
                }
            ?>

            <form class="form" action="login.php" method="post">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input required placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" autocomplete="on" name="email" id="email" type="email">
                </div>
        
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input required name="password" placeholder="Enter your password" minlength="8" id="password" type="password">
                </div>
        
                <input type="submit" class="form-submit-btn" value="Submit" name="login-btn">
            </form>
            
            <p class="indicator">* indicates a required field</p>

            <div class="line"></div>

            <div class="links">
                <p>
                    Don't have an account?
                    <a class="signup-link link" href="registration.php"> Sign up now</a>
                </p>
                <p>
                    &larr;
                    <a class="index-link link" href="../index.php"> Go Back </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>