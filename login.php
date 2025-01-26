<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and bind
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['username'];

                // Redirect to homepage.html
                header("Location: homepage.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that email.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Please provide both email and password.";
    }
}
?>
