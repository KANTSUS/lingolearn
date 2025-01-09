<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['username'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
$submitted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    // Store answers in the database
    foreach ($_POST as $question_id => $answer) {
        // Avoid storing form field names like "question_1", so we check if it's a valid answer field
        if (strpos($question_id, 'question_') === 0) {
            $question_id = str_replace('question_', '', $question_id);
            $stmt = $conn->prepare("INSERT INTO answers (student_username, question_id, answer) VALUES (?, ?, ?)");
            $stmt->bind_param("sis", $_SESSION['username'], $question_id, $answer);
            $stmt->execute();
        }
    }
}

// Fetch all questions from the database
$sql = "SELECT * FROM questions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check if any questions are fetched
if ($result->num_rows === 0) {
    echo "<p>No questions found in the database.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .question-container {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        .question-container p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .question-container label {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .question-container input[type="text"],
        .question-container input[type="radio"] {
            margin-right: 10px;
        }

        .question-container input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .question-container input[type="radio"] {
            margin-right: 10px;
        }

        .btn {
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin: 20px auto;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #4cae4c;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .back-button:hover {
            text-decoration: underline;
        }

        .question-container .multiple-choice {
            margin-bottom: 10px;
        }

        /* Popup Styling */
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            text-align: center;
            z-index: 9999;
        }

        .popup .popup-content {
            margin-bottom: 20px;
        }

        .popup .btn-close {
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .popup .btn-close:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Answer the Questions</h1>
    
    <?php if ($submitted): ?>
        <div class="popup" id="popup">
            <div class="popup-content">
                <h2>Submitted Successfully!</h2>
                <p>Your answers have been submitted.</p>
            </div>
            <button class="btn-close" onclick="closePopup()">Close</button>
        </div>
    <?php endif; ?>

    <form action="view_questions.php" method="post" <?php echo $submitted ? 'style="display:none;"' : ''; ?>>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="question-container">
                <p><strong>Question: </strong><?php echo $row['question_text']; ?></p>
                
                <?php if ($row['question_type'] == 'multiple_choice') {
                    $choices = explode(",", $row['choices']);
                    $letters = ['a', 'b', 'c', 'd']; 
                    foreach ($choices as $index => $choice) { ?>
                        <div class="multiple-choice">
                            <label>
                                <input type="radio" name="question_<?php echo $row['id']; ?>" value="<?php echo $letters[$index]; ?>">
                                <?php echo $letters[$index] . '. ' . htmlspecialchars($choice); ?>
                            </label>
                        </div>
                    <?php }
                } else { ?>
                    <label for="answer">Your Answer:</label>
                    <input type="text" name="question_<?php echo $row['id']; ?>" placeholder="Enter your answer here">
                <?php } ?>
            </div>
        <?php } ?>
        <button type="submit" class="btn">Submit Answers</button>
    </form>

    <a href="home.php" class="back-button">Back to Homepage</a>
</div>

<script>
    <?php if ($submitted): ?>
        window.onload = function() {
            document.getElementById('popup').style.display = 'block';
        };

        function closePopup() {
            window.location.href = 'home.php'; // Redirect to home page after closing popup
        }
    <?php endif; ?>
</script>

</body>
</html>
