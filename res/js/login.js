$(function () {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
});

$(document).ready(function () {

    var validateEmail = /^[a-z]+[0-9a-zA-Z_\.]*@[a-z_]+\.[a-z]+$/;
    //var validatePassword = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{6,}$/;

    // === Submit the register form ===
    $("#login_submit").click(function () {

        var log_uemail = $.trim($("#uemail_id").val());
        var log_upass = $.trim($("#upass_id").val());

        if (log_uemail.length == "") {
            $("#logErrDisplay").css('display', '');
            $("#logErrDisplay").html('Email is required !')

        } else if (validateEmail.test(log_uemail)) {

            if (log_upass.length == "") {
                $("#logErrDisplay").css('display', '');
                $("#logErrDisplay").html('Password is required !')
            } else {
                $.ajax({
                    type: 'POST',
                    url: '../controller/loginController.php?login=true',
                    data: $("#login_form").serialize(),
                    dataType: "JSON",
                    beforeSend: function () {
                        $("#login_submit").attr("disabled", true);
                        $("#login_submit").val("Logging in ....");
                    },
                    success: function (feedback) {
                        console.log(feedback);
                        if (feedback['status'] == 'success') {
                            $("#logSucDisplay").css('display', '');
                            $("#logSucDisplay").html("Success")
                            $("#login_submit").val("Login");
                            setTimeout(function () {
                                location = feedback['msg'];
                            }, 1000);

                        } else if (feedback['status'] == 'email_fail') {
                            $("#logErrDisplay").css('display', '');
                            $("#logErrDisplay").html("Email Not Found !")
                            $("#login_submit").attr("disabled", false);
                            $("#login_submit").val("Login");
                        } else if (feedback['status'] == 'pass_fail') {
                            $("#logErrDisplay").css('display', '');
                            $("#logErrDisplay").html("Incorrect Password !")
                            $("#login_submit").attr("disabled", false);
                            $("#login_submit").val("Login");
                        }
                    },
                    error: function (error) {
                        console.log(error);
                        $("#logErrDisplay").css('display', '');
                        $("#logErrDisplay").html(JSON.stringify(error.responseText))
                        $("#login_submit").attr("disabled", false);
                        $("#login_submit").val("Login");
                    }
                })
            }

        } else {
            $("#logErrDisplay").css('display', '');
            $("#logErrDisplay").html('Invalid Email !')
        }
    })

    //----------------------------------------register---------------------------------------warning

})

function clrErr() {
    document.getElementById("logErrDisplay").style.display = "none";
    document.getElementById("logSucDisplay").style.display = "none";
}