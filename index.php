<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
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
    <script src="function/ajax_user_check.js"></script>

    <title>PIC_DRIVE</title>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row m-0 pt-4">

            <div class="col-md-4 p-4 text-center">
                <img src="media/main_pic.jpg" alt="main-pic" width="80%" class="shadow bg-white rounded main_img">
            </div>

            <!-- signup coding start  -->
            <div class="col-md-4 d-flex flex-column py-4 pr-5">
                <div class="signup_box">SIGN UP</div>
                <form class="d-flex flex-column" autocomplete="off">
                    <input type="text" name="username" id="uname" class="mt-3 p-2 pl-4 inp_field" placeholder="Username"
                        required>

                    <div class="mt-3">
                        <input type="email" name="email" id="email" class="w-100 p-2 pl-4 inp_field" placeholder="Email"
                            required>
                        <i class="fa-solid fa-spinner fa-spin fa-lg d-none" id="email_togglePassword"
                            style="color:#ccc;cursor: pointer; position:absolute; margin-top:18px; margin-left:-35px;"></i>
                    </div>
                    <div class="mt-3">
                        <input type="password" name="password" id="pass" placeholder="Set your password" required
                            class=" w-100 p-2 pl-4 inp_field">
                        <i class="far fa-eye" id="togglePassword"
                            style="color:#ccc;cursor: pointer; position:absolute; padding-top:10px; margin-left:-35px"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3 text-center">
                        <p class="strong_pwd_txt pl-4">Click to generate strong password</p>
                        <div class="btn btn-light generate_btn">GENERATE</div>
                    </div>
                    <input type="submit" value="Register Now" class="mt-3 ml-4 text-center btn btn-dark"
                        id="submit_btn">
                </form>
                <span class="alert_msg pl-4 mt-3" style="font-size:18px; font-weight:700;">&nbsp; </span>
            </div>
            <!-- signup coding end  -->

            <!-- login coding start  -->
            <div class="col-md-4 d-flex flex-column py-4 pr-5">
                <div class="login_box">LOGIN</div>
                <form class="d-flex flex-column" autocomplete="off" id="login_form">
                    <input type="email" name="login_email" id="login_email" class="mt-3 p-2 pl-4 inp_field"
                        placeholder="Enter your registered email id" required>

                    <div class="mt-3">
                        <input type="password" name="password" id="login_pass" placeholder="Enter password" required
                            class=" w-100 p-2 pl-4 inp_field">
                        <i class="far fa-eye" id="login_eye_pass"
                            style="color:#ccc;cursor: pointer; position:absolute; padding-top:10px; margin-left:-35px"></i>
                    </div>
                    <input type="submit" value="Login Now" class="mt-3 ml-4 text-center btn btn-dark w-25"
                        id="login_btn">
                </form>
                <span class="alert_login_msg pl-4 mt-3" style="font-size:18px; font-weight:700;"></span>
            </div>
            <!-- login coding end  -->

        </div>
    </div>








    <script>
        $(document).ready(function () {

            // generate random password start
            $(".generate_btn").click(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "function/ajax.php",
                    data: {
                        gen_pass: "gen_pass"
                    },
                    cache: false,
                    success: function (result) {
                        $("#pass").val(result)
                    }
                });
            });

            $("#togglePassword").click(function () {
                if ($("#pass").attr("type") == "password") {
                    $("#pass").attr("type", "text");
                } else {
                    $("#pass").attr("type", "password");
                }
            });

            $("#login_eye_pass").click(function () {
                if ($("#login_pass").attr("type") == "password") {
                    $("#login_pass").attr("type", "text");
                } else {
                    $("#login_pass").attr("type", "password");
                }
            });
            // generate random password end

            // email id find in data base start
            $("#email").on("change", function () {
                uname = $("#uname").val();
                uemail = $("#email").val();
                upass = $("#pass").val();
                if ($("#email").val() !== "") {
                    $.ajax({
                        type: "POST",
                        url: "function/ajax.php",
                        data: {
                            user_check: "user_check",
                            uemail: uemail
                        },
                        beforeSend: function () {
                            $("#email_togglePassword").removeClass("d-none");
                        },
                        cache: false,
                        success: function (result) {
                            $("#email_togglePassword").addClass("d-none");
                            if (result.trim() > 0) {
                                $(".alert_msg").html("Email already registered!!");
                                $(".alert_msg").css("color", "red");
                                $("#submit_btn").attr("disabled", "disabled");
                            } else {
                                $("#submit_btn").removeAttr("disabled", "disabled");
                                $(".alert_msg").html("");
                            }
                        }
                    });
                };
            });
            // email id find in data base end

            // insert user data into database start
            $("#submit_btn").click(function (e) {
                e.preventDefault();
                uname = $("#uname").val();
                uemail = $("#email").val();
                upass = $("#pass").val();
                if (uname != "" && uemail != "" && upass != "") {

                    $.ajax({
                        type: "POST",
                        url: "function/ajax.php",
                        data: {
                            reg_succ: "reg_succ",
                            uname: uname,
                            uemail: uemail,
                            upass: upass
                        },
                        cache: false,
                        success: function (result) {
                            if (result.trim() =="success") {
                                $(".alert_msg").html("Signup Success, Please Login Now..");
                                $(".alert_msg").css("color", "green");
                                $("#uname").val("");
                                $("#email").val("");
                                $("#pass").val("");
                                setTimeout(function(){
                                    $(".alert_msg").html("");
                                },3000)
                            } else {
                                $(".alert_msg").html("Registration not completed, Please Retry");
                                $(".alert_msg").css("color", "red");
                            }
                        }
                    })
                } else {
                    $(".alert_msg").html("All input fields should be filled!!");
                    $(".alert_msg").css("color", "red");
                }
            });
            // insert user data into database end


            // login user coding start 
            $("#login_form").submit(function (e) {
                e.preventDefault();
                var login_email=$("#login_email").val();
                var login_pass=$("#login_pass").val();
                if(login_email!="" && login_pass!=""){
                    $.ajax({
                        type:"POST",
                        url: "function/ajax.php",
                        data: {
                            login_user_check: "check",
                            login_email:login_email,
                            login_pass:login_pass
                        },
                        cache: false,
                        success:function(result){
                            if(result.trim()=="user found"){
                                location.href = "profile/profile.php";   
                                                             
                            }else if(result.trim()=="user not found"){
                                $(".alert_login_msg").html("User not found, Please signup first!!");
                                $(".alert_login_msg").css("color", "red");
                                setTimeout(function(){
                                    $(".alert_login_msg").html("");                                        
                                },3000)                     
                            }else{
                                $(".alert_login_msg").html("Some error please contact technical person!!");
                                $(".alert_login_msg").css("color", "red");
                            }
                        }
                    })
                };
            });
            // login user coding end




        });
    </script>
</body>

</html>