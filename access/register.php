<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <title>Covid-19 | Register</title>
    <link rel="icon" type="image/png" href="../res/logo/logo_ico.png">
    <link rel="stylesheet" type="text/css" href="../res/css/access.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .w3-wide {
            font-family: "Montserrat", sans-serif;
        }
    </style>

<body>

    <div class="accessbox">
        <center onclick="location.replace('../');" style="cursor: pointer;">
            <img src="../res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;">
            <h2 class="w3-wide"><b>Covid-19</b></h2>
        </center>
        <br>
        <form method="post" id="register_form">

            <div style="max-height: 350px;overflow:auto;" class="w3-padding">
                <p title="Employer Identification Number"><i class="fa fa-id-badge w3-margin-right" aria-hidden="true"></i>E.I.N</p>
                <input type="number" class="w3-small" name="uein" id="uein_id" placeholder="Enter Your EIN" required onfocus="clrErr()">
                <p><i class="fa fa-circle w3-margin-right" aria-hidden="true"></i>First name</p>
                <input type="text" class="w3-small" name="ufname" id="ufname_id" placeholder="Enter Your First name" required onfocus="clrErr()">
                <p><i class="fa fa-circle w3-margin-right" aria-hidden="true"></i>Last name</p>
                <input type="text" class="w3-small" name="ulname" id="ulname_id" placeholder="Enter Your Last name" required onfocus="clrErr()">
                <p><i class="fa fa-envelope w3-margin-right" aria-hidden="true"></i>Email</p>
                <input type="email" class="w3-small" name="uemail" id="uemail_id" placeholder="Enter Your Email" required onfocus="clrErr()">
                <p><i class="fa fa-key w3-margin-right" aria-hidden="true"></i>Password</p>
                <input type="password" class="w3-small" name="upass" id="upass_id" placeholder="Enter Your Password" required onfocus="clrErr()">
                <p><i class="fa fa-key w3-margin-right" aria-hidden="true"></i>Retype Password</p>
                <input type="password" class="w3-small" name="urepass" id="urepass_id" placeholder="Retype Your Password" required onfocus="clrErr()">
            </div>
            <br>
            <div class="successalert" id="regSucDisplay" style="display: none;"></div>
            <div class="dangeralert" id="regErrDisplay" style="display: none;"></div>
            <input type="button" name="registerSubmit" id="register_submit" value="Register">
            <a href="login.php">Already have an account? <b>Login</b></a>
        </form>
    </div>



    <script src="../res/js/register.js"></script>
</body>
</head>

</html>