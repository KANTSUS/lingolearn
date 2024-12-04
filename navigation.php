<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<nav class="navigation">
    <button onclick="navigateTo('home.php')">Home</button>
    <button onclick="navigateTo('about.php')">About</button>
    <button onclick="navigateTo('contact.php')">Contact</button>
    <button onclick="logout()">Logout</button>
</nav>

<script>
    function navigateTo(page) {
        window.location.href = page;
    }
    function logout() {
        window.location.href = 'logout.php';
    }
</script>