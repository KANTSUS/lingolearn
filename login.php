<?php
session_start();
$host = 'localhost';
$db = 'lingolearn';
$user = 'root';
$password = ''; 

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

// Create Account
if (isset($_POST['create_account'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'Student';  // Only Student role
    $prefix = null;
    $grade = $_POST['grade'];  // Grade is required for Student

    // Use prepared statement to insert data
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, grade) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $username, $email, $password, $role, $grade);
    
    if ($stmt->execute()) {
        $error_message = "Account created successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the login is for admin
    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'Admin';

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Use prepared statement to fetch user data for non-admin users
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];
                $_SESSION['grade'] = $row['grade'];  // Store grade in session if it exists

                // Redirect to home page
                header("Location: home.php");  
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }

        $stmt->close();
    }
}

// Guest Login
if (isset($_POST['guest_login'])) {
    $_SESSION['username'] = "Guest_" . rand(1000, 9999); 
    header("Location: home.php");
    exit();
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
        <button type="submit" name="guest_login" class="guest-login-button">Guest Login</button>
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
        <div class="input-field">
            <label for="grade">Grade:</label>
            <select name="grade" id="grade" required>
                <option value="" disabled selected>Select grade</option>
                <option value="7">Grade 7</option>
                <option value="8">Grade 8</option>
                <option value="9">Grade 9</option>
                <option value="10">Grade 10</option>
            </select>
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

    <script>
        // No need to toggle role fields anymore
    </script>
    <script src="login.js"></script>
</body>
</html>
