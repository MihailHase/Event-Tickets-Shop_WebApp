<?php

global $conn;
require __DIR__ . "/vendor/autoload.php";

$stripe_secret_key = " your_secret_key ";

\Stripe\Stripe::setApiKey($stripe_secret_key);

session_start();
$user_id = $_SESSION['user_id'];

// Fetch events from the cart
$cart_items = [];
$grand_total = 0;

include 'config.php';
$select_cart = mysqli_query($conn, "SELECT * FROM `cos` WHERE user_id = '$user_id'") or die('Query failed');
if (mysqli_num_rows($select_cart) > 0) {
    while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
        $event_name = $fetch_cart['titlu'];
        $event_quantity = $fetch_cart['cantitate'];
        $event_price = $fetch_cart['pret'];

        $sub_total = $event_quantity * $event_price;
        $grand_total += $sub_total;

        // Add event to line_items array
        $cart_items[] = [
            "quantity" => $event_quantity,
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => $event_price * 100, // Amount should be in cents
                "product_data" => [
                    "name" => $event_name,
                ],
            ],
        ];
    }
}

// Create Checkout session with dynamic line_items
$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost/project/success.php",
    "cancel_url" => "http://localhost/project/cart.php",
    "locale" => "ro",
    "line_items" => $cart_items,
]);

http_response_code(303);
header("Location: " . $checkout_session->url);



