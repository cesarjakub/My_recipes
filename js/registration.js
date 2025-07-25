const password1 = document.getElementById("password");
const password2 = document.getElementById("conf-password");
const username = document.getElementById("name");
const email = document.getElementById("email");

const error = document.querySelector(".alert");

const form = document.querySelector(".register-form");

function validatePassword(event){
    validatePasswordMatch(event);
}

function validatePasswordMatch(event){
    if(validatePasswordCheckForWhiteSpace() && validateName() && validateEmail()){
        if(password1.value == password2.value){
            error.style.display = "none";
        }else{
            error.innerHTML = "<p>Passwords do not match!</p>";
            error.style.display = "block";
            password1.value = "";
            password2.value = "";
            event.preventDefault();
        }
    }else{
        event.preventDefault();
    }
}

function validatePasswordCheckForWhiteSpace(){
    const regex = /\s+/;
    result = regex.test(password1.value);
    if(result){
        error.innerHTML = "<p>Password can't contain white spaces!</p>";
        error.style.display = "block";
        password1.value = "";
        password2.value = "";
        return false;
    }else{
        error.style.display = "none";
        return true;
    }
}

function validateName() {
    const nameRegex = /^[a-zA-Zá-žÁ-Ž\s]+$/;
    result = nameRegex.test(username.value);
    if(result){
        return true;
    }else{
        error.innerHTML = "<p>Only letters and spaces allowed in name!</p>";
        error.style.display = "block";
        password1.value = "";
        password2.value = "";
        return false;
    }
}

function validateEmail() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    result = emailRegex.test(email.value);
    if (result){
        return true;
    }else{
        error.innerHTML = "<p>Please enter a valid email address!</p>";
        error.style.display = "block";
        password1.value = "";
        password2.value = "";
        return false;
    }
}

form.addEventListener("submit", validatePassword);