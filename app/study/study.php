<?php
require_once "../../config.php";
require_once CLASS_PATH . "Study.class.php";

$ps=array();
$maedtype = $_COOKIE['maedtype'];
$lang = $_COOKIE['langCookie'];
$study=new Study($maedtype);

switch ($_POST['action'])
{
    case 'get':
        echo Study::getAll();
    break;
    case 'create':
        $studyName = $_POST['studyname'];
        $desc = $_POST['desc'];

        if (!file_exists(USER_CASE_PATH . $studyName))
        {
            mkdir(USER_CASE_PATH . $studyName, 0777, true);
            $data = array();
            $data['studyName'] = $studyName;
            $data['Year'] = date("Y");
            if ($maedtype == 'maedd')
            {
                $data['populationunit'] = 'Million';
                $data['Gdpunit'] = 'Billion';
                $data['Currency'] = 'US$';
                $data['energyunit'] = 'GWyr';
                $data['punit'] = 'Billion';
                $data['funit'] = 'Billion';
                $data['Unit'] = 'Million';
            }
            $data['Desc'] = $desc;
            $study->create($data, $lang);
            echo Study::getAll();
        }
        else
        {
            echo "exists";
        }
    break;

    case 'delete':
        $studyName = $_POST['studyname'];
        if (file_exists(USER_CASE_PATH . $studyName))
        {
            $study->delete(USER_CASE_PATH . $studyName);
            echo Study::getAll();
        }
    break;

    case 'copy':
        $studyName = $_POST['studyname'];
        $source = USER_CASE_PATH . $studyName;
        $destination = USER_CASE_PATH . $studyName . " - Copy";
        if (!is_dir($destination))
        {
            mkdir($destination);
            chmod($destination, 0777);
        }
        $study->copy($source, $destination);
        echo Study::getAll();
    break;

    case 'backup':
        $studyNameOriginal = $_POST['studynameoriginal'];
        $studyNameNew = $_POST['studynamenew'];
        $source = USER_CASE_PATH . $studyNameOriginal . '/';
        if ($studyNameNew != '')
        {
            $studyName = $studyNameNew . '.zip';
        }else{
            $studyName = $studyNameOriginal . '.zip';
        }
        
        if (!is_dir(BACKUP_PATH))
        {
            mkdir(BACKUP_PATH, 0777, true);
        }
        $destination = BACKUP_PATH . $studyName;

        $study->backup($studyNameOriginal, $studyName);
        if (!is_file($destination))
        {
            $url="../../app.html";
            echo "<script type='text/javascript'>document.location.href='{$url}';</script>";
        }
        else
        {
            header("Content-Type: octet/stream");
            header("Content-Disposition: attachment; filename=\"" . $studyName . "\"");
            $fp = fopen($destination, "r");
            $data = fread($fp, filesize($destination));
            fclose($fp);
            print $data;
        }
        if (is_file($destination))
        {
            unlink($destination);
        }       
    break;

    case 'restore':
        $source = $_FILES['files']['tmp_name'][0];
        if (!is_dir(BACKUP_PATH))
        {
            mkdir(BACKUP_PATH);
            chmod(BACKUP_PATH, 0777);
        }
        $ext = pathinfo($_FILES['files']['name'][0], PATHINFO_EXTENSION);
        if ($ext=="zip" || $ext=="rar"){
        $study -> restore($source, BACKUP_PATH);
        $studyName = scandir(BACKUP_PATH)[2];
        $studyPath=BACKUP_PATH . $studyName;
        $study -> allowedFiles($studyPath);

        if (
            (file_exists($studyPath."/geninf_data.xml")) && 
        (
            ($maedtype=="maedd" && file_exists($studyPath."/dweltype.xml")) || ($maedtype=="maedel" && file_exists($studyPath."/typedaydef_data.xml"))
        )
        ) {
            $studyNameNew=$studyName;
            $i = 1;
            while (file_exists(USER_CASE_PATH . $studyNameNew))
            {
                $studyNameNew = (string)$studyNameNew . "(" . $i . ")";
                $i++;
            }
            $study->copy($studyPath, USER_CASE_PATH . $studyNameNew);
            $study->delete($studyPath);
            echo Study::getAll();
        }
        else
        {
            $study->delete($studyPath);
            echo "wrongformat";
        }
    }else{
        echo "wrongformat";
    }
    break;

    default:
    break;
}

