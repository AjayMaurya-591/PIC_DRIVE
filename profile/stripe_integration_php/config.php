<?php 

session_start();
$user_id = $_SESSION["user_id"];
include '../../function/database_check.php';

define('STRIPE_API_KEY', 'sk_test_51OWzDOSIoAhYxMg8wnGqj1xRLLSGr7V9DkL1R3XPLSG5TzgiiSWszC9FhowE0tESrHRAmAI3ZhxDya8ionMcLWbr00dPmy9fLJ'); 
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51OWzDOSIoAhYxMg8tpwHR9aYRQLe88v2uO1JxHb48d8GptpivrzjauKLZmXFs6W64RcL1Vj0RwP8j59x20rCfsBf00mqA1rzOU'); 
  
// Database configuration  
define('DB_HOST', 'localhost');  
define('DB_USERNAME', 'root');  
define('DB_PASSWORD', 'root');  
define('DB_NAME', 'codexworld'); 

// update plan code start 
// $itemName = $_POST['name']; 
// $itemPrice = $_POST['amount'];  
// $currency = "USD";  
$itemName = "Demo Product"; 
$itemPrice = 25;  
$currency = "USD";  
    
    // if ($itemPrice == 99) {
    //     $storage = 1024;
    // }
    // if ($itemPrice == 500) {
    //     $storage = 0;
    // }
    // $sql_plan = "UPDATE `user_data` SET `plans`='" . $itemName . "', `storage`='" . $storage . "' WHERE id = '" . $user_id . "'";
    // $result = mysqli_query($conn, $sql_plan);
    // if($result){
    //     echo $itemName."-".$itemPrice."-".$currency;
    // }
?>