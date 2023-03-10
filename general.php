<?php
require_once CLASS_PATH . "Data.class.php";
$caseStudyId = $_COOKIE['titlecs'];
$cg = 'geninf_data';
$dc = new Data($caseStudyId, $cg);
$aaData = $dc->getRow();

$str = file_get_contents(ROOT_FOLDER . "/app/files.json");
$fileNames = json_decode($str, true);

foreach ($fileNames as $key => $value)
{
    ${$key} = $value;
}

$DefaultStudy = $aaData['studyName'];
$DefaultYear = $aaData['Year'];
$AllYear = explode(",", $DefaultYear);

$maintype = Config1::getData('maintype', $caseStudyId);

if($maedtype=="maedd"){
    $DefaultCurrency = $aaData['Currency'];
    $DefaultUnit = $aaData['Unit'];
    $DefaultPop = $aaData['populationunit'];
    $DefaultEne = $aaData['energyunit'];
    $DefaultPas = $aaData['punit'];
    $Defaultfri = $aaData['funit'];
    $DefaultGdp = $aaData['Gdpunit'];

    $energyUnit['GWyr'] = 'kWh';
    $energyUnit['PJ'] = 'MJ';
    $energyUnit['Tcal'] = 'kcal';
    $energyUnit['Mtoe'] = 'kgoe';
    $energyUnit['GBTU'] = 'kBTU';

    $maintype = Config1::getData('maintype', $caseStudyId);
    $fueltype = Config1::getData('fueltype', $caseStudyId);
    $pentype = Config1::getData('pentype', $caseStudyId, true);
    $endtype = Config1::getData('endtype', $caseStudyId, true);
    $houtype = Config1::getData('houtype', $caseStudyId, true);
    $houendtype = Config1::getData('houendtype', $caseStudyId, true);
    $dweltype = Config1::getData('dweltype', $caseStudyId, true);
    $sertype = Config1::getData('sertype', $caseStudyId, true);
    $serendtype = Config1::getData('serendtype', $caseStudyId, true);
    $conversionfactor = Config1::getData('unitconversionfactor', $caseStudyId, true);
    $facmtype = Config1::getData('facmtype', $caseStudyId, true);
}

class Config1
{
    private function __construct()
    {
    }
    public static function getData($configName, $path, $common = false)
    {
        if ($common)
        {
            $a1=@file_get_contents(COMMON_DATA_FILE_PATH . $configName . "." . DATA_FILE_EXT);
        }
        else
        {
            $a1=@file_get_contents(USER_CASE_PATH . $path . "/" . $configName . "." . DATA_FILE_EXT);
        }

        $ob= simplexml_load_string($a1);
        $json = json_encode($ob);
        $array = json_decode($json,TRUE);
        $a=$array[$configName];
		if (count($a) === count($a, COUNT_RECURSIVE)) 
		{
			$a[0] = $a;
			$a = array_intersect_key($a, array_flip(array_filter(array_keys($a) , 'is_numeric')));
		}
        return $a;
    }
}
class Config2
{
    private function __construct()
    {
    }
    public static function getData($configName, $id, $field, $path)
    {
        $xmldoc = new DOMDocument();
        $xmldoc->load(USER_CASE_PATH . $path . "/" . $configName . "." . DATA_FILE_EXT);
        $rows = $xmldoc->getElementsByTagName($configName);
        foreach ($rows as $row)
        {
            $rowIds = $row->getElementsByTagName('id');
            $rowId = $rowIds->item(0)->nodeValue;
            if ($id == $rowId)
            {
                $rowVals = $row->getElementsByTagName($field);
                $rowVal = $rowVals->item(0)->nodeValue;
                return $rowVal;
            }
        }
        return false;
    }
}
?>
