//edit modal 
const updateProfileModal = document.querySelector(".edit-profile-card-modal");
const addRecipeModal = document.querySelector(".add-recipe-modal");

const openProfileModal = document.querySelector(".edit-btn");
const openAddRecipeModal = document.querySelector(".add-recipe-btn");

const closeProfileModal = document.querySelector(".close-profile-modal");
const closeAddRecipeModal = document.querySelector(".close-add-recipe-modal");

const shadow = document.querySelector(".shadow");

openProfileModal.addEventListener("click", () => {
    updateProfileModal.style.display = "block";
    shadow.style.display = "block";
});

openAddRecipeModal.addEventListener("click", () => {
    addRecipeModal.style.display = "block";
    shadow.style.display = "block";
});

closeProfileModal.addEventListener("click", () => {
    updateProfileModal.style.display = "none";
    shadow.style.display = "none";
});

closeAddRecipeModal.addEventListener("click", () => {
    addRecipeModal.style.display = "none";
    shadow.style.display = "none";
});


const file = document.getElementById("image");
const fileName = document.querySelector(".image-file-name");

file.addEventListener("change", () => {
    const name = file.files[0].name;
    if (name) {
        fileName.innerHTML = `Selected file: ${name}`;
    }else{
        fileName.innerHTML = `Selected file: None`;
    }
});

//form validation
const username = document.getElementById("name");
const email = document.getElementById("email");

const error = document.querySelector(".alert");

const form = document.querySelector(".edit-profile-form");

function validate(event){
    validateName(event);
    validateEmail(event);
}

function validateName(event) {
    const nameRegex = /^[a-zA-Zá-žÁ-Ž\s]+$/;
    result = nameRegex.test(username.value);
    if(result){
        return
    }else{
        error.innerHTML = "<p>Only letters and spaces allowed in name!</p>";
        error.style.display = "block";
        event.preventDefault();
    }
}

function validateEmail(event) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    result = emailRegex.test(email.value);
    if (result){
        return
    }else{
        error.innerHTML = "<p>Please enter a valid email address!</p>";
        error.style.display = "block";
        event.preventDefault();
    }
}

form.addEventListener("submit", validate);