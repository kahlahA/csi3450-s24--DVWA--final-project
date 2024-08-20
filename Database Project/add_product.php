<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.html');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root"; // Replace with your actual DB username
$password = ""; // Replace with your actual DB password
$dbname = "SpaManufacturers";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Capture all the form data
$manufacturer = $_POST['manufacturer'];
$brand = $_POST['brand'];
$model = $_POST['model'];
$jets = $_POST['jets'];
$motors = $_POST['motors'];
$horsepower = $_POST['horsepower'];
$price = $_POST['price'];
$weight = $_POST['weight'];
$capacity = $_POST['capacity'];
$seating = $_POST['seating'];

// Step 2: Insert new manufacturer if it doesn't exist
$manufacturer_id = null;
$sql = "SELECT manufacturer_code FROM Manufacturers WHERE company_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $manufacturer);
$stmt->execute();
$stmt->bind_result($manufacturer_id);
$stmt->fetch();
$stmt->close();

if (!$manufacturer_id) {
    $sql = "INSERT INTO Manufacturers (company_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $manufacturer);
    if ($stmt->execute()) {
        $manufacturer_id = $stmt->insert_id;
        echo "Manufacturer added with ID: " . $manufacturer_id . "<br>";
    } else {
        die("Error adding manufacturer: " . $stmt->error);
    }
    $stmt->close();
} else {
    echo "Manufacturer found with ID: " . $manufacturer_id . "<br>";
}

// Step 3: Insert new brand if it doesn't exist
$brand_id = null;
$sql = "SELECT brand_id FROM Brands WHERE brand_name = ? AND manufacturer_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $brand, $manufacturer_id);
$stmt->execute();
$stmt->bind_result($brand_id);
$stmt->fetch();
$stmt->close();

if (!$brand_id) {
    $sql = "INSERT INTO Brands (brand_name, manufacturer_code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $brand, $manufacturer_id);
    if ($stmt->execute()) {
        $brand_id = $stmt->insert_id;
        echo "Brand added with ID: " . $brand_id . "<br>";
    } else {
        die("Error adding brand: " . $stmt->error);
    }
    $stmt->close();
} else {
    echo "Brand found with ID: " . $brand_id . "<br>";
}

// Step 4: Insert the new model
$sql = "INSERT INTO Models (brand_id, model_number, number_of_jets, number_of_motors, horsepower_per_motor, suggested_retail_price, dry_weight, water_capacity, seating_capacity) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isiiidddd", $brand_id, $model, $jets, $motors, $horsepower, $price, $weight, $capacity, $seating);

if ($stmt->execute()) {
    echo "New product added successfully";
} else {
    echo "Error adding product: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
