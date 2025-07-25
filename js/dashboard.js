//choose buttons
const userTableBtn = document.querySelector(".users");
const recipesTableBtn = document.querySelector(".recipes");

//load users and recipes
const userTable = document.querySelector(".user-table");
const recipeTable = document.querySelector(".recipe-table");

//modals
const addRecipeModal = document.querySelector(".add-recipe-modal");
const editRecipeModal = document.querySelector(".edit-recipe-modal");

const openEditRecipe = document.querySelectorAll(".edit-recipe");
const openAddRecipeModal = document.querySelector(".add-recipe-btn");

const closeAddRecipeModal = document.querySelector(".close-add-recipe-modal");
const closeRecipeModal = document.querySelector(".close-edit-recipe-modal");

const shadow = document.querySelector(".shadow");

const idInput = document.getElementById("edit-id");
const titleInput = document.getElementById('edit-title');
const descriptionInput = document.getElementById('edit-description');

openEditRecipe.forEach(button => {
    button.addEventListener("click", (event) => {
        editRecipeModal.style.display = "block";
        shadow.style.display = "block";
        const card = button.closest('.card');
        const recipeId = card.querySelector('#id-of-card').value;
        const title = card.querySelector('.card-header h2').textContent;
        const description = card.querySelector('.card-content').textContent.trim();

        idInput.value = recipeId; 
        titleInput.value = title;
        descriptionInput.value = description;
    });
});

userTableBtn.addEventListener("click", () => {
    userTable.style.display = "block";
    recipeTable.style.display = "none";
});

recipesTableBtn.addEventListener("click", () => {
    userTable.style.display = "none";
    recipeTable.style.display = "flex";
});

openAddRecipeModal.addEventListener("click", () => {
    addRecipeModal.style.display = "block";
    shadow.style.display = "block";
});

closeAddRecipeModal.addEventListener("click", () => {
    addRecipeModal.style.display = "none";
    shadow.style.display = "none";
});

closeRecipeModal.addEventListener("click", () => {
    editRecipeModal.style.display = "none";
    shadow.style.display = "none";
});


const removeRecord = document.querySelectorAll(".remove-button");

removeRecord.forEach(button => {
    button.addEventListener("click", (event) => {
        console.log(event.currentTarget.parentNode.parentNode);
    });
});


const file1 = document.getElementById("image");
const file2 = document.getElementById("edit-image");

const fileName1 = document.querySelector(".image-file-name");
const fileName2 = document.querySelector(".image-file-name-2");


file1.addEventListener("change", () => {
    const name = file1.files[0].name;
    if (name) {
        fileName1.innerHTML = `Selected file: ${name}`;
    }else{
        fileName1.innerHTML = `Selected file: None`;
    }
});

file2.addEventListener("change", () => {
    const name = file2.files[0].name;
    if (name) {
        fileName2.innerHTML = `Selected file: ${name}`;
    }else{
        fileName2.innerHTML = `Selected file: None`;
    }
});