<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user orders
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders_result = $stmt->get_result();
$orders = [];

while ($order = $orders_result->fetch_assoc()) {
    $order_id = $order['order_id'];
    $stmt_items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
    $items = [];

    while ($item = $items_result->fetch_assoc()) {
        // Fetch product details for each item
        $stmt_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt_product->bind_param("i", $item['product_id']);
        $stmt_product->execute();
        $product = $stmt_product->get_result()->fetch_assoc();
        $stmt_product->close();

        // Check if product data was retrieved successfully
        if ($product) {
            $items[] = [
                'product_name' => $product['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'] ?? $product['price'], // Use price from order_items or fall back to product price
            ];
        } else {
            // Handle the case where the product does not exist
            $items[] = [
                'product_name' => 'Product no longer available',
                'quantity' => $item['quantity'],
                'price' => 0, // Set price to 0 or handle it accordingly
            ];
        }
    }

    $orders[$order_id] = [
        'details' => $order,
        'items' => $items,
    ];

    $stmt_items->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="orders.css">
</head>
<body>
    <div class="top-nav">
        <h1>My Orders</h1>
    </div>

    <div class="orders-container">
        <?php foreach ($orders as $order_id => $order): ?>
            <div class="order-item">
                <div class="item-info">
                    <div>
                        <strong>Order ID:</strong> <?php echo $order_id; ?><br>
                        <strong>Date:</strong> <?php echo $order['details']['created_at']; ?><br>
                        <strong>Total:</strong> ₹<?php echo number_format($order['details']['total_amount'], 2); ?><br>
                        <strong>Address:</strong> <?php echo htmlspecialchars($order['details']['address']); ?>
                    </div>
                    <ul>
                        <?php foreach ($order['items'] as $item): ?>
                            <li class="item-name">
                                <?php echo htmlspecialchars($item['product_name']); ?> - <?php echo $item['quantity']; ?>N
                                <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="item-actions">
                    <form method="POST" action="return.php">
                        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                        <button type="submit" class="return-btn">Return/Cancel Order</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
