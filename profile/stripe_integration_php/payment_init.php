<?php

// Include the configuration file 
require_once 'config.php';

// Include the database connection file 
include_once 'dbConnect.php';

// Include the Stripe PHP library 
require_once 'stripe-php/init.php';

// Set API key 
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStr);

if ($jsonObj->request_type == 'create_payment_intent') {

    // Define item price and convert to cents 
    $itemPriceCents = round($itemPrice * 100);

    // Set content type to JSON 
    header('Content-Type: application/json');

    try {
        // Create PaymentIntent with amount and currency 
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $itemPriceCents,
            'currency' => $currency,
            'description' => $itemName,
            'payment_method_types' => [
                'card'
            ]
        ]);

        $output = [
            'id' => $paymentIntent->id,
            'clientSecret' => $paymentIntent->client_secret
        ];

        echo json_encode($output);
    } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($jsonObj->request_type == 'create_customer') {
    $payment_intent_id = !empty($jsonObj->payment_intent_id) ? $jsonObj->payment_intent_id : '';
    $name = !empty($jsonObj->name) ? $jsonObj->name : '';
    $email = !empty($jsonObj->email) ? $jsonObj->email : '';

    // Add customer to stripe 
    try {
        $customer = \Stripe\Customer::create(
            array(
                'name' => $name,
                'email' => $email
            )
        );
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    if (empty($api_error) && $customer) {
        try {
            // Update PaymentIntent with the customer ID 
            $paymentIntent = \Stripe\PaymentIntent::update($payment_intent_id, [
                'customer' => $customer->id
            ]);
        } catch (Exception $e) {
            // log or do what you want 
        }

        $output = [
            'id' => $payment_intent_id,
            'customer_id' => $customer->id
        ];
        echo json_encode($output);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $api_error]);
    }
} elseif ($jsonObj->request_type == 'payment_insert') {
    $payment_intent = !empty($jsonObj->payment_intent) ? $jsonObj->payment_intent : '';
    $customer_id = !empty($jsonObj->customer_id) ? $jsonObj->customer_id : '';

    // Retrieve customer info 
    try {
        $customer = \Stripe\Customer::retrieve($customer_id);
    } catch (Exception $e) {
        $api_error = $e->getMessage();
    }

    // Check whether the charge was successful 
    if (!empty($payment_intent) && $payment_intent->status == 'succeeded') {
        // Transaction details  
        $transaction_id = $payment_intent->id;
        $paid_amount = $payment_intent->amount;
        $paid_amount = ($paid_amount / 100);
        $paid_currency = $payment_intent->currency;
        $payment_status = $payment_intent->status;

        $customer_name = $customer_email = '';
        if (!empty($customer)) {
            $customer_name = !empty($customer->name) ? $customer->name : '';
            $customer_email = !empty($customer->email) ? $customer->email : '';
        }

        // Check if any transaction data is exists already with the same TXN ID 
        $sqlQ = "SELECT id FROM transactions WHERE txn_id = ?";
        $stmt = $db->prepare($sqlQ);
        $stmt->bind_param("s", $transaction_id);
        $stmt->execute();
        $stmt->bind_result($row_id);
        $stmt->fetch();

        $payment_id = 0;
        if (!empty($row_id)) {
            $payment_id = $row_id;
        } else {
            // Insert transaction data into the database 
            $sqlQ = "INSERT INTO transactions (customer_name,customer_email,item_name,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,created,modified) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())";
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param("sssdsdsss", $customer_name, $customer_email, $itemName, $itemPrice, $currency, $paid_amount, $paid_currency, $transaction_id, $payment_status);
            $insert = $stmt->execute();

            if ($insert) {
                $payment_id = $stmt->insert_id;
            }
        }

        $output = [
            'payment_txn_id' => base64_encode($transaction_id)
        ];
        echo json_encode($output);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Transaction has been failed!']);
    }
}

?>
Payment Status (payment-status.php)
Based on the paymentIntent.status the user is redirected to this page.

Fetch transaction data from the database using PHP and MySQL.
Display payment status with customer details.
<?php
// Include the configuration file  
require_once 'config.php';

// Include the database connection file  
require_once 'dbConnect.php';

$payment_ref_id = $statusMsg = '';
$status = 'error';

// Check whether the payment ID is not empty 
if (!empty($_GET['pid'])) {
    $payment_txn_id = base64_decode($_GET['pid']);

    // Fetch transaction data from the database 
    $sqlQ = "SELECT id,txn_id,paid_amount,paid_amount_currency,payment_status,customer_name,customer_email FROM transactions WHERE txn_id = ?";
    $stmt = $db->prepare($sqlQ);
    $stmt->bind_param("s", $payment_txn_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Get transaction details 
        $stmt->bind_result($payment_ref_id, $txn_id, $paid_amount, $paid_amount_currency, $payment_status, $customer_name, $customer_email);
        $stmt->fetch();

        $status = 'success';
        $statusMsg = 'Your Payment has been Successful!';
    } else {
        $statusMsg = "Transaction has been failed!";
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<?php if (!empty($payment_ref_id)) { ?>
    <h1 class="<?php echo $status; ?>">
        <?php echo $statusMsg; ?>
    </h1>

    <h4>Payment Information</h4>
    <p><b>Reference Number:</b>
        <?php echo $payment_ref_id; ?>
    </p>
    <p><b>Transaction ID:</b>
        <?php echo $txn_id; ?>
    </p>
    <p><b>Paid Amount:</b>
        <?php echo $paid_amount . ' ' . $paid_amount_currency; ?>
    </p>
    <p><b>Payment Status:</b>
        <?php echo $payment_status; ?>
    </p>

    <h4>Customer Information</h4>
    <p><b>Name:</b>
        <?php echo $customer_name; ?>
    </p>
    <p><b>Email:</b>
        <?php echo $customer_email; ?>
    </p>

    <h4>Product Information</h4>
    <p><b>Name:</b>
        <?php echo $itemName; ?>
    </p>
    <p><b>Price:</b>
        <?php echo $itemPrice . ' ' . $currency; ?>
    </p>
<?php } else { ?>
    <h1 class="error">Your Payment been failed!</h1>
    <p class="error">
        <?php echo $statusMsg; ?>
    </p>
<?php } ?>