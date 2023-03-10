<?php
require_once "../../config.php";
require_once CLASS_PATH."Data.class.php";
require_once BASE_PATH."general.php";

$bjd = new Data($caseStudyId, $bjxml);
$bjData = $bjd->getByField(1, 'SID');
$currencies = Config1::getData('currencies', $caseStudyId, true);

switch ($_POST['action'])
{
    case 'update':
        $dc->update($_POST);
        if ($_POST["studyName"] != $caseStudyId)
        {
            rename(USER_CASE_PATH . $caseStudyId, USER_CASE_PATH . $_POST["studyName"]);
            setcookie("titlecs", USER_CASE_PATH . $_POST["studyName"], time() + (86400 * 30) , "/");
        }
    break;
    case 'updatesector':
        $bjd->deleteByField(1, 'SID');
        $bjd->add($_POST['dataenduse']);
        $mid = $_POST['datasectors']['mid'];

        if ($_POST['datasectors'][$mid . '_A'])
        {
            $xml_doc[$mid . "_A"] = $_POST['datasectors'][$mid . '_A'];
            $xml_doc[$mid . "_H"] = $_POST['datasectors'][$mid . '_H'];
            $TypeChunk = explode(",", $_POST['datasectors'][$mid . '_A']);

            for ($j = 0;$j < count($TypeChunk);$j++)
            {
                $xml_doc[$TypeChunk[$j]] = rtrim(ltrim($_POST['datasectors'][$TypeChunk[$j]])); //name of type
                $xml_doc[$TypeChunk[$j] . "_fr"] = $_POST['datasectors'][$TypeChunk[$j] . '_fr'];
                $xml_doc[$TypeChunk[$j] . "_ps"] = $_POST['datasectors'][$TypeChunk[$j] . '_ps'];
                if ($mid == "Trp")
                {
                    $xml_doc[$TypeChunk[$j] . "_fr_fl"] = $_POST['datasectors'][$TypeChunk[$j] . '_fr_fl'];
                    $xml_doc[$TypeChunk[$j] . "_ps_fl"] = $_POST['datasectors'][$TypeChunk[$j] . '_ps_fl'];
                    $xml_doc[$TypeChunk[$j] . "_psi"] = $_POST['datasectors'][$TypeChunk[$j] . '_psi'];
                    $xml_doc[$TypeChunk[$j] . "_psi_fl"] = $_POST['datasectors'][$TypeChunk[$j] . '_psi_fl'];
                    $xml_doc[$TypeChunk[$j] . "_psp"] = $_POST['datasectors'][$TypeChunk[$j] . '_psp'];
                    $xml_doc[$TypeChunk[$j] . "_psp_fl"] = $_POST['datasectors'][$TypeChunk[$j] . '_psp_fl'];
                    $xml_doc[$TypeChunk[$j] . "_car"] = $_POST['datasectors'][$TypeChunk[$j] . '_car'];
                    $xml_doc[$TypeChunk[$j] . "_plane"] = $_POST['datasectors'][$TypeChunk[$j] . '_plane'];
                }

                if ($_POST['datasectors'][$TypeChunk[$j] . '_A'])
                {
                    $xml_doc[$TypeChunk[$j] . "_A"] = rtrim(ltrim($_POST['datasectors'][$TypeChunk[$j] . '_A'])); //list of subsector types
                    $xml_doc[$TypeChunk[$j] . "_H"] = rtrim(ltrim($_POST['datasectors'][$TypeChunk[$j] . '_H'])); //no of subsector types
                    $SubChunk = explode(",", $_POST['datasectors'][$TypeChunk[$j] . '_A']);

                    for ($k = 0;$k < count($SubChunk);$k++)
                    {
                        if ($_POST['datasectors'][$SubChunk[$k]])
                        {
                            $xml_doc[$SubChunk[$k]] = rtrim(ltrim($_POST['datasectors'][$SubChunk[$k]])); //name of subtype
                            
                        }
                    }
                }
            }
        }
        $xml_doc['SID'] = $mid;
        $aaad = new Data($caseStudyId, 'sectors_data');
        $aaad->deleteByField($mid, 'SID');
        $aaad->add($xml_doc);
    break;
    case 'select':
        $sectors = array();
        foreach ($maintype as $maintypes)
        {
            $abxml = $maintypes['id'];
            $abd = new Data($caseStudyId, 'sectors_data');
            $abData = $abd->getByField($abxml, 'SID');
            $TypeChunk = explode(",", $abData[$abxml . '_A']);
            $row['id'] = $maintypes['id'];
            $row['autoid'] = $abData['id'];
            $row['item'] = $maintypes['value'];
            $row['a'] = $abData[$abxml . '_A'];
            $row['h'] = $abData[$abxml . '_H'];
            $subsectors = array();
            for ($j = 0;$j < count($TypeChunk);$j++)
            {
                $row1 = array();
                $row1['id'] = $TypeChunk[$j];
                $row1['clientname'] = $abData[$TypeChunk[$j]];

                if ($row['id'] == 'Trp')
                {
                    $row1[$TypeChunk[$j] . '_fr'] = $abData[$TypeChunk[$j] . '_fr'];
                    $row1[$TypeChunk[$j] . '_fr_fl'] = $abData[$TypeChunk[$j] . '_fr_fl'];
                    $row1[$TypeChunk[$j] . '_ps'] = $abData[$TypeChunk[$j] . '_ps'];
                    $row1[$TypeChunk[$j] . '_ps_fl'] = $abData[$TypeChunk[$j] . '_ps_fl'];
                    $row1[$TypeChunk[$j] . '_psi'] = $abData[$TypeChunk[$j] . '_psi'];
                    $row1[$TypeChunk[$j] . '_psi_fl'] = $abData[$TypeChunk[$j] . '_psi_fl'];
                    $row1[$TypeChunk[$j] . '_psp'] = $abData[$TypeChunk[$j] . '_psp'];
                    $row1[$TypeChunk[$j] . '_psp_fl'] = $abData[$TypeChunk[$j] . '_psp_fl'];
                    $row1[$TypeChunk[$j] . '_car'] = $abData[$TypeChunk[$j] . '_car'];
                    $row1[$TypeChunk[$j] . '_plane'] = $abData[$TypeChunk[$j] . '_plane'];
                }

                //subclients
                $subclients = array();
                if ($abData[$TypeChunk[$j] . '_A'] != null)
                {
                    $TypeChunk1 = explode(",", $abData[$TypeChunk[$j] . '_A']);
                    for ($k = 0;$k < count($TypeChunk1);$k++)
                    {
                        $row2 = array();
                        $row2['id'] = $TypeChunk1[$k];
                        $row2['subclientname'] = $abData[$TypeChunk1[$k]];
                        array_push($subclients, $row2);
                    }
                    $row1['subclients'] = $subclients;
                }
                array_push($subsectors, $row1);
            }
            $row['clients'] = $subsectors;
            array_push($sectors, $row);
        }

        $results = [];
        $results['sectors'] = $sectors;
        $results['currency'] = $currencies;
        $results['geninf'] = $aaData;
        $results['enduse'] = $bjData;
        $results['fueltypes'] = $fueltype;
        $results['endtype'] = $endtype;

        echo (json_encode($results));
    break;

    case 'endtype':
        $results['endtype'] = $endtype;
        echo (json_encode($results));
    break;

    case 'fueltype':
        $results['fueltypes'] = $fueltype;
        echo (json_encode($results));
    break;

    case 'updatefueltype':
        $xml = new DOMDocument();
        $xml->formatOutput = true;
        $fueltypes = $_POST['fueltypes'];
        $r = $xml->createElement("fueltypes");
        $xml->appendChild($r);

        for ($j = 0;$j < count($fueltypes);$j++)
        {
            $b = $xml->createElement("fueltype");
            $id = $xml->createElement("id");
            $id->appendChild($xml->createTextNode($fueltypes[$j]['id']));
            $value = $xml->createElement("value");
            $value->appendChild($xml->createTextNode($fueltypes[$j]['value']));
            $ftype = $xml->createElement("ftype");
            $ftype->appendChild($xml->createTextNode($fueltypes[$j]['ftype']));
            $b->appendChild($id);
            $b->appendChild($value);
            $b->appendChild($ftype);
            $r->appendChild($b);
        }
        if (is_file(USER_CASE_PATH . $caseStudyId . "/fueltype.xml"))
        {
            unlink(USER_CASE_PATH . $caseStudyId . "/fueltype.xml");
        }
        $xml->save(USER_CASE_PATH . $caseStudyId . "/fueltype.xml") or die("Error");
    break;
}

?>
