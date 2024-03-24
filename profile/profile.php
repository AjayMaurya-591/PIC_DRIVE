<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header('Location:../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <script src="js/profile.js"></script>
    <title>Pic-drive-profile</title>
    <style>
        body {
            background: #ffd1d2 !important;
        }

        .click_btn:hover {
            cursor: pointer;
            box-shadow: 3px 3px 25px 5px #ccc;
        }

        .text_msg {
            font-weight: 600;
            position: absolute;
        }
    </style>
</head>

<body>
    <?php

    include '../function/database_check.php';

    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM `user_data` WHERE id= " . $user_id;
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $total_storage = $data["storage"];
    $used_storage = $data["used_storage"];
    $plan=$data["plans"];
    $name=$data["name"];

    if ($total_storage != 0) {
        $balance_storage = $total_storage - $used_storage;
        $percent_storage_used = round(($used_storage * 100) / $total_storage, 2)."% storage is used";
        $mem_used= $used_storage." MB / ".$total_storage." MB";
    }else{
        $percent_storage_used="Unlimited Plan";
        $balance_storage = "Unlimited Plan";
        $mem_used= $used_storage." MB Used";
    }

    if ($percent_storage_used > 80) {
        $bar_color = "bg-danger";
    } else {
        $bar_color = "bg-primary";
    }

    $sql2 = "SELECT count(data_id) AS total FROM user_" . $user_id;
    $result2 = mysqli_query($conn, $sql2);
    $data2 = mysqli_fetch_assoc($result2);
    $total_img = strtoupper($data2["total"]);


    ?>

    <div class="container-fluid p-0">
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="#"><?php echo "Welcome ".$name; ?></a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="../logout.php" class="nav-link">LOGOUT</a></li>
            </ul>
        </nav>

        <div class="row m-0 p-4 d-flex flex-column justify-content-center align-items-center pt-4">
            <h4 class=" text_msg "></h4>
        </div>

        <div class="row m-0 p-3">

            <div class="col-md-3 p-3 border d-flex flex-column align-items-center">

                <!-- upload div code start -->
                <div
                    class="bg-light w-75 py-2 mb-4 d-flex flex-column justify-content-center align-items-center upload_btn click_btn">
                    <i class="fa-solid fa-file fa-2xl p-5" style="font-size:80px;"></i>
                    <h3 class="m-0">Upload file</h3>
                    <p class="pt-1">Free Space:
                        <?php echo $balance_storage; ?>
                    </p>
                    <p class="p-0 m-0"><b>
                            <span class="percent_used">
                                <?php echo $percent_storage_used; ?>
                            </span>
                        </b></p>

                </div>
                <!-- upload div code end -->

                <!-- memory status div code start  -->
                <div class="bg-light w-75 py-3 d-flex flex-column justify-content-center align-items-center"><i
                        class="fa-solid fa-database fa-2xl p-5" style="font-size:80px;"></i>
                    <h3>Memory Status</h3>
                    <p>
                        <?php echo $mem_used; ?>
                        
                    </p>
                    <div class="progress w-50 mb-2" style="height:5px;">
                        <div class="progress-bar <?php echo $bar_color; ?>"
                            style="width:<?php echo $percent_storage_used; ?>%;"></div>
                    </div>

                </div>
                <!-- memory status div code end  -->
            </div>


            <!-- center container code start  -->
            <div class="col-md-6 p-3 border d-flex flex-column justify-content-center align-items-center ">
                <div class="bg-light p-5 mb-4 d-flex flex-column justify-content-center align-items-center"><i
                        class="fa-solid fa-shop fa-2xl p-5" style="font-size:80px;"></i>
                    <h3>Remove</h3>
                </div>
            </div>
            <!-- center container code end  -->



            <div class="col-md-3 p-3 border d-flex flex-column align-items-center">
                <!-- gallary div code start  -->

                <div
                    class="bg-light w-75 py-4 mb-4 d-flex flex-column justify-content-center align-items-center user_gallary click_btn">
                    <i class="fa-regular fa-image fa-2xl p-5" style="font-size:80px;"></i>
                    <h3>My Gallary </h3>
                    <hp>
                        <span class="img_cont">
                            <?php echo $total_img; ?>
                        </span> Photos
                        </p>
                </div>

                <!-- gallary div code end  -->

                <div
                    class="bg-light w-75 py-4 mb-4 d-flex flex-column justify-content-center align-items-center shop_btn click_btn">
                    <i class="fa fa-shopping-cart fa-2xl p-5" style="font-size:80px;"></i>
                    <h3>Storage Plans</h3>
                    <p>Current Plan: <?php echo $plan; ?></p>
                </div>
            </div>
            <!-- <a href="user_gallary.php"> -->
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".user_gallary").on('click', function () {
                location.href = "user_gallary.php";
            })

            $(".shop_btn").on('click', function () {
                location.href = "shop_page.php";

            })
        });


    </script>
</body>

</html>