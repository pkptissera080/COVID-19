<?php

if (isset($_POST["delPhiPC"])) {
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


if (isset($_POST["UpdateReport"])) {

    $edid = $_POST['edid'];
    $edSubject = $_POST['edSubject'];
    $edDescription = $_POST['edDescription'];
    $edkeepimg = $_POST['edkeepimg'];
    $ednewImg = $_FILES['ednewImg'];
    $edimgstate = $_POST['edimgstate'];


    $con = mysqli_connect(Database::$host, Database::$username, Database::$password, Database::$dbname);
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }

    if ($edimgstate == "1") {
        // Get image name
        $image = $_FILES['ednewImg']['name'];
        if ($image != '') {
            $finalImage = $image;
            $target = "../res/pcrimg/" . basename($image);
        } else {
            $finalImage = $edkeepimg;
            $target = "../res/pcrimg/" . basename($edkeepimg);
        }
    } else {
        $finalImage = $edkeepimg;
        $target = "../res/pcrimg/" . basename($edkeepimg);
    }

    $sql = "UPDATE locations SET subject='$edSubject',description='$edDescription',pcr_rpt_img='$finalImage' WHERE id=$edid";

    if ($con->query($sql) === TRUE) {
        //echo "Record updated successfully";
        if (move_uploaded_file($_FILES['ednewImg']['tmp_name'], $target)) {
            $msg = "Image uploaded successfully";
            echo "<script>window.location.replace(location);</script>";
        } else {
            $msg = "Failed to upload image";
        }
    } else {
        echo "Error updating record: " . $con->error;
    }
    //echo $msg . "<br>";
}
