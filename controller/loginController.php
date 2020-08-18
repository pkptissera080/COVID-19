<?php

session_start();
include_once '../classes/DbConnecter.php';
include_once '../classes/User.php';

if (isset($_GET['login']) && $_GET['login'] == 'true') {

    $lgemail = $_POST['uemail'];
    $lgpassword = $_POST['upass'];

    $md5_pass = md5($lgpassword);
    $shal_pass = Sha1($md5_pass);
    $crypt_pass = crypt($shal_pass, "cv");

    $con = Database::connectDB();
    $Query = $con->prepare("SELECT * FROM users WHERE uemail = ?");
    $Query->execute(array($lgemail));
    if ($Query->rowCount() != 0) {
        while ($r = $Query->fetch(PDO::FETCH_BOTH)) {
            $db_password = $r["upassword"];
            if ($crypt_pass == $db_password) {

                $user_obj = new User();
                $user_obj->setUser_id($r["uid"]);
                $user_obj->setFname($r["ufname"]);
                $user_obj->setLname($r["ulname"]);
                $user_obj->setEmail($r["uemail"]);
                $user_obj->setType($r["utype"]);

                $_SESSION["current_user"] = serialize($user_obj);

                if($r["utype"] == 'dg'){
                    echo json_encode(['status' => 'success', 'msg' => '../dg/']);
                }else if($r["utype"] == 'phi'){
                    echo json_encode(['status' => 'success', 'msg' => '../phi/']);
                }else{
                    echo json_encode(['status' => 'success', 'msg' => '../access/login.php']);
                }
                
            } else {
                echo json_encode(array('status' => 'pass_fail'));
            }
        }
    } else {
        echo json_encode(array('status' => 'email_fail'));
    }
}
