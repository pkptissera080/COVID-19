<?php
include_once 'classes/DbConnecter.php';


function get_allConfirmed_locations()
{

    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // update location with location_status if admin location_status.
    $sqldata = mysqli_query($con, "select id ,lat,lng,subject,description,pcr_rpt_img,location_status as isconfirmed from locations WHERE  location_status = 1");

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

