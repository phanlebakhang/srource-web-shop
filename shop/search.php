<?php
include('config.php');

// Lấy từ query string
$query = $_GET['query'] ?? '';

$sql = "SELECT * FROM products WHERE name LIKE '%$query%' OR category LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>";
        echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>" . $row['category'] . "</p>";
        echo "<p>$" . $row['price'] . "</p>";
        echo "</div>";
    }
} else {
    echo "No results found";
}

$conn->close();
?>
