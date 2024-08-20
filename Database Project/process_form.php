<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "SpaManufacturers";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

$sql = "SELECT * FROM Models 
        JOIN Brands ON Models.brand_id = Brands.brand_id
        JOIN Manufacturers ON Brands.manufacturer_code = Manufacturers.manufacturer_code
        WHERE 1=1";

if ($manufacturer) {
    $sql .= " AND Manufacturers.manufacturer_code = $manufacturer";
}
if ($brand) {
    $sql .= " AND Brands.brand_id = $brand";
}
if ($model) {
    $sql .= " AND Models.model_id = $model";
}
if ($jets) {
    $sql .= " AND Models.number_of_jets >= $jets";
}
if ($motors) {
    $sql .= " AND Models.number_of_motors >= $motors";
}
if ($horsepower) {
    $sql .= " AND Models.horsepower_per_motor >= $horsepower";
}
if ($price) {
    $sql .= " AND Models.suggested_retail_price <= $price";
}
if ($weight) {
    $sql .= " AND Models.dry_weight <= $weight";
}
if ($capacity) {
    $sql .= " AND Models.water_capacity >= $capacity";
}
if ($seating) {
    $sql .= " AND Models.seating_capacity >= $seating";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Filtered Results</h2><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>Model: " . $row['model_number'] . ", Brand: " . $row['brand_name'] . ", Manufacturer: " . $row['company_name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}

$conn->close();
?>
