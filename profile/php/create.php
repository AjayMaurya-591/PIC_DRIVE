<?php
// your_php_file.php

// Retrieve JSON data sent from the previous PHP file
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Access the values as PHP variables
$updated_img_count_received = $data['img_count'];
$used_storage_received = $data['new_used_storage'];
$tot_storage_received = $data['tot_storage'];

// Now you can use these variables as needed in this PHP file
echo "Received values: img_count = $updated_img_count_received, used_storage = $used_storage_received, tot_storage = $tot_storage_received";
?>