<?php 
session_start();
include 'init.php'; // DB connection, session setup

$customerID = isset($_SESSION['customerID']) ? $_SESSION['customerID'] : null;
$order_success = false; 

// Helper function to calculate the total cart amount
function getCartItems($customerID) {
    global $con;

    $cart_items = [];
    if ($customerID) {
        // Fetch cart items for logged-in customer
        $stmt = $con->prepare("SELECT c.item_id, c.quantity, i.Name, i.Price, i.picture FROM cart c JOIN items i ON c.item_id = i.Item_ID WHERE c.customerID = ?");
        $stmt->execute([$customerID]);
        $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $cart_items;
}

function calculateTotal($cart_items) {
    return array_sum(array_map(fn($item) => $item['Price'] * $item['quantity'], $cart_items));
}

// Fetch cart items for logged-in customer
$cart_items = getCartItems($customerID);
$total_price = calculateTotal($cart_items);
$error_message = ''; // Initialize the error message

if (empty($cart_items)) {
    echo '<p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>';
    exit;
}

// Handle form submission (e.g., placing an order)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping = $_POST['shipping'] ?? '';
    $delivery = $_POST['delivery'] ?? '';
    $recipient_name = $_POST['recipient_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';

    try {
        $con->beginTransaction(); // Start transaction

        // Ensure customerID is available for order
        if (!$customerID) {
            throw new Exception("Unable to retrieve customer information.");
        }

        // Insert into order_items table
        $stmt = $con->prepare("INSERT INTO order_items (Item_ID, Quantity, Price, shipping, delivery, recipient_name, address, contactNumber, customerID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([
                $item['item_id'], 
                $item['quantity'], 
                $item['Price'], 
                $shipping, 
                $delivery, 
                $recipient_name, 
                $address, 
                $contactNumber, 
                $customerID
            ]);
        }

        // Update stock after placing the order
        $stmt = $con->prepare("UPDATE items SET Stock_quantity = Stock_quantity - ? WHERE Item_ID = ?");
        foreach ($cart_items as $item) {
            $stmt->execute([$item['quantity'], $item['item_id']]);
        }

        $con->commit(); // Commit transaction

        // Clear cart after successful order
        $stmt = $con->prepare("DELETE FROM cart WHERE customerID = ?");
        $stmt->execute([$customerID]);

        $order_success = true;
    } catch (Exception $e) {
        $con->rollBack(); // Rollback transaction on error
        $error_message = 'Error placing order: ' . htmlspecialchars($e->getMessage());
        echo $error_message;
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
      
        .error { color: red; }

        .checkout-container {
    width: 90%;
    max-width: 800px;
    padding: 30px;
    background-color: #ffffff;
    border: 5px solid #87CEEB;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    margin-top: 225px;
    /* Centering with margin and position */
    position: absolute;
    top: 50%; /* Center vertically */
    left: 50%; /* Center horizontally */
    transform: translate(-50%, -50%); /* Adjust for container size */
}



        h2 {
            color: #ffffff;
            margin-bottom: 20px;
        
        }

        h3, h4 {
            color: #ffffff;
            margin-bottom: 10px;
           
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            text-align: left;
        }

        ul li {
            margin: 5px 0;
        }

        form {
            margin-top: 20px;
            text-align: left;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #87CEEB;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #5aaedb;
        }

        a {
            color: #87CEEB;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="checkout-container" id="checkout-container" <?php if ($order_success) echo 'style="display:none;"'; ?>>
    <div style="background-color: #227ac7; padding: 20px; border-radius: 8px; color: white; margin-top: 0px;">
        <h2>Checkout</h2>
        <?php if (!empty($cart_items)): ?>
            <div>
                <h3>Order Summary</h3>
                <p>Total Price: <strong>P<?php echo number_format($total_price, 2); ?></strong></p>
            </div> 
        </div>
        <div>
            <label style="margin-top:20px;">Items in Your Cart:</label>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li>
                        <?php echo htmlspecialchars($item['Name']); ?> - 
                        Quantity: <?php echo $item['quantity']; ?> - 
                        Price: P<?php echo number_format($item['Price'] * $item['quantity'], 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if ($error_message): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST" action="checkout.php">
            <div>
                <label for="recipient_name">Recipient Name</label>
                <input type="text" id="recipient_name" name="recipient_name" required>
            </div>
            <div>
                <label for="address">Delivery Address</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div>
                <label for="contactNumber">Contact Number</label>
                <input type="text" id="contactNumber" name="contactNumber" required>
            </div>
            <div>
                <label for="shipping">Shipping Method</label>
                <select id="shipping" name="shipping" required style="width: 100%; font-size: 16px; padding: 10px;">
                    <option value="standard">Standard Shipping</option>
                    <option value="express">Express Shipping</option>
                    <option value="overnight">Overnight Shipping</option>
                </select>
            </div>
            <div>
                <label for="delivery">Delivery Method</label>
                <select id="delivery" name="delivery" required style="width: 100%; font-size: 16px; padding: 10px;">
                    <option value="home">Home Delivery</option>
                    <option value="pickup">Pick Up</option>
                    <option value="store">In-Store Pickup</option>
                </select>
            </div>
            <button type="submit" style="background-color: #227ac7;">Confirm Purchase</button>
        </form>
    <?php else: ?>
        
    <?php endif; ?>
</div>

<?php if ($order_success): ?>
    <div style="text-align: center; color: green; margin-top: 40px;">
    <img src="images/thanku.png" alt="Order Success" style="width: 300px; height: 300px;">
        <h3 style="color: #227ac7;">Order placed successfully!</h3>
        <p style ="color:rgb(44, 44, 44);">Thank you for your purchase. Your order is being processed.</p>
        <a href="index.php">Continue shopping</a>
    </div>
<?php endif; ?>
</body>
</html>

