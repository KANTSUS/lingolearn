document.addEventListener('DOMContentLoaded', function() {
    
    const homeLink = document.getElementById('home-link');
    const aboutLink = document.getElementById('about-link');
    const contactLink = document.getElementById('contact-link');
    
    
    homeLink.addEventListener('click', function(event) {
        event.preventDefault(); 
        window.location.href = 'home.php'; 
    });

    aboutLink.addEventListener('click', function(event) {
        event.preventDefault(); 
        window.location.href = 'about.php'; 
    });

    contactLink.addEventListener('click', function(event) {
        event.preventDefault(); 
        window.location.href = 'contact.php'; 
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const logoutButton = document.getElementById('logout-button');
    
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            alert('Logging out...');
            window.location.href = 'login.php'; 
        });
    }
});
