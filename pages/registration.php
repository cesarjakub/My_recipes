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
    if(isset($_POST["registr-btn"])){
        $name = $_POST["name"];
        $email = $_POST["email"];
        $pass1 = $_POST["password"];
        $pass2 = $_POST["conf-password"];

        $error = "";
        
        $valid_password = validatePasswords($pass1, $pass2);
        $check_lenght = validateLenght($pass1, $pass2);
        $checkForWhiteSpaces = validatePasswordCheckForWhiteSpace($pass1);
        $validateUserName = validateName($name);
        $validateUserEmail = validateEmail($email);
        $validateEmailExist = validateEmailExist($email, $users);

        if ($valid_password && $check_lenght && $checkForWhiteSpaces && $validateUserName && $validateUserEmail && $validateEmailExist) {
            $hashedPass = password_hash($pass1, PASSWORD_DEFAULT);
            $admin = "False";

            $maxId = 0;

            foreach ($users as $user) {
                if ($user['id'] > $maxId) {
                    $maxId = $user['id'];
                }
            }

            $newRecord = [
                "id" => $maxId + 1, 
                "jmeno" => $name,
                "email" => $email,
                "heslo" => $hashedPass,
                "admin" => $admin
            ];

            $users[] = $newRecord;

            file_put_contents($path, json_encode($users));
            
            $_SESSION['username'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['admin'] = $admin;
            $_SESSION['loggedin'] = true;
            header("Location: home.php");
            die();

        } else {
            if (!$valid_password) {
                $error = "<div class='alert-php'><p>Passwords do not match!</p></div>";
            }
            if (!$check_lenght) {
                $error = "<div class='alert-php'><p>Passwords must meet the required length!</p></div>";
            }
            if (!$checkForWhiteSpaces) {
                $error = "<div class='alert-php'><p>Password can't contain white spaces!</p></div>";
            }
            if (!$validateUserName) {
                $error = "<div class='alert-php'><p>Only letters and spaces allowed in name!</p></div>";
            }
            if (!$validateUserEmail) {
                $error = "<div class='alert-php'><p>Please enter a valid email address!</p></div>";
            }
            if (!$validateEmailExist) {
                $error = "<div class='alert-php'><p>This email already exists!</p></div>";
            }
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

    <script src="../js/registration.js" defer></script>

    <title>My recipes</title>
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <p class="title">Register </p>
            <p class="message">Signup now and get full access to our website. </p>
            
            <div class="line"></div>

            <div class="alert">

            </div>
            <?php 
                if (!empty($error)) {
                    echo $error;
                }
            ?>
            <form class="form register-form" action="registration.php" method="post">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input required placeholder="Enter your name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" autocomplete="on" name="name" id="name" type="text">
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input required placeholder="Enter your email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" autocomplete="on" name="email" id="email" type="email">
                </div>
        
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input required name="password" minlength="8" placeholder="Enter your password" id="password" type="password">
                </div>

                <div class="form-group">
                    <label for="conf-password">Confirm password *</label>
                    <input required name="conf-password" minlength="8" placeholder="Confirm your password" id="conf-password" type="password">
                </div>
        
                <input type="submit" class="form-submit-btn" value="Submit" name="registr-btn">
            </form>
            <p class="indicator">* indicates a required field</p>
            <div class="line"></div>

            <div class="links">
                <p>
                    Already have an account?
                    <a class="login-link link" href="login.php">Log in now</a>
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