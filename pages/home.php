<?php
    session_start();
    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "True") {
            header("Location: dashboard.php");
            die();
        }
    }else{
        header("Location: ../index.php");
    }

    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = "special_token";
    }

    require '../includes/view.php';
    require '../includes/validation.php';
    require '../includes/helpermethods.php';

    $json = file_get_contents('../db/recipes.json');
    $recipes = json_decode($json, true);

    $jsonUsers = file_get_contents('../db/users.json');
    $users = json_decode($jsonUsers, true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }

        $_SESSION['token'] = uniqid('', TRUE);

        //update user
        if(isset($_POST["edit-user-btn"])){
            $username = $_POST["name"];
            $email = $_POST["email"];

            $error = "";

            $validateUserName = validateName($username);
            $validateUserEmail = validateEmail($email);

            if($validateUserName && $validateUserEmail){
                foreach($users as &$user){
                    if($user["email"] === $_SESSION['email'] && $user["jmeno"] === $_SESSION['username']){
                        $user["jmeno"] = $username;
                        $user["email"] = $email;

                        $_SESSION["username"] = $username;
                        $_SESSION["email"] = $email;

                        file_put_contents('../db/users.json', json_encode($users));
                        break;
                    }
                }
            }else{
                if (!$validateUserName) {
                    $error = "<div class='alert-php'><p>Only letters and spaces allowed in name!</p></div>";
                }
                if (!$validateUserEmail) {
                    $error = "<div class='alert-php'><p>Please enter a valid email address!</p></div>";
                }
            }

        }


        //insert new recipe
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["add-recipe-btn-form"])){
            $title = $_POST["title"];
            $description = $_POST["description"];
            $author = $_SESSION["username"];
            $image = handleImage('image');
            $time = date('Y-m-d');

            $error = "";
            $maxId = 0;

            foreach ($recipes as $recipe) {
                if ($recipe['id'] > $maxId) {
                    $maxId = $recipe['id'];
                }
            }
            if (isset($image['success'])) {
                $imageFileName = $image['fileName'];
            
                $newRecipe = [
                    "id" => $maxId + 1,
                    "user_name" => $author,
                    "title" => $title,
                    "image" => $imageFileName,
                    "text_receptu" => $description,
                    "time" => $time
                ];

                $recipes[] = $newRecipe;
                file_put_contents('../db/recipes.json', json_encode($recipes));
                $error = "<div class='alert-php'><p>Recipe added successfully!</p></div>";

            }else {
                $error = "<div class='alert-php'><p>Error: {$image['error']}</p></div>";
            }

        }
    }

    //pagination
    $recipesPerPage = 12; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $page = max(1, $page); 
    $totalRecipes = count($recipes);
    $totalPages = ceil($totalRecipes / $recipesPerPage); 
    $offset = ($page - 1) * $recipesPerPage;

    $currentRecipes = array_slice($recipes, $offset, $recipesPerPage);

    if (isset($_GET['xhr']) && $_GET['xhr'] === 'true') {
        ob_start();
        foreach ($currentRecipes as $recipe) {
            echo recipeCard($recipe);
        }
        $recipesHtml = ob_get_clean();

        echo json_encode([
            'html' => $recipesHtml,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
        exit;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style.css">
    <!--<link rel="stylesheet" href="#" media="print">-->

    <script src="../js/home.js" defer></script>
    <script src="../js/pagination.js" defer></script>

    <title>My recipes</title>
</head>
<body>

    <div class="main-container">
        <div class="shadow"></div>
        <div class="profile-card">
            <div class="profile-info">
                <h2 class="card-profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p class="card-profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            <div class="btn-group">
                <button class="edit-btn" type="submit">
                    <img src="../images/icons/edit.svg" alt="edit-icon">
                </button>
                <button class="add-recipe-btn" type="submit" value="Add recipe">
                    <img src="../images/icons/add.svg" alt="add-icon">
                </button>
                <a class="my-recipes-btn" href="myRecipes.php">
                    <img src="../images/icons/settings.svg" alt="settings-icon">
                </a>
                <form action="../includes/logout.php" method="post">
                    <button type="submit" name="logout" value="logout" class="logout-btn">
                        <img src="../images/icons/logout.svg" alt="logout-icon">
                    </button>
                </form>
            </div>
        </div>
        <?php
            if (isset($json) && $json === false) {
                echo "<div class='alert-php'><p>Unable to load recipes</p></div>";
            }
            if (!empty($error)) {
                echo $error;
            }
        ?>
        <div class="edit-profile-card-modal">
            <div class="modal-header">
                <h3>Edit Profile</h3>
                <span class="close-profile-modal">&times;</span>
            </div>
            <div class="alert">

            </div>
            <form class="form edit-profile-form" action="home.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input required placeholder="Enter your name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" autocomplete="off" name="name" id="name" type="text">
                </div>
        
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input required name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" autocomplete="off" id="email" type="email">
                </div>
                
                <input type="submit" class="form-submit-btn" value="Save Changes" name="edit-user-btn">
            </form>
            <p class="indicator">* indicates a required field</p>
        </div>

        <div class="add-recipe-modal">
            <div class="modal-header">
                <h3>Add recipe</h3>
                <span class="close-add-recipe-modal">&times;</span>
            </div>
            <form class="form" action="home.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="token"  value="<?php echo htmlspecialchars($_SESSION['token']); ?>">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input required placeholder="Enter title" name="title" id="title" type="text">
                </div>
        
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea required name="description" placeholder="Enter description" id="description"></textarea>
                </div>

                <div class="form-group">
                    <label class="image-file" for="image">Upload Image</label>
                    <input type="file" name="image" id="image" accept="image/png, image/jpeg">
                    <p class="image-file-name"></p>
                </div>
        
                <input type="submit" name="add-recipe-btn-form" class="form-submit-btn" value="Save recipe">
            </form>
            <p class="indicator">* indicates a required field</p>
        </div>

        <div class="cards-pagination">
            <div class="recipes-cards">

            </div>
            <div class="pagination">

            </div>
        </div>      

    </div>
</body>
</html>

                <!--                <button class="pagination-btn active">&laquo;</button>
                <button class="pagination-btn">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">&raquo;</button>-->