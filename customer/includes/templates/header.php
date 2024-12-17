<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="<?php echo $css ?>front.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f8fc;
            color: #455a64;
        }

        .header {
            background-color: #64b5f6;
            color: white;
            padding: 10px 20px;
        }

        /* Logo and Navbar Section */
        .header .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .header .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header .logo img {
            width: 80px;
            height: 65px;
            border-radius: 10%;
        }

        .header .logo span {
            font-size: 2rem;
            font-weight: bold;
        }

        .header .navbar {
            flex-grow: 1;
			margin-top: 40px;
			margin-bottom: -50px;
        }

        .header .navbar ul {
            display: flex;
            align-items: center;
            gap: 15px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .header .navbar ul li {
            padding: 0;
        }

        .header .navbar ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .header .navbar ul li a:hover {
            text-decoration: underline;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header .user-info img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .header .user-info .btn-group {
            margin: 0;
        }

        .header .user-info a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        .header .user-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">

        <div class="top-bar">
            <!-- Logo Section -->
			
            <div class="logo">
                <img src="images/logo.png" alt="Logo">
                
                 <span>	<h3>Welcome to PlushTopia!</h3>
				 <h6>Your next best friend is waiting.</h6></span>
				
            </div>

            <!-- Navigation Links Section (Home, Categories) -->
            
            <!-- User Info Section -->
            <div class="user-info" style="margin-right: -500px;">
            <?php if (isset($_SESSION['customerID'])) { 
        // Fetch the customer's name from the database
        $customerID = $_SESSION['customerID'];
        $stmt = $con->prepare("SELECT FullName FROM customer WHERE customerID = ?");
        $stmt->execute([$customerID]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            $customerName = $customer['FullName']; // Get the customer's full name
        }
    ?>
       
        <div class="btn-group" style="margin-right: -25px; margin-top: -15px;">
        <img src="images/customer.png" style="margin-right: -50px; margin-top: 0px; width: 30px; height: 30px;"/>
            <span class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="color: white; background-color: transparent; border: none; margin-left: 500px">
                <?php echo htmlspecialchars($customerName); ?> <!-- Display the customer's full name -->
                <span class="caret"></span>
            </span>
            <ul class="dropdown-menu" style="margin-left: 500px">
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="cart.php">My Cart</a></li>
                <li><a href="myPurchases.php">My Purchases</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
                <?php } else { ?>
                    <a href="login.php" style="margin-right: -325px; margin-top: -10px;">Login / Signup</a>
				
                <?php } ?>

				
            </div>
			
            <div>

<span><div class="navbar">
        <ul>
    
            <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
            <?php
            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
            foreach ($allCats as $cat) {
                echo '<li><a href="categories.php?pageid=' . $cat['ID'] . '">' . $cat['Name'] . '</a></li>';
            }
            ?>
        </ul>
    </div>
</span>

</div>
        </div>
      
    </div>
</body>
</html>
