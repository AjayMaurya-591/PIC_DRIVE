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

        .buy_btn {
            font-size: 25px;
            font-weight: 600;
        }

        .plan {
            font-size: 25px;
            font-weight: 600;
        }


        .text_msg {
            color: green;
            font-size: 20px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <?php

    define('API_USERNAME', 'sb-ugxym27894743@business.example.com');
    define('API_PASSWORD', 'iI-E6R!w');
    define('API_SIGNATURE', 'EC26VQRZB8afiB1Ia2pLyKyE0q9-QpmhejRuGvSgYN5s870rNdv6F4v5ZUy26EHFURhhWkAKUG2D_E0C');
    define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
    define('USE_PROXY', FALSE);
    define('PROXY_HOST', '127.0.0.1');
    define('PROXY_PORT', '808');
    define('PAYPAL_URL', 'https://www.PayPal.com/webscr&cmd=_express-checkout&token=');
    define('VERSION', '53.0');


    include '../function/database_check.php';

    $user_id = $_SESSION["user_id"];
    $sql = "SELECT `storage`, `used_storage`, `plans` FROM `user_data` WHERE id= " . $user_id;
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $total_storage = $data["storage"];
    $used_storage = $data["used_storage"];
    $plan = $data['plans'];
    if ($plan == "starter") {
        $display_n = "d-none";
        $display_b = "d-block";
    } elseif ($plan == "exclusive") {
        $display_b = "d-none";
        $display_n = "d-block";
    } else {
        $display_b = "";
        $display_n = "";
    }

    ?>

    <div class="container-fluid p-0">
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="../logout.php" class="nav-link">LOGOUT</a></li>
            </ul>
        </nav>

        <div class="row m-0 p-4 d-flex flex-column justify-content-center align-items-center pt-4">
            <h2> Our Plans</h2>
            <span class=" text_msg">
                <!-- Plan Updated -->
            </span>
        </div>

        <div class="row m-0 p-3 d-flex justify-content-center align-items-center">

            <div class="col-md-5">
                <ul class="list-group w-100">
                    <li class="list-group-item bg-success">
                        <h3 class="text-center text-light">Starter Plan</h3>
                    </li>
                    <li class="list-group-item bold">1 GB STORAGE</li>
                    <li class="list-group-item" style="color:#ccc9c9">24 x TECHNICAL SUPPORT</li>
                    <li class="list-group-item" style="color:#ccc9c9">INSTANT EMAIL SOLUTION</li>
                    <li class="list-group-item" style="color:#ccc9c9">SEO SERVICE</li>
                    <li class="list-group-item" style="color:#ccc9c9">DATA SECURITY</li>
                    <li class="list-group-item bg-warning text-center text-light plan <?php echo $display_b; ?>">
                        Currently Active Plan</li>
                    <li class="list-group-item <?php echo $display_n; ?>">
                        <form action="stripe_integration_php/index.php" method="post"
                            class="shadow-lg btn buy_btn text-center display-2 starter_plan w-100">
                            <input type="hidden" name="amount" value="99">
                            <input type="hidden" name="name" value="starter">
                            <input type="submit" name="submit" value="$ 99.00 / Month" class="w-100">
                        </form>
                    </li>

                </ul>
            </div>

            <div class="col-md-1"></div>

            <div class="col-md-5">
                <ul class="list-group w-100">
                    <li class="list-group-item  bg-primary">
                        <h3 class="text-center text-light">Exclusive Plan</h3>
                    </li>
                    <li class="list-group-item bold">UNLIMITED STORAGE</li>
                    <li class="list-group-item">24 x TECHNICAL SUPPORT</li>
                    <li class="list-group-item">INSTANT EMAIL SOLUTION</li>
                    <li class="list-group-item">SEO SERVICE</li>
                    <li class="list-group-item">DATA SECURITY</li>
                    <li class="list-group-item bg-warning text-center text-light plan <?php echo $display_n; ?>">
                        Currently Active Plan</li>
                    <li class="list-group-item <?php echo $display_b; ?>">
                        <form action="stripe_integration_php/index.php" method="post"
                            class="shadow-lg btn buy_btn text-center display-2 starter_plan w-100">
                            <input type="hidden" name="amount" value="500">
                            <input type="hidden" name="name" value="exclusive">
                            <input type="submit" name="submit" value="$ 500.00 / Month" class="w-100">
                        </form>
                    </li>
                </ul>
            </div>

        </div>
    </div>

</body>

</html>