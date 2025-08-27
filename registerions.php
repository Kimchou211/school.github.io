<?php
$host = "localhost"; // usually localhost
$user = "root";      // your MySQL username
$pass = "";          // your MySQL password
$db   = "teach_computer_school";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Receive form values
$fullname = $_POST['fullname'];
$dob      = $_POST['dob'];
$phone    = $_POST['phone'];
$email    = $_POST['email'];
$address  = $_POST['address'];
$course   = $_POST['course'];
$message  = $_POST['message'];

// Insert into DB
$sql = "INSERT INTO students (fullname, dob, phone, email, address, course, message)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $fullname, $dob, $phone, $email, $address, $course, $message);

if ($stmt->execute()) {
    echo "<h2>✅ Registration successful!</h2>";
    echo "<a href='index.html'>Go Back</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
