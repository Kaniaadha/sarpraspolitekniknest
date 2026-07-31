// ======================================================
// AUTO FOCUS
// ======================================================

document.addEventListener("DOMContentLoaded", () => {

    const username = document.getElementById("username");

    if (username) {
        username.focus();
    }

});

// ======================================================
// SHOW / HIDE PASSWORD
// ======================================================

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

if (togglePassword && passwordInput) {

    togglePassword.addEventListener("click", () => {

        const icon = togglePassword.querySelector("i");

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");

        } else {

            passwordInput.type = "password";

            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");

        }

    });

}

// ======================================================
// LOGIN LOADING
// ======================================================

const loginForm = document.getElementById("loginForm");
const loginButton = document.getElementById("loginButton");

if (loginForm && loginButton) {

    loginForm.addEventListener("submit", () => {

        loginButton.disabled = true;

        loginButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Memproses...
        `;

    });

}