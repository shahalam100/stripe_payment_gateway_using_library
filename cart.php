<?php
session_start(); // Start the session

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo '<h2>Your cart is empty</h2>';
} else {
    echo '<h2>Cart</h2>';
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Product Image</th>';
    echo '<th>Product Name</th>';
    echo '<th>Product Price</th>';
    echo '<th>Quantity</th>';
    echo '<th>Total</th>';
    echo '<th>Action</th>';
    echo '</tr>';

    $total = 0;

    foreach ($_SESSION['cart'] as $key => $product) {
        $subtotal = $product['product_price'] * $product['quantity'];
        $total += $subtotal;

        echo '<tr>';
        echo '<td><img src="' . $product['product_image'] . '" height="50"></td>';
        echo '<td>' . $product['product_name'] . '</td>';
        echo '<td>$' . number_format($product['product_price'], 2) . '</td>';
        echo '<td>' . $product['quantity'] . '</td>';
        echo '<td>$' . number_format($subtotal, 2) . '</td>';
        echo '<td><a href="delete_product.php?key=' . $key . '">Delete</a></td>';
        echo '</tr>';
    }

    echo '<tr>';
    echo '<td colspan="4" align="right"><strong>Total:</strong></td>';
    echo '<td>$' . number_format($total, 2) . '</td>';
    echo '<td></td>'; // Empty cell for action column
    echo '</tr>';

    echo '</table>';
    
    // Add Proceed to Checkout button
    echo '<br>';
    echo '<form action="ss.html" method="post">';
    echo '<input type="submit" name="checkout" value="Proceed to Checkout">';
    echo '</form>';
}
?>
