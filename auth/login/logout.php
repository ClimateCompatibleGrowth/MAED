<?php 
session_start();
session_destroy();
if (isset($_COOKIE['langCookie'])) {
    unset($_COOKIE['langCookie']); 
    setcookie('langCookie', null, -1, '/'); 
}

if (isset($_COOKIE['maedtype'])) {
    unset($_COOKIE['maedtype']); 
    setcookie('maedtype', null, -1, '/'); 
}

if (isset($_COOKIE['decimal'])) {
    unset($_COOKIE['decimal']); 
    setcookie('decimal', null, -1, '/'); 
}

echo "1";
?>