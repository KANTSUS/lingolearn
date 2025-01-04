function showCreateAccount() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('create-account-form').style.display = 'block';
    document.getElementById('form-title').innerText = "Create Account";
}

function showForgotPassword() {
    document.getElementById('login-form').style.display = 'none';
    document.getElementById('forgot-password-form').style.display = 'block';
    document.getElementById('form-title').innerText = "Forgot Password";
}

function showLogin() {
    document.getElementById('login-form').style.display = 'block';
    document.getElementById('create-account-form').style.display = 'none';
    document.getElementById('forgot-password-form').style.display = 'none';
    document.getElementById('form-title').innerText = "Log In";
}

document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;

    if (path.endsWith('login.php')) {
        document.getElementById('login-button').addEventListener('click', () => {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            username && password
                ? (window.location.href = 'Home.php')
                : showError('Please enter both username and password.');
        });
    } else if (path.endsWith('Home.php')) {
        document.getElementById('logout-button').addEventListener('click', () => {
            window.location.href = 'login.php';
        });
    }

    function showError(message) {
        const error = document.getElementById('error-message');
        error.textContent = message;
        error.classList.remove('hidden');
    }
});

