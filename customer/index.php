<?php
ob_start();
session_start();
$pageTitle = 'Homepage';
include 'init.php'; // Include database connection

// Check if the user is logged in
$user_id = isset($_SESSION['customerID']) ? $_SESSION['customerID'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $item_id = intval($_POST['item_id']);
    $quantity = 1; // Default quantity is 1 since no input field for quantity is in the design

    if ($user_id) {
        // User is logged in, save cart item to the database
        $stmt = $con->prepare("INSERT INTO cart (customerID, item_id, quantity) 
                               VALUES (?, ?, ?) 
                               ON DUPLICATE KEY UPDATE quantity = quantity + ?");
        $stmt->execute([$user_id, $item_id, $quantity, $quantity]);
    } else {
        // Use session-based cart for guests
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$item_id])) {
            $_SESSION['cart'][$item_id] += $quantity;
        } else {
            $_SESSION['cart'][$item_id] = $quantity;
        }
    }
    header('Location: cart.php'); // Redirect to cart page
    exit();
}

$query = "SELECT * FROM items WHERE Approve = 1";

// Handle Search
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = htmlspecialchars($_GET['search']);
    $query .= " AND (Name LIKE '%$search%' OR Description LIKE '%$search%' OR Country_Made LIKE '%$search%')";
}

// Handle Price Range Filter
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $min_price = floatval($_GET['min_price']);
    $query .= " AND Price >= $min_price";
}

if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $max_price = floatval($_GET['max_price']);
    $query .= " AND Price <= $max_price";
}

$allItems = $con->query($query)->fetchAll();
?>


<head>
    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<!-- Add background color to the page -->
<body style="background-color: #deefff; margin: 0; padding: 0;">

<div class="container" style="padding-top: 10px;">
    <!-- Search and Filter Section -->
    <div class="search-filter-container" style="margin: 10px 0; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">
        <form method="GET" action="" style="flex: 0 0 auto; display: flex; gap: 5px;">
            <input type="text" name="search" placeholder="Search..." style="width: 150px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" />
            <button type="submit" style="padding: 5px 10px; background-color: #227ac7; color: #fff; border: none; border-radius: 3px; font-size: 14px; cursor: pointer;">Search</button>
        </form>
        <form method="GET" action="" style="flex: 0 0 auto; display: flex; gap: 5px;">
            <input type="number" name="min_price" placeholder="Min" style="width: 70px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" min="0" />
            <input type="number" name="max_price" placeholder="Max" style="width: 70px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" min="0" />
            <button type="submit" style="padding: 5px 10px; background-color: #227ac7; color: #fff; border: none; border-radius: 3px; font-size: 14px; cursor: pointer;">Filter</button>
        </form>
    </div>

    <div class="scroll-container">
        <?php foreach ($allItems as $item): ?>
            <?php
            $maxDescriptionLength = 100; // Maximum number of characters for the description
            $description = strlen($item['Description']) > $maxDescriptionLength
                ? substr($item['Description'], 0, $maxDescriptionLength) . '...'
                : $item['Description'];
            ?>
            <div class="item-box" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center;">
                <a href="items.php?itemid=<?php echo $item['Item_ID']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="thumbnail" style="overflow: hidden;">
                        <?php if (empty($item['picture'])): ?>
                            <img src='../admin/uploads/default.png' alt='Default Image' style='width: 200px; height: 200px; object-fit: cover; border-radius: 3px;' />
                        <?php else: ?>
                            <img src='../admin/uploads/items/<?php echo $item['picture']; ?>' alt='Item Image' style='width: 200px; height: 200px; object-fit: cover; border-radius: 3px;' />
                        <?php endif; ?>
                        <div>
                            <span>P<?php echo $item['Price']; ?></span>
                            <h3><?php echo $item['Name']; ?></h3>
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                </a>
                <!-- Buttons for Add to Cart and Buy Now -->
                <div class="item-buttons" style="margin-bottom: 10px;">
                    <form method="POST" action="" style="display: inline-block; margin-left: -80px; margin-right: -210px;">
                        <input type="hidden" name="item_id" value="<?php echo $item['Item_ID']; ?>">
                        <button type="submit" name="add_to_cart" style="padding: 2px 6px; font-size: 12px; border: 1px solid #ddd; background-color:rgb(83, 168, 238); color: #fff; border-radius: 3px; cursor: pointer;">
    <i class="fas fa-cart-plus" style="margin-right: 5px;"></i> Add to Cart
</button>

                    </form>
                   
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>

<?php
include $tpl . 'footer.php';
ob_end_flush();
?>
