<?php
require_once './products/config.php';

// Check if the purchase button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase'])) {
    // Your purchase logic here

    // Display a success message after purchase
    echo '<script>alert("Thank you for purchasing!"); window.location.href = "index.html";</script>';
}

// Your existing PHP code for displaying the sales invoice
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart'])) {
    // Decode JSON-encoded cart data
    $cart = json_decode($_POST['cart'], true);

    if (is_array($cart)) {
        // Initialize variables for total price and invoice HTML
        $totalPrice = 0;
        $invoiceHTML = '<h2 class="invoice-title">Sales Invoice</h2>';
        $invoiceHTML .= '<div class="table-responsive">';
        $invoiceHTML .= '<table class="table table-bordered invoice-table">';
        $invoiceHTML .= '<thead class="thead-light"><tr><th scope="col">Product ID</th><th scope="col">Product Name</th><th scope="col">Quantity</th><th scope="col">Price</th><th scope="col">Total</th></tr></thead>';
        $invoiceHTML .= '<tbody>';

        // Iterate over the cart items and insert them into the database
        foreach ($cart as $productId => $product) {
            $stmt = $pdo->prepare('INSERT INTO purchases (product_id, quantity, price, purchase_date) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$productId, $product['quantity'], $product['price']]);

            // Calculate total price and add product details to invoice table
            $subtotal = $product['quantity'] * $product['price'];
            $totalPrice += $subtotal;
            $invoiceHTML .= "<tr><td>$productId</td><td>{$product['title']}</td><td>{$product['quantity']}</td><td>₱{$product['price']}</td><td>₱$subtotal</td></tr>";
        }

        // Add total price row to the invoice table
        $invoiceHTML .= "<tr><td colspan='4' class='text-right'><strong>Total Price:</strong></td><td><strong>₱$totalPrice</strong></td></tr>";
        $invoiceHTML .= '</tbody>';
        $invoiceHTML .= '</table>';
        $invoiceHTML .= '</div>';


        // Display the sales invoice
        echo $invoiceHTML;
    } else {
        echo "Invalid cart data format.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .invoice-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        .invoice-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .invoice-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .invoice-table tbody tr:hover {
            background-color: #e9ecef;
        }

        .invoice-table .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="invoice-title">Sales Invoice</h2>
        <!-- Your invoice table and content here -->

        <!-- Purchase button -->
        <form action="" method="POST">
            <div class="form-group text-center">
                <button type="submit" name="purchase" class="btn btn-success">Purchase</button>
            </div>
        </form>

        <!-- Back button -->
        <div class="text-center">
            <a href="index.html" class="btn btn-primary">Back</a>
        </div>
    </div>
</body>
</html>