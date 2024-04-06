<?php

global $conn;
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];


if (!isset($admin_id) && !isset($user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['update_cart'])) {
    $cart_id = $_POST['cart_id'];
    $quantity = $_POST['cantitate'];
    mysqli_query($conn, "UPDATE `cos` SET cantitate = '$quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cos` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cos` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body class="w3-main">
<header class="w3-container w3-large">
    <p class="w3-left w3-xlarge">Shopping Cart</p>
    <p class="w3-right">
        <a href="home.php"><i class="fa fa-home w3-margin-right w3-xxlarge"></i></a>
    </p>
</header>
<div class="w3-display-container w3-container">
    <img src="img/banner.png" alt="Banner" style="width:100%">
</div>

<div class="w3-container">
    <h1 class="title mb-4">Events Added</h1>
    <div class="w3-row w3-grayscale"> <!-- Using w3 classes for row and grayscale -->
        <?php
        $grand_total = 0; // Initialize grand total
        $select_cart = mysqli_query($conn, "SELECT * FROM `cos` WHERE user_id = '$user_id'") or die('Query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                ?>
                <div class="w3-col l4 s12"> <!-- Column structure -->
                    <div class="w3-container"> <!-- Box container -->
                        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>"
                           onclick="return confirm('Delete this from cart?');">
                            <button><i class="fa fa-times" aria-hidden="true"></i></button>
                        </a>

                        <div class="titlu"><?php echo $fetch_cart['titlu']; ?></div>
                        <div class="pret">Pret bilet: <?php echo $fetch_cart['pret']; ?>$</div>
                        <form action="" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                            <input type="number" min="1" name="cantitate"
                                   value="<?php echo $fetch_cart['cantitate']; ?>" class="form-control mb-2">
                            <button type="submit" name="update_cart" value="Update"
                                    class="w3-button w3-black btn-block">Update <i class="fa fa-refresh"></i></button>
                        </form>
                        <div class="sub-total">Sub Total:
                            <span>$<?php echo $sub_total = ($fetch_cart['cantitate'] * $fetch_cart['pret']); ?></span>
                        </div>
                    </div>
                    <?php
                    $grand_total += $sub_total;
                    ?>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty text-center">Your cart is empty</p>';
        }
        ?>
    </div>
</div>
<div class="w3-container w3-center">
    <a href="cart.php?delete_all" onclick="return confirm('Delete all from cart?');">
        <button class="w3-button w3-red" <?php echo ($grand_total < 1) ? 'disabled="disabled"' : ''; ?>>Empty cart
        </button>
    </a>

    <a href="home.php" class="w3-button w3-blue">Continue Shopping</a>
</div>
<div class="w3-container w3-center">
    <p class="w3-large">Subtotal: <span class="event-price">$<?php echo $grand_total; ?></span></p>
    <div class="mt-3">
        <form method="post" action="checkout.php">
            <button type="submit"
                    class="w3-button w3-green" <?php echo ($grand_total < 1) ? 'disabled="disabled"' : ''; ?>>Proceed to
                Checkout
            </button>
        </form>
    </div>
</div>

<footer class="w3-padding-64 w3-light-grey w3-small w3-center" id="footer">
    <div class="w3-row-padding">
        <div class="w3-third">
            <h4>About</h4>
            <p><a href="#">About us</a></p>
            <p><a href="#">We're hiring</a></p>
            <p><a href="#">Support</a></p>
        </div>

        <div class="w3-third">
            <h4>Store</h4>
            <p><i class="fa fa-fw fa-map-marker"></i> Company Name</p>
            <p><i class="fa fa-fw fa-phone"></i> 0044123123</p>
            <p><i class="fa fa-fw fa-envelope"></i> ex@mail.com</p>
        </div>

        <div class="w3-third">
            <h4>We accept</h4>
            <p><i class="fa fa-fw fa-cc-amex"></i> Amex</p>
            <p><i class="fa fa-fw fa-credit-card"></i> Credit Card</p>
            <br>
            <i class="fa fa-facebook-official w3-hover-opacity w3-large"></i>
            <i class="fa fa-instagram w3-hover-opacity w3-large"></i>
            <i class="fa fa-snapchat w3-hover-opacity w3-large"></i>
            <i class="fa fa-pinterest-p w3-hover-opacity w3-large"></i>
            <i class="fa fa-twitter w3-hover-opacity w3-large"></i>
            <i class="fa fa-linkedin w3-hover-opacity w3-large"></i>
        </div>
    </div>
</footer>

<div class="w3-black w3-center w3-padding-24">Powered by
    <a href="#" title="#" target="_blank" class="w3-hover-opacity">Mihail</a>
</div>

</body>
</html>
