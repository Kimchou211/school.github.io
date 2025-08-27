<?php
require_once 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $dob = $_POST['dob'];
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $course = $_POST['course'];
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate required fields
    if (empty($fullname) || empty($dob) || empty($phone) || empty($email) || empty($address) || empty($course)) {
        echo json_encode(['success' => false, 'message' => 'សូមបំពេញព័ត៌មានចាំបាច់ទាំងអស់។']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'អាស័យដ្ឋានអ៊ីមែលមិនត្រឹមត្រូវ។']);
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => false, 'message' => 'អាស័យដ្ឋានអ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ។']);
            exit;
        }

        // Insert new student
        $stmt = $pdo->prepare("INSERT INTO students (fullname, dob, phone, email, address, course, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt->execute([$fullname, $dob, $phone, $email, $address, $course, $message])) {
            echo json_encode(['success' => true, 'message' => 'ការចុះឈ្មោះរបស់អ្នកជោគជ័យ! យើងនឹងទាក់ទងអ្នកឆាប់ៗនេះ។']);
        } else {
            echo json_encode(['success' => false, 'message' => 'កំហុសក្នុងការចុះឈ្មោះ។ សូមព្យាយាមម្តងទៀត។']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'កំហុសប្រព័ន្ធ: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'សំណើមិនត្រឹមត្រូវ។']);
}
?>