<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch cart products from session
$cart_products = [];
$total_amount = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id) {
        $product_id = (int)$product_id; // Ensure $product_id is an integer
        $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
    
        if ($product && isset($product['price'])) {
            $product['quantity'] = 1; // Default quantity
            $cart_products[] = $product;
            $total_amount += $product['price'] * 1; // Default quantity
        } else {
            echo "Product not found or price missing: ID " . htmlspecialchars($product_id);
            exit();
        }
    }
} else {
    echo "Your cart is empty.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['payment_checkbox'])) {
        $address = $_POST['address'];

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, address) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $user_id, $total_amount, $address);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert ordered products into order_items table
        foreach ($cart_products as $product) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $order_id, $product['id'], $product['quantity']);
            $stmt->execute();
            $stmt->close();

            // Update product quantity in products table
            $stmt = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
            $stmt->bind_param("ii", $product['quantity'], $product['id']);
            $stmt->execute();
            $stmt->close();
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to thank you page
        header("Location: thankyou.php?order_id=" . $order_id);
        exit();
    } else {
        $error_message = "Please confirm that you have made the payment.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>shoppershub.com</title>

  <!-- CSS -->
  <link rel="stylesheet" href="checkout.css" />

  <!-- ICONS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />

  <!-- FAVICON -->
  <link rel="shortcut icon" href="resources/shoppershubcom-favicon-black.png" type="image/x-icon" />
</head>
<body>
    <div class="top-nav">
        <h2>Place Your Order</h2>
    </div>

    <div class="checkout-container">
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form class="checkout-form" method="POST" action="checkout.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="address">Address</label>
            <textarea id="address" name="address" rows="4" required></textarea>

            <input type="checkbox" id="payment_checkbox" name="payment_checkbox" required>
            <label for="payment_checkbox">I confirm that I have made the payment.</label>

            <button type="submit">Place My Order</button>
        </form>
    </div>
</body>
</html>
