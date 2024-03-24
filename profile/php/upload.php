<?php
session_start();
$user_id = $_SESSION["user_id"];

include '../../function/database_check.php';


$file = $_FILES["data"];
$user_file_name = $file["name"];
$user_tmp_name = $file["tmp_name"];
$destination_path = "../gallary/user_" . $user_id . "/" . $user_file_name;

$user_file_size_bytes = $file["size"] / 1024 / 1024;
$user_file_size = number_format((float) $user_file_size_bytes, 2, '.', '');

$sql2 = "SELECT storage, used_storage FROM `user_data` WHERE id=$user_id";
$result2 = mysqli_query($conn, $sql2);
$data = mysqli_fetch_assoc($result2);
$total_storage = $data["storage"];
$used_storage = $data["used_storage"] + $user_file_size;

$sql4 = "SELECT data_id FROM user_" . $user_id . " WHERE image_name = '" . $user_file_name . "'";
$result4 = mysqli_query($conn, $sql4);
if ($result4) {
    if (mysqli_num_rows($result4) == 0) {
        if ($result2) {
            if ($total_storage != 0) {
                if ($total_storage > $used_storage) {
                    $sql = "INSERT INTO user_" . $user_id . " (image_name, image_size, image_path)
                VALUES ('$user_file_name', '$user_file_size', '$destination_path')";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $sql3 = "UPDATE `user_data` SET `used_storage`='$used_storage' WHERE id=$user_id";
                        $result3 = mysqli_query($conn, $sql3);
                        if ($result3) {
                            move_uploaded_file($user_tmp_name, $destination_path);


                            $sql5 = "SELECT * FROM user_" . $user_id;
                            $result5 = mysqli_query($conn, $sql5);

                            $sql6 = "SELECT storage, used_storage FROM user_data WHERE id=" . $user_id;
                            $result6 = mysqli_query($conn, $sql6);
                            $data6 = mysqli_fetch_assoc($result6);

                            $updated_img_count = mysqli_num_rows($result5);
                            $used_storage = $data6['used_storage'];
                            $tot_storage = $data6['storage'];

                            $updated_db_values = array('img_count' => $updated_img_count, 'new_used_storage' => $used_storage, 'tot_storage' => $tot_storage);
                            $json_data = json_encode($updated_db_values);

                            echo "success";
                        }
                    }
                } else {
                    echo "space full";
                }
            } else {

                $sql = "INSERT INTO user_" . $user_id . " (image_name, image_size, image_path)
                VALUES ('$user_file_name', '$user_file_size', '$destination_path')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $sql3 = "UPDATE `user_data` SET `used_storage`='$used_storage' WHERE id=$user_id";
                    $result3 = mysqli_query($conn, $sql3);
                    if ($result3) {
                        move_uploaded_file($user_tmp_name, $destination_path);


                        $sql5 = "SELECT * FROM user_" . $user_id;
                        $result5 = mysqli_query($conn, $sql5);

                        $sql6 = "SELECT storage, used_storage FROM user_data WHERE id=" . $user_id;
                        $result6 = mysqli_query($conn, $sql6);
                        $data6 = mysqli_fetch_assoc($result6);

                        $updated_img_count = mysqli_num_rows($result5);
                        $used_storage = $data6['used_storage'];
                        $tot_storage = $data6['storage'];

                        $updated_db_values = array('img_count' => $updated_img_count, 'new_used_storage' => $used_storage, 'tot_storage' => $tot_storage);
                        $json_data = json_encode($updated_db_values);

                        echo "success";
                    }
                }
            }
        } else {
            echo "error";
        }
    } else {
        echo "already uploaded";
    }
} else {
    echo "already uploaded";
}

?>