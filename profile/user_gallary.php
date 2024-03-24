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

        .img_img {
            min-width: 290px;
            height: 200px;
            object-fit: cover;
        }

        .image_container,
        .image_container div {
            justify-content: space-between;
            min-width: 300px;
        }

        .card {
            min-width: 300px;
        }

        .delete_btn {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php
    include '../function/database_check.php';
    $user_id = $_SESSION["user_id"];
    ?>

    <div class="container-fluid p-0">
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Navbar</a>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="../logout.php" class="nav-link">LOGOUT</a></li>
            </ul>
        </nav>
        <div class="container-fluid d-flex image_container">
            <div class="row">
                <?php
                $sql = "SELECT * FROM user_" . $user_id;
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) !== 0) {
                    while ($data = mysqli_fetch_assoc($result)) {
                        $img_full_name = $data['image_name'];
                        $img_name = pathinfo($data['image_name']);
                        $img_name = $img_name['filename'];
                        $image_id = $data["data_id"];
                        $image_path = $data["image_path"];
                        $img_src = "gallary/user_" . $user_id . "/" . $data['image_name'];

                        echo "
                      <div class='col-md-3 my-2'>
                          <div class='card '>
                            <div class='border card-body d-flex justify-content-center align-items-center'>
                              <img src='" . $img_src . "' alt='abc' class='img_img'>
                            </div>
                            <div class='card-footer d-flex justify-content-around align-items-center'>
                                <span><b>" . $img_name . "</b></span>
                                <i class='fas fa-edit edit_btn'></i>
                                <i class='fa-solid fa-download download_btn'></i>
                                <i class='fa-solid fa-trash delete_btn'></i>

                                <input type='hidden' name='img_id' class='img_id' value='" . $image_id . "'>
                                <input type='hidden' name='img_full_name' class='img_full_name' value='" . $img_full_name . "'>
                            </div>
                          </div>
                      </div>";
                    }
                } else {
                    echo "No Img Available";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $(".delete_btn").on('click', function () {

                var img_name = $(this).parent().find('span').text();
                var img_id = $(this).siblings('.img_id').val();
                var img_full_name = $(this).siblings('.img_full_name').val();
                var parent_div= $(this).parent().parent().parent();
                var img_directory = "gallary/user_<? echo $user_id; ?>/".img_full_name;

                $.ajax({
                    type: "POST",
                    url: "php/edit.php",
                    data: {
                        del_img: "del_img",
                        img_id: img_id,
                        img_full_name: img_full_name,
                        img_name: img_name
                    },
                    success: function (result) {
                        if (result.trim() == "success") {
                            $(parent_div).fadeOut();
                        }else{
                            alert(result);
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>