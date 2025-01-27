<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
            exit;
        }
        // Check if the email already exists
        $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();
        if ($checkEmail->num_rows > 0) {
            // Email already exists
            echo "<script>alert('Email ID already exists.'); window.history.back();</script>";
            $checkEmail->close();
            exit;
        }
        $checkEmail->close();
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'index.html';
                  </script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
        $conn->close();
    } else {
        echo "<script>alert('Form data is missing.'); window.history.back();</script>";
    }
}
?>
