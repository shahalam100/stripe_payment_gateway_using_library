<?php
require_once 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51OTzcRSGC8nTUlwdz8BqTuVPoFDJR1JUx77yXthby5eGyjC0VUnhE7l7FtILUIhTvnA92AAf7PMDkzCl19cvBVJz00Ck1qwQU0');

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost'; // Adjust this to your domain

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => 'Stubborn Attachments',
            ],
            'unit_amount' => 2000,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/ajax/success.html',
    'cancel_url' => $YOUR_DOMAIN . '/ajax/cancel.html',
    'billing_address_collection' => 'required',
]);

echo json_encode(['id' => $checkout_session->id]);
?>
