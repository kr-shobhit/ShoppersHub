<?php
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    header("Location: index.php"); // Redirect to homepage if accessed directly
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>shoppershub.com</title>

  <!-- CSS -->
  <link rel="stylesheet" href="thankyou.css" />

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
        <h1>Order Placed</h1>
    </div>
    <div class="confirmation-container">
        <h2>Thank You for Your Order!</h2>
        <p>Your order has been confirmed.</p>
        <p>Order ID: <strong><?php echo htmlspecialchars($order_id); ?></strong></p>
        <a href="homepage.php">Return to Homepage</a> or <a href="orders.php">View Your Orders</a>
    </div>
</body>
</html>

