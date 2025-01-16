<?php
session_start();
include('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $grade = $_POST['grade']; // Retrieve the selected grade

    // Check if the email is already in use
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        echo "Email is already in use. Please choose a different email.";
    } else {
        // Insert the new user with the grade
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, grade) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $grade]);

        // Log the user in
        $user_id = $pdo->lastInsertId();
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username; 

        // Redirect to home page
        header("Location: home.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    <form method="POST" action="create_account.php">
        <div class="input-field">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="input-field">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="input-field">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="input-field">
            <label for="grade">Grade:</label>
            <select name="grade" id="grade" required>
                <option value="" disabled selected>Select Grade</option>
                <option value="Grade 7">Grade 7</option>
                <option value="Grade 8">Grade 8</option>
                <option value="Grade 9">Grade 9</option>
                <option value="Grade 10">Grade 10</option>
            </select>
        </div>
        <button type="submit">Create Account</button>
    </form>
</body>
</html>
