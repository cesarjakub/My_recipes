<?php
    session_start();
    if (isset($_SESSION['loggedin'])) {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === "True") {
            header('Location: dashboard.php');
            die();
        }
    }else{
        header('Location: ../index.php');
    }

    require '../includes/view.php';
    require '../includes/helpermethods.php';

    $json = file_get_contents('../db/recipes.json');
    $recipes = json_decode($json, true);

    // delete recipe
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
    if(isset($_POST['edit-recipe-btn-form'])){
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

    <script src="../js/myRecipe.js" defer></script>

    <title>My recipes</title>
</head>
<body>
    <div class="my-recipes-container">
        <div class="shadow"></div>
        <div class="go-back">
            <a href="home.php">&larr; Go back</a>
        </div>
        <?php
            if (isset($json) && $json === false) {
                echo "<div class='alert-php'><p>Unable to load recipes</p></div>";
            }
            if (!empty($error)) {
                echo $error;
            }
        ?>
        <div class="recipe-container">
            <div class="my-recipes">
            <?php
                $hasRecipes = FALSE;

                foreach ($recipes as $recipe) {
                    if($recipe['user_name'] === $_SESSION['username']){
                        echo editRecipeCard($recipe);
                        $hasRecipes = TRUE;
                    }
                }
                if(!$hasRecipes){
                    echo "<div class='alert-php'><p>You have not created any recipes yet.</p></div>";
                    
                }
            ?>
            </div>
        </div>

        <div class="edit-my-recipe-modal">
            <div class="modal-header">
                <h3>Edit recipe</h3>
                <span class="close-edit-recipe-modal">&times;</span>
            </div>
            <form class="form" action="myRecipes.php" method="post" enctype="multipart/form-data">
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
                    <p class="image-file-name"></p>
                </div>
                <input type="hidden" name="edit-id" id="edit-id" value="">
                <input type="submit" class="form-submit-btn" name="edit-recipe-btn-form" value="Save recipe">
            </form>
            <p class="indicator">* indicates a required field</p>
        </div>

    </div>
</body>
</html>