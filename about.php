

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <div class="about-us-container">
        <div class="content">
            <h1>About Us</h1>
            <p>Welcome to LingoLearn, where language learning meets innovation! Our mission is to empower individuals from all walks of life to master new languages through a personalized, engaging, and immersive experience.</p>
            
            <div class="navigation">
                <button onclick="navigateToPage('home')">Home</button>
                <button onclick="navigateToPage('about')">About</button>
                <button onclick="navigateToPage('contact')">Contact</button>
            </div>
        </div>
    </div>
    
    <script src="about.js"></script>
    <script>
        function navigateToPage(page) {
            if (page === 'home') {
                window.location.href = 'home.php';
            } else if (page === 'about') {
                window.location.href = 'about.php';
            } else if (page === 'contact') {
                window.location.href = 'contact.php';
            }
        }
    </script>
</body>
</html>