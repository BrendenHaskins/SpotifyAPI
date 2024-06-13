//on load, run these
window.addEventListener("DOMContentLoaded", (event) => {
    const username_field = document.getElementById("username");
    const username_error = document.getElementById("usernameError");
    const password_field = document.getElementById("password");
    const password_error = document.getElementById("passwordError");
    const password2_field = document.getElementById("password2");
    const password_error2 = document.getElementById("passwordError2");
    const submit_button = document.getElementById("submitButton");
    let usernameValid = false;
    let passwordValid = false;
    let password2Valid = false;

    //username validation (3-100 characters)
    username_field.addEventListener('keyup', function() {
        if (username_field.value.trim().length >= 3 && username_field.value.trim().length < 100) {
            username_error.style.visibility = "hidden";
            username_error.innerHTML = "";
            usernameValid = true;
        } else {
            username_error.style.visibility = "visible";
            username_error.innerHTML = "Invalid username.<br> Make sure above 3 characters and under 100<br>";
            usernameValid = false;
        }
        verifyForm(submit_button, usernameValid, passwordValid, password2_field, password2Valid);
    });

    //password validation (regexp of 1 number, 1 a-Z character, 8-16 characters)
    password_field.addEventListener('keyup', function() {
        let passwordRegex = /^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/;
        if (passwordRegex.test(password_field.value.trim())) {
            password_error.style.visibility = "hidden";
            password_error.innerHTML = "";
            passwordValid = true;
        } else {
            password_error.style.visibility = "visible";
            password_error.innerHTML = "Invalid password. <br>" +
                "Includes at least 1 number and 1 a-Z character.<br>" +
                "Make sure above 8 characters and under 16.<br><br>";
            passwordValid = false;
        }

        //checks if element 2 exists before trying to check
        if (typeof(password2_field) != 'undefined' && password2_field != null) {
            password2Valid = testPassword(password_field, password2_field, password_error2);
        }
        verifyForm(submit_button, usernameValid, passwordValid, password2_field, password2Valid);
    });

    //checks if element 2 exists before trying to add listener
    if (typeof(password2_field) != 'undefined' && password2_field != null) {
        password2_field.addEventListener('keyup', function() {
            password2Valid = testPassword(password_field, password2_field, password_error2);
            verifyForm(submit_button, usernameValid, passwordValid, password2_field, password2Valid);
        });
    }
});

/**
 * Tests password 2 to see if it matches password 1
 *
 * @param password_field first field
 * @param password2_field second field
 * @param password_error2 error field
 * @return boolean
 */
function testPassword(password_field, password2_field, password_error2) {
    if (password_field.value.trim() === password2_field.value.trim()) {
        password_error2.style.visibility = "hidden";
        password_error2.innerHTML = "";
        return true;
    } else {
        password_error2.style.visibility = "visible";
        password_error2.innerHTML = "Passwords do not match.<br><br>";
        return false;
    }
}

/**
 * Checks if form is valid
 *
 * @param submit_button button
 * @param usernameValid true or false value
 * @param passwordValid true or false value
 * @param password2_field check if real
 * @param password2Valid true or false value
 */
function verifyForm(submit_button, usernameValid, passwordValid, password2_field, password2Valid) {
    if (usernameValid === true && passwordValid === true) {
        //check if it's submit form or login form
        if (typeof(password2_field) != 'undefined' && password2_field != null) {
            if (password2Valid === true) {
                submit_button.classList.remove("disabled");
            } else {
                submit_button.classList.add("disabled");
            }
        }
        submit_button.classList.remove("disabled");
    } else {
        submit_button.classList.add("disabled");
    }
}