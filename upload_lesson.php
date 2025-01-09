<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['username'];

// Connect to the database (update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted for question addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['questionText']) && isset($_POST['questionType'])) {
    $questions = $_POST['questionText'];
    $questionType = $_POST['questionType'];
    $choices = isset($_POST['choices']) ? $_POST['choices'] : [];
    $correctAnswers = isset($_POST['correctAnswer']) ? $_POST['correctAnswer'] : [];

    foreach ($questions as $index => $questionText) {
        $question_type = $questionType[$index];

        if ($question_type == "multiple_choice") {
            // Convert choices array to a comma-separated string
            $choicesList = implode(", ", $choices[$index]);
            $correctAnswer = $correctAnswers[$index];  // Correct answer choice

            // Insert multiple-choice question into the database
            $stmt = $conn->prepare("INSERT INTO questions (question_text, question_type, choices, correct_answer) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $questionText, $question_type, $choicesList, $correctAnswer);
        } elseif ($question_type == "fill_in_the_blank") {
            // Insert fill-in-the-blank question into the database
            $stmt = $conn->prepare("INSERT INTO questions (question_text, question_type) VALUES (?, ?)");
            $stmt->bind_param("ss", $questionText, $question_type);
        }

        // Execute statement and check for errors
        if ($stmt->execute()) {
            echo "<div class='success'>Question added successfully!</div>";
        } else {
            echo "<div class='error'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Lesson and Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        input, select, textarea {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .btn {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #4cae4c;
        }
        .question-container {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Lesson and Questions</h1>
        
        <h2>Add Questions</h2>
        <form action="" method="post">
            <div id="questionsSection">
                <div class="question-container">
                    <label for="questionType">Question Type:</label>
                    <select name="questionType[]" class="questionType" onchange="showFields(this)" required>
                        <option value="">Choose...</option>
                        <option value="fill_in_the_blank">Fill in the Blank</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>

                    <div class="fill-in-the-blank" style="display: none;">
                        <label for="questionText">Question:</label>
                        <input type="text" name="questionText[]">
                    </div>

                    <div class="multiple-choice" style="display: none;">
                        <label for="questionText">Question:</label>
                        <input type="text" name="questionText[]">

                        <label for="choices">Choices:</label>
                        <input type="text" name="choices[][0]" placeholder="Choice A">
                        <input type="text" name="choices[][1]" placeholder="Choice B">
                        <input type="text" name="choices[][2]" placeholder="Choice C">
                        <input type="text" name="choices[][3]" placeholder="Choice D">

                        <label for="correctAnswer">Correct Answer:</label>
                        <input type="text" name="correctAnswer[]" placeholder="Enter correct answer (a, b, c, d)">
                    </div>
                </div>
            </div>
            <button type="button" class="btn" onclick="addQuestion()">Add Another Question</button>
            <button type="submit" class="btn">Submit Questions</button>
        </form>
    </div>
    <a href="home.php" class="back-button">Back to Homepage</a>
    <script>
        function showFields(select) {
            const container = select.closest('.question-container');
            const fillInTheBlank = container.querySelector('.fill-in-the-blank');
            const multipleChoice = container.querySelector('.multiple-choice');

            fillInTheBlank.style.display = select.value === 'fill_in_the_blank' ? 'block' : 'none';
            multipleChoice.style.display = select.value === 'multiple_choice' ? 'block' : 'none';
        }
        
        function addQuestion() {
            const questionSection = document.getElementById("questionsSection");
            const newQuestion = document.createElement("div");
            newQuestion.classList.add("question-container");
            newQuestion.innerHTML = questionSection.innerHTML;
            questionSection.appendChild(newQuestion);
        }
    </script>
</body>
</html>
