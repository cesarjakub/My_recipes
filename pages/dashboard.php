<?php
    session_start();
    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "False") {
            header('Location: home.php');
            die();
        }
    }else{
        header('Location: ../index.php');
    }

    require '../includes/view.php';
    require '../includes/helpermethods.php';

    $recipeJson = file_get_contents('../db/recipes.json');
    $recipes = json_decode($recipeJson, true);

    $usersJson = file_get_contents('../db/users.json');
    $users = json_decode($usersJson, true);

    // promote user to admin
    if(isset($_POST['update-user-as-admin'])){
        $updateId = $_POST['update-user-id'];
        foreach ($users as &$user) {
            if($user['id'] == $updateId){
                $user['admin'] = "True";
                break;
            }
        }
        file_put_contents('../db/users.json', json_encode($users));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    // delete user
    if(isset($_POST['delete-user-dashboard-btn'])){
        $deleteUserId = $_POST['delete-user-id'];

        foreach ($users as $key => $user) {
            if ($user['id'] == $deleteUserId) {
                unset($users[$key]);
                break; 
            }
        }

        file_put_contents('../db/users.json', json_encode($users));
    }

    //add recipe
    if(isset($_POST["add-recipe-admin"])){
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

        }else {
            $error = "<div class='alert-php'><p>Error: {$image['error']}</p></div>";
        }

    }

    //delete recipes
    if(isset($_POST['delete_id'])){
        $deleteId = $_POST['delete_id'];

        foreach ($recipes as $key => $recipe) {
            if ($recipe['id'] == $deleteId) {
                unset($recipes[$key]);
                break; 
            }
        }

        file_put_contents('../db/recipes.json', json_encode($recipes));
    }

     // edit recipes
     if(isset($_POST['update-dashboard-recipe'])){
        $title = $_POST["edit-title"];
        $description = $_POST["edit-description"];
        $author = $_SESSION["username"];
        $image = handleImage('edit-image');
        $time = date('Y-m-d');

        $recipe_id = $_POST['edit-id'];

        $error = "";

        if (isset($image['success'])) {
            $imageFileName = $image['fileName'];
            foreach($recipes as &$recipe){
                if($recipe['id'] == $recipe_id){
                    $recipe['user_name'] = $author;
                    $recipe['title'] = $title;
                    $recipe['image'] = $imageFileName;
                    $recipe['text_receptu'] = $description;
                    $recipe['time'] = $time;
                    break;
                }
            }
            file_put_contents('../db/recipes.json', json_encode($recipes));
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }else {
            $error = "<div class='alert-php'><p>Error: {$image['error']}</p></div>";
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

    <script src="../js/dashboard.js" defer></script>

    <title>Admin dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="shadow"></div>
        <div class="profile">
            <div class="profile-info">
                <h2 class="card-profile-name"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                <p class="card-profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>
            <h2>Admin dashboard</h2>
            <div class="btn-group">
                <button class="add-recipe-btn" type="submit">
                    <img src="../images/icons/add.svg" alt="add-icon">
                </button>
                <form action="../includes/logout.php" method="post">
                    <button type="submit" name="logout" value="logout" class="logout-btn">
                        <img src="../images/icons/logout.svg" alt="logout-icon">
                    </button>
                </form>
            </div>
        </div>
        <?php
            if (isset($recipeJson) && $recipeJson === false || isset($usersJson) && $usersJson === false) {
                echo "<div class='alert-php'><p>Unable to load json files</p></div>";
            }
            if (!empty($error)) {
                echo $error;
            }
        ?>
        <div class="add-recipe-modal">
            <div class="modal-header">
                <h3>Add recipe</h3>
                <span class="close-add-recipe-modal">&times;</span>
            </div>
            <form class="form" action="dashboard.php" method="post" enctype="multipart/form-data">
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
        
                <input type="submit" name="add-recipe-admin" class="form-submit-btn" value="Save recipe">
            </form>
            <p class="indicator">* indicates a required field</p>
        </div>

        <div class="choose-btn">
            <button class="users">Users</button>
            <button class="recipes">Recipes</button>
        </div>

        <div class="user-table">
        <?php
            foreach ($users as $user) {
                echo userCard($user);
            }
        ?>
        </div>

        <div class="recipe-table">
        <?php
            foreach ($recipes as $recipe) {
                echo dashboardEditRecipeCard($recipe);
            }
        ?>
        </div>

        <div class="edit-recipe-modal">
            <div class="modal-header">
                <h3>Edit recipe</h3>
                <span class="close-edit-recipe-modal">&times;</span>
            </div>
            <form class="form" action="dashboard.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="edit-title">Title *</label>
                    <input required placeholder="Enter title" name="edit-title" id="edit-title" type="text">
                </div>
        
                <div class="form-group">
                    <label for="edit-description">Description *</label>
                    <textarea required name="edit-description" placeholder="Enter description" id="edit-description"></textarea>
                </div>

                <div class="form-group">
                    <label class="image-file" for="edit-image">Upload Image</label>
                    <input type="file" name="edit-image" id="edit-image" accept="image/png, image/jpeg">
                    <p class="image-file-name-2"></p>
                </div>
                <input type="hidden" name="edit-id" id="edit-id" value="">
                <input type="submit" class="form-submit-btn" value="Save recipe" name="update-dashboard-recipe">
            </form>
            <p class="indicator">* indicates a required field</p>
        </div>

    </div>
</body>
</html>