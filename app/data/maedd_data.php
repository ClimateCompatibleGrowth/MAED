<?php
require_once "../../config.php";
require_once CLASS_PATH."Data.class.php";
require_once BASE_PATH."general.php";

$householdUnit['GWyr'] = 'Wh';
$householdUnit['PJ'] = 'kJ';
$householdUnit['Tcal'] = 'cal';
$householdUnit['Mtoe'] = 'goe';
$householdUnit['GBTU'] = 'BTU';

$results['maintype'] = $maintype;
$results['allyear'] = $AllYear;
$results['casestudyid'] = $caseStudyId;
$results['defaultunit'] = $DefaultUnit;
$results['defaultpop'] = $DefaultPop;
$results['defaultcurrency'] = $DefaultCurrency;
$results['user_path'] = USER_CASE_PATH;
$results['defaultene'] = $energyUnit[$DefaultEne];

if(file_exists(USER_CASE_PATH.$caseStudyId."/datanotes.json"))
    $dataNotes= json_decode(file_get_contents(USER_CASE_PATH.$caseStudyId."/datanotes.json"), true);

$action = $_REQUEST['action'];
$id = $_REQUEST['id'];
$results['datanotes']=$dataNotes[$id];
switch ($id)
{
    case 'economic_demography':
        $xml = new Data($caseStudyId, $acxml);
        if ($action == 'get')
        {
            $acData = $xml->getByField(1, 'SID');
            $results['acData'] = $acData;
        }
    break;

    case 'economic_gdp':
    case 'economic_gdp_subsectors':
        $xml = new Data($caseStudyId, $adxml);
        if ($action == 'get')
        {
            $acd = new Data($caseStudyId, $acxml);
            $acData = $acd->getByField(1, 'SID');
            $adData = $xml->getByField(1, 'SID');
            $results['acData'] = $acData;
            $results['adData'] = $adData;
            $results['defaultgdp'] = $DefaultGdp;
            $results['gdp_text'] = $GDP_TEXT;
        }
    break;

    case 'service_factors':
        $xml = new Data($caseStudyId, $bgxml);
        if ($action == 'get')
        {
            $bgData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $acd = new Data($caseStudyId, $acxml);
            $acData = $acd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['bgData'] = $bgData;
            $results['acData'] = $acData;
            $results['unittypeKU'] = Config2::getData('unittype', 'KU', 'val', $caseStudyId);
            $results['unittypeNU'] = Config2::getData('unittype', 'NU', 'val', $caseStudyId);
        }
    break;

    case 'service_intensity':
        $xml = new Data($caseStudyId, $bhxml);
        if ($action == 'get')
        {
            $bhData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bhData'] = $bhData;
            $results['bjData'] = $bjData;
            $results['serendtype'] = $serendtype;
        }
    break;

    case 'service_penetration':
        $xml = new Data($caseStudyId, $bixml);
        if ($action == 'get')
        {
            $biData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['biData'] = $biData;
            $results['bjData'] = $bjData;
            $results['serendtype'] = $serendtype;
            $results['sertype'] = $sertype;
        }
    break;

    case 'household_factors_urban':
        $xml = new Data($caseStudyId, $bexml);
        if ($action == 'get')
        {
            $beData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['beData'] = $beData;
            $results['bjData'] = $bjData;
            $results['dweltype'] = $dweltype;
            $results['houendtype'] = $houendtype;
            $results['houtype'] = $houtype;
            $results['unit_degree_days'] = Config2::getData('unittype', 'CU', 'val', $caseStudyId);
            $results['defaulteneh'] = $householdUnit[$DefaultEne];
        }
    break;

    case 'household_factors_rural':
        $xml = new Data($caseStudyId, $blxml);
        if ($action == 'get')
        {
            $blData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['blData'] = $blData;
            $results['bjData'] = $bjData;
            $results['dweltype'] = $dweltype;
            $results['houendtype'] = $houendtype;
            $results['houtype'] = $houtype;
            $results['unit_degree_days'] = Config2::getData('unittype', 'CU', 'val', $caseStudyId);
            $results['defaulteneh'] = $householdUnit[$DefaultEne];
        }
    break;

    case 'transport_freight_generation':
        $xml = new Data($caseStudyId, $auxml);
        if ($action == 'get')
        {
            $auData = $xml->getByField(1, 'SID');
            $results['unittype'] = Config2::getData('unittype', 'UH', 'val', $caseStudyId);
            $results['unittype2'] = Config2::getData('unittype', 'UG', 'val', $caseStudyId);
            $results['auData'] = $auData;
        }
    break;

    case 'transport_freight_modal':
        $xml = new Data($caseStudyId, $avxml);
        if ($action == 'get')
        {
            $avData = $xml->getByField(1, 'SID');
            $results['avData'] = $avData;
        }
    break;

    case 'transport_freight_intensity':
        $xml = new Data($caseStudyId, $azxml);
        if ($action == 'get')
        {
            $azData = $xml->getByField(1, 'SID');
            $results['azData'] = $azData;
        }
    break;

    case 'transport_intercity_factors':
        $xml = new Data($caseStudyId, $baxml);
        if ($action == 'get')
        {
            $baData = $xml->getByField(1, 'SID');
            $results['baData'] = $baData;
            $results['unituy'] = Config2::getData('unittype', 'UY', 'val', $caseStudyId);
            $results['unituz'] = Config2::getData('unittype', 'UZ', 'val', $caseStudyId);
        }
    break;

    case 'transport_intercity_modal':
        $xml = new Data($caseStudyId, $bbxml);
        if ($action == 'get')
        {
            $bbData = $xml->getByField(1, 'SID');
            $results['bbData'] = $bbData;
        }
    break;

    case 'transport_intercity_intensity':
        $xml = new Data($caseStudyId, $bcxml);
        if ($action == 'get')
        {
            $bcData = $xml->getByField(1, 'SID');
            $results['bcData'] = $bcData;
        }
    break;

    case 'transport_urban_factors':
        $xml = new Data($caseStudyId, $axxml);
        if ($action == 'get')
        {
            $axData = $xml->getByField(1, 'SID');
            $results['axData'] = $axData;
        }
    break;

    case 'transport_urban_modal':
        $xml = new Data($caseStudyId, $awxml);
        if ($action == 'get')
        {
            $awData = $xml->getByField(1, 'SID');
            $results['awData'] = $awData;
        }

    break;

    case 'transport_urban_intensity':
        $xml = new Data($caseStudyId, $ayxml);
        if ($action == 'get')
        {
            $ayData = $xml->getByField(1, 'SID');
            $results['ayData'] = $ayData;
        }
    break;

    case 'transport_international':
        $xml = new Data($caseStudyId, $bdxml);
        if ($action == 'get')
        {
            $bdData = $xml->getByField(1, 'SID');
            $results['bdData'] = $bdData;
            $results['mainunit'] = $DefaultEne;
        }
    break;

    case 'industry_intensity_motivepower':
        $xml = new Data($caseStudyId, $afxml);
        if ($action == 'get')
        {
            $afData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $add = new Data($caseStudyId, $adxml);
            $adData = $add->getByField(1, 'SID');
            $results['adData'] = $adData;
            $results['bjData'] = $bjData;
            $results['afData'] = $afData;
            $results['mainunit'] = $DefaultEne;
        }

    break;

    case 'industry_intensity_electricity':
        $xml = new Data($caseStudyId, $anxml);
        if ($action == 'get')
        {
            $anData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['anData'] = $anData;
            $results['bjData'] = $bjData;
            $results['mainunit'] = $DefaultEne;
        }
    break;

    case 'industry_intensity_thermal':
        $xml = new Data($caseStudyId, $aoxml);
        if ($action == 'get')
        {
            $aoData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['aoData'] = $aoData;
            $results['bjData'] = $bjData;
            $results['mainunit'] = $DefaultEne;
        }
    break;

    case 'industry_intensity_other':
        $xml = new Data($caseStudyId, $bkxml);
        if ($action == 'get')
        {
            $bkData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['bkData'] = $bkData;
            $results['mainunit'] = $DefaultEne;
        }
    break;

    case 'industry_efficiency_penetration':
        $xml = new Data($caseStudyId, $apxml);
        if ($action == 'get')
        {
            $apData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['apData'] = $apData;
            $results['pentype'] = $pentype;
        }
    break;

    case 'industry_efficiency_factors':
        $xml = new Data($caseStudyId, $aqxml);
        if ($action == 'get')
        {
            $aqData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['aqData'] = $aqData;
            $results['pentype'] = $pentype;
            $results['max_text'] = $MAX;
        }
    break;

    case 'industry_manufacturing_factors':
        $xml = new Data($caseStudyId, $arxml);
        if ($action == 'get')
        {
            $arData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['arData'] = $arData;
            $results['facmtype'] = $facmtype;
        }
    break;

    case 'industry_manufacturing_penetration':
        $xml = new Data($caseStudyId, $asxml);
        if ($action == 'get')
        {
            $asData = $xml->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['asData'] = $asData;
            $results['pentype'] = $pentype;
            $results['facmtype'] = $facmtype;
        }
    break;

    case 'industry_manufacturing_ratio':
        $xml = new Data($caseStudyId, $atxml);
        if ($action == 'get')
        {
            $atData = $xml->getByField(1, 'SID');
            $unitsut = array();
            foreach ($facmtype as $facmtypes)
            {
                $unitsut[$facmtypes['id']] = Config2::getData('unittype', $facmtypes['id'], 'val', $caseStudyId);
            }
            $add = new Data($caseStudyId, $adxml);
            $adData = $add->getByField(1, 'SID');
            $bjd = new Data($caseStudyId, $bjxml);
            $bjData = $bjd->getByField(1, 'SID');
            $results['bjData'] = $bjData;
            $results['atData'] = $atData;
            $results['adData'] = $adData;
            $results['pentype'] = $pentype;
            $results['facmtype'] = $facmtype;
            $results['unitsut'] = $unitsut;
        }
    break;
}

if ($action == 'edit')
{
    $xml->deleteByField(1, 'SID');
    $xml->add(json_decode($_POST['data'], true));

    $dataNotes[$id]=$_POST['datanotes'];
    $json_data = json_encode($dataNotes);
    file_put_contents(USER_CASE_PATH.$caseStudyId."/datanotes.json", $json_data);
}
else
{
    echo (json_encode($results));
}
?>
