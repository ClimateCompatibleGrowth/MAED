<?php
    require_once "../../config.php";
    $caseStudyId = $_COOKIE['titlecs'];
    $excelFile = USER_CASE_PATH.$caseStudyId."/result/Results.xlsx";
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=".$caseStudyId."_Results.xlsx");
    header("Content-Transfer-Encoding: binary");
    header("Content-Type: binary/octet-stream");
    readfile($excelFile);		
?>