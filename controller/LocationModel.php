<?php
include_once '../classes/DbConnecter.php';
include_once '../classes/User.php';

// Gets data from URL parameters.
if (isset($_GET['add_location'])) {
    add_location();
}
if (isset($_GET['confirm_location'])) {
    confirm_location();
}



function add_location()
{
    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $lat = $_GET['lat'];
    $lng = $_GET['lng'];
    $description = $_GET['description'];
    // Inserts new row with place data.
    $query = sprintf(
        "INSERT INTO locations " .
            " (id, lat, lng, description) " .
            " VALUES (NULL, '%s', '%s', '%s');",
        mysqli_real_escape_string($con, $lat),
        mysqli_real_escape_string($con, $lng),
        mysqli_real_escape_string($con, $description)
    );

    $result = mysqli_query($con, $query);
    echo "Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function confirm_location()
{
    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    $id = $_GET['id'];
    $confirmed = $_GET['confirmed'];
    // update location with confirm if admin confirm.
    $query = "update locations set location_status = $confirmed WHERE id = $id ";
    $result = mysqli_query($con, $query);
    echo "Inserted Successfully";
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
function get_confirmed_locations()
{
    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con, "
select id ,lat,lng,description,location_status as isconfirmed
from locations WHERE  location_status = 1
  ");

    $rows = array();

    while ($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;
    }

    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function get_all_locations()
{
    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con, "select id ,lat,lng,subject,description,pcr_rpt_img,location_status as isconfirmed from locations");

    $rows = array();
    while ($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;
    }
    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}

function get_myall_locations()
{
    $userid = unserialize($_SESSION['current_user'])->getUser_id();

    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con, "select id ,lat,lng,subject,description,pcr_rpt_img,location_status as isconfirmed from locations where officer_id = " . $userid . "");

    $rows = array();
    while ($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;
    }
    $indexed = array_map('array_values', $rows);
    //  $array = array_filter($indexed);

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
function array_flatten($array)
{
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}


if (isset($_POST["uploadReport"])) {
    $userid = unserialize($_SESSION['current_user'])->getUser_id();
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $subject = $_POST['subject'];
    $description = $_POST['Description'];

    // Get image name
    $image = $_FILES['pcrRptImg']['name'];

    // image file directory
    $target = "../res/pcrimg/" . basename($image);

    $con = Database::connectDB();

    $sql = "INSERT INTO locations (lat, lng, subject,description,pcr_rpt_img,location_status,officer_id) VALUES ('$lat', '$lng', '$subject', '$description', '$image', 0 ,$userid)";
    // execute query
    if ($con->query($sql) == TRUE) {
        //echo "New record created successfully";
        if (move_uploaded_file($_FILES['pcrRptImg']['tmp_name'], $target)) {
            //$msg = "Image uploaded successfully";
            echo "<script>window.location.replace(location);</script>";
        } else {
            $msg = "Failed to upload image";
        }
    } else {
        echo "Error: " . $sql . "<br>";
    }


    echo $msg . "<br>";
}
