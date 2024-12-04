<?php
session_start();
$host = 'localhost';
$db = 'lingolearn';
$user = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

// Handle Account Creation
if (isset($_POST['create_account'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        $error_message = "Account created successfully!";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LingoLearn</title> 

    <link rel="stylesheet" href="login.css">
</head>
<body>

    <div class="container">
        <h1 class="main-heading">LINGOLEARN</h1>
        <p class="subheading">Speak Freely, Learn Effortlessly</p>
        <p class="description">
            A website that offers an intuitive platform for personalized language learning, 
            featuring AI-powered tools that enhance skills and facilitate immersive conversations.
        </p>
    </div>

    <section class="login-form">
        <h1 id="form-title" class="form-title">Log In</h1>

        <!-- Display error messages -->
        <?php if (!empty($error_message)): ?>
            <p id="error-message" class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" id="login-form">
            <div class="input-field">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="options">
                <label>
                    <input type="checkbox"> Keep me logged-in
                </label>
                <a href="#" class="forgot-password" onclick="showForgotPassword()">Forgot password?</a>
            </div>
            <button type="submit" name="login" class="login-button">Log In</button>
            <a href="#" class="create-account" onclick="showCreateAccount()">Create Account</a>
        </form>

        <form method="POST" id="create-account-form" style="display: none;">
            <div class="input-field">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-field">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-field">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="create_account" class="create-button">Create Account</button>
            <a href="#" class="back-to-login" onclick="showLogin()">Back to Login</a>
        </form>

        <div id="forgot-password-form" style="display: none;">
            <div class="input-field">
                <input type="email" id="forgot-email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="reset-button">Reset Password</button>
            <a href="#" class="back-to-login" onclick="showLogin()">Back to Login</a>
        </div>
    </section>
    <script src="login.js"></script>

</body>
</html>
