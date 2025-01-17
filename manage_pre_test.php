<?php
require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        // Get file details
        $file_name = $_FILES['file_upload']['name'];
        $file_tmp = $_FILES['file_upload']['tmp_name'];
        $file_size = $_FILES['file_upload']['size'];
        $file_type = $_FILES['file_upload']['type'];

        // Define a target directory to store the uploaded file
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file_name);

        // Check if the file already exists
        if (file_exists($target_file)) {
            echo "<div class='error'>Sorry, file already exists.</div>";
        } elseif ($file_size > 5000000) { // Limit file size to 5MB
            echo "<div class='error'>Sorry, your file is too large.</div>";
        } else {
            // Try to upload the file
            if (move_uploaded_file($file_tmp, $target_file)) {
                echo "<div class='success'>The file " . basename($file_name) . " has been uploaded.</div>";
            } else {
                echo "<div class='error'>Sorry, there was an error uploading your file.</div>";
            }
        }
    }

    // Insert multiple questions if provided
    if (isset($_POST['questions']) && is_array($_POST['questions'])) {
        foreach ($_POST['questions'] as $question_data) {
            $question = $question_data['question_text'];
            $correct_answer = $question_data['correct_answer'];
            $options = $question_data['options'];

            // Insert question into database
            $stmt = $pdo->prepare("
                INSERT INTO pre_test_questions (question_text, correct_answer, option_a, option_b, option_c, option_d, grade_level)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $question, 
                $correct_answer,
                $options['a'],
                $options['b'],
                $options['c'],
                $options['d'],
                $_POST['grade_level']
            ]);

            // Fetch the last inserted question's ID
            $pre_test_id = $pdo->lastInsertId();

            // Check if students are selected and assign them
            if (isset($_POST['students']) && is_array($_POST['students'])) {
                foreach ($_POST['students'] as $student_username) {
                    $stmt = $pdo->prepare("
                        INSERT INTO pre_test_assignments (student_username, pre_test_id)
                        VALUES (?, ?)
                    ");
                    $stmt->execute([$student_username, $pre_test_id]);
                }
            }
        }
        echo "<div class='success'>Questions and assignments saved successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pre-Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background: #45a049;
        }
        .success {
            color: green;
            margin-top: 10px;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Manage Pre-Test Questions</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="grade_level">Grade Level:</label>
        <input type="number" name="grade_level" required><br>

        <label for="file_upload">Upload File:</label>
        <input type="file" name="file_upload" accept=".txt,.doc,.pdf,.jpg,.png"><br><br>

        <label for="students">Assign Students:</label>
        <select name="students[]" multiple required>
            <?php
            // Fetch students from the database
            $stmt = $pdo->query("SELECT username FROM students");
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($students as $student) {
                echo "<option value='" . htmlspecialchars($student['username']) . "'>" . htmlspecialchars($student['username']) . "</option>";
            }
            ?>
        </select><br><br>

        <div id="questions-container">
            <div class="question">
                <label for="question_text">Question Text:</label>
                <input type="text" name="questions[0][question_text]" required><br>

                <label for="correct_answer">Correct Answer:</label>
                <input type="text" name="questions[0][correct_answer]" required><br>
                
                <label for="options">Options (A, B, C, D):</label><br>
                <input type="text" name="questions[0][options][a]" placeholder="Option A" required><br>
                <input type="text" name="questions[0][options][b]" placeholder="Option B" required><br>
                <input type="text" name="questions[0][options][c]" placeholder="Option C" required><br>
                <input type="text" name="questions[0][options][d]" placeholder="Option D" required><br><br>
            </div>
        </div>

        <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
        <button type="submit">Save Questions</button>
    </form>

    <script>
        let questionCount = 1;

        function addQuestion() {
            const container = document.getElementById('questions-container');
            const newQuestion = document.createElement('div');
            newQuestion.classList.add('question');
            newQuestion.innerHTML = `
                <label for="question_text">Question Text:</label>
                <input type="text" name="questions[${questionCount}][question_text]" required><br>

                <label for="correct_answer">Correct Answer:</label>
                <input type="text" name="questions[${questionCount}][correct_answer]" required><br>
                
                <label for="options">Options (A, B, C, D):</label><br>
                <input type="text" name="questions[${questionCount}][options][a]" placeholder="Option A" required><br>
                <input type="text" name="questions[${questionCount}][options][b]" placeholder="Option B" required><br>
                <input type="text" name="questions[${questionCount}][options][c]" placeholder="Option C" required><br>
                <input type="text" name="questions[${questionCount}][options][d]" placeholder="Option D" required><br><br>
            `;
            container.appendChild(newQuestion);
            questionCount++;
        }
    </script>
</body>
</html>
