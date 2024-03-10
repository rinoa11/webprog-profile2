<?php
require_once "config.php"; // Make sure this is the correct path to your config.php file

session_start(); // Start session to access session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        $message = $_POST['message'];

        try {
            // Retrieve the user ID from the session
            $user_id = $_SESSION['id'];

            // First, let's find the email based on the user ID
            $user_stmt = $link->prepare("SELECT email FROM users WHERE id = ?");
            $user_stmt->bind_param("i", $user_id);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();

            if ($user_result->num_rows > 0) {
                // If user exists, fetch the email
                $user = $user_result->fetch_assoc();
                $email = $user['email'];

                // Prepare the INSERT statement to insert into leaderboard
                $stmt = $link->prepare("INSERT INTO messages (user_id, email, message) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $user_id, $email, $message);
                $stmt->execute();
                
                // Redirect the user to home page
                header("Location: home.html");
                exit();
            } else {
                // Handle case where the email for the provided user ID does not exist
                echo "Error: email for the provided user ID does not exist.";
            }
        } catch (Exception $e) {
            echo "Error:" . $e->getMessage();
        }
    }
}
?>