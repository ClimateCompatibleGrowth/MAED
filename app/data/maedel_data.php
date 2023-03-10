<?php 
require_once "../../config.php";
require_once CLASS_PATH."Data.class.php";
require_once BASE_PATH."general.php";
    
    $daytype = Config1::getData('daytype', $caseStudyId, true);
    $results['maintype'] =  $maintype;
    $results['allyear'] =  $AllYear;
    $results['casestudyid'] = $caseStudyId;  
    $results['defaultunit'] =  $DefaultUnit; 
    $results['user_path']=USER_CASE_PATH;
    if(file_exists(USER_CASE_PATH.$caseStudyId."/datanotes.json"))
    $dataNotes= json_decode(file_get_contents(USER_CASE_PATH.$caseStudyId."/datanotes.json"), true);

    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];
    $results['datanotes']=$dataNotes[$id];
    switch($id){
        case 'electricity_final_demand': 
            $xml= new Data($caseStudyId,$ccxml);
            if($action=='get'){
                $ccData = $xml->getByField(1,'SID');
                $results['total_text']=$TOTAL_TEXT;
                $results['ccData']=$ccData;
            }
        break;

        case 'electricity_supplied_from_grid': 
            $xml= new Data($caseStudyId,$ckxml);
            if($action=='get'){
            $ckData = $xml->getByField(1,'SID');
            $results['ckData']=$ckData;
            }
        break;

        case 'electricity_demand_clients': 
            $xml= new Data($caseStudyId,$cbxml);
            if($action=='get'){
                $cbData = $xml->getByField(1,'SID');
                $results['cbData']=$cbData;
                $results['total_text']=$TOTAL_TEXT;
            }
        break;

        case 'electricity_losses':
            $xml= new Data($caseStudyId,$cgxml);
            if($action=='get'){
                $cgData = $xml->getByField(1,'SID');
                $results['cgData']=$cgData;
                }
        break;

        case 'calendar':
            if($action=='get'){
			    $cfd = new Data($caseStudyId,$cfxml);
			    $cfData = $cfd->getByField(1,'SID');
                
			    $ced = new Data($caseStudyId,$cexml);
			    $ceData = $ced->getByField(1,'SID');
                
			    $cdd = new Data($caseStudyId,$cdxml);
			    $cdData = $cdd->getByField(1,'SID');

			    $results['allyear']=$AllYear;

			    $results['calendar']=$cdData;
			    $results['seasons']=$ceData;
                $results['daytypes']=$cfData;	
            }
            if ($action=="save"){
                $cdd = new Data($caseStudyId,$cdxml);
                $cdd->deleteByField(1,'SID');	
                $cdd->add(json_decode($_POST['calendar'],true));
                
                $ced = new Data($caseStudyId,$cexml);
                $ced->deleteByField(1,'SID');	
                $ced->add(json_decode($_POST['calendar_def'],true));
                
                $cfd = new Data($caseStudyId,$cfxml);
                $cfd->deleteByField(1,'SID');	
                $cfd->add(json_decode($_POST['typedaydef'],true));
            }
		break;

        case 'coefficient_weekly': 
			$sectors= array();
			foreach($maintype as $maintypes){ 
				$abxml = $maintypes['id'];
				$abd = new Data($caseStudyId,'sectors_data');
				$abData = $abd->getByField($abxml,'SID');
				$TypeChunk = explode(",",$abData[$abxml.'_A']);
				$row['id']=$maintypes['id'];
				$row['autoid']=$abData['id'];
				$row['item']=$maintypes['value'];
				$row['sub1']=$maintypes['sub1'];
				$row['clients']=null;
				if($TypeChunk[0]!=""){
				$clients=array();
				for($j = 0; $j < count($TypeChunk); $j++){ 
					$row1=array();
					$row1['id']=$TypeChunk[$j];
					$row1['clientname']=$abData[$TypeChunk[$j]];
					array_push($clients,$row1);
				} 
				$row['clients']=$clients;
				}
				array_push($sectors,$row);
		}
            $idsector = $_COOKIE['idsector'];
            $year = $_COOKIE['year'];
            $idclient=$_COOKIE['idclient'];

            $abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($idsector,'SID');
            $TypeChunk = explode(",",$abData[$idsector.'_A']);

            $chd = new Data($caseStudyId,$chxml);
            for($i=0;$i<count($AllYear); $i++){
                $chData[$AllYear[$i]] = $chd->getByField($idclient.'_'.$AllYear[$i],'SID');
            }
            $results['idsector']=$idsector;
            $results['idclient']=$idclient;
            $results['year']=$year;
            $results['chData']=$chData;
            $results['sectors']=$sectors;
        break;

        case 'coefficient_daily': 
            $sectors= array();
			foreach($maintype as $maintypes){ 
				$abxml = $maintypes['id'];
				$abd = new Data($caseStudyId,'sectors_data');
				$abData = $abd->getByField($abxml,'SID');
				$TypeChunk = explode(",",$abData[$abxml.'_A']);
				$row['id']=$maintypes['id'];
				$row['autoid']=$abData['id'];
				$row['item']=$maintypes['value'];
				$row['sub1']=$maintypes['sub1'];
				$row['clients']=null;
				if($TypeChunk[0]!=""){
				$clients=array();
				for($j = 0; $j < count($TypeChunk); $j++){ 
					$row1=array();
					$row1['id']=$TypeChunk[$j];
					$row1['clientname']=$abData[$TypeChunk[$j]];
					array_push($clients,$row1);
				} 
				$row['clients']=$clients;
				}
				array_push($sectors,$row);
            }
        
            $idsector = $_COOKIE['idsector'];
            $year = $_COOKIE['year'];
            $idclient=$_COOKIE['idclient'];

            $abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($idsector,'SID');
            $TypeChunk = explode(",",$abData[$idsector.'_A']);

            $cid = new Data($caseStudyId,$cixml);
            $ciData = $cid->getByField($idclient.'_'.$year,'SID');
            $results['idclient']=$idclient;
            $results['year']=$year;
            $results['daytype']=$daytype;
            $results['ciData']=$ciData;
            $results['sectors']=$sectors;
        break;

        case 'coefficient_hourly': 
            $sectors= array();
			foreach($maintype as $maintypes){ 
				$abxml = $maintypes['id'];
				$abd = new Data($caseStudyId,'sectors_data');
				$abData = $abd->getByField($abxml,'SID');
				$TypeChunk = explode(",",$abData[$abxml.'_A']);
				$row['id']=$maintypes['id'];
				$row['autoid']=$abData['id'];
				$row['item']=$maintypes['value'];
				$row['sub1']=$maintypes['sub1'];
				$row['clients']=null;
				if($TypeChunk[0]!=""){
				$clients=array();
				for($j = 0; $j < count($TypeChunk); $j++){ 
					$row1=array();
					$row1['id']=$TypeChunk[$j];
					$row1['clientname']=$abData[$TypeChunk[$j]];
					array_push($clients,$row1);
				} 
				$row['clients']=$clients;
				}
				array_push($sectors,$row);
            }
        
            $idsector = $_COOKIE['idsector'];
            $year = $_COOKIE['year'];
            $idclient=$_COOKIE['idclient'];

            $cdd = new Data($caseStudyId,$cdxml);
            $cdData = $cdd->getByField(1,'SID');

            $ced = new Data($caseStudyId,$cexml);
            $ceData = $ced->getByField(1,'SID');

            $abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($idsector,'SID');
            $TypeChunk = explode(",",$abData[$idsector.'_A']);

            $cjd = new Data($caseStudyId,$cjxml);
			$cjData = $cjd->getByField($idclient.'_'.$year,'SID');
            $results['idclient']=$idclient;
            $results['year']=$year;
            $results['daytype']=$daytype;
            $results['cdData']=$cdData;
            $results['ceData']=$ceData;
            $results['cjData']=$cjData;
            $results['sectors']=$sectors;
        break;
    }

    if($action=='edit'){
        $idclient=$_COOKIE['idclient'];
        $year=$_COOKIE['year'];
        switch($id){
            case 'coefficient_weekly':
                $data=json_decode($_POST['data'], true);
                for($i=0;$i<count($AllYear); $i++){
                    $chd->deleteByField($idclient.'_'.$AllYear[$i],'SID');	
                    $chd->add($data[$AllYear[$i]]);
                }
            break;
            case 'coefficient_daily':
                $cid->deleteByField($idclient.'_'.$year,'SID');	
                $cid->add(json_decode($_POST['data'], true));
            break;
            case 'coefficient_hourly':
                $cjd->deleteByField($idclient.'_'.$year,'SID');	
                $cjd->add(json_decode($_POST['data'], true));
            break;
            
            default:
                $xml->deleteByField(1,'SID');	
                $xml->add(json_decode($_POST['data'],true));
            break;
        }

        $dataNotes[$id]=$_POST['datanotes'];
        $json_data = json_encode($dataNotes);
        file_put_contents(USER_CASE_PATH.$caseStudyId."/datanotes.json", $json_data);
    }
    else
    {
        echo (json_encode($results));
    }
?>
