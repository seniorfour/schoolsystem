// main.js - base JS for School Management System

document.addEventListener('DOMContentLoaded', function() {
    // Example: dismiss error messages
    document.querySelectorAll('.error, .success').forEach(function(msg) {
        msg.addEventListener('click', function() {
            msg.style.display = 'none';
        });
    });
});