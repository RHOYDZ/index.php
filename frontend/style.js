// Toggle Hamburger Menu
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('show');
});

// Toggle User Dropdown
const userDropdown = document.getElementById('userDropdown');
const userDropdownMenu = document.getElementById('userDropdownMenu');

if (userDropdown) {
    userDropdown.addEventListener('click', (e) => {
        e.preventDefault();
        userDropdownMenu.classList.toggle('show');
    });
}

// Toggle Certificates Dropdown
const certDropdown = document.getElementById('certDropdown');
const certDropdownMenu = document.getElementById('certDropdownMenu');

certDropdown.addEventListener('click', (e) => {
    e.preventDefault();
    certDropdownMenu.classList.toggle('show');
});





//log in js start
// Get the elements for login and create account form
const loginForm = document.getElementById('login-form');
const createAccountForm = document.getElementById('create-account-form');
const signUpLink = document.getElementById('sign-up-link');
const loginLink = document.getElementById('login-link');
const formContainer = document.getElementById('form-container');

// Switch to Create Account form when Sign Up link is clicked
signUpLink.addEventListener('click', () => {
    loginForm.style.display = 'none';
    createAccountForm.style.display = 'block';
    formContainer.style.transform = 'translateX(-100%)';  // Slide to the left
});

// Switch back to Login form when Login link is clicked
loginLink.addEventListener('click', () => {
    createAccountForm.style.display = 'none';
    loginForm.style.display = 'block';
    formContainer.style.transform = 'translateX(0)';  // Slide back to the right
});



//log in js end



//passowrd see create
// Toggle Password Visibility for Create Account Form
document.getElementById('toggle-password').addEventListener('click', function() {
    const passwordField = document.getElementById('password');
    if (passwordField.type === "password") {
        passwordField.type = "text";
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash'); // Change to eye-slash
    } else {
        passwordField.type = "password";
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye'); // Change back to eye
    }
});

// Toggle Confirm Password Visibility
document.getElementById('toggle-confirm-password').addEventListener('click', function() {
    const confirmPasswordField = document.getElementById('confirm-password');
    if (confirmPasswordField.type === "password") {
        confirmPasswordField.type = "text";
        this.classList.remove('fa-eye');
        this.classList.add('fa-eye-slash'); // Change to eye-slash
    } else {
        confirmPasswordField.type = "password";
        this.classList.remove('fa-eye-slash');
        this.classList.add('fa-eye'); // Change back to eye
    }
});


// JavaScript to simulate the loading spinner behavior
window.addEventListener('load', function () {
    // Hide the spinner once the page is fully loaded
    const spinner = document.getElementById('spinner');
    spinner.style.display = 'none';  // Hide the spinner when the page has loaded
});


