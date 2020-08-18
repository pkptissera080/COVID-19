<?php

if (isset($_POST["approveReport"])) {
    $pcid = $_POST['pcid'];

    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // sql to delete a record
    $sql = "UPDATE locations SET location_status='1' WHERE id=$pcid";

    if ($con->query($sql) === TRUE) {
        //echo "Record deleted successfully";
        echo "<script>window.location.replace(location);</script>";
    } else {
        echo "Error deleting record: " . $con->error;
    }
}

if (isset($_POST["rejectReport"])) {
    $pcid = $_POST['pcid'];

    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }
    // sql to delete a record
    $sql = "DELETE FROM locations WHERE id=$pcid";

    if ($con->query($sql) === TRUE) {
        //echo "Record deleted successfully";
        echo "<script>window.location.replace(location);</script>";
    } else {
        echo "Error deleting record: " . $con->error;
    }
}

?>