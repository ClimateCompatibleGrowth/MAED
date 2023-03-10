<?php
/*
 * Password hashing with PBKDF2.
 * Author: havoc AT defuse.ca
 * www: https://defuse.ca/php-pbkdf2.htm
 */
require_once("../../config.php");
require_once("constants.php");
require_once("validate.php");
require_once("pbkdf2.php");
// username and password sent from form 
$username=$_POST['username'];
$password=$_POST['password'];
$users=array();

//get users
$url=ROOT_FOLDER.'/Auth/us.json';
$file = (file_get_contents($url));
$users=json_decode($file, true);

//validate
$login=false;
for ($i=0; $i<count($users); $i++) {
    if (strtolower($username)==strtolower($users[$i]["username"])) {
        $login=validate_password($password, $users[$i]["password"]);
        if ($login){
            $group=$users[$i]["usergroup"];
            $isadmin=$users[$i]["isadmin"];
            $maedtype=$users[$i]["maedtype"];
            $language=$users[$i]["language"];
            $decimal=$users[$i]['decimal'];
        }
    }
}

if ($login) {
    if($maedtype==null) $maedtype="maedd";
    if($language==null) $language="en";
    if($decimal==null) $decimal=3;
    $_SESSION['us'] = $username; 
    $_SESSION['gr'] = $group; 
    $_SESSION['isadmin'] = $isadmin; 
    setcookie("maedtype", $maedtype,time() + (86400 * 30), "/");
    setcookie("langCookie", $language, time() + (86400 * 30), "/");
    setcookie("decimal", $decimal,time() + (86400 * 30), "/");
    $url="../../app.html";
}else{
    $url="../../index.html?e=1";
}
echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
?>