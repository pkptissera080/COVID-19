<?php
session_start();
include_once 'classes/User.php';
if (!isset($_SESSION["current_user"]) || $_SESSION["current_user"] == null) {
    /*header("Location:  access/login.php");*/
} else {
    $utype = unserialize($_SESSION['current_user'])->gettype();
    if ($utype == 'phi') {
        header("Location:  phi/");
    } else if ($utype == 'dg') {
        header("Location:  dg/");
    } else {
        /*header("Location:  access/login.php");*/
    }
}
?>
<!DOCTYPE html>
<html>
<title>Covid-19</title>
<link rel="icon" type="image/png" href="res/logo/logo_ico.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
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

    .w3-row-padding img {
        margin-bottom: 12px
    }

    .bgimg {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 100%;
    }

    /* The imgmodal (background) */
    .imgmodal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 4;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* imgmodal Content (image) */
    .imgmodal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }


    @-webkit-keyframes zoom {
        from {
            -webkit-transform: scale(0)
        }

        to {
            -webkit-transform: scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* The Close Button */
    #myimgclose {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    #myimgclose:hover,
    #myimgclose:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .imgmodal-content {
            width: 100%;
        }
    }

    .positiveClassPrevImg {
        width: 100px;
        height: 100px;
        float: right;
        cursor: pointer;
    }

    .fixed {
        position: fixed;
        top: 0;
        right: 0;
        border: 3px solid #73AD21;
        border-radius: 0px 0px 0px 20px;
    }
</style>

<body>

    <!-- Sidebar with image -->
    <nav class="w3-sidebar w3-hide-medium w3-hide-small" style="width:40%">
        <div class="bgimg" id="map"></div>
    </nav>



    <?php
    include_once 'controller/publicMap.php';
    ?>


    <!-- Page Content -->
    <div class="w3-main w3-padding-large" style="margin-left:40%">

        <a href="access/login.php" class="w3-btn fixed w3-green">Join us</a>

        <!-- Header -->
        <div class="w3-container w3-center">
            <h1 class="w3-wide"><img src="res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;"><br><b>Covid-19</b></h1>
            <p>Stay Home Stay Safe</p>
        </div>


        <div class="w3-content w3-justify w3-text-grey w3-padding" id="about">
            <h4>Positive cases ( <label id="con_count"></label> )</h4>
            <hr class="w3-opacity">
            <div class="w3-row-padding w3-margin-bottom" style="max-height: 400px;overflow:auto;">

                <?php
                $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
                if ($con->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $query = "SELECT * FROM locations WHERE location_status='1' ORDER BY datentime DESC";
                $result = mysqli_query($con, $query);
                $con_count = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $con_count++;
                    $imgdir = "'res/pcrimg/" . $row['pcr_rpt_img'] . "'";
                    echo '<div class="w3-full w3-padding" >
        <div class="w3-container w3-light-gray w3-padding-16 w3-leftbar w3-border-red">
          <table class="w3-table">
            <tr>
              <td><b>&#9929; ' . $row['subject'] . '</b></td>
              <td rowspan="2">
                <img src="res/pcrimg/' . $row['pcr_rpt_img'] . '" class="positiveClassPrevImg" onclick="viewImg(' . $imgdir . ')" >
              </td>
            </tr>
            <tr>
              <td>
                <div style="height:70px;overflow: auto;">' . $row['description'] . '</div>
              </td>
            </tr>
            <tr>
              <td style="font-size: 10px;color:light-gray;">';
                    $officer_id = $row['officer_id'];
                    $query2 = "SELECT * FROM users WHERE uid = $officer_id ";
                    $result2 = mysqli_query($con, $query2);
                    if ($row2 = mysqli_fetch_array($result2)) {
                        echo '
                Created by : <b>' . $row2['ufname'] . ' ' . $row2['ulname'] . '</b>';
                    }
                    echo '  <i>' . $row['datentime'] . '</i>
              </td>
            </tr>
          </table>
        </div>
        </div>';
                }
                echo '<script>document.getElementById("con_count").innerHTML = "' . $con_count . '";</script>';
                ?>
            </div>

        </div>

        <!-- About Section -->
        <div class="w3-content w3-justify w3-text-grey w3-padding" id="about">
            <h4>About</h4>
            <hr class="w3-opacity">
            <p>The Epidemiology Unit of Ministry of is about to improve their web site to track the number of COVID-19 patients around Sri Lanka , where the health officers can report positive cases and the director general of health services can confirm</p>
            <p>On 31 December 2019, the WHO Country Office for China was informed about cases of pneumonia of unknown cause in Wuhan, China. Authorities identified a new type of coronavirus, subsequently named COVID-19. On 30 January 2020, WHO Director-General declared the outbreak of COVID-19 a Public Health Emergency of International Concern (PHEIC), and on 11 March 2020, COVID-19 was characterized as a pandemic. </p>
            <p>The Ministry of Health and Indigenous Medical Services in collaboration with the WHO Country Office for Sri Lanka continue to closely monitor the situation and to strengthen preparedness and response efforts. We are closely working with key partners to effectively and efficiently combat COVID-19. More than six months into this pandemic, Sri Lanka has made great progress in controlling the spread of COVID-19; however, the threat is not over yet. We must build on the progress made to keep ourselves, our loved ones, and our communities safe, healthy, and thriving.</p>
            <p>Rumors and misinformation can facilitate transmission and cost lives. We encourage everyone to seek high-quality, valid, sources of information that empower us to stay healthy. Solidarity is one of our most formidable weapons. We all have a part to play; it is not a choice between lives and livelihoods.</p>
            <img src="res/img/logo_english.png" alt="" style="width: 100%;">

            <!-- End About Section -->
        </div>

        <!-- The Modal -->
        <div id="myimgModal" class="imgmodal">
            <span id="myimgclose">&times;</span>
            <img class="imgmodal-content" id="img01view">
            <div id="caption"></div>
        </div>

        <!-- Footer -->
        <footer class="w3-padding w3-light-grey w3-small w3-center" id="footer">
            <div class="w3-row">
                <p><i class="fa fa-fw fa-map-marker"></i> 231 De Saram Pl, Colombo 01000</p>
                <p><i class="fa fa-fw fa-phone"></i> 0112 695 112</p>
                <p><i class="fa fa-fw fa-envelope"></i> EpidemiologyUnitofMinistryofHealth@mail.com</p>
                <br>
                <i class="fa fa-facebook-official w3-hover-opacity w3-large"></i>
                <i class="fa fa-instagram w3-hover-opacity w3-large"></i>
                <i class="fa fa-snapchat w3-hover-opacity w3-large"></i>
                <i class="fa fa-pinterest-p w3-hover-opacity w3-large"></i>
                <i class="fa fa-twitter w3-hover-opacity w3-large"></i>
                <i class="fa fa-linkedin w3-hover-opacity w3-large"></i>
            </div>
        </footer>

        <div class="w3-black w3-center w3-padding-24">Stay Home | Stay Safe <img src="res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;"><b>Covid-19</b></div>

        <!-- END PAGE CONTENT -->
    </div>

    <script>
        // Get the <span> element that closes the modal
        var span = document.getElementById("myimgclose");
        var myimgModal = document.getElementById("myimgModal");
        var modalImg = document.getElementById("img01view");

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            myimgModal.style.display = "none";
        }

        function viewImg(xx) {
            myimgModal.style.display = "block";
            modalImg.src = xx;
        }

        function enlargeimgdgmap() {
            var imgUlr = document.getElementById('imgdgmap').src;
            viewImg(imgUlr);
        }
    </script>

</body>

</html>