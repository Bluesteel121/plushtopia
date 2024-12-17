<?php
ob_start();
session_start();
$pageTitle = 'Show Items';
include 'init.php';

// Check if the item ID is numeric and retrieve its value
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

// Prepare the query to fetch item details
$stmt = $con->prepare("
    SELECT 
        items.*, 
        items.Stock_quantity, 
        categories.Name AS category_name, 
        customer.Username 
    FROM 
        items
    INNER JOIN 
        categories 
    ON 
        categories.ID = items.Cat_ID 
    INNER JOIN 
        customer 
    ON 
        customer.customerID = items.Member_ID 
    WHERE 
        Item_ID = ? 
    AND 
        Approve = 1
");

$stmt->execute([$itemid]);
$count = $stmt->rowCount();

if ($count > 0) {
    $item = $stmt->fetch(); // Fetch the item details

    // Ensure the session has user data (username and customerID)
    if (isset($_SESSION['customer'])) {
        $customerID = $_SESSION['customerID']; // The logged-in user's ID (from session data)
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stars = isset($_POST['stars']) ? intval($_POST['stars']) : 0;
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    
            // Check if the comment and stars are valid
            if ($stars >= 1 && $stars <= 5 && !empty($comment)) {
                try {
                    // Insert the review into the database
                    $stmt = $con->prepare("INSERT INTO 
                        reviews(stars, comment, comment_date, item_id, customerID)
                        VALUES(:stars, :comment, NOW(), :itemid, :customerID)");
    
                    $stmt->execute([
                        'stars' => $stars,
                        'comment' => $comment,
                        'itemid' => $itemid, // Ensure $itemid is set
                        'customerID' => $customerID // Use $customerID here
                    ]);
    
                    echo '<div class="alert alert-success">Your review has been submitted!</div>';
                } catch (PDOException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                echo '<div class="alert alert-danger">Please fill in all fields and provide a valid rating.</div>';
            }
        }
    } else {
        echo '<p><a href="login.php">Login</a> or <a href="register.php">Register</a> to leave a review.</p>';
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="layout/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Alice Blue */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background: #e6f7ff; /* Light Blue Background */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

       

        a {
            color: #007bff; /* Blue Links */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .img-thumbnail {
            border: 2px solid #b3d9ff; /* Light Blue Border */
            border-radius: 8px;
        }

        .list-unstyled li {
            margin: 10px 0;
        }

        /* Stars Rating Styling */
        .rating-stars {
            display: inline-flex;
            flex-direction: row;
            margin: 10px 0;
        }

        .rating-stars input[type="radio"] {
            display: none;
        }

        .rating-stars label {
            font-size: 30px;
            color: lightgray;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .rating-stars input[type="radio"]:checked ~ label {
            color: #ffd700; /* Gold for Selected Stars */
        }

        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #ffc107; /* Hover Color for Stars */
        }

        /* Button Styling */
        .btn {
            background-color: #007bff; /* Blue Button */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #0056b3; /* Darker Blue on Hover */
        }

        /* Feedback Form */
        textarea {
            width: 100%;
            min-height: 80px;
            border: 1px solid #b3d9ff;
            border-radius: 4px;
            padding: 10px;
            font-size: 16px;
            resize: none;
        }

        textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Review Section */
        .feedback-item {
            background: #f7fbff; /* Slightly Lighter Blue for Reviews */
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 10px 15px;
            margin: 15px 0;
        }

        .feedback-item strong {
            color: #0056b3;
        }

        .feedback-item .star-icon {
            font-size: 20px;
            color: #ffd700; /* Gold Stars */
        }

        .custom-hr {
            border: 1px solid #b3d9ff;
            margin: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .my-5 {
            margin-top: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center" style="color: #0056b3;"><?php echo $item['Name']; ?></h1>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="<?php echo empty($item['picture']) ? '../admin/uploads/default.png' : '../admin/uploads/items/' . $item['picture']; ?>" 
                     alt="Item Image" 
                     class="img-thumbnail">
                     
            </div>
            <div class="col-md-8 item-info">
                <h2 style="color: #0056b3;"><?php echo $item['Name']; ?></h2>
                <p><?php echo $item['Description']; ?></p>
                <ul class="list-unstyled">
                    <li><strong>Added Date:</strong> <?php echo $item['Add_Date']; ?></li>
                    <li><strong>Price:</strong> <?php echo $item['Price']; ?></li>
                    <li><strong>Category:</strong> 
                        <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>">
                            <?php echo $item['category_name']; ?>
                        </a>
                    </li>
                    <li><strong>Items Left:</strong> <?php echo $item['Stock_quantity']; ?></li>
                </ul>
                
            </div>
        </div>

        <hr class="custom-hr">

        <?php if (isset($_SESSION['customer'])) { ?>
        <div class="feedback-form">
            <h3 style="color: #0056b3;">Leave Your Review</h3>
            <form id="feedbackForm" action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $itemid; ?>" method="POST">
                <div class="rating-stars">
                    <?php for ($i = 5; $i >= 1; $i--) { ?>
                        <input type="radio" id="star<?php echo $i; ?>" name="stars" value="<?php echo $i; ?>" />
                        <label for="star<?php echo $i; ?>">★</label>
                    <?php } ?>
                </div>
                <textarea name="comment" placeholder="Write your review here..." required></textarea>
                <button type="submit" class="btn mt-2" style="margin-top: 10px;">Submit Review</button>
            </form>
        </div>
        <?php } else { ?>
            <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to leave a review.</p>
        <?php } ?>

        <hr class="custom-hr">

        <div class="feedback-section">
            <h3 style="color: #0056b3;">Reviews</h3>
            <?php
            $stmt = $con->prepare("SELECT reviews.*, customer.Username FROM reviews INNER JOIN customer ON customer.customerID = reviews.customerID WHERE item_id = ? ORDER BY reviewID DESC");
            $stmt->execute([$itemid]);
            $reviews = $stmt->fetchAll();

            if ($reviews) {
                foreach ($reviews as $review) { ?>
                    <div class="feedback-item">
                        <strong><?php echo $review['Username']; ?></strong>
                        <div>
                            <?php for ($i = 5; $i >= 1; $i--) { 
                                echo $i <= $review['stars'] ? '<span class="star-icon">★</span>' : '<span class="star-icon">☆</span>';
                            } ?>
                        </div>
                        <p><?php echo $review['comment']; ?></p>
                        <small><?php echo $review['comment_date']; ?></small>
                    </div>
                <?php }
            } else {
                echo '<p>No reviews yet. Be the first to leave one!</p>';
            } ?>
        </div>
    </div>
</body>
</html>


<?php
} else {
    echo '<div class="container"><div class="alert alert-danger">This item does not exist or is awaiting approval.</div></div>';
}
include $tpl . 'footer.php';
ob_end_flush();
?>
