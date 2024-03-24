<?php
session_start();

include 'database_check.php';

// generate strong password code start 
if (isset($_POST['gen_pass'])) {
    $varchar = "abjklrsty}/<>zABCMNOHI#%PQDEFGmnopq^&*JKLRST;':,.UVWX]{?YZ012cdefghi3456789!@()_+-=[uvwx";
    $i;
    $unique_pass = [];
    for ($i = 0; $i < 8; $i++) {
        $num = rand(1, 87);
        $unique_char[] = $varchar[$num];
    }
    $unique_pass = implode($unique_char);
    echo ($unique_pass);
}
// generate strong password code end


// check user already registered in database code start 
if (isset($_POST["user_check"])) {
    $uemail = $_POST["uemail"];
    $sql = "SELECT * FROM user_data WHERE email= '$uemail'";
    $result = mysqli_query($conn, $sql);
    $data_length = mysqli_num_rows($result);
    echo $data_length;
}
// check user already registered in database code end


// insert user data into database start 
if (isset($_POST["reg_succ"])) {
    $uname = $_POST["uname"];
    $uemail = $_POST["uemail"];
    $upass = $_POST["upass"];

    $sql = "INSERT INTO user_data (name, email, password) VALUES ('$uname','$uemail','$upass')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "success";
    }
}
// insert user data into database end


// check login user in data base start 
if (isset($_POST["login_user_check"])) {
    $login_email = $_POST["login_email"];
    $login_pass = $_POST["login_pass"];

    $sql = "SELECT id FROM user_data WHERE email= '$login_email' AND password='$login_pass'";
    $result = mysqli_query($conn, $sql);
    $data_length = mysqli_num_rows($result);

    if ($data_length !== 0) {
        $data = mysqli_fetch_assoc($result);
        $user_id = implode($data);

        // code to check the user_table available or not 
        $check_individual_table = "SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = 'pic_drive'
          AND table_name = 'user_" . $user_id . "'";
        $result2 = mysqli_query($conn, $check_individual_table);
        $result2_length = mysqli_num_rows($result2);

        if ($result2_length == 0) {
            $sql3 = "CREATE TABLE user_" . $user_id . " (
                data_id INT NOT NULL AUTO_INCREMENT,
                image_name VARCHAR(255),
                image_size FLOAT(10),
                image_path VARCHAR(255),
                image_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (data_id)
            );";
            $result3 = mysqli_query($conn, $sql3);
            if ($result3) {
                mkdir("../profile/gallary/user_".$user_id);
                $_SESSION["user_id"]= $user_id;
                echo "user found";
            } else {
                echo "error";
            }
        } else {
            $_SESSION["user_id"]= $user_id;
            echo "user found";
        }
    } else {
        echo "user not found";
    }
}
// check login user in data base end

?>