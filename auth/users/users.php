<?php
require_once("../../config.php");
require_once("../login/constants.php");
require_once("../login/validate.php");
require_once("../login/pbkdf2.php");

$action=$_POST['action'];

$users=array();

//get users
$url=ROOT_FOLDER.'/auth/us.json';
$file = (file_get_contents($url));
$users=json_decode($file, true);

//get groups
$urlgroups=ROOT_FOLDER.'/auth/gr.json';
$filegroups = (file_get_contents($urlgroups));
$groups=json_decode($filegroups, true);

if($action=="getusers"){
    echo json_encode($users);
}

if($action=="getgroups"){
    echo json_encode($groups);
}

//change password
if ($action=="changepassword") {
    $error='';
    $valid=true;
    $username=$_SESSION['us'];
    $currentpassword=$_POST['currentpassword'];
    $newpassword=$_POST['newpassword'];

    //checks
    if ($currentpassword==$newpassword) {
        $valid=false;
        $error="New password is the same like current<br/>";
    }

//find user
    $currentpass=false;
    for ($i=0; $i<count($users); $i++) {
        if ($username==$users[$i]["username"]) {
            $currentpass=validate_password($currentpassword, $users[$i]["password"]);
        }
    }

//current pass wrong
    if (!$currentpass) {
        $valid=false;
        $error.="Current password is wrong<br/>";
    }

//new pass is valid
    $newpassvalid=valid_pass($newpassword);
    if ($newpassvalid!==true) {
        $valid=false;
        $error.=$newpassvalid;
    }

    if ($valid) {
        for ($i=0; $i<count($users); $i++) {
            if ($username==$users[$i]["username"]) {
                $users[$i]["password"]=create_hash($newpassword);
            }
        }

        $us= $jsondata = json_encode($users, true);
        if (file_put_contents($url, $us)) {
            echo "success";
        }
    } else {
        echo $error;
    }
}

//add new user
if ($action=="adduser") {
    $valid=true;
    $error='';
    $username=$_POST['username'];
    $password=$_POST['password'];
    $group=$_POST['usergroup'];
    $isadmin=$_POST['isadmin'];
    $language=$_POST['language'];
    $maedtype=$_POST['maedtype'];

    //check if user exist
    $userexist=false;
    for ($i=0; $i<count($users); $i++) {
        if ($username==$users[$i]["username"]) {
            $userexist=true;
            $error.='Username alredy exist';
        }
    }

    if ($userexist) {
        $valid=false;
    }

    //check password policy
    $passvalid=valid_pass($password);
    if ($passvalid!==true) {
        $valid=false;
        $error.=$passvalid;
    } else {
        $password=create_hash($password);
    }
    

    if ($valid) {
        $users[]=array("username"=>$username, "password"=>$password, "usergroup"=>$group, "isadmin"=>$isadmin, "language"=>$language, "maedtype"=>$maedtype);
        
        $us= json_encode($users, true);
        if (file_put_contents($url, $us)) {
            echo "success";
        }
    } else {
        echo $error;
    }
}

//add new user
if ($action=="updateuser") {

    $username=$_POST['username'];
    $usergroup=$_POST['usergroup'];
    $isadmin=$_POST['isadmin'];
    $language=$_POST['language'];
    $maedtype=$_POST['maedtype'];
    $decimal=$_POST['decimal'];
    
    for ($i=0; $i<count($users); $i++) {
        if ($username==$users[$i]["username"]) {
            $users[$i]["usergroup"]=$usergroup;
            $users[$i]["isadmin"]=$isadmin;
            $users[$i]["language"]=$language;
            $users[$i]["maedtype"]=$maedtype;
            $users[$i]["decimal"]=$decimal;
        }
    }
        
    $us= json_encode($users, true);
    if (file_put_contents($url, $us)) {
        echo "success";
    }

}

//delete user
if ($action=="deleteuser") {
    $username=$_POST['username'];
    for ($i=0; $i<count($users); $i++) {
        if ($username==$users[$i]["username"]) {
            unset($users[$i]);
        }
    }
    
    $us = json_encode($users, true);
    if (file_put_contents($url, $us)) {
        echo "success";
    }
}

//add new group
if ($action=="addgroup") {
    $valid=true;
    $error='';
    $name=$_POST['name'];

    //check if group exist
    $groupexist=false;
    for ($i=0; $i<count($groups); $i++) {
        if ($name==$groups[$i]["name"]) {
            $groupexist=true;
            $error.='Group alredy exist';
        }
    }

    if ($groupexist) {
        $valid=false;
    }    

    if ($valid) {
        $groups[]=array("name"=>$name);
        
        $gr= json_encode($groups, true);
        if (file_put_contents($urlgroups, $gr)) {
            echo "success";
        }
        if (!file_exists(PROJECT_DATA_FILE_PATH . $name))
        {
            mkdir(PROJECT_DATA_FILE_PATH . $name, 0777, true);
        }
    } else {
        echo $error;
    }
}

//delete group
if ($action=="deletegroup") {
    $name=$_POST['name'];
    for ($i=0; $i<count($groups); $i++) {
        if ($name==$groups[$i]["name"]) {
            unset($groups[$i]);
        }
    }
    
    $gr = json_encode($groups, true);
    if (file_put_contents($urlgroups, $gr)) {
        echo "success";
    }
}

//create hash
function create_hash($password)
{
    // format: algorithm:iterations:salt:hash
    //$salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTES, MCRYPT_DEV_URANDOM));
    $salt = base64_encode(random_bytes(PBKDF2_SALT_BYTES));
    return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" .  $salt . ":" .
        base64_encode(pbkdf2(
            PBKDF2_HASH_ALGORITHM,
            $password,
            $salt,
            PBKDF2_ITERATIONS,
            PBKDF2_HASH_BYTES,
            true
        ));
}

function valid_pass($candidate)
{
    $r1='/[A-Z]/';  
    $r2='/[a-z]/';  
    $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';
    $r4='/[0-9]/';  
 
    if (preg_match_all($r1, $candidate, $o)<1) {
        return 'Minimum one uppercase';
    }
 
    if (preg_match_all($r2, $candidate, $o)<1) {
        return 'Minimum one lowercase';
    }
 
    if (preg_match_all($r3, $candidate, $o)<1) {
        return 'Minimum on special character !@#$%^&*()\-_=+{};:,<.>';
    }
 
    if (preg_match_all($r4, $candidate, $o)<1) {
        return 'Minimum one number';
    }
 
    if (strlen($candidate)<8) {
        return 'Minimum length 8';
    }
 
    return true;
}
