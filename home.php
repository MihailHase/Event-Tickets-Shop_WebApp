<?php
global $conn;
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];


if (!isset($admin_id) && !isset($user_id)) {
    header('location:login.php');
    exit; // Make sure to exit after sending the header to prevent further execution
}
if (isset($_POST['add_to_cart'])) {

    $title = $_POST['titlu'];
    $price = $_POST['pret'];
    $quantity = $_POST['cantitate'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cos` WHERE titlu = '$title' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'Already added to cart!';
    } else {
        mysqli_query($conn, "INSERT INTO `cos`(user_id, titlu, pret, cantitate) VALUES('$user_id', '$title', '$price', '$quantity')") or die('query failed');
        $message[] = 'Product added to cart!';
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>HOME PAGE</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidebar">
    <div class="w3-container w3-center">
        <img src="img/logo.png" alt=" " style="width:75%">
    </div>
    <div class="w3-padding-40 w3-large w3-text-grey" style="font-weight:bold">
        <a href="home.php" class="w3-bar-item w3-button">Events</a>
        <a href="cart.php" class="w3-bar-item w3-button">Shopping Cart</a>
        <a onclick="myAccFunc()" href="javascript:void(0)" class="w3-button w3-block w3-white w3-left-align">
            My Account <i class="fa fa-caret-down"></i>
        </a>
        <div id="dropdown" class="w3-bar-block w3-hide w3-padding-large w3-medium">
            <a href="register.php" class="w3-bar-item w3-button">Create a new account</a>
            <a href="logout.php" class="w3-bar-item w3-button">Sign Out</a>
            <a href="admin_page.php" class="w3-bar-item w3-button">Admin Pannel</a>
        </div>
    </div>
    <a href="#footer" class="w3-bar-item w3-button w3-padding">Contact</a>
</nav>

<div class="w3-main" style="margin-left:250px">
    <header class="w3-container w3-xlarge">
        <p class="w3-left">BestEvents</p>
        <p class="w3-right">
            <a href="cart.php"><i class="fa fa-shopping-cart w3-margin-right"></i></a>
            <a href="logout.php"><i class="fa fa-sign-out w3-margin-right"></i></a>
        </p>
        <p class="w3-center">Welcome <?php echo $_SESSION['user_name']; ?>!</p>
    </header>
    <div class="w3-display-container w3-container">
        <img src="img/banner.png" alt="" style="width:110%">
    </div>
    <div class="w3-container w3-text-grey">
        <p><?php
            $select_events = mysqli_query($conn, "SELECT * FROM `evenimente`") or die('Query failed');
            echo mysqli_num_rows($select_events); ?> events
        </p>
    </div>
    <div>
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message">' . $msg . '</div>';
            }
        }
        ?>
    </div>

    <div class="w3-row w3-grayscale">
        <?php
        $select_events = mysqli_query($conn, "SELECT * FROM `evenimente`") or die('Query failed');
        $event_count = 0;
        if (mysqli_num_rows($select_events) > 0) {
            while ($fetch_events = mysqli_fetch_assoc($select_events)) {
                if ($event_count % 3 == 0) {
                    echo '<div class="w3-row-padding">';
                }
                ?>
                <div class="w3-col l4 s12">
                    <div class="w3-container">
                        <p class="event-title"><?php echo $fetch_events['titlu']; ?></p>
                        <p class="event-price"><b>$<?php echo $fetch_events['pret']; ?></b></p>
                        <textbox><strong>Description:</strong> <?php echo $fetch_events['descriere']; ?></
                        >
                        <ul class="event-list">
                            <li><strong>Date:</strong> <?php echo $fetch_events['data']; ?></li>
                            <li><strong>Location:</strong> <?php echo $fetch_events['locatie']; ?></li>
                            <li><strong>Speaker:</strong> <?php echo $fetch_events['speaker']; ?></li>
                            <li><strong>Partners:</strong> <?php echo $fetch_events['parteneri']; ?></li>
                            <li><strong>Sponsors:</strong> <?php echo $fetch_events['sponsori']; ?></li>
                        </ul>
                        <form action="home.php" method="post" class="event-form">
                            <input type="hidden" name="titlu" value="<?php echo $fetch_events['titlu']; ?>">
                            <input type="hidden" name="pret" value="<?php echo $fetch_events['pret']; ?>">
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" min="1" name="cantitate" value="1" id="quantity"
                                       class="form-control">
                            </div>
                            <button type="submit" name="add_to_cart" class="w3-button w3-black btn-block">Add to Cart <i
                                        class="fa fa-shopping-cart"></i></button>
                        </form>
                    </div>

                </div>
                <?php
                $event_count++;
                if ($event_count % 3 == 0) {
                    echo '</div>';
                }
            }
            if ($event_count % 3 != 0) {
                echo '</div>';
            }
        } else {
            echo '<p class="text-center">No events added yet!</p>';
        }
        ?>
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
</div>
<script>
    function myAccFunc() {
        var x = document.getElementById("dropdown");
        if (x.className.indexOf("w3-show") === -1) {
            x.className += " w3-show";
        } else {
            x.className = x.className.replace(" w3-show", "");
        }
    }
</script>

</body>
</html>
