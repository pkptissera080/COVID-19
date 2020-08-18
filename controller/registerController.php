<?php

session_start();
include_once '../classes/DbConnecter.php';

if (isset($_GET['register']) && $_GET['register'] == 'true') {

    $con = Database::connectDB();

    $uein     = $_POST['uein'];
    $ufname    = $_POST['ufname'];
    $ulname = $_POST['ulname'];
    $uemail    = $_POST['uemail'];
    $upass    = $_POST['upass'];

    $md5_pass = md5($upass);
    $shal_pass = Sha1($md5_pass);
    $crypt_pass = crypt($shal_pass, "cv");

    $QueryEIN = $con->prepare("SELECT  uein FROM users WHERE uein = ?");
    $QueryEIN->execute(array($uein));
    if ($QueryEIN->rowCount() == 0) {
        $QueryEMAIL = $con->prepare("SELECT  uemail FROM users WHERE uemail = ?");
        $QueryEMAIL->execute(array($uemail));
        if ($QueryEMAIL->rowCount() == 0) {
            $Query = $con->prepare("INSERT INTO users (uein, ufname, ulname, uemail, upassword, utype) VALUES (?,?,?,?,?,?)");
            $Query->execute([$uein, $ufname, $ulname, $uemail, $crypt_pass, 'phi']);
            if ($Query) {
                echo json_encode(['status' => 'success', 'msg' => 'login.php']);
            }
        } else {
            echo json_encode(array('status' => 'email_fail'));
        }
    } else {
        echo json_encode(array('status' => 'ein_fail'));
    }
}
