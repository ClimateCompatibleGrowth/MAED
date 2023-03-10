<?php
require_once("../../config.php");
setcookie("l", LOGIN,time() + (86400 * 30), "/");
if(LOGIN==0){
//get users
$url=ROOT_FOLDER.'/Auth/us.json';
$file = (file_get_contents($url));
$users=json_decode($file, true);
$username="admin";
//validate
$login=true;
for ($i=0; $i<count($users); $i++) {
    if (strtolower($username)==strtolower($users[$i]["username"])) {
        $group=$users[$i]["usergroup"];
        $isadmin=$users[$i]["isadmin"];
        $maedtype=$users[$i]["maedtype"];
        $language=$users[$i]["language"];
        $decimal=$users[$i]['decimal'];

        $_SESSION['us'] = $username; 
        $_SESSION['gr'] = $group; 
        $_SESSION['maedtype'] = $maedtype; 
        $_SESSION['isadmin'] = $isadmin; 
        $_SESSION['language'] = $language; 
        $_SESSION['decimal'] = $decimal; 
        setcookie("maedtype", $maedtype,time() + (86400 * 30), "/");
        setcookie("langCookie", $language, time() + (86400 * 30), "/");
        setcookie("decimal", $decimal,time() + (86400 * 30), "/");
    }
}
}
    echo json_encode(LOGIN);

?>