<?php
session_start();
ob_start();
$pageTitle = 'Cart';
include 'init.php'; // Include database connection

// Check if the user is logged in
$customerID = isset($_SESSION['customerID']) ? $_SESSION['customerID'] : null;

$cart_items = [];

// Fetch cart items for logged-in users
if ($customerID) {
    $stmt = $con->prepare("  
        SELECT 
            c.item_id, c.quantity, i.Name, i.Price, i.picture
        FROM 
            cart c
        JOIN 
            items i
        ON 
            c.item_id = i.Item_ID
        WHERE 
            c.customerID = ?
    ");
    $stmt->execute([$customerID]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fetch cart items for guest users from the session
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $item_ids = array_keys($_SESSION['cart']);
        if (!empty($item_ids)) {
            $placeholders = implode(',', array_fill(0, count($item_ids), '?'));
            $stmt = $con->prepare("  
                SELECT 
                    Item_ID AS item_id, Name, Price, picture
                FROM 
                    items
                WHERE 
                    Item_ID IN ($placeholders)
            ");
            $stmt->execute($item_ids);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Add quantity from session to each item
            foreach ($items as $item) {
                $item['quantity'] = $_SESSION['cart'][$item['item_id']];
                $cart_items[] = $item;
            }
        }
    }
}

// Handle cart updates (quantity changes or deletions)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_changes'])) {
    // Handle item deletions
    if (!empty($_POST['delete'])) {
        $delete_ids = array_map('intval', $_POST['delete']); // Ensure IDs are integers

        if ($customerID) {
            // Delete items for logged-in users
            $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));
            $stmt = $con->prepare("DELETE FROM cart WHERE customerID = ? AND item_id IN ($placeholders)");
            $stmt->execute(array_merge([$customerID], $delete_ids));
        } else {
            // Delete items for guest users (session-based cart)
            foreach ($delete_ids as $id) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    // Handle quantity updates
    if (!empty($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $item_id => $quantity) {
            $item_id = intval($item_id); // Ensure valid item ID
            $quantity = max(1, intval($quantity)); // Ensure valid quantity (min 1)

            if ($customerID) {
                // Update quantities for logged-in users
                $stmt = $con->prepare("UPDATE cart SET quantity = ? WHERE customerID = ? AND item_id = ?");
                $stmt->execute([$quantity, $customerID, $item_id]);
            } else {
                // Update quantities for guest users
                if (isset($_SESSION['cart'][$item_id])) {
                    $_SESSION['cart'][$item_id] = $quantity;
                }
            }
        }
    }

    // Redirect to refresh the cart page
    header('Location: cart.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Alice Blue */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            background-color: #e6f7ff; /* Light Blue Background */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0056b3; /* Deep Blue for Heading */
            text-align: center;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #b3d9ff; /* Light Blue Border */
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-info h4 {
            margin: 0 0 5px;
            font-size: 18px;
            color: #0056b3; /* Deep Blue */
        }

        .cart-item-info p {
            margin: 0;
            color: #666;
        }

        .cart-item-actions {
            text-align: right;
        }

        .cart-item-actions button {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }

        .cart-item-actions button:hover {
            background-color: #f1f1f1;
        }

        /* Remove Checkbox Styling */
        .cart-item input[type="checkbox"] {
            margin-top: 10px;
            margin-left: 15px;
        }

        /* Cart Summary Styling */
        .cart-summary {
            text-align: right;
            margin-top: 20px;
        }

        .cart-summary h3 {
            margin: 0;
            color: #0056b3;
        }

        .checkout-btn {
            padding: 8px 16px;
            background-color: #227ac7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
            background-color: rgb(40, 116, 182); /* Darker Blue */
        }

        /* Input and Form Styling */
        input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #b3d9ff;
            border-radius: 4px;
            background-color: #fff;
            font-size: 16px;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #227ac7;
            box-shadow: 0 0 5px rgba(34, 122, 199, 0.5);
        }
    </style>
    <script>
        // Function to update item quantity
        function updateQuantity(itemId, quantity) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Failed to update quantity.');
                    }
                }
            };
            xhr.send(`action=update&item_id=${itemId}&quantity=${quantity}`);
        }

        // Function to delete an item
        function deleteItem(itemId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete item.');
                    }
                }
            };
            xhr.send(`action=delete&item_id=${itemId}`);
        }
    </script>
</head>
<body>
<div id="cart-container">
    <h2>Your Cart</h2>
    <?php if (!empty($cart_items)): ?>
        <?php $total_price = 0; ?>
        <form id="cart-form" method="POST" action="cart.php">
            <?php foreach ($cart_items as $item): ?>
                <?php $total_price += $item['Price'] * $item['quantity']; ?>
                <div class="cart-item" id="item-<?php echo $item['item_id']; ?>">
                    <input 
                        type="checkbox" 
                        name="delete[]" 
                        value="<?php echo $item['item_id']; ?>">
                    <label for="delete-<?php echo $item['item_id']; ?>" style="margin-left: 15px; margin-top: 10px;">Remove</label>

                    <img src="../admin/uploads/items/<?php echo htmlspecialchars($item['picture']) ?: 'default.png'; ?>" 
                        alt="Item Image" 
                        width="80" 
                        height="80"
                        style="margin-left: 35px;">
                    <div class="cart-item-info">
                        <h4><?php echo htmlspecialchars($item['Name']); ?></h4>
                        <p>Price: P<?php echo number_format($item['Price'], 2); ?></p>
                        <p>
                            Quantity: 
                            <input 
                                type="number" 
                                name="quantity[<?php echo $item['item_id']; ?>]" 
                                value="<?php echo $item['quantity']; ?>" 
                                min="1" 
                                onchange="updateQuantity(<?php echo $item['item_id']; ?>, this.value)">
                        </p>
                        <p>Subtotal: P<?php echo number_format($item['Price'] * $item['quantity'], 2); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="cart-summary">
                <h3 id="total-price">Total: P<?php echo number_format($total_price, 2); ?></h3>
                <button type="submit" class="checkout-btn" name="apply_changes">Apply Changes</button>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        </form>
    <?php else: ?>
        <p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
    <?php endif; ?>
</div>

<script>
    function deleteItem(itemId, button) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Change the button text to 'Deleted'
                    button.textContent = 'Deleted';
                    button.disabled = true; // Optionally disable the button after deletion

                    // Remove the item from the DOM
                    const itemElement = document.getElementById(`item-${itemId}`);
                    if (itemElement) {
                        itemElement.remove();
                    }

                    // Update the total price
                    document.getElementById('total-price').innerText = 'P' + response.total_price.toFixed(2);

                    // Show empty cart message if total price is 0
                    if (response.total_price === 0) {
                        document.getElementById('cart-container').innerHTML = '<p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>';
                    }
                } else {
                    alert(response.error || 'Failed to delete item.');
                }
            }
        };
        xhr.send(`action=delete&item_id=${itemId}`);
    }
</script>
</body>
</html>
