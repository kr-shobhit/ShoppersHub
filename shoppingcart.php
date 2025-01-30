<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}
if (isset($_SESSION['cart'])) {
    $cart_products = [];
    foreach ($_SESSION['cart'] as $id) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_products[] = $result->fetch_assoc();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_product_id'])) {
    $remove_id = $_POST['remove_product_id'];
    if (isset($_SESSION['cart'])) {
        if (($key = array_search($remove_id, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
        }
    }
    header("Location: shoppingcart.php");
    exit();
}
if (isset($_SESSION['cart'])) {
    $cart_products = [];
    foreach ($_SESSION['cart'] as $id) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_products[] = $result->fetch_assoc();
    }
}
?>
<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <!-- FAVICON -->
    <link rel="shortcut icon" href="resources/shoppershubcom-favicon-black.png" type="image/x-icon" />
    <link rel="stylesheet" href="shoppingcart.css">
    <!-- JS -->
    <script src="shoppingcart.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <h2>Shopping Cart</h2>
    </nav>
    <!-- Parent Div -->
    <div class="cart-container">
        <!-- Left Side Div -->
        <div class="cart-left">
            <!-- Upper Div for Headings -->
            <div class="cart-headings">
                <div class="heading-product">Product Name</div>
                <div class="heading-price">Price</div>
                <div class="heading-quantity">Quantity</div>
            </div>
            <!-- Lower Div for Products -->
            <div class="cart-items">
                <?php
                if (!empty($cart_products)) {
                    foreach ($cart_products as $product) {
                        echo '<div class="cart-item">';
                        echo '<div class="item-name">' . $product['name'] . '</div>';
                        echo '<div class="item-price">₹' . $product['price'] . '</div>';
                        echo '<div class="item-quantity">';
                        echo '<button class="quantity-btn decrease">-</button>';
                        echo '<span class="quantity">1</span>';
                        echo '<button class="quantity-btn increase">+</button>';
                        echo '</div>';
                        echo '<form method="POST" action="shoppingcart.php">';
                        echo '<input type="hidden" name="remove_product_id" value="' . $product['id'] . '">';
                        echo '<button type="submit" class="remove-btn">Remove</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Your cart is empty.</p>';
                }
                ?>
            </div>
        </div>
        <!-- Right Side Div -->
        <div class="cart-right">
            <!-- Upper Div for Price Details -->
            <div class="price-details">
                <?php
                $subtotal = 0;
                foreach ($cart_products as $product) {
                    $subtotal += $product['price'];
                }
                $tax = $subtotal * 0.10; // 10% tax
                $delivery_charges = 0; // Free delivery
                $total = $subtotal + $tax + $delivery_charges;

                echo '<div class="price-item">';
                echo '<span class="label">Subtotal:</span>';
                echo '<span class="amount">₹' . number_format($subtotal, 2) . '</span>';
                echo '</div>';
                echo '<div class="price-item">';
                echo '<span class="label">Delivery Charges:</span>';
                echo '<span class="amount free" style="color:#5dc172; font-weight:bold;">FREE</span>';
                echo '</div>';
                echo '<div class="price-item">';
                echo '<span class="label">Tax:</span>';
                echo '<span class="amount">₹' . number_format($tax, 2) . '</span>';
                echo '</div>';
                echo '<div class="price-item total">';
                echo '<h3 class="label">Total:</h3>';
                echo '<h3 class="amount">₹' . number_format($total, 2) . '</h3>';
                echo '</div>';
                ?>
            </div>
            <!-- Lower Div for Proceed Button -->
            <div class="proceed">
                <form action="checkout.php" method="POST">
                    <button type="submit">Proceed To Checkout <i class="fa-solid fa-arrow-right"></i></button>
                </form>
                <button onclick="location.href='homepage.php'" class="back-to-home">Back to Homepage <i class="fa-solid fa-arrow-left"></i></button>
            </div>
            </div>
        </div>
    </div>
</body>
</html>