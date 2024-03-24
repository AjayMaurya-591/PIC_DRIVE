<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location:../index.php');
    exit();
}
include '../../function/database_check.php';

$user_id = $_SESSION["user_id"];

if (isset($_POST['check_memory_percent'])) {
    $sql = "SELECT `storage`, `used_storage` FROM `user_data` WHERE id= " . $user_id;
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $total_storage = $data["storage"];
    $used_storage = $data["used_storage"];

    if($total_storage!=0){
    $balance_storage = $total_storage - $used_storage;
    $percent_storage_used = round(($used_storage * 100) / $total_storage, 2);
    }else{
        $percent_storage_used="Unlimeted Plan";
        $balance_storage="unlimited plan";
    }
    echo $percent_storage_used;
}

if (isset($_POST['check_memory_status'])) {
    $sql = "SELECT `storage`, `used_storage` FROM `user_data` WHERE id= " . $user_id;
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $used_storage = $data["used_storage"];
    echo $used_storage;
}

if (isset($_POST['img_cont'])) {
    $sql2 = "SELECT count(data_id) AS total FROM user_" . $user_id;
    $result2 = mysqli_query($conn, $sql2);
    $data2 = mysqli_fetch_assoc($result2);
    $total_img = $data2["total"];
    echo $total_img;
}


?>