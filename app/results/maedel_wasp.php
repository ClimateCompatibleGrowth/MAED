<?php
    require_once "../../config.php";
    $caseStudyId = $_COOKIE['titlecs'];
    $loadsyFile = DATA_FILE_PATH.'wasp/' . $caseStudyId . ".dat";
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=".$caseStudyId.".dat");
    header("Content-Transfer-Encoding: binary");
    header("Content-Type: binary/octet-stream");
    readfile($loadsyFile);		
?>