<?php  
    session_start();
    include 'init.php';
?> 

<body style="background-color: #deefff; margin: 0; padding: 0;">

<div class="container" style="padding-top: 50px;">
    <?php
    if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
        $category = intval($_GET['pageid']);

        // Search and Filter Section
        echo '<div class="search-filter-container" style="margin: 10px 0; margin-top: -20px; display: flex; justify-content: flex-end; align-items: center; gap: 10px;">';
        echo '<form method="GET" action="" style="flex: 0 0 auto; display: flex; gap: 5px;">';
        echo '<input type="hidden" name="pageid" value="' . $category . '" />';
        echo '<input type="text" name="search" placeholder="Search..." style="width: 150px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" />';
        echo '<button type="submit" style="padding: 5px 10px; background-color: #227ac7; color: #fff; border: none; border-radius: 3px; font-size: 14px; cursor: pointer;">Search</button>';
        echo '</form>';
        echo '<form method="GET" action="" style="flex: 0 0 auto; display: flex; gap: 5px;">';
        echo '<input type="hidden" name="pageid" value="' . $category . '" />';
        echo '<input type="number" name="min_price" placeholder="Min" style="width: 70px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" min="0" />';
        echo '<input type="number" name="max_price" placeholder="Max" style="width: 70px; padding: 5px; border: 1px solid #ccc; border-radius: 3px; font-size: 14px;" min="0" />';
        echo '<button type="submit" style="padding: 5px 10px; background-color: #227ac7; color: #fff; border: none; border-radius: 3px; font-size: 14px; cursor: pointer;">Filter</button>';
        echo '</form>';
        echo '</div>';

        $query = "SELECT * FROM items WHERE Approve = 1 AND Cat_ID = $category";

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

        // Display Category Name
        function getSingleValue($con, $sql, $parameters) {
            $q = $con->prepare($sql);
            $q->execute($parameters);
            return $q->fetchColumn();
        }

        $myCategory = getSingleValue($con, "SELECT Name FROM categories WHERE id=?", [$category]);
        echo '<h3 class="text-center">' . $myCategory . '</h3>';

        echo '<div class="scroll-container">';
        foreach ($allItems as $item) {
            echo '<div class="item-box" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center;">';
                echo '<a href="items.php?itemid=' . $item['Item_ID'] . '" style="text-decoration: none; color: inherit;">';
                    echo '<div class="thumbnail" style="overflow: hidden;">';
                        if (empty($item['picture'])) {
                            echo "<img src='admin/uploads/default.png' alt='Default Image' style='width: 200px; height: 200px; object-fit: cover; border-radius: 3px;' />";
                        } else {
                            echo "<img src='admin/uploads/items/" . $item['picture'] . "' alt='Item Image' style='width: 200px; height: 200px; object-fit: cover; border-radius: 3px;' />";
                        }
                        echo '<div>';
                            echo '<span>P' . $item['Price'] . '</span>';
                            echo '<h3>' . $item['Name'] . '</h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                        echo '</div>';
                    echo '</div>';
                echo '</a>';
                // Add the Buy Now button
                echo '<div class="item-buttons" style="margin-bottom: 10px; margin-left: 50px;">';
                    echo '<a href="login.php?itemid=' . $item['Item_ID'] . '" class="fas fa-cart-plus" style="padding: 5px 8px; font-size: 12px; border: 1px solid #ddd; background-color:rgb(83, 168, 238); color: #fff; text-decoration: none; border-radius: 3px; margin-right: -80px;">';
                        echo 'Buy Now';
                    echo '</a>';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="text-center">You Must Add Page ID</p>';
    }
    ?>
</div>
</body>

<?php include $tpl . 'footer.php'; ?>
