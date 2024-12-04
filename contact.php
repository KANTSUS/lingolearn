
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <link rel="stylesheet" href="contact.css">
</head>
<body>
    
  <section class="contact-us">
    <h1>Contact Us</h1>
    <div class="contact-container">
     
      <div class="contact-info">
        <h2>Contact Information</h2>
        <ul>
          <li><i class="fas fa-phone"></i> +1 012 3456 789</li>
          <li><i class="fas fa-envelope"></i> demo@gmail.com</li>
          <li><i class="fas fa-map-marker-alt"></i> 2600</li>
        </ul>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      
      <div class="contact-form">
        <form>
          <div class="form-group">
            <input type="text" placeholder="First Name" required>
            <input type="text" placeholder="Last Name" required>
          </div>
          <input type="email" placeholder="Email" required>
          <input type="tel" placeholder="Phone Number" required>
          <div class="form-group">
            <label>Select Subject:</label>
            <div class="radio-group">
              <label><input type="radio" name="subject" value="General Inquiry" checked> General Inquiry</label>
              <label><input type="radio" name="subject" value="Support"> Support</label>
              <label><input type="radio" name="subject" value="Feedback"> Feedback</label>
            </div>
          </div>
          <textarea placeholder="Write your message..." rows="5"></textarea>
          <button type="submit">Send Message</button>
        </form>
      </div>
    </div>
  </section>
  
  <div class="navigation">
    
    <button onclick="goToHome()">Home</button>
    <button onclick="goToAbout()">About</button>
    <button onclick="goToContact()">Contact</button>
    </div>



  
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="contact.js"></script>
</body>
</html>