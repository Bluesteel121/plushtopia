<?php
ob_start();
session_start();
$pageTitle = 'Show Items';
include 'init.php'; // Includes database connection, paths, and other dependencies

// Check if the item ID is numeric and retrieve its value
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

// Prepare the query to fetch item details
$stmt = $con->prepare("
    SELECT 
        items.*, 
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

// Execute the query
$stmt->execute([$itemid]);
$count = $stmt->rowCount();

if ($count > 0) {
    $item = $stmt->fetch(); // Fetch the item details
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="layout/css/custom.css"> <!-- Add your custom CSS file here -->
    <style>
        .rating-stars {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin: 10px 0;
        }
        .rating-stars input[type="radio"] {
            display: none;
        }
        .rating-stars label {
            font-size: 24px;
            color: gray;
            cursor: pointer;
        }
        .rating-stars input[type="radio"]:checked ~ label {
            color: gold;
        }
        .feedback-form {
            margin: 20px 0;
        }
        .feedback-section {
            margin-top: 30px;
        }
        .feedback-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center"><?php echo $item['Name']; ?></h1>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="<?php echo empty($item['picture']) ? 'admin/uploads/default.png' : 'admin/uploads/items/' . $item['picture']; ?>" 
                     alt="Item Image" 
                     class="img-thumbnail">
            </div>
            <div class="col-md-8 item-info">
                <h2><?php echo $item['Name']; ?></h2>
                <p><?php echo $item['Description']; ?></p>
                <ul class="list-unstyled">
                    <li><strong>Added Date:</strong> <?php echo $item['Add_Date']; ?></li>
                    <li><strong>Price:</strong> <?php echo $item['Price']; ?></li>
                    <li><strong>Category:</strong> 
                        <a href="categories.php?pageid=<?php echo $item['Cat_ID']; ?>">
                            <?php echo $item['category_name']; ?>
                        </a>
                    </li>
                    <li><strong>Added By:</strong> <?php echo $item['Username']; ?></li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">

        <!-- Feedback Section -->
        <?php if (isset($_SESSION['user'])) { ?>
        <div class="feedback-form">
            <h3>Leave Your Review</h3>
            <form id="feedbackForm" action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID']; ?>" method="POST">
                <div class="rating-stars">
                    <?php for ($i = 5; $i >= 1; $i--) { ?>
                        <input type="radio" id="star<?php echo $i; ?>" name="stars" value="<?php echo $i; ?>" />
                        <label for="star<?php echo $i; ?>">★</label>
                    <?php } ?>
                </div>
                <textarea name="comment" placeholder="Write your review here..." required></textarea>
                <button type="submit" class="btn btn-primary mt-2">Submit Review</button>
            </form>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $stars = isset($_POST['stars']) ? intval($_POST['stars']) : 0;
                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                $userid = $_SESSION['uid'];

                if ($stars >= 1 && $stars <= 5 && !empty($comment)) {
                    $stmt = $con->prepare("INSERT INTO 
                        reviews(stars, comment, comment_date, item_id, customerID)
                        VALUES(:stars, :comment, NOW(), :itemid, :userid)");

                    $stmt->execute([
                        'stars' => $stars,
                        'comment' => $comment,
                        'itemid' => $itemid,
                        'userid' => $userid
                    ]);

                    echo '<div class="alert alert-success">Your review has been submitted!</div>';
                } else {
                    echo '<div class="alert alert-danger">Please fill in all fields and provide a valid rating.</div>';
                }
            }
            ?>
        </div>
        <?php } else {
            echo '<p><a href="login.php">Login</a> or <a href="register.php">Register</a> to leave a review.</p>';
        } ?>

        <hr class="custom-hr">

        <!-- Display Reviews -->
        <div class="feedback-section">
            <h3>Reviews</h3>
            <?php
            $stmt = $con->prepare("
                SELECT 
                    reviews.*, customer.Username 
                FROM 
                    reviews
                INNER JOIN 
                    customer 
                ON 
                    customer.customerID = reviews.customerID
                WHERE 
                    item_id = ?
                ORDER BY 
                    reviewID DESC
            ");
            $stmt->execute([$itemid]);
            $reviews = $stmt->fetchAll();

            if ($reviews) {
                foreach ($reviews as $review) {
            ?>
            <div class="feedback-item">
                <strong><?php echo $review['Username']; ?></strong>
                <div>
                    <?php for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $review['stars'] ? '★' : '☆';
                    } ?>
                </div>
                <p><?php echo $review['comment']; ?></p>
                <small><?php echo $review['comment_date']; ?></small>
            </div>
            <?php
                }
            } else {
                echo '<p>No reviews yet. Be the first to leave one!</p>';
            }
            ?>
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
