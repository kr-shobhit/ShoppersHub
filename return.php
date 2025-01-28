<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Fetch product details before deleting to restore quantities
    $stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Restore product quantities
    while ($item = $result->fetch_assoc()) {
        $stmt_update = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        $stmt_update->bind_param("ii", $item['quantity'], $item['product_id']);
        $stmt_update->execute();
        $stmt_update->close();
    }

    $stmt->close();

    // Delete the order items
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Optionally, delete the order record itself if no items remain
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the orders page
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return/Cancel Order</title>

    <!-- CSS -->
    <link rel="stylesheet" href="return.css">

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
        <h2>Return/Cancel Order</h2>
    </div>

    <div class="return-container">
        <h3>Are you sure you want to return/cancel this order?</h3>
        <form method="POST" action="return.php">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($_POST['order_id']); ?>">
            <label for="payment_checkbox">I confirm that I have received the payment back.</label>
            <input type="checkbox" id="payment_checkbox" name="payment_checkbox" required>
            <button type="submit">Confirm Return</button>
        </form>
    </div>
</body>
</html>
