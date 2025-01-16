<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $host = 'localhost';
    $db = 'lingolearn';
    $user = 'root'; 
    $db_password = ''; 

    $conn = new mysqli($host, $user, $db_password, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Account Created</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f9;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .popup {
                    background-color: #fff;
                    padding: 20px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                    text-align: center;
                    border-radius: 8px;
                    width: 300px;
                }
                .popup h1 {
                    margin-bottom: 15px;
                    color: #333;
                }
                .popup p {
                    margin-bottom: 20px;
                }
                .popup button {
                    padding: 10px 20px;
                    font-size: 16px;
                    background-color: #007bff;
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
                .popup button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class='popup'>
                <h1>Success!</h1>
                <p>Teacher account has been created successfully.</p>
                <button onclick='redirectToLogin()'>Go to Login</button>
            </div>
            <script>
                function redirectToLogin() {
                    window.location.href = 'login.php';
                }
                // Automatically redirect after 5 seconds
                setTimeout(redirectToLogin, 5000);
            </script>
        </body>
        </html>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
