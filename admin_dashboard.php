<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    // Redirect to home page if not an admin
    header("Location: home.php");
    exit();
}

$host = 'localhost';
$db = 'lingolearn';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin exists, if not, create it
$admin_username = 'admin';
$admin_email = 'admin@lingolearn.com';
$admin_password = password_hash('admin123', PASSWORD_BCRYPT);
$admin_role = 'Admin';

// Check if the admin account already exists
$admin_check_query = "SELECT * FROM users WHERE username = ? AND role = ?";
$stmt = $conn->prepare($admin_check_query);
$stmt->bind_param("ss", $admin_username, $admin_role);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // If admin does not exist, create a new admin account
    $insert_admin_query = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_admin_query);
    $stmt_insert->bind_param("ssss", $admin_username, $admin_email, $admin_password, $admin_role);

    if ($stmt_insert->execute()) {
        echo "Admin account created successfully.";
    } else {
        echo "Error creating admin account: " . $stmt_insert->error;
    }
    $stmt_insert->close();
} else {
    echo "Admin account already exists.";
}

$stmt->close();

// Fetch all users from the database
$sql = "SELECT id, username, email, role, grade FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        h1 {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        p {
            text-align: center;
            font-size: 18px;
            margin-top: 10px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #333;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .container {
            text-align: center;
            margin: 20px 0;
        }
        .form-container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container label {
            margin: 10px 0 5px;
            font-weight: bold;
        }
        .form-container input, .form-container select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>

</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welcome, Admin!</p>
    <a href="login.php">Back to Login Page</a>

    <!-- Create Teacher Form -->
    <div class="form-container">
        <h2>Create Teacher Account</h2>
        <form action="create_teacher.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <input type="hidden" name="role" value="Teacher">

            <button type="submit">Create Account</button>
        </form>
    </div>

    <!-- User List -->
    <h2>User List</h2>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Grade</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['grade']; ?></td>
            <td>
                <!-- Delete User Form -->
                <form action="delete_user.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
