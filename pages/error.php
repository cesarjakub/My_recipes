<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "False") {
            header("Location: home.php");
            exit;
        }
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "True") {
            header("Location: dashboard.php");
            exit;
        }
    }

    if (!isset($_GET["code"])) {
        header("Location: ../index.php");
    }

    $code = htmlspecialchars($_GET["code"]);

    if ($code == "404") {
        http_response_code(404);
    } elseif ($code == "500") {
        http_response_code(500);
    } elseif ($code == "403") {
        http_response_code(403);
    } else {
        http_response_code(400); 
        $code = "400";
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">
    <!--<link rel="stylesheet" href="#" media="print">-->

    <title>My recipes ERROR</title>
</head>
<body>
    <div class="error">
        <h1 class="error-code"><?php echo $code;?> ERROR PAGE</h1>
        <p class="error-message" id="error-message">An unexpected error occurred.</p>
        <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "True") {
            echo "<p>&larr;<a class='home-link link' href='dashboard.php'> Go Back </a> (jiny odkaz)</p>";
        }else if(isset($_SESSION['admin']) && $_SESSION['admin'] === "False"){
            echo "<p>&larr;<a class='home-link link' href='home.php'> Go Back </a> (jiny odkaz)</p>";
        }else{
            echo "<p>&larr;<a class='index-link link' href='../index.php'> Go Back </a> (jiny odkaz)</p>";
        }

        ?>
        
    </div>
</body>
</html>