<?php
require_once "../../config.php";
require_once CLASS_PATH."Data.class.php";
require_once BASE_PATH."general.php";

	switch($_POST['action']){	
		case 'select':
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
			$results=[];
			$results['sectors']=$sectors;
			$results['geninf']=$aaData;
			$results['allyear']=$AllYear;
			echo (json_encode($results));
		break;

		case 'add':
			$d->add($_POST['data']);

		break;
		
		case 'delete':
					
		break;

		case 'update':
			$dc->update($_POST);
			if ($_POST["studyName"] != $caseStudyId)
			{
				rename(USER_CASE_PATH . $caseStudyId, USER_CASE_PATH . $_POST["studyName"]);
				setcookie("titlecs", USER_CASE_PATH . $_POST["studyName"], time() + (86400 * 30) , "/");
			}
		break;
						
		case 'updatesector':
			$SectorItem =$_POST['datasectors'];
			$xml = new DOMDocument();
			$xml->formatOutput = true;
			$r = $xml->createElement( "maintypes" );
			$xml->appendChild( $r );
				for($j = 0; $j < count($SectorItem); $j++){ 
					$b = $xml->createElement( "maintype" );
					$id = $xml->createElement("id");
					$id->appendChild(
					$xml->createTextNode($SectorItem[$j]['id']));
					
					$sub1   = $xml->createElement("sub1");
					$sub1->appendChild(
					$xml->createTextNode($SectorItem[$j]['sub1']));
					
					$value   = $xml->createElement("value");
					$value->appendChild(
					$xml->createTextNode($SectorItem[$j]['value']));
					
					$fname   = $xml->createElement("fname");
					$fname->appendChild(
					$xml->createTextNode($SectorItem[$j]['fname']));
					
					$b->appendChild( $id );
					$b->appendChild( $value );
					$b->appendChild( $sub1 );
					$b->appendChild( $fname );
					$r->appendChild( $b );

					//subsectors
					$aaad = new Data($caseStudyId,'sectors_data');
					$aaad->deleteByField($SectorItem[$j]['id'],'SID');

					$xml_clients = array();
					if($SectorItem[$j]['clients']['id']!=null && $SectorItem[$j]['clients'][$SectorItem[$j]['id'].'_A']!=""){
						$xml_clients['SID'] = $SectorItem[$j]['id'];
						$xml_clients[$SectorItem[$j]['id'].'_A']=$SectorItem[$j]['clients'][$SectorItem[$j]['id'].'_A'];
						$xml_clients[$SectorItem[$j]['id'].'_H']=$SectorItem[$j]['clients'][$SectorItem[$j]['id'].'_H'];

						$SubChunk = explode(",",$SectorItem[$j]['clients'][$SectorItem[$j]['id'].'_A']); 
					
						for($k = 0; $k < count($SubChunk); $k++){ 
						//	if($_POST['datasectors'][$SubChunk[$k]]){
								$xml_clients[$SubChunk[$k]] =  rtrim(ltrim($SectorItem[$j]['clients'][$SubChunk[$k]]));	
						//	}		
						}

						$aaad->add($xml_clients);
					}
				}
		  
		//	$xml->saveXML();
		  
		  if(is_file(USER_CASE_PATH.$caseStudyId."/maintype.xml")){ unlink(USER_CASE_PATH.$caseStudyId."/maintype.xml");}
		  $xml->save(USER_CASE_PATH.$caseStudyId."/maintype.xml");
		break;
	}
?>