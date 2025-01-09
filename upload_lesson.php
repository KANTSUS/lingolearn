<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$display_name = $_SESSION['username'];

// Ensure teacher is logged in and retrieve display name with prefix
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Teacher') {
    $display_name = $_SESSION['prefix'] . ' ' . $display_name;
}

// Connect to the database (update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lingolearn";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted for file upload or question addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade = $_POST['grade'];
    $lesson_type = $_POST['lessonType'];

    // Process file upload with validation
    if (isset($_FILES['lessonFile'])) {
        $lessonFile = $_FILES['lessonFile'];
        $fileName = $lessonFile['name'];
        $fileTmpName = $lessonFile['tmp_name'];
        $fileSize = $lessonFile['size'];
        $fileError = $lessonFile['error'];

        // Validate file type (only allow PDF and DOCX for now)
        $allowedExtensions = ['pdf', 'docx'];
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            echo "<div class='error'>Invalid file type. Only PDF and DOCX files are allowed.</div>";
        } elseif ($fileSize > 5000000) { // 5MB limit
            echo "<div class='error'>File is too large. Maximum allowed size is 5MB.</div>";
        } else {
            if ($fileError === 0) {
                $fileDestination = 'uploads/' . $fileName;
                move_uploaded_file($fileTmpName, $fileDestination);

                // Insert file upload info into the database using prepared statement
                $stmt = $conn->prepare("INSERT INTO lessons (grade, lesson_type, file_name) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $grade, $lesson_type, $fileName);
                if ($stmt->execute()) {
                    echo "<div class='success'>Lesson file uploaded successfully!</div>";
                } else {
                    echo "<div class='error'>Error: " . $conn->error . "</div>";
                }
                $stmt->close();
            } else {
                echo "<div class='error'>There was an error uploading the file.</div>";
            }
        }
    }

    // Process text entry for questions with prepared statements
    if (isset($_POST['questionText']) && isset($_POST['questionType'])) {
        $questions = $_POST['questionText'];
        $questionType = $_POST['questionType'];
        $choices = isset($_POST['choices']) ? $_POST['choices'] : [];
        $correctAnswers = isset($_POST['correctAnswer']) ? $_POST['correctAnswer'] : [];

        foreach ($questions as $index => $questionText) {
            if ($questionType[$index] == "multiple_choice") {
                $choicesList = implode(", ", $choices[$index]);
                $correctAnswer = $correctAnswers[$index];

                // Insert multiple choice question into database using prepared statements
                $stmt = $conn->prepare("INSERT INTO questions (question_text, question_type, grade, lesson_type, choices, correct_answer) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssis", $questionText, $questionType[$index], $grade, $lesson_type, $choicesList, $correctAnswer);
                if ($stmt->execute()) {
                    echo "<div class='success'>Multiple choice question added successfully!</div>";
                } else {
                    echo "<div class='error'>Error: " . $conn->error . "</div>";
                }
            } else if ($questionType[$index] == "fill_in_the_blank") {
                $stmt = $conn->prepare("INSERT INTO questions (question_text, question_type, grade, lesson_type) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssis", $questionText, $questionType[$index], $grade, $lesson_type);
                if ($stmt->execute()) {
                    echo "<div class='success'>Fill in the blank question added successfully!</div>";
                } else {
                    echo "<div class='error'>Error: " . $conn->error . "</div>";
                }
            }
        }
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
        
        <h2>Upload Lesson</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="lessonFile">Upload Lesson File (PDF, DOCX):</label>
            <input type="file" name="lessonFile" id="lessonFile" required>
            
            <label for="lessonType">Select Lesson Type:</label>
            <select name="lessonType" id="lessonType" required>
                <option value="basics">Basics</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
            </select>
            
            <label for="grade">Grade Level:</label>
            <input type="number" name="grade" id="grade" min="1" max="12" required>
            
            <button type="submit" class="btn">Upload Lesson</button>
        </form>

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

    <script>
        function showFields(select) {
            const container = select.closest('.question-container');
            const fillInTheBlank = container.querySelector('.fill-in-the-blank');
            const multipleChoice = container.querySelector('.multiple-choice');

            fillInTheBlank.style.display = select.value === 'fill_in_the_blank' ? 'block' : 'none';
            multipleChoice.style.display = select.value === 'multiple_choice' ? 'block' : 'none';
        }

        function addQuestion() {
            const questionsSection = document.getElementById('questionsSection');
            const newQuestion = document.querySelector('.question-container').cloneNode(true);

            // Reset values in the cloned question
            newQuestion.querySelectorAll('input').forEach(input => input.value = '');
            newQuestion.querySelectorAll('select').forEach(select => select.value = '');
            newQuestion.querySelector('.fill-in-the-blank').style.display = 'none';
            newQuestion.querySelector('.multiple-choice').style.display = 'none';

            questionsSection.appendChild(newQuestion);
        }
    </script>
</body>
</html>
