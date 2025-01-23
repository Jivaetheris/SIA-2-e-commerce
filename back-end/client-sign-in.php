<?php
session_start();  // Start the session to keep track of logged-in user

// Database connection
$host = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'shoppepatos';
$connection = new mysqli($host, $dbusername, $dbpassword, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST['email'];
    $user_password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($user_password)) {
        echo "Both email and password are required!";
        exit;
    }

    // Prepare SQL query to check if email exists in the database
    $stmt = $connection->prepare("SELECT id, firstName, password FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);  // 's' means the email is a string
    $stmt->execute();
    $stmt->store_result();

    // If email exists, fetch user data
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $firstName, $hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($user_password, $hashedPassword)) {
            // Correct password, start session and redirect to the dashboard or home page
            $_SESSION['user_id'] = $userId;  // Store user id in session
            $_SESSION['first_name'] = $firstName;  // Store first name in session
            header("Location: ../html/p-dashboard.php");  // Redirect to user dashboard (change as needed)
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No account found with that email!";
    }

    $stmt->close();
}

// Close the database connection
$connection->close();
?>
