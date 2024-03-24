<?php
session_start();
$user_id = $_SESSION["user_id"];
include '../../function/database_check.php';

if (isset($_POST["del_img"])) {

    $img_id = $_POST["img_id"];
    $img_full_name = $_POST["img_full_name"];
    $img_name = $_POST["img_name"];

    $sql2 = "SELECT image_size FROM user_" . $user_id . " WHERE data_id = '" . $img_id . "'";
    $result2 = mysqli_query($conn, $sql2);
    $data2 = mysqli_fetch_assoc($result2);
    $del_img_size = $data2['image_size'];

    if ($result2) {
        $sql4 = "SELECT used_storage FROM user_data WHERE id = '" . $user_id . "'";
        $result4 = mysqli_query($conn, $sql4);
        $data4 = mysqli_fetch_assoc($result4);
        $prev_img_size = $data4['used_storage'];

        if ($result4) {
            $sql = "DELETE FROM user_" . $user_id . " WHERE data_id=" . $img_id;
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $img_directory = "../gallary/user_" . $user_id . "/" . $img_full_name;
                unlink($img_directory);

                $updated_used_storage = $prev_img_size - $del_img_size;
                $sql3 = "UPDATE `user_data` SET `used_storage`='" . $updated_used_storage . "' WHERE id=$user_id";
                $result3 = mysqli_query($conn, $sql3);
                if ($result3) {
                    echo "success";
                }
            }
        }
    }
}




?>