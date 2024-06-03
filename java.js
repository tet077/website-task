document.addEventListener('DOMContentLoaded', function() {
    var icon = document.getElementById("icon");

    
    function toggleDarkMode() {
        document.body.classList.toggle('dark-theme');
        const isDarkMode = document.body.classList.contains('dark-theme');
        localStorage.setItem('dark-mode', isDarkMode);
       
        if (isDarkMode) {
            icon.src = "sun.png";
        } else {
            icon.src = "moon.png";
        }
    }

    icon.addEventListener('click', toggleDarkMode);

    
    const isDarkModeSaved = localStorage.getItem('dark-mode');
    if (isDarkModeSaved && isDarkModeSaved === 'true') {
        toggleDarkMode();
    }
});

function myMenuFunction() {
    var i = document.getElementById("navMenu");

    if (i.className === "nav-menu") {
        i.className += " responsive";
    } else {
        i.className = "nav-menu";
    }
}

var loginBtn = document.getElementById("loginBtn");
var registerBtn = document.getElementById("registerBtn");
var loginForm = document.getElementById("login");
var registerForm = document.getElementById("register");

loginBtn.addEventListener("click", function() {
    loginForm.style.left = "4px";
    registerForm.style.right = "-520px";
});

registerBtn.addEventListener("click", function() {
    loginForm.style.left = "-510px";
    registerForm.style.right = "5px";
});





