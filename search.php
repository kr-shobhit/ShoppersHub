<?php
session_start();

if (!isset($_SESSION['user_name'])) {
  header("Location: login.php");
  exit();
}

$user_name = $_SESSION['user_name'];

require_once 'config.php';

if (isset($_GET['query'])) {
    $searchQuery = $_GET['query'];

    $sql = "SELECT id, name, image, price FROM products WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<h2>Search Results for "' . htmlspecialchars($searchQuery) . '"</h2>';
    echo '<div class="product-cards-container">';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            $name = $row['name'];
            $image = $row['image'];
            $price = $row['price'];

            echo '<div class="product-card">';
            echo '  <div class="product-info">';
            echo '    <img src="' . $image . '" alt="' . $name . '"/>';
            echo '    <h4 class="product-name" title="' . $name . '">' . $name . '</h4>';
            echo '    <span class="product-price">₹' . $price . '</span>';
            echo '    <span class="product-rating">';
            echo '      <i class="fa fa-star"></i>';
            echo '      <i class="fa fa-star"></i>';
            echo '      <i class="fa fa-star"></i>';
            echo '      <i class="fa fa-star"></i>';
            echo '      <i class="fa fa-star"></i>';
            echo '    </span>';
            echo '  </div>';
            echo '  <div class="product-buttons">';
            echo '    <form method="POST" action="shoppingcart.php">';
            echo '      <input type="hidden" name="product_id" value="' . $id . '">';
            echo '      <button type="submit" class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Add to Cart</button>';
            echo '    </form>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No products found matching "' . htmlspecialchars($searchQuery) . '".</p>';
    }

    echo '</div>';

    $stmt->close();
} else {
    echo '<p>Please enter a search query.</p>';
}

$conn->close();
echo'<a href="homepage.php" class="back-to-home">Back to Home</a>'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="search.css" />
</head>
<body>
    
</body>
</html>
