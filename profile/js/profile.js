
$(document).ready(function () {
    $(".upload_btn").click(function () {
        var input = document.createElement("INPUT");
        input.type = "file";
        input.name = "data";
        input.accept = "image/*";
        input.click();

        input.onchange = function () {
            var file = new FormData();
            file.append("data", this.files[0]);
            $.ajax({
                type: "POST",
                url: "../profile/php/upload.php",
                data: file,
                contentType: false,
                processData: false,
                cache: false,
                success: function (result) {
                    if (result.trim() == "success") {
                        $(".text_msg").html("Successfully Uploaded");
                        $(".text_msg").css("color", "rgb(0, 193, 0)");
                        setTimeout(function () {
                            $(".text_msg").html("");
                        }, 2000);

                        $.ajax({
                            type: "POST",
                            url: "js/update_db_data.php",
                            data:{
                                check_memory_percent:"memory_status"
                            },
                            cache: false,
                            success: function(data1){
                                $(".percent_used").html(data1);
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "js/update_db_data.php",
                            data:{
                                check_memory_status:"memory_status"
                            },
                            cache: false,
                            success: function(data2){
                                $(".used_storage").html(data2);
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "js/update_db_data.php",
                            data:{
                                img_cont:"memory_status"
                            },
                            cache: false,
                            success: function(data3){
                                $(".img_cont").html(data3);
                            }
                        });

                    } else if (result.trim() == "space full") {
                        $(".text_msg").html("Space full !!!");
                        $(".text_msg").css("color", "red");
                        setTimeout(function () {
                            $(".text_msg").html("");
                        }, 2000)
                    } else if (result.trim() == "already uploaded") {
                        $(".text_msg").html("already uploaded !!!");
                        $(".text_msg").css("color", "red");
                        setTimeout(function () {
                            $(".text_msg").html("");
                        }, 2000)
                    } else if (result.trim() == "error") {
                        $(".text_msg").html("Some error !!!");
                        $(".text_msg").css("color", "red");
                        setTimeout(function () {
                            $(".text_msg").html("");
                        }, 2000)
                    } else {
                        alert(result+"error");
                    }
                }
            });
        };
    });
});


