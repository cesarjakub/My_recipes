<?php

function recipeCard($recipe){
    return "
    <div class='card'>
        <div class='card-header'>
            <h2>".htmlspecialchars($recipe['title'])."</h2>
        </div>
        <div class='image-placeholder'>
            <img src='../images/img/".htmlspecialchars($recipe['image'])."' alt='".htmlspecialchars($recipe['title'])."'>
        </div>
        <div class='card-cotn'>
            <div class='card-content'>
                ".htmlspecialchars($recipe['text_receptu'])."
            </div>
            <div class='card-footer'>
                <h4>".htmlspecialchars($recipe['user_name'])."</h4>
                <p>".htmlspecialchars($recipe['time'])."</p>
            </div>
        </div>
    </div>
    ";
}


function editRecipeCard($recipe){
    return "
    <div class='card'>
        <input type='hidden' name='id-of-card' id='id-of-card' value='" . htmlspecialchars($recipe['id']) . "'>
        <div class='card-header'>
            <h2>".htmlspecialchars($recipe['title'])."</h2>
        </div>
        <div class='image-placeholder'>
            <img src='../images/img/".htmlspecialchars($recipe['image'])."' alt='".htmlspecialchars($recipe['title'])."'>
        </div>
        <div class='card-cotn'>
            <div class='card-content'>
                ".htmlspecialchars($recipe['text_receptu'])."
            </div>
            <div class='card-footer'>
                <div class='recipe-action'>
                <button class='edit-recipe'>
                    <img src='../images/icons/edit.svg' alt='edit-icon'>
                </button>
                <form action='myRecipes.php' method='post'>
                    <input type='hidden' name='delete_id' value='" . htmlspecialchars($recipe['id']) . "'>
                    <button class='remove-button' type='submit'>
                        <img src='../images/icons/delete.svg' alt='delete-icon'>
                    </button>
                </form>
            </div>
            </div>
        </div>
    </div>
    ";
}

function dashboardEditRecipeCard($recipe){
    return "
    <div class='card'>
        <input type='hidden' name='id-of-card' id='id-of-card' value='" . htmlspecialchars($recipe['id']) . "'>
        <div class='card-header'>
            <h2>".htmlspecialchars($recipe['title'])."</h2>
        </div>
        <div class='image-placeholder'>
            <img src='../images/img/".htmlspecialchars($recipe['image'])."' alt='".htmlspecialchars($recipe['title'])."'>
        </div>
        <div class='card-cotn'>
            <div class='card-content'>
                ".htmlspecialchars($recipe['text_receptu'])."
            </div>
            <div class='card-footer'>
                <div class='recipe-action'>
                <button class='edit-recipe'>
                    <img src='../images/icons/edit.svg' alt='edit-icon'>
                </button>
                <form action='dashboard.php' method='post'>
                    <input type='hidden' name='delete_id' value='" . htmlspecialchars($recipe['id']) . "'>
                    <button class='remove-button' type='submit'>
                        <img src='../images/icons/delete.svg' alt='delete-icon'>
                    </button>
                </form>
            </div>
            </div>
        </div>
    </div>
    ";
}

function userCard($user){
    return "
    <div class='dashboard-user'>
        <h2>".htmlspecialchars($user['jmeno'])."</h2>
        <p>".htmlspecialchars($user['email'])."</p>
        <p>Admin: ".htmlspecialchars($user['admin'])."</p>
        <div class='profile-action'>
            ".($user['admin'] !== "True" ? "
                <form action='dashboard.php' method='post'>
                    <input type='hidden' name='update-user-id' value='".htmlspecialchars($user['id'])."'>
                    <button class='edit-role' type='submit' name='update-user-as-admin'>
                        <img src='../images/icons/set-admin.svg' alt='set-admin-icon'>
                    </button>
                </form>
                <form action='dashboard.php' method='post'>
                    <input type='hidden' name='delete-user-id' value='".htmlspecialchars($user['id'])."'>
                    <button class='remove-button' type='submit' name='delete-user-dashboard-btn'>
                        <img src='../images/icons/delete.svg' alt='delete-icon'>
                    </button>
                </form>
            " : "")."
        </div>
    </div>
    ";
}
    
?>