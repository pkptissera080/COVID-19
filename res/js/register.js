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
    var validatePassword = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]{6,}$/;

    // === Submit the register form ===
    $("#register_submit").click(function () {

        var reg_uein = $.trim($("#uein_id").val());
        var reg_ufname = $.trim($("#ufname_id").val());
        var reg_ulname = $.trim($("#ulname_id").val());
        var reg_uemail = $.trim($("#uemail_id").val());
        var reg_upass = $.trim($("#upass_id").val());
        var reg_urepass = $.trim($("#urepass_id").val());

        if (reg_uein.length == "") {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('E.I.N is required !')
        } else if (reg_uein.length != 8) {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('invalid E.I.N ! , required 8 digit code')
        } else if (reg_ufname.length == "") {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('First name is required !')
        } else if (reg_ulname.length == "") {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('Last name is required !')
        } else if (reg_uemail.length == "") {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('Email is required !')
        } else if (validateEmail.test(reg_uemail)) {

            if (reg_upass.length == "") {
                $("#regErrDisplay").css('display', '');
                $("#regErrDisplay").html('Password is required !')
            } else if (validatePassword.test(reg_upass)) {
                if (reg_urepass.length == "") {
                    $("#regErrDisplay").css('display', '');
                    $("#regErrDisplay").html('Retype your password!')
                } else if (reg_upass == reg_urepass) {
                    $.ajax({
                        type: 'POST',
                        url: '../controller/registerController.php?register=true',
                        data: $("#register_form").serialize(),
                        dataType: "JSON",
                        beforeSend: function () {
                            $("#register_submit").attr("disabled", true);
                            $("#register_submit").val("Registering ....");
                        },
                        success: function (feedback) {
                            console.log(feedback);
                            if (feedback['status'] == 'success') {
                                $("#regSucDisplay").css('display', '');
                                $("#regSucDisplay").html("Success")
                                $("#register_submit").val("Registered !");
                                setTimeout(function () {
                                    location = feedback['msg'];
                                }, 1000);

                            } else if (feedback['status'] == 'email_fail') {
                                $("#regErrDisplay").css('display', '');
                                $("#regErrDisplay").html("This Email address already has an Account.")
                                $("#register_submit").attr("disabled", false);
                                $("#register_submit").val("Register");
                            } else if (feedback['status'] == 'ein_fail') {
                                $("#regErrDisplay").css('display', '');
                                $("#regErrDisplay").html("This Employer Identification Number already have an Account.")
                                $("#register_submit").attr("disabled", false);
                                $("#register_submit").val("Register");
                            }
                        },
                        error: function (error) {
                            console.log(error);
                            $("#regErrDisplay").css('display', '');
                            $("#regErrDisplay").html(JSON.stringify(error.responseText))
                            $("#register_submit").attr("disabled", false);
                            $("#register_submit").val("Register");
                        }
                    })
                } else {
                    $("#regErrDisplay").css('display', '');
                    $("#regErrDisplay").html('Password mismatch!')
                    $("#urepass_id").val('');
                }
            } else {
                $("#regErrDisplay").css('display', '');
                $("#regErrDisplay").html('Your password must be at least 6 characters and contain at least one uppercase, lowercase, number and <b>do not include special characters</b>.')
            }
        } else {
            $("#regErrDisplay").css('display', '');
            $("#regErrDisplay").html('Invalid Email !')
        }
    })

    //----------------------------------------register---------------------------------------warning

})

function clrErr() {
    document.getElementById("regErrDisplay").style.display = "none";
    document.getElementById("regSucDisplay").style.display = "none";
}