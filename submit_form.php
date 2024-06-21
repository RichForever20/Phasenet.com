<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "phasenet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $organizationName = $_POST['organizationName'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $problemDescription = $_POST['problemDescription'];
    $awareOfSolution = $_POST['awareOfSolution'];
    $existingProductDetails = $_POST['existingProductDetails'];
    $requestNda = isset($_POST['requestNda']) ? 1 : 0;

    // Handle file upload
    $ndaFilePath = NULL;
    if (!$requestNda && isset($_FILES['ndaFile']) && $_FILES['ndaFile']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/nda_files/";
        $ndaFilePath = $targetDir . basename($_FILES["ndaFile"]["name"]);
        move_uploaded_file($_FILES["ndaFile"]["tmp_name"], $ndaFilePath);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO submissions (first_name, last_name, organization_name, country, email, problem_description, aware_of_solution, existing_product_details, nda_file_path, request_nda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $firstName, $lastName, $organizationName, $country, $email, $problemDescription, $awareOfSolution, $existingProductDetails, $ndaFilePath, $requestNda);

    if ($stmt->execute()) {
        echo "We appreciate your interest in joining us as a partner. A representative from our team will reach out to you soon.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>


<!-- database start -->
CREATE DATABASE phasenet;

USE phasenet;

CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    organization_name VARCHAR(100),
    country VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    problem_description TEXT NOT NULL,
    aware_of_solution ENUM('yes', 'no') NOT NULL,
    existing_product_details TEXT,
    nda_file_path VARCHAR(255),
    request_nda TINYINT(1) NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<!-- database end -->