<?php
header("Content-Type: application/json");

// Database connection
$host = 'localhost';
$db = 'login_system';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Helper function to hash passwords
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Helper function to verify passwords
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

// Signup endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUp'])) {
    $username = $_POST['fName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    // Hash the password
    $hashedPassword = hashPassword($password);

    // Insert user into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(['message' => 'User registered successfully']);
    } else {
        echo json_encode(['error' => 'Failed to register user']);
    }

    $stmt->close();
    exit;
}

// Login endpoint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Email and password are required']);
        exit;
    }

    // Find user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $user = $result->fetch_assoc();

    // Verify password
    if (!verifyPassword($password, $user['password'])) {
        echo json_encode(['error' => 'Invalid credentials']);
        exit;
    }

    // Start a session and store user data
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    echo json_encode(['message' => 'Login successful']);
    exit;
}

// Close the database connection
$conn->close();
?>