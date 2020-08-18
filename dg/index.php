<?php
session_start();
include_once '../classes/User.php';
if (isset($_SESSION["current_user"]) && $_SESSION["current_user"] != null) {
    $firstName = unserialize($_SESSION['current_user'])->getFname();
    $lastName = unserialize($_SESSION['current_user'])->getLname();
    $userid = unserialize($_SESSION['current_user'])->getUser_id();
    $usertype = unserialize($_SESSION['current_user'])->getType();
    if ($usertype != 'dg') {
        header('location:  ../');
    }
}
?>
<!DOCTYPE html>
<html>
<title>Covid-19</title>
<link rel="icon" type="image/png" href="../res/logo/logo_ico.png">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../res/css/index.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .w3-sidebar a {
    font-family: "Roboto", sans-serif
  }

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<body class="w3-content" style="max-width:1200px">

  <!-- Sidebar/menu -->
  <nav class="w3-sidebar w3-bar-block w3-white w3-collapse w3-top" style="z-index:3;width:250px" id="mySidebar">
    <div class="w3-container w3-display-container w3-padding-16">
      <i onclick="w3_close()" class="fa fa-remove w3-hide-large w3-button w3-display-topright"></i>
      <h3 class="w3-wide"><img src="../res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;"><b>Covid-19</b></h3>
    </div>

    <div class="chip">
      <img src="../res/img/img_avatar.png" alt="Person" width="96" height="96">
      <?php echo $firstName . ' ' . $lastName; ?>
    </div>

    <div class="w3-padding-32 w3-text-grey">
      <a href="index.php" class="w3-bar-item w3-button w3-leftbar w3-border-green">Home<i class="fa fa-home w3-margin-left"></i></a>
      <a href="about-us.php" class="w3-bar-item w3-button w3-leftbar">About us<i class="fa fa-info-circle w3-margin-left"></i></a>
      <a href="contact-us.php" class="w3-bar-item w3-button w3-leftbar">Contact us<i class="fa fa-comments w3-margin-left"></i></a>
      <a href="../access/login.php" class="w3-bar-item w3-button w3-leftbar">logout<i class="fa fa-sign-out w3-margin-left"></i></a>
    </div>
  </nav>

  <!-- Top menu on small screens -->
  <header class="w3-bar w3-top w3-hide-large w3-black w3-xlarge">
    <div class="w3-bar-item w3-padding-24 w3-wide"><img src="../res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;">Covid-19</div>
    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-padding-24 w3-right" onclick="w3_open()"><i class="fa fa-bars"></i></a>
  </header>

  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

  <!-- !PAGE CONTENT! -->
  <div class="w3-main" style="margin-left:250px">

    <!-- Push down content on small screens -->
    <div class="w3-hide-large" style="margin-top:83px"></div>

    <!-- Top header -->
    <header class="w3-container w3-xlarge">
      <p class="w3-left">Map</p>
    </header>

    <!-- Image header -->
    <div class="w3-display-container w3-container">
      <div id="map" style="height: 700px;width:100%;"></div>
    </div>

    <?php
    include_once '../controller/dgMap.php';
    include_once '../controller/dgindexController.php';
    ?>

    <br><br>
    <div class="w3-container w3-text-grey">
      Covid-19 Positive cases ( <label id="all_count"></label> )
      <hr>
      <p><img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" alt=""> Confirmed positive cases ( <label id="con_count"></label> )</p>
    </div>

    <div class="w3-row-padding w3-margin-bottom" style="max-height: 480px;overflow:auto;">

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
        $imgdir = "'../res/pcrimg/" . $row['pcr_rpt_img'] . "'";
        echo '<div class="w3-half w3-padding" >
        <div class="w3-container w3-light-gray w3-padding-16">
          <table class="w3-table">
            <tr>
              <td><b>&#9929; ' . $row['subject'] . '</b></td>
              <td rowspan="2">
                <img src="../res/pcrimg/' . $row['pcr_rpt_img'] . '" class="positiveClassPrevImg" onclick="viewImg(' . $imgdir . ')" >
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
              <td>
                <div style="float:right;">
                  <table>
                    <tr>
                      <td>
                        <form action="" method="post">
                          <input type="text" style="display: none;" name="pcid" value="' . $row['id'] . '">
                          <button class="w3-button w3-red fa fa-trash" type="submit" name="rejectReport" style="float: right;border-radius: 5px;" title="Delete"></button>
                        </form>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
        </div>';
      }
      echo '<script>document.getElementById("con_count").innerHTML = "' . $con_count . '";</script>';
      ?>
    </div>

    <hr>


    <div class="w3-container w3-text-grey">
      <p><img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt=""> Unconfirmed positive cases ( <label id="uncon_count"></label> )</p>
    </div>

    <div class="w3-row-padding w3-margin-bottom" style="max-height: 500px;overflow:auto;">
      <?php
      $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
      if ($con->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      $query = "SELECT * FROM locations WHERE location_status='0'  ORDER BY datentime DESC";
      $result = mysqli_query($con, $query);
      $uncon_count = 0;
      while ($row = mysqli_fetch_array($result)) {
        $uncon_count++;
        $imgdir = "'../res/pcrimg/" . $row['pcr_rpt_img'] . "'";
        echo '<div class="w3-half w3-padding" >
        <div class="w3-container w3-light-gray w3-padding-16">
          <table class="w3-table">
            <tr>
              <td><b>&#9929; ' . $row['subject'] . '</b></td>
              <td rowspan="2">
                <img src="../res/pcrimg/' . $row['pcr_rpt_img'] . '" class="positiveClassPrevImg" onclick="viewImg(' . $imgdir . ')" >
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
              <td>
                <div style="float:right;">
                  <table>
                    <tr>
                      <td>
                        <form action="" method="post">
                          <input type="text" style="display: none;" name="pcid" value="' . $row['id'] . '">
                          <button class="w3-button w3-green" type="submit" name="approveReport" style="float: right;border-radius: 5px;" title="Approve">&#10004;</button>
                        </form>
                      </td>
                      <td>
                        <form action="" method="post">
                          <input type="text" style="display: none;" name="pcid" value="' . $row['id'] . '">
                          <button class="w3-button w3-red" type="submit" name="rejectReport" style="float: right;border-radius: 5px;"  title="Reject">&#10008;</button>
                        </form>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
        </div>';
      }
      echo '<script>document.getElementById("uncon_count").innerHTML = "' . $uncon_count . '";</script>';
      ?>

    </div>


    <!-- The Modal -->
    <div id="myimgModal" class="imgmodal">
      <span id="myimgclose">&times;</span>
      <img class="imgmodal-content" id="img01view">
      <div id="caption"></div>
    </div>


    <!-- Footer -->
    <footer class="w3-padding-64 w3-light-grey w3-small w3-center" id="footer">
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

    <div class="w3-black w3-center w3-padding-24">Stay Home | Stay Safe <img src="../res/logo/logo_ico.png" alt="Covid-19" style="width: 50px;height:50px;"><b>Covid-19</b></div>

    <!-- End page content -->
  </div>

  <!-- Newsletter Modal -->
  <div id="newsletter" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom" style="padding:32px">
      <div class="w3-container w3-white w3-center">
        <i onclick="document.getElementById('newsletter').style.display='none'" class="fa fa-remove w3-right w3-button w3-transparent w3-xxlarge"></i>
        <h2 class="w3-wide">NEWSLETTER</h2>
        <p>Join our mailing list to receive updates on new arrivals and special offers.</p>
        <p><input class="w3-input w3-border" type="text" placeholder="Enter e-mail"></p>
        <button type="button" class="w3-button w3-padding-large w3-red w3-margin-bottom" onclick="document.getElementById('newsletter').style.display='none'">Subscribe</button>
      </div>
    </div>
  </div>

  <script src="../res/js/index.js"></script>

</body>

</html>