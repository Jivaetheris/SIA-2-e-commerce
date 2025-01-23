<?php 
$host = 'localhost';
$dbusername = 'root';
$dbpassword = '';
$dbname = 'shoppepatos';

// Create connection
$connection = new mysqli($host, $dbusername, $dbpassword);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Create database if it does not exist
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!$connection->query($createDatabaseQuery)) {
    die("Error creating database: " . $connection->error);
}

// Select database
$connection->select_db($dbname);

// Create the registration table if it does not exist
$createRegistrationTableQuery = "
CREATE TABLE IF NOT EXISTS registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100),
    lastName VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    gender VARCHAR(10),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$connection->query($createRegistrationTableQuery)) {
    die("Error creating table 'registration': " . $connection->error);
}


// Get input from form
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

// Validate inputs (optional, but recommended)
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($address) || empty($gender) || empty($_POST['password'])) {
    die("All fields are required!");
}

// Prepare and bind the statement to insert user data
$stmt = $connection->prepare("INSERT INTO registration (firstName, lastName, email, phone, address, gender, password) 
VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $address, $gender, $password);

// Execute the statement and check for success
if ($stmt->execute()) {
    header("Location:../html/reg-success.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$connection->close();
?>
