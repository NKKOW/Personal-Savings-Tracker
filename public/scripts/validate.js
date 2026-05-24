const form = document.querySelector("form");
const emailInput = form?.querySelector('input[name="email"]');
const passwordInput = form?.querySelector('input[name="password"]');
const confirmedPasswordInput = form?.querySelector('input[name="password_repeat"]');

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}

function markValidation(element, condition) {
    if (!condition) {
        element.classList.add("invalid");
    } else {
        element.classList.remove("invalid");
    }
}

function validateEmail() {
    setTimeout(function () {
        if (emailInput) {
            markValidation(emailInput, isEmail(emailInput.value));
        }
    }, 1000);
}

function validatePassword() {
    setTimeout(function () {
        if (passwordInput && confirmedPasswordInput) {
            const condition = arePasswordsSame(
                passwordInput.value,
                confirmedPasswordInput.value
            );
            markValidation(confirmedPasswordInput, condition);
        }
    }, 1000);
}

if (emailInput) {
    emailInput.addEventListener("keyup", validateEmail);
}

if (confirmedPasswordInput) {
    confirmedPasswordInput.addEventListener("keyup", validatePassword);
}