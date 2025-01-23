<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  // Redirect to login page if not logged in
  header("Location: login.php");
  exit();
}

$user_name = $_SESSION['user_name'];


require_once 'config.php';

function displayProducts($conn, $category_id, $section_title)
{
  $sql = "SELECT id , name, image, price FROM products WHERE category_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $category_id);
  $stmt->execute();
  $result = $stmt->get_result();

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
    echo '<p>No products available in this category.</p>';
  }

  echo '</div>';

  $stmt->close();
}

?>

<!-- HTML THING -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>shoppershub.com</title>

  <!-- CSS -->
  <link rel="stylesheet" href="homepage.css" />

  <!-- ICONS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- FONTS -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />

  <!-- FAVICON -->
  <link rel="shortcut icon" href="resources/shoppershubcom-favicon-black.png" type="image/x-icon" />

  <!-- JS -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
    var searchButton = document.getElementById("searchButton");
    var searchQuery = document.getElementById("searchQuery");

    searchButton.addEventListener("click", function() {
        var query = searchQuery.value;
        if (query) {
            window.location.href = "search.php?query=" + encodeURIComponent(query);
        }
    });

    searchQuery.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); 
            searchButton.click(); 
        }
    });
});

  </script>

</head>

<body>
  <!-- NAV BAR -->

  <div class="top-nav">
    <img src="resources/shoppershubcom-high-resolution-logo-black-transparent.png" alt="logo" />
    <div class="top-nav-searchbox">
      <input type="text" id="searchQuery" placeholder="Search Products" />
      <button id="searchButton"><i class="fas fa-search"></i></button>
    </div>

    <div class="user-greeting">
      Welcome,
      <?php echo htmlspecialchars($user_name); ?>
      !
    </div>

    <div class="top-nav-links">
      <a href="orders.php" style="color:inherit; text-decoration:none;">
        <h4>Orders</h4>
      </a>
      <a href="shoppingcart.php" style="color:inherit; text-decoration:none;">
        <h4>Shopping Cart</h4>
      </a>
    </div>
  </div>

  <!-- PRODUCT CATEGORY  -->

  <div class="categories-section">
    <!-- Category -->
    <a href="#product-section-dotd">
      <div class="category">
        <img src="resources/1.png" alt="Electronics" />
        <h5>Deals of the Day</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-mobiles">
      <div class="category">
        <img src="resources/3.png" alt="Home" />
        <h5>Mobiles and Tablet</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-headphones">
      <div class="category">
        <img src="resources/4.png" alt="Kitchen" />
        <h5>Headphones and Earbuds</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-smartwearables">
      <div class="category">
        <img src="resources/5.png" alt="Electronics" />
        <h5>Smart Wearables</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-homeappliance">
      <div class="category">
        <img src="resources/2.png" alt="Mobiles" />
        <h5>Home Appliances</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-speakers">
      <div class="category">
        <img src="resources/7.png" alt="Home" />
        <h5>Speakers and Audio</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-makeup">
      <div class="category">
        <img src="resources/6.png" alt="Mobiles" />
        <h5>Makeup and Skin Care</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-homedecorations">
      <div class="category">
        <img src="resources/8.png" alt="Kitchen" />
        <h5>Home Decorations</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-furniture">
      <div class="category">
        <img src="resources/11.png" alt="Kitchen" />
        <h5>Furnitures and Tools</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-kitchen">
      <div class="category">
        <img src="resources/10.png" alt="Kitchen" />
        <h5>Kitchen Utensils</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-books">
      <div class="category">
        <img src="resources/9.png" alt="Kitchen" />
        <h5>Books and Notebooks</h5>
      </div>
    </a>

    <!-- Category -->

    <a href="#product-section-stationary">
      <div class="category">
        <img src="resources/12.png" alt="Kitchen" />
        <h5>Stationary and Crafts</h5>
      </div>
    </a>

    <!-- Category -->
    <a href="#product-section-music">
      <div class="category">
        <img src="resources/13.png" alt="Kitchen" />
        <h5>Musical Equipments</h5>
      </div>
    </a>
  </div>

  <!-- Products Section - DOTD -->

  <div id="product-section-dotd" class="product-section">
    <h3 id="section-title" class="section-title">
      DEALS OF THE DAY
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 1, 'Deals of the Day'); ?>

  </div>
  <!-- Product Section End -->

  <!-- Products Section - MOBILES -->

  <div id="product-section-mobiles" class="product-section">
    <h3 id="section-title" class="section-title">
      MOBILES AND TABLETS
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 2, 'Mobiles and Tablet'); ?>

  </div>
  <!-- Product Section End -->

  <!-- Products Section - HEADPHONES -->

  <div id="product-section-headphones" class="product-section">
    <h3 id="section-title" class="section-title">
      HEADPHONES AND EARPHONES
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 3, 'Headphones'); ?>

  </div>
  <!-- Product Section End -->

  <!-- Products Section - SMART WEARABLES -->

  <div id="product-section-smartwearables" class="product-section">
    <h3 id="section-title" class="section-title">
      SMARTWATCHES
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 4, 'Smart Wearables'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - HOME APPLIANCE -->

  <div id="product-section-homeappliance" class="product-section">
    <h3 id="section-title" class="section-title">
      HOME APPLIANCES
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 5, 'Home Appliances'); ?>

  </div>
  <!-- Product Section End -->

  <!-- Products Section - SPEAKERS AND AUDIO -->

  <div id="product-section-speakers" class="product-section">
    <h3 id="section-title" class="section-title">
      SPEAKERS AND AUDIO
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 6, 'Speakers and Audio'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - MAKEUP AND SKIN CARE -->

  <div id="product-section-makeup" class="product-section">
    <h3 id="section-title" class="section-title">
      MAKEUP AND SKIN CARE
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 7, 'Makeup and Skin Care'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - HOME DECORATIONS -->

  <div id="product-section-homedecorations" class="product-section">
    <h3 id="section-title" class="section-title">
      HOME DECORATIONS
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 8, 'Home Decorations'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - FURNITURES -->

  <div id="product-section-furniture" class="product-section">
    <h3 id="section-title" class="section-title">
      FURNITURES <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 9, 'Furnitures and Tools'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - KITCHEN UTENSILS -->

  <div id="product-section-kitchen" class="product-section">
    <h3 id="section-title" class="section-title">
      KITCHEN UTENSILS
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 10, 'Kitchen Utensils'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - BOOKS AND NOTEBOOKS -->

  <div id="product-section-books" class="product-section">
    <h3 id="section-title" class="section-title">
      BOOKS AND NOTEBOOKS
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 11, 'Books and Notebooks'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - STATIONARY AND CRAFT -->

  <div id="product-section-stationary" class="product-section">
    <h3 id="section-title" class="section-title">
      STATIONARY AND CRAFT
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 12, 'Stationary and Crafts'); ?>
  </div>
  <!-- Product Section End -->

  <!-- Products Section - MUSICAL EQUIPMENTS -->

  <div id="product-section-music" class="product-section">
    <h3 id="section-title" class="section-title">
      MUSICAL EQUIPMENTS
      <a id="view-all-link" href="#" class="view-all">View All</a>
    </h3>

    <?php displayProducts($conn, 13, 'Musical Equipments'); ?>
  </div>
  <!-- Product Section End -->
</body>

</html>