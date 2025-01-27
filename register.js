document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match. Please try again.');
        } else {
            // alert('Registration successful!');
            // Perform the registration process here (e.g., send data to the server)
        }
    });
});
