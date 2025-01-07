<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="feature3.css">
</head>
<body>
    <div class="sidebar">
        <h2>LingoLearn</h2>
        <ul>
            <li><button id="go-to-feature1">Feature1</button></li>
            <li><button id="go-to-feature2">Feature2</button></li>
            <li><button id="go-to-feature3">AI Essay Feedback</button></li>
        </ul>
        <button id="logout-button" onclick="logout()">Logout</button>
    </div>

    <div class="main-content">
        <header>
            <h1 class="welcome-text">Welcome Back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <nav>
                <a href="home.php" id="home-link">Home</a>
                <a href="about.php" id="about-link">About</a>
                <a href="contact.php" id="contact-link">Contact</a>
            </nav>
        </header>
    </div>
    <div class="quizzes-section">
    <h2>AI Essay Feedback</h2>
    <div class="container">
        <textarea id="essayInput" placeholder="Paste your essay here..."></textarea>
        <button id="submitButton">Check Essay</button>
        <div id="feedbackSection" class="feedback">
            <h2>Feedback</h2>
            <p id="feedbackText">Your feedback will appear here.</p>
        </div>
    </div>

    <script>
        document.getElementById('submitButton').addEventListener('click', function() {
            const essay = document.getElementById('essayInput').value.trim();
            const feedbackSection = document.getElementById('feedbackText');
            feedbackSection.innerHTML = ""; // Clear previous feedback

            if (!essay) {
                feedbackSection.textContent = "Please enter some text to analyze.";
                return;
            }

            // Split essay into sentences
            const sentences = essay.split(/[.!?]/).filter(sentence => sentence.trim().length > 0);

            sentences.forEach((sentence, index) => {
                const trimmedSentence = sentence.trim();
                const score = rateSentence(trimmedSentence);

                // Create feedback for each sentence
                const sentenceDiv = document.createElement("div");
                sentenceDiv.className = "sentence";

                const sentenceText = document.createElement("p");
                sentenceText.textContent = `Sentence ${index + 1}: "${trimmedSentence}"`;
                sentenceDiv.appendChild(sentenceText);

                const stars = document.createElement("p");
                stars.className = "stars";
                stars.innerHTML = "★".repeat(score) + "☆".repeat(5 - score);
                sentenceDiv.appendChild(stars);

                feedbackSection.appendChild(sentenceDiv);
            });
        });

        // Function to rate a sentence
        function rateSentence(sentence) {
            const wordCount = sentence.split(/\s+/).filter(word => word.length > 0).length;

            // Example rating criteria:
            // 1 star: < 5 words
            // 2 stars: 5-10 words
            // 3 stars: 11-15 words
            // 4 stars: 16-20 words
            // 5 stars: > 20 words
            if (wordCount < 5) return 1;
            if (wordCount <= 10) return 2;
            if (wordCount <= 15) return 3;
            if (wordCount <= 20) return 4;
            return 5;
        }
        function logout() {
            window.location.href = 'logout.php';
        }

        document.getElementById('go-to-feature1').addEventListener('click', function() {
        window.location.href = 'feature1.php';
    });

    document.getElementById('go-to-feature2').addEventListener('click', function() {
        window.location.href = 'feature2.php';
    });

    document.getElementById('go-to-feature3').addEventListener('click', function() {
        window.location.href = 'feature3.php';
    });

    function goToHome() {
        window.location.href = 'home.php';
    }
    </script>
</body>
</html>