<?php
    ob_start();
    session_start();
    $pageTitle = 'Login';
    if (isset($_SESSION['user']) || isset($_SESSION['seller'])) {
        header('Location: index.php');
    }

    include 'init.php';

    $backgroundImage = 'images/webBackground5.jpg';

    // Check If User Coming From HTTP Post Request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Check if it's a customer or seller
        if (isset($_POST['login'])) {

            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

            // Check If The User Exist In Customer Database
            $stmt = $con->prepare("SELECT customerID, Username, Password FROM customer WHERE Username = ? AND Password = ?");
            $stmt->execute(array($user, $hashedPass));
            $get = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0) {
                // Register session for customer
                $_SESSION['customer'] = $user;
                $_SESSION['customerID'] = $get['customerID'];  // Store customerID
             

                header('Location: customer/index.php');  // Redirect to customer dashboard
                exit();
            } else {
                // Check if the user exists in the seller (users) database
                $stmt = $con->prepare("SELECT UserID, Username, Password, FROM users WHERE Username = ? AND Password = ?");
                $stmt->execute(array($user, $hashedPass));
                $get = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($count > 0) {
                    // Register session for seller
                    $_SESSION['seller'] = $user;
                    $_SESSION['userID'] = $get['UserID'];  // Store userID for seller
                    

                    header('Location: index.php');  // Redirect to seller dashboard
                    exit();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('<?php echo $backgroundImage; ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
        }
    </style>
</head>
<body>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Customer</span> | 
        <span data-class="signup">Seller</span>
    </h1>
    
    <!-- Start Login Form -->
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="input-container">
            <input 
                class="form-control" 
                type="text" 
                name="username" 
                autocomplete="off"
                placeholder="Username" 
                required />
        </div>
        <div class="input-container">
            <input 
                class="form-control" 
                type="password" 
                name="password" 
                autocomplete="new-password"
                placeholder="Password" 
                required />
        </div>
        <div class="button-container" style="display: flex; justify-content: space-between; align-items: center;">
            <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
        </div>
        <a href="signin.php" style="margin-left: 285px; text-align: center; color: #0c5a9e; text-decoration: none; font-weight: bold;">Signup Here</a>
    </form>
    <!-- End Login Form -->

    <!-- Start Signup Form (optional, not yet implemented) -->
    <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <!-- Signup fields here, if you plan to implement it -->
    </form>
    <!-- End Signup Form -->

    <div class="the-errors text-center">
        <?php 
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }
            if (isset($succesMsg)) {
                echo '<div class="msg success">' . $succesMsg . '</div>';
            }
        ?>
    </div>
</div>

</body>
</html>

<?php 
    include $tpl . 'footer.php';
    ob_end_flush();
?>
