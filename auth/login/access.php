<?php
require_once("../../config.php");
if (isset($_SESSION['us'])){
    echo $_SESSION['us'].'|'.$_SESSION['gr'].'|'.$_SESSION['isadmin'];
}else{
    echo "-1";
}
?>