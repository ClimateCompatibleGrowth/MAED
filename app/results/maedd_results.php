<?php
	require_once "../../config.php";
	require_once BASE_PATH."general.php";
	require_once CLASS_PATH."Data.class.php";
	
	$multiple['ThousandPopulation']=0.001;
	$multiple['MillionPopulation']=1;

	$multiple['Million']=1000;
	$multiple['Billion']=1;
	$multiple['Trillion']=0.001;

	$labelUnit['Million']="10^6";
	$labelUnit['Billion']="10^9";
	$labelUnit['Trillion']="10^12";

	$amd = new Data($caseStudyId,$amxml);
	$amData = $amd->getByField(1,'SID');

    //Manage files
    switch($_REQUEST['id']){
		//-----GDP
		case '1.1.'://GDP formation by sector/subsector (absolute values)

			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['gdp']=='Y') {
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData[$maintypes['id'].'_S_'.$AY]);
					}
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
						$row1['chart']=false;
						$row1['css']='';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AY]);			
						}		
						array_push($sectors,$row1);
					} 
				} 

			}

			$row2['item']='Total GDP';
			$row2['css']='readonly';
			$row2['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['G_'.$AY]);
        	}
			array_push($sectors,$row2);
			$results=[];
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultCurrency.' '.$labelUnit[$DefaultGdp];
        	echo (json_encode($results));
			break;	
			
			case "1.2.": //Per Capita GDP by sector          
			$sectors= array();
			$series= array();
			$row['item']='GDP/cap'; 
			$row['css']='readonly';
			$row['chart']=true;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['GC_'.$AY]);							
				}	
			
			array_push($sectors,$row);
			$series[]='GDP/cap'; //$GDP_cap; 
			foreach($maintype as $maintypes){ 
				if($maintypes['gdp']=='Y'){
					$row1['item']=$maintypes['value'];
					$row1['chart']=true;
					$row1['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData[$maintypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row1);
					$series[]=$maintypes['value'];
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultCurrency.' /Capita';
        	echo (json_encode($results));
			break;

			case "1.3.": //GDP formation by sector/subsectors (growth rates)
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['gdp']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['GR_'.$maintypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
						$row1['chart']=false;
						$row1['css']='';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber($amData['GR_'.$TypeChunk[$j].'_'.$AY]);			
						}		
						array_push($sectors,$row1);
					} 
				} 

			}

			$row2['item']='Total GDP';//$Total_GDP;
			$row2['css']='readonly';
			$row2['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['GRT_'.$AY]);
        	}
			array_push($sectors,$row2);

			$row3['item']='GDP/cap';//$Total_GDP;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['GRC_'.$AY]);
        	}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
        	echo (json_encode($results));
			break;

			//-----END GDP

			//INDUSTRY - Useful Energy
			case "2.1.1.": //Useful energy demand for Motive Power
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';
					$exist=false;
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($abData[$TypeChunk[$j]] and $bjData['MP_'.$TypeChunk[$j]]=='Y'){
							$exist=true;
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=formatnumber($amData['MT_'.$maintypes['id'].'_'.$AY]);
							 }
						}
					}
					if ($exist)
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['MP_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
								$row1['chart']=false;
								$row1['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['M_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='MP'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Others Motive Power';
							$row2['chart']=false;
							$row2['css']='';						 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['MO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							
							array_push($sectors,$row2);		 
					  	}
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			
			break;

			case "2.1.2.": //Useful energy demand for Electricity specific uses
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';
					$exist=false;
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($abData[$TypeChunk[$j]] and $bjData['AP_'.$TypeChunk[$j]]=='Y'){
							$exist=true;
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=formatnumber($amData['ET_'.$maintypes['id'].'_'.$AY]);
							 }
						}
					}
					if ($exist)
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['AP_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
								$row1['chart']=false;
								$row1['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['E_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='AP'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Other Elec Spec use';
							$row2['chart']=false;
							$row2['css']='';						 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['EO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							
							array_push($sectors,$row2);		 
					  	}
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.1.3.": //Useful energy demand for Thermal uses
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';
					$exist=false;
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($abData[$TypeChunk[$j]] and $bjData['TU_'.$TypeChunk[$j]]=='Y'){
							$exist=true;
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=formatnumber($amData['TT_'.$maintypes['id'].'_'.$AY]);
							 }
						}
					}
					if ($exist)
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['TU_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
								$row1['chart']=false;
								$row1['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['T_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Other Thermal use';
							$row2['chart']=false;
							$row2['css']='';						 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['TO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							array_push($sectors,$row2);		 
					  	}
					}
				}
			}		
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.1.4.": //Industry: Total useful energy demand in Industry
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['ToT_'.$maintypes['id'].'_'.$AY]);
					 }					
					 array_push($sectors,$row);
					 $series[]=$maintypes['value'];

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
						$row1['chart']=false;
						$row1['css']='';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['A_'.$TypeChunk[$j].'_'.$AY]);			
							}		
						array_push($sectors,$row1);		
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//INDUSTRY - Energy Demand ACM
			case "2.2.1.": //Total final energy demand for thermal uses in Agriculture, Construction & Mining
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(4,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['fac']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					
					 for($j = 0; $j < count($TypeChunk); $j++){
						if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=false;
							$row1['css']='readonly';	
							for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
							array_push($sectors,$row1);		
							foreach($pentype as $pentypes){ 
								if($pentypes[$maintypes['id'].'_TU']=='Y'){	
									$row2['item']=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									$row2['chart']=true;
									$row2['css']='';
									$series[]=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									for($y = 0; $y < count($AllYear); $y++){  
										$AY=$AllYear[$y];
										$row2[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$pentypes['id'].'_'.$AllYear[$y]]);
									}
								array_push($sectors,$row2);		
								}	
							}
						}

						if($bjData['TU_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row3['item']=$abData[$TypeChunk[$j]].'- Other thermal use';
							$row3['chart']=false;
							$row3['css']='readonly';	
							for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }					 
							array_push($sectors,$row3);	

							foreach($pentype as $pentypes){ 
								if($pentypes[$maintypes['id'].'_TU']=='Y'){	
									$row4['item']=$pentypes['value'];
									$row4['chart']=false;
									$row4['css']='';
									for($y = 0; $y < count($AllYear); $y++){  
										$AY=$AllYear[$y];
										$row4[$AY]=formatnumber($amData['OT_'.$TypeChunk[$j].'_'.$pentypes['id'].'_'.$AllYear[$y]]);
									}
								array_push($sectors,$row4);		
								}					
					  		}
						}
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.2.2.": //Total final energy demand (absolute) in Agriculture, Construction & Mining
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(4,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['fac']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData[$maintypes['id'].'_'.$AllYear[$y]]);
					 }
					array_push($sectors,$row);

					$series[]=$maintypes['value'];	
					foreach($pentype as $pentypes){ 
						if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y' ){	
							$row1['item']=$pentypes['value'];
							$row1['chart']=false;
							$row1['css']='';
							for($y = 0; $y < count($AllYear); $y++){  
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]]);
							}
						array_push($sectors,$row1);		
						}	
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.2.3.": //Total final energy demand (shares) in Agriculture, Construction & Mining
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(4,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['fac']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);

					//$series[]=$maintypes['value'];	
					foreach($pentype as $pentypes){
						if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y'){	
							$row1['item']=$pentypes['value']." - ".$maintypes['value'];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$pentypes['value']." - ".$maintypes['value'];	
							for($y = 0; $y < count($AllYear); $y++){  
								$AY=$AllYear[$y];
								$fieldid = $maintypes['id'].'_'.$pentypes['id'].'_'.$AY;
								$row1[$AY]=formatnumber($amData['S_'.$fieldid]);
							}
						array_push($sectors,$row1);		
						}	
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "2.2.4.": //Total Final Energy Demand per Value Added in Agriculture, Construction & Mining
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(4,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['fac']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=true;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$mainid = $maintypes['id'].'_'.$AllYear[$y];
						$row[$AY]=formatnumber($amData['T_'.$mainid]);
					 }
					array_push($sectors,$row);

					$series[]=$maintypes['value'];	
					foreach($pentype as $pentypes){ 
						if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y'){	
							$row1['item']=$pentypes['value'];
							$row1['chart']=false;
							$row1['css']='';
							for($y = 0; $y < count($AllYear); $y++){  
								$AY=$AllYear[$y];
								$fieldid = $maintypes['id'].'_'.$pentypes['id'].'_'.$AY;
								$row1[$AY]=formatnumber($amData['T_'.$fieldid]);
							}
						array_push($sectors,$row1);		
						}	
					}
				}
			}		

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/'.$DefaultCurrency;
			echo (json_encode($results));
			break;

			//INDUSTRY - Final Demand Manufacturing
			case "2.3.1.": //Useful Thermal Energy Demand in Manufacturing
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(5,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Man') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					for($y = 0; $y < count($AllYear); $y++){ 
						$total[$AllYear[$y]]=0;
					}

					for($j = 0; $j < count($TypeChunk); $j++){
						if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
						 	$row1['chart']=true;
							$row1['css']='readonly1';
							$series[]=$abData[$TypeChunk[$j]];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$total[$AY]=$total[$AY]+$amData[$TypeChunk[$j].'_'.$AllYear[$y]];
									$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AllYear[$y]]);		
								}		
							array_push($sectors,$row1);		
						}

						foreach($facmtype as $facmtypes){ 
							if(($bjData['TU_'.$TypeChunk[$j]]=='Y') and $facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y'){
								$row2['item']=$facmtypes['value'];
								$row2['chart']=true;
								$row2['css']='';
								$series[]=$facmtypes['value'];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);		
								}		
							array_push($sectors,$row2);	
							}
						}

						if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row3['item']=$facmtypes['value'].' - Other Thermal Uses';
							$row3['chart']=false;
							$row3['css']='';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row3[$AY]=formatnumber($amData['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);		
							}	
							array_push($sectors,$row3);
						}
					}
				}
			}
			
			$row4['item']='Total';//$TOTAL;
			$row4['css']='readonly';
			$row4['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row4[$AY]=formatnumber($total[$AY]);
			}
			$series[]='Total';
			array_push($sectors,$row4);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.3.2.": //Penetration of Energy Carriers into Useful Thermal Energy Demand in Manufacturing

			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(6,'SID');
			$anData = $amd->getByField(7,'SID');
			$sectors= array();
			$series= array();
			
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Man') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					for($y = 0; $y < count($AllYear); $y++){ 
						$total[$y]=0;
					}

					for($j = 0; $j < count($TypeChunk); $j++){
						if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=false;
							$row1['css']='readonly1';
							for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
							array_push($sectors,$row1);	

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if(($pentypes['Man_SWH']=='Y' or  $pentypes['Man_SG']=='Y' or $pentypes['Man_FDH']=='Y')){	
									$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
									$row2['item']=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									$row2['chart']=true;
									$row2['css']='';
									$series[]=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row2[$AY]=formatnumber($amData[$eid.'_'.$AllYear[$y]]);		
									}	
									array_push($sectors,$row2);	
									
									if($pentypes['id']=='EL' ){
										$row3['item']='Heat Pumps';
										$row3['chart']=false;
										$row3['css']='';
										for($y = 0; $y < count($AllYear); $y++){
											$AY=$AllYear[$y];
											$row3[$AY]=$amData['H_'.$eid.'_'.$AY];
										}
										array_push($sectors,$row3);	
									}
								}
							}
						}

													
						if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row4['item']=$abData[$TypeChunk[$j]].' - Other Thermal Uses';
							$row4['chart']=false;
							$row4['css']='';
							for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
							array_push($sectors,$row4);	

							foreach($facmtype as $facmtypes){ 
								if($facmtypes['id']=='SG'){
									$tmtype ="";
									foreach($pentype as $pentypes){ 
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
											$nmid = $eid.'_'.$cid;
											$tmtype = $tmtype.','.$nmid;

											$row5['item']=$pentypes['value'];
											$row5['chart']=false;
											$row5['css']='';
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$row5[$AY]=formatnumber($amData['OT_'.$eid.'_'.$AY]);			
											}		
											array_push($sectors,$row5);	

											if($pentypes['id']=='EL' and ($facmtypes['id']=='SG' or $facmtypes['id']=='SWH')){
												$row6['item']='(of which Heat Pumps)';
												$row6['chart']=false;
												$row6['css']='';
												for($y = 0; $y < count($AllYear); $y++){ 
													$AY=$AllYear[$y];
													$row6[$AY]=formatnumber(['OT_H_'.$eid.'_'.$AY]);			
												}		
												array_push($sectors,$row6);	
											}
										}
									}
								}
							}
						}
					}
				}
			}
			
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "2.3.3.": //Total final energy demand for thermal uses in Manufacturing
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(8,'SID');
			$sectors= array();
			$series= array();

			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Man') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					for($y = 0; $y < count($AllYear); $y++){ 
						$total[$y]=0;
					}

					for($j = 0; $j < count($TypeChunk); $j++){
						if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=false;
							$row1['css']='readonly1';
							for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
							array_push($sectors,$row1);	
							foreach($facmtype as $facmtypes){ 
								if($facmtypes['PE']=='N' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y'){
									$row2['item']=$facmtypes['value'];
									$row2['chart']=false;
									$row2['css']='readonly';
									for($y = 0; $y < count($AllYear); $y++){ $row2[$AllYear[$y]]=""; }
									array_push($sectors,$row2);

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if($pentypes['Man_'.$cid]=='Y' and $pentypes['id']!='CG'){
									if(($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')){	
										$eid = $TypeChunk[$j].'_'.$pentypes['id'];
										$row3['item']=$pentypes['value'].' ('.$facmtypes['value'].')';
										$row3['chart']=true;
										$row3['css']='';
										$series[]=$pentypes['value'].' ('.$facmtypes['value'].')';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row3[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$pentypes['id'].'_'.$facmtypes['id'].'_'.$AY]);		
									}	
									array_push($sectors,$row3);	
								}
							}
						}

						}elseif(($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG')){
							$row4['item']=$pentypes['value'];
							$row4['chart']=false;
							$row4['css']='';
							for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
							array_push($sectors,$row4);	

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if($pentypes['Man_'.$cid]=='Y' and $pentypes['id']!='CG'){	
										$eid = $TypeChunk[$j].'_'.$pentypes['id'];
										$row5['item']=$pentypes['value'];
										$row5['chart']=false;
										$row5['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row5[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$pentypes['id'].'_'.$facmtypes['id'].'_'.$AY]);
									}	
									array_push($sectors,$row5);	
								}
							}
						}
					}
						
						if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row4['item']=$abData[$TypeChunk[$j]].' - Other Thermal Uses';
							$row4['chart']=false;
							$row4['css']='';
							for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
							array_push($sectors,$row4);	

							foreach($facmtype as $facmtypes){ 
								if($facmtypes['id']=='SG'){
									foreach($pentype as $pentypes){ 
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											$row5['item']=$pentypes['value'];
											$row5['chart']=false;
											$row5['css']='';
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$row5[$AY]=formatnumber($amData['OT_'.$TypeChunk[$j].'_'.$pentypes['id'].'_'.$facmtypes['id'].'_'.$AY]);			
											}		
											array_push($sectors,$row5);	
										}
									}
								}
							}
						}
					}
				}
			}
		}
			
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));

			break;

			case "2.3.4.": // Total Final Energy Demand in Manufacturing (absolute)
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();

			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData[$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total';//$TOTAL;
			$row1['css']='readonly';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['Y_'.$AY]);
			}
			$series[]=$TOTAL;
			array_push($sectors,$row1);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.3.5.": // Total Final Energy Demand in Manufacturing (shares)
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();

			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['S_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "2.3.6.": // Total Final Energy Demand per Value Added in Manufacturing
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();

			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['V_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total';//$TOTAL;
			$row1['css']='readonly';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['V_'.$AY]);
			}
			$series[]='Total';//$TOTAL;
			array_push($sectors,$row1);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/'.$DefaultCurrency;
			echo (json_encode($results));
			break;

			//INDUSTRY - Demand Industry
			case "2.4.1.": // Total Final Energy Demand in Industry (absolute)
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();

			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['A_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total';//$TOTAL;
			$row1['css']='readonly';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['A_'.$AY]);
			}
			$series[]='Total'; //$TOTAL;
			array_push($sectors,$row1);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "2.4.2.": // Total Final Energy Demand in Industry (shares)
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();

			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['F_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "2.4.3.": // Total Final Energy Demand Per Value Added in Industry
			$amData = $amd->getByField(9,'SID');
			$sectors= array();
			$series= array();
			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['T_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total'; //$TOTAL;
			$row1['css']='readonly';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['TY_'.$AY]);
			}
			$series[]='Total'; //$TOTAL;
			array_push($sectors,$row1);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/'.$DefaultCurrency;
			echo (json_encode($results));
			break;

			//INDUSTRY - Freight
			case "3.1.1.": //Energy Demand of Freight Transportation
			$amData = $amd->getByField(10,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);	

					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_fr']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['M_'.$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}

				}
			}
			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['MY_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.1.2.": //Energy Demand of Freight Transportation (by fuel)
			$amData = $amd->getByField(10,'SID');
			$abxml = 'Trp';
			$sectors= array();
			$series= array();
			$fuelss=array();
			if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
			{
				//get fules which exists				
				$abd = new Data($caseStudyId,'sectors_data');
				$abData = $abd->getByField($abxml,'SID');
				$TypeChunk = explode(",",$abData['Trp_A']); 
				for($i=0; $i<count($TypeChunk); $i++){ 
					if ($abData[$TypeChunk[$i].'_fr_fl']!=''){
						$fuelss[$i]=$abData[$TypeChunk[$i].'_fr_fl'];
					}
				}
				foreach($fueltype as $fueltypes){
					if (in_array($fueltypes['id'], $fuelss)){
						$row['item']=$fueltypes['value'];
						$row['css']='';
						$row['chart']=true;
						$series[]=$fueltypes['value'];
						for($y = 0; $y < count($AllYear); $y++){
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['Fu_'.$fueltypes['id'].'_'.$AY]);
						}  
						array_push($sectors,$row);	
					}
				}
			}
			else
			{
				if (in_array($fueltype['id'], $fuelss)){
					$row1['item']=$fueltypes['value'];
					$row1['css']='';
					$row1['chart']=true;
					$series[]=$fueltypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
					}
					array_push($sectors,$row1);						
				}	
			}
			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['TFu_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.1.3.": //Energy Demand of Freight Transportation (by fuel group)
			$amData = $amd->getByField(10,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
			}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.1.4.": //Energy intensities of freight transportation
			$amData = $amd->getByField(10,'SID');
			$sectors= array();
			$series= array();

			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);	

					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_fr']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}

				}
			}

			//array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/100tkm';
			echo (json_encode($results));
			break;

			case "3.1.5.": //Total freight-kilometers
			$amData = $amd->getByField(10,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Freight-km';
			$row['css']='';
			$row['chart']=true;
			$series[]='Freight-km';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['F_'.$AY]*$multiple[$Defaultfri]);
			}
			array_push($sectors,$row);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$labelUnit[$Defaultfri]." tkm";
			echo (json_encode($results));
			break;

			case "3.2.1.": //Intercity Intercity Transportation by mode
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Transportation';
			$row['css']='readonly1';
			$row['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
			array_push($sectors,$row);

			$row1['item']='Cars';
			$row1['css']='';
			$row1['chart']=true;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['C_'.$AY]*$multiple[$DefaultPas]);
			}
			array_push($sectors,$row1);
			$series[]='Cars';

			$row2['item']='Public Transport';
			$row2['css']='';
			$row2['chart']=true;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['P_'.$AY]*$multiple[$DefaultPas]);
			}
			array_push($sectors,$row2);
			$series[]='Public Transport';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
			}
			array_push($sectors,$row3);


			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row4['item']=$maintypes['value'].' by Mode';
					$row4['chart']=false;
					$row4['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
					array_push($sectors,$row4);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_ps']=='Y'){
							$row5['item']=$abData[$TypeChunk[$j]];
							$row5['chart']=true;
							$row5['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row5[$AY]=formatnumber($amData['CT_'.$TypeChunk[$j].'_'.$AY]*$multiple[$DefaultPas]);
							}
							array_push($sectors,$row5);	
						}
					}
				}
			}

			$row6['item']='Total'; //$TOTAL;
			$row6['css']='readonly';
			$row6['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row6[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
			}
			array_push($sectors,$row6);


			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$labelUnit[$DefaultPas].' pkm';
			echo (json_encode($results));
			break;

			case "3.2.2.": //Energy Intensity of Intercity Passenger Transportation(energy units)
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_ps']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['EI_'.$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/pkm';
			echo (json_encode($results));
			break;

			case "3.2.3.": //Energy Demand of Intercity Passenger Transportation(by mode)
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_ps']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['EC_'.$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}
				}
			}

			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.2.4.": //Energy Demand of Intercity Passenger Transportation (by fuel)
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();
			if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
			{
				foreach($fueltype as $fueltypes){
					$row['item']=$fueltypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$fueltypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['Fu_'.$fueltypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}
			else
            {
				$row1['item']=$fueltype['value'];
				$row1['css']='';
				$row1['chart']=true;
				$series[]=$fueltype['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
				}
				array_push($sectors,$row1);
			}

			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.2.5.": //Energy Demand of Intercity Passenger Transportation (by fuel group)
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber((double)$amData['EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber((double)$amData['CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
			}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//Urban
			case "3.3.1.": //Urban transport Passenger by mode
			$amData = $amd->getByField(11,'SID');
			$sectors= array();
			$series= array();

			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_psi']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['M_'.$TypeChunk[$j].'_'.$AY]*$multiple[$DefaultPas]);
							}
							array_push($sectors,$row1);	
						}
					}
				}
			}

			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
			}
			array_push($sectors,$row2);


			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$labelUnit[$DefaultPas].' pkm';
			echo (json_encode($results));
			break;

			case "3.3.2.": //Energy Intensity of Urban transport (energy units)
			$amData = $amd->getByField(11,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_psi']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['I_'.$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$energyUnit[$DefaultEne].'/pkm';
			echo (json_encode($results));
			break;

			case "3.3.3.": //Energy Demand of Urban transport (by mode)
			$amData = $amd->getByField(11,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Trp') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					$row['chart']=false;
					$row['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_psi']=='Y'){
							$row1['item']=$abData[$TypeChunk[$j]];
							$row1['chart']=true;
							$row1['css']='';
							$series[]=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['C_'.$TypeChunk[$j].'_'.$AY]);
							}
							array_push($sectors,$row1);	
						}
					}
				}
			}

			$row2['item']='Total';//$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['CY_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.3.4.": //Energy Demand of Urban transport (by fuel)
			$amData = $amd->getByField(11,'SID');
			$sectors= array();
			$series= array();
			if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
			{
				foreach($fueltype as $fueltypes){
					$row['item']=$fueltypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$fueltypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['Fu_'.$fueltypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}
			else
            {
				$row1['item']=$fueltype['value'];
				$row1['css']='';
				$row1['chart']=true;
				$series[]=$fueltype['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
				}
				array_push($sectors,$row1);
			}

			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.3.5.": //Energy Demand of Urban transport (by fuel group)
			$amData = $amd->getByField(11,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
			}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//Final Demand Transport
			case "3.4.1.": //Final energy demand in Transportation sector (by fuels)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();
			if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
			{
				foreach($fueltype as $fueltypes){
					$fid = $fueltypes['id'];
					$row['item']=$fueltypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$fueltypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['Fu_'.$fid.'_'.$AllYear[$y]]);
					}
					array_push($sectors,$row);
				}
			}
			else
            {
				$fid = $fueltype['id'];
				$row1['item']=$fueltype['value'];
				$row1['css']='';
				$row1['chart']=true;
				$series[]=$fueltype['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['Fu_'.$fid.'_'.$AY]);
				}
				array_push($sectors,$row1);
			}

			$row2['item']='International Transport';
			$row2['css']='';
			$row2['chart']=false;
			$series[]='International Transport';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['Intl_military_'.$AY]);
			}
			array_push($sectors,$row2);
			
			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;

			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['Fu_'.$AY]);
			}
			array_push($sectors,$row3);
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.4.2.": //Final energy demand in Transportation sector (shares)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();
			if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
			{
				foreach($fueltype as $fueltypes){
					$fid = $fueltypes['id'];
					$row['item']=$fueltypes['value'];
					$row['css']='';
					$row['chart']=true;
					$series[]=$fueltypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['SFu_'.$fid.'_'.$AllYear[$y]]);
					}
					array_push($sectors,$row);
				}
			}
			else
            {
				$fid = $fueltype['id'];
				$row1['item']=$fueltype['value'];
				$row1['css']='';
				$row1['chart']=true;
				$series[]=$fueltype['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['SFu_'.$fid.'_'.$AY]);
				}
				array_push($sectors,$row1);
			}

			$row2['item']='International Transport';
			$row2['css']='';
			$row2['chart']=false;
			$series[]='International Transport';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['SFu_Mil_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "3.4.3.": //Final energy demand in Transportation sector (by fuel groups)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
			}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.4.4.": //Final energy demand in Transportation sector (shares by fuel groups)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['S_EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['S_CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['S_MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']='%';
			echo (json_encode($results));
			break;

			case "3.4.5.": //Final energy demand in Transportation sector (by subsector)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Freight';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['Freight_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Freight';

			$row1['item']='Pass Urban transport';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['Pass_intracity_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Pass Urban transport';

			$row2['item']='Pass Intercity';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['Pass_intercity_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Pass Intercity';

			$row3['item']='International Transport';
			$row3['css']='';
			$row3['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['Intl_military_'.$AY]);
			}
			array_push($sectors,$row3);
			$series[]='International Transport';

			$row4['item']='Total'; //$TOTAL;
			$row4['css']='readonly';
			$row4['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row4[$AY]=formatnumber($amData['TED_'.$AY]);
			}
			array_push($sectors,$row4);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "3.4.6.": //Final energy demand in Transportation sector (by subsector)
			$amData = $amd->getByField(13,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Freight';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['S_Freight_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Freight';

			$row1['item']='Pass Urban transport';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['S_Pass_intracity_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Pass Urban transport';

			$row2['item']='Pass Intercity';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['S_Pass_intercity_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Pass Intercity';

			$row3['item']='International Transport';
			$row3['css']='';
			$row3['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['S_Intl_military_'.$AY]);
			}
			array_push($sectors,$row3);
			$series[]='International Transport';

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']="%";
			echo (json_encode($results));
			break;


			case "3.4.7.": //Energy Demand of Urban + Intercity + International Passenger Transportation (by fuel group)
			$amData = $amd->getByField(12,'SID');
			$sectors= array();
			$series= array();

			$row['item']='Electricity';
			$row['css']='';
			$row['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['Mi_EL_'.$AY]);
			}
			array_push($sectors,$row);
			$series[]='Electricity';

			$row1['item']='Steam Coal';
			$row1['css']='';
			$row1['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['Mi_CK_'.$AY]);
			}
			array_push($sectors,$row1);
			$series[]='Steam Coal';

			$row2['item']='Motor Fuels';
			$row2['css']='';
			$row2['chart']=true;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['Mi_MF_'.$AY]);
			}
			array_push($sectors,$row2);
			$series[]='Motor Fuels';

			$row3['item']='Total'; //$TOTAL;
			$row3['css']='readonly';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['TPass_'.$AY]);
			}
			array_push($sectors,$row3);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//HOUSEHOLDS
			case "4.1.": //Final Energy Demand in Household Sector
			$amData = $amd->getByField(15,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j].'_A']){
							$row['item']=strtoupper($abData[$TypeChunk[$j]]);
							$row['chart']=false;
							$row['css']='bold';
							for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
							array_push($sectors,$row);	

							foreach($houendtype as $houendtypes){
								if($houendtypes['id']!='LH'){
									$row1['item']=$houendtypes['value'];
									$row1['chart']=false;
									$row1['css']='readonly1';
									for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
									array_push($sectors,$row1);

									foreach($houtype as $houtypes){  
										if($houtypes[$houendtypes['id']]=='Y' ){
											$row2['item']=$houtypes['value'];
											$row2['chart']=false;
											$row2['css']='';
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
												$row2[$AY]=formatnumber($amData[$IN]);
											}
											array_push($sectors,$row2);
										}
									}
									$row3['item']='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;
									$row3['css']='readonly';
									$row3['chart']=true;
									$series[]='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
										$row3[$AY]=formatnumber($amData[$IN]);
									}
									array_push($sectors,$row3);
								}
								elseif($houendtypes['id']=='LH' and $bjData['LH_'.$TypeChunk[$j]]=='Y'){
									$row4['item']=$houendtypes['value'];
									$row4['chart']=false;
									$row4['css']='readonly1';
									for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
									array_push($sectors,$row4);

									foreach($houtype as $houtypes){  
										if($houtypes[$houendtypes['id']]=='Y' ){
											$row5['item']=$houtypes['value'];
											$row5['chart']=false;
											$row5['css']='';
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
												$row5[$AY]=formatnumber($amData[$IN]);
											}
											array_push($sectors,$row5);
										}
									}
									$row6['item']='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;
									$row6['css']='readonly';
									$row6['chart']=true;
									$series[]='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')';
						
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
										$row6[$AY]=formatnumber($amData[$IN]);
									}
									array_push($sectors,$row6);
								}
							}
						}

						$row7['item']='Total Final Energy Demand';
						$row7['chart']=false;
						$row7['css']='readonly1';
						for($y = 0; $y < count($AllYear); $y++){ $row7[$AllYear[$y]]=""; }
						array_push($sectors,$row7);

						foreach($houtype as $houtypes){  
								$row8['item']=$houtypes['value'];
								$row8['chart']=false;
								$row8['css']='';
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$ED = $TypeChunk[$j].'_'.$houtypes['id'].'_'.$AY;
									$row8[$AY]=formatnumber($amData[$ED]);
								}
								array_push($sectors,$row8);
						}
						$row9['item']='Total ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;
						$row9['css']='readonly';
						$row9['chart']=false;
			
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$ED = $TypeChunk[$j].'_'.$AY;
							$row9[$AY]=formatnumber($amData[$ED]);
						}
						array_push($sectors,$row9);


					}
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "4.2.": //Useful Energy Demand in Household Sector
			$amData = $amd->getByField(14,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					for($j = 0; $j < count($TypeChunk); $j++){
						$row['item']=strtoupper($abData[$TypeChunk[$j]]);
						$row['chart']=false;
						$row['css']='bold';
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						array_push($sectors,$row);	
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);
							for($k = 0; $k < count($SubChunk); $k++){
								$row1['item']=$abData[$SubChunk[$k]];
								$row1['chart']=false;
								$row1['css']='readonly1';
								for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
								array_push($sectors,$row1);	

							foreach($houendtype as $houendtypes){
								if($houendtypes['id']=='LH'){
									$row2['item']='Electricity - Lighting';
									$row2['chart']=false;
									$row2['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$IN = $SubChunk[$k].'_LH_'.$AY;
										$row2[$AY]=formatnumber($amData[$IN]);
									}
									array_push($sectors,$row2);

									$row3['item']='Fossil Fuels - Lighting';
									$row3['chart']=false;
									$row3['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$IN = $SubChunk[$k].'_FF_'.$AY;
										$row3[$AY]=formatnumber($amData[$IN]);
									}
									array_push($sectors,$row3);
									}else{
									$row4['item']=$houendtypes['value'];
									$row4['chart']=false;
									$row4['css']='';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$IN = $SubChunk[$k].'_'.$houendtypes['id'].'_'.$AY;
										$row4[$AY]=formatnumber($amData[$IN]);
									}
									array_push($sectors,$row4);
								}
							}
						}
					}
					$row5['item']='Total Useful';
					$row5['chart']=false;
					$row5['css']='readonly1';
					for($y = 0; $y < count($AllYear); $y++){ $row5[$AllYear[$y]]=""; }
					array_push($sectors,$row5);

					foreach($houendtype as $houendtypes){  
						if($houendtypes['id']=='LH'){
							$row6['item']='Electricity - Lighting';
							$row6['chart']=false;
							$row6['css']='';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$EN = $TypeChunk[$j].'_LH_'.$AY;
								$row6[$AY]=formatnumber($amData[$EN]);
							}
							array_push($sectors,$row6);

							$row7['item']='Fossil Fuels - Lighting';
							$row7['chart']=false;
							$row7['css']='';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$EN = $TypeChunk[$j].'_FF_'.$AY;
								$row7[$AY]=formatnumber($amData[$EN]);
							}
							array_push($sectors,$row7);
						}else{
							$row8['item']=$houendtypes['value'];
							$row8['chart']=false;
							$row8['css']='';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$EN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
								$row8[$AY]=formatnumber($amData[$EN]);
							}
							array_push($sectors,$row8);
						}
					}

					$row9['item']='Total ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;
					$row9['css']='readonly';
					$row9['chart']=true;
					$series[]='Total ('.$abData[$TypeChunk[$j]].')';
		
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row9[$AY]=formatnumber($amData[$TypeChunk[$j].'TFF_'.$AY]);
					}
					array_push($sectors,$row9);

				}



			}
		}
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "4.3.": //Total Final Energy Demand in Household
			$amData = $amd->getByField(15,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();

			foreach($houendtype as $houendtypes){
				$row['item']=$houendtypes['value'];
				$row['chart']=false;
				$row['css']='readonly1';
				for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
				array_push($sectors,$row);

				foreach($houtype as $houtypes){  
					if($houtypes[$houendtypes['id']]=='Y' ){
						$row1['item']=$houtypes['value'];
						$row1['chart']=false;
						$row1['css']='';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$IN = $houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
							$row1[$AY]=formatnumber($amData[$IN]);
						}
						array_push($sectors,$row1);
					}
				}

				$row2['item']='Total - '.$houendtypes['value'];
				$row2['css']='readonly';
				$row2['chart']=true;
				$series[]='Total - '.$houendtypes['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$IN = $houendtypes['id'].'_'.$AY;
					$row2[$AY]=formatnumber($amData[$IN]);
				}
				array_push($sectors,$row2);
			}

			$row3['item']='Total Final Energy Demand';
			$row3['chart']=false;
			$row3['css']='readonly1';
			for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
			array_push($sectors,$row3);

			foreach($houtype as $houtypes){  
				$row4['item']=$houtypes['value'];
				$row4['chart']=false;
				$row4['css']='';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$FD = $houtypes['id'].'_'.$AY;
					$row4[$AY]=formatnumber($amData[$FD]);
				}
				array_push($sectors,$row4);
			}

			$row5['item']='Total - Total Final Energy Demand';
			$row5['css']='readonly';
			$row5['chart']=true;
			$series[]='Total - Total Final Energy Demand';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row5[$AY]=formatnumber($amData['T_'.$AY]);
			}
			array_push($sectors,$row5);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//SERVICES
			case "5.1.": //Useful Energy Demand in Service Sector
			$amData = $amd->getByField(16,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();

			$row12['item']='Useful energy demand for Space heating & air conditioning';
			$row12['css']='readonly1';
			$row12['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row12[$AllYear[$y]]=""; }
			array_push($sectors,$row12);
			
			$row['item']='Total area heated (Million m2)';
			$row['css']='';
			$row['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['TA_'.$AY]);
			}
			array_push($sectors,$row);

			$row1['item']='Space heating';
			$row1['css']='';
			$row1['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['SH_'.$AllYear[$y]]);
			}
			array_push($sectors,$row1);

			$row2['item']='Air conditioning';
			$row2['css']='';
			$row2['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['AC_'.$AY]);
			}
			array_push($sectors,$row2);

			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Ser' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); 
					foreach($serendtype as $serendtypes){
						if($serendtypes['id']!='SH' and $serendtypes['id']!='AC'){
							$row3['item']='Useful energy demand for '.$serendtypes['value'];
							$row3['css']='readonly1';
							$row3['chart']=false;
							for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
							array_push($sectors,$row3);

							for($j = 0; $j < count($TypeChunk); $j++){ 
								$row4['item']=$abData[$TypeChunk[$j]].'('.$serendtypes['value'].')';
								$row4['css']='';
								$row4['chart']=false;
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row4[$AY]=formatnumber($amData[$serendtypes['id'].'_'.$TypeChunk[$j].'_'.$AY]);
								}
								array_push($sectors,$row4);
							}
						}
					}
				}
			}

			$row5['item']='Total Useful energy demand';
			$row5['css']='readonly1';
			$row5['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row5[$AllYear[$y]]=""; }
			array_push($sectors,$row5);

			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Ser' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); 
					for($j = 0; $j < count($TypeChunk); $j++){
						$row6['item']=$abData[$TypeChunk[$j]];
						$row6['css']='readonly';
						$row6['chart']=false;
						for($y = 0; $y < count($AllYear); $y++){ $row6[$AllYear[$y]]=""; }
						array_push($sectors,$row6);

						foreach($serendtype as $serendtypes){
							if($serendtypes['id']!='SH' and $serendtypes['id']!='AC'){						
								$row7['item']=$serendtypes['value'];
								$row7['css']='';
								$row7['chart']=false;
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									if($serendtypes['id']=='SH'){
										$IN = $serendtypes['id'].'_'.$AY;
									}elseif($serendtypes['id']=='AC'){
										$IN = $serendtypes['id'].'_'.$AY;
									}else{
										$IN = $serendtypes['id'].'_'.$TypeChunk[$j].'_'.$AY;
									}
									$row7[$AY]=formatnumber($amData[$IN]);
								}
								array_push($sectors,$row7);
							}
						}
			
					}

					$row8['item']='Total'; //$TOTAL;
					$row8['css']='readonly';
					$row8['chart']=false;

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row8[$AY]=formatnumber($amData['T_'.$AY]);
					}
					array_push($sectors,$row8);

				}
			}

			$row9['item']='Total Useful energy demand';
			$row9['css']='readonly1';
			$row9['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row9[$AllYear[$y]]=""; }
			array_push($sectors,$row9);		
			
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Ser' ){
					foreach($serendtype as $serendtypes){
						$row10['item']=$serendtypes['value'];
						$row10['css']='';
						$row10['chart']=true;
						$series[]=$serendtypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$IN = 'T_'.$serendtypes['id'].'_'.$AY;
							$row10[$AY]=formatnumber($amData[$IN]);
						}
						array_push($sectors,$row10);

					}

					$row11['item']='Total'; //$TOTAL;
					$row11['css']='readonly';
					$row11['chart']=true;
					$series[]='Total';

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row11[$AY]=formatnumber($amData['T_'.$AY]);
					}
					array_push($sectors,$row11);
				}
			}
			
			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "5.2.": //Final Energy Demand in Service Sector
			$amData = $amd->getByField(16,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();

			foreach($serendtype as $serendtypes){
				if($serendtypes['pen']=='Y' and $serendtypes['id']!='SH' or $serendtypes['id']=='AP'){					
					$row['item']=$serendtypes['value'];
					$row['css']='readonly1';
					$row['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					array_push($sectors,$row);

					foreach($sertype as $sertypes){  
						if($sertypes[$serendtypes['id']]=='Y'){
							$row1['item']=$sertypes['value'];
							$row1['css']='';
							$row1['chart']=false;
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$FN = 'P_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AY;
								$row1[$AY]=formatnumber($amData[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AY]);
							}
							array_push($sectors,$row1);

						}
					}

					$row2['item']='Total - '.$serendtypes['value'];
					$row2['css']='readonly';
					$row2['chart']=true;
					$series[]='Total - '.$serendtypes['value'];

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row2[$AY]=formatnumber($amData['ToF_'.$serendtypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row2);
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			case "5.3.": //Total Final Energy Demand in Service Sector (by energy forms)
			$amData = $amd->getByField(16,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();
			foreach($sertype as $sertypes){  
				$row1['item']=$sertypes['value'];
				$row1['css']='';
				$row1['chart']=true;
				$series[]=$sertypes['value'];
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['F_'.$sertypes['id'].'_'.$AY]);
				}
				array_push($sectors,$row1);
			}

			$row2['item']='Total'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=false;
			
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['ToF_'.$AY]);
			}
			array_push($sectors,$row2);

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$DefaultEne;
			echo (json_encode($results));
			break;

			//FINAL ENERGY DEMAND
			case "6.1.": //Final Energy Demand
			$unitpercapita=array();
			$unitpercapita['PJ']="GJ/cap";
			$unitpercapita['GWyr']="MWh/cap";
			$unitpercapita['GWh']="MWh/cap";
			$unitpercapita['Tcal']="Mcal/cap";
			$unitpercapita['Mtoe']="toe/cap";
			$unitpercapita['GBTU']="kBTU/cap";

			$unitgdp=array();
			$unitgdp['PJ']="MJ/US$";
			$unitgdp['GWyr']="kWh/US$";
			$unitgdp['GWh']="kWh/US$";
			$unitgdp['Tcal']="kcal/US$";
			$unitgdp['Mtoe']="kgoe/US$";
			$unitgdp['GBTU']="BTU/US$";

			$unitdropdown=$_POST['unit'];
			$unit=$DefaultEne;
			if($unitdropdown==null){
				$unitdropdown=$DefaultEne;
			}

			$u=1;
			$unitconversion = Config1::getData('unitconversionfactor',$caseStudyId, true);

			for($i=0; $i<count($unitconversion); $i++){
				if($unitconversion[$i]["id"]==$unit){
					$u=$unitconversion[$i][$unitdropdown];
				}
			}
			$unit=$unitdropdown;
			$amData = $amd->getByField(17,'SID');
			$bjd = new Data($caseStudyId,$bjxml);
			$sectors= array();
			$series= array();

			$row['item']='Final Energy Demand by Energy Form'; //$Final_Energy_Demand_Energy_Form;
			$row['css']='readonly1';
			$row['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
			array_push($sectors,$row);

			foreach($pentype as $pentypes){
				$pid = 	$pentypes['id'];
				if ($pid!='CG'){
					if($pid=='CK'){ 
						$row1['item']="Coke & Steam Coal";
					}
					else
					{
						$row1['item']=$pentypes['value'];
					}
					$row1['css']='';
					$row1['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber((double)$amData[$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row1);
				}
			}

			$row2['item']='Total - Final Energy Demand by Energy Form'; //$TOTAL;
			$row2['css']='readonly';
			$row2['chart']=true;
			$series[]='Total - Final Energy Demand by Energy Form';
			
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber((double)$amData['T_'.$AY]*$u);
			}
			array_push($sectors,$row2);
		

			$row3['item']='Final Energy Demand per Capita & per GDP'; //$Final_Energy_Demand_Capita_GDP;
			$row3['css']='readonly1';
			$row3['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
			array_push($sectors,$row3);
			
			$row4['item']='FE per Capita ('.$unitpercapita[$unit].')';
			$row4['css']='';
			$row4['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row4[$AY]=formatnumber((double)$amData['FCAP_'.$AY]*$multiple[$DefaultPop."Population"]*$u);
			}
			array_push($sectors,$row4);

			$row5['item']='FE per GDP ('.$unitgdp[$unit].')';
			$row5['css']='';
			$row5['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row5[$AY]=formatnumber((double)$amData['FGDP_'.$AY]*$u);
			}
			array_push($sectors,$row5);

			$row6['item']='Final Energy Demand by Sector';//$Final_Energy_Demand_Sector;
			$row6['css']='readonly1';
			$row6['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row6[$AllYear[$y]]=""; }
			array_push($sectors,$row6);
			
			$row7['item']='Industry';
			$row7['css']='';
			$row7['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row7[$AY]= formatnumber((double)$amData['Ind_'.$AY]*$u);
			}
			array_push($sectors,$row7);

			$row8['item']='    Manufacturing';
			$row8['css']='';
			$row8['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row8[$AY]= formatnumber((double)$amData['Man_'.$AY]*$u);
			}
			array_push($sectors,$row8);

			$row9['item']='    ACM';
			$row9['css']='';
			$row9['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row9[$AY]= formatnumber((double)$amData['ACM_'.$AY]*$u);
			}
			array_push($sectors,$row9);

			$row10['item']='Transportation';
			$row10['css']='';
			$row10['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row10[$AY]= formatnumber((double)$amData['Trp_'.$AY]*$u);
			}
			array_push($sectors,$row10);

			$row11['item']='    Freig. transp.';
			$row11['css']='';
			$row11['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row11[$AY]= formatnumber((double)$amData['Fre_'.$AY]*$u);
			}
			array_push($sectors,$row11);

			$row12['item']='    Pass. transp';
			$row12['css']='';
			$row12['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row12[$AY]= formatnumber((double)$amData['Pass_'.$AY]*$u);
			}
			array_push($sectors,$row12);

			$row13['item']='Household';
			$row13['css']='';
			$row13['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row13[$AY]= formatnumber((double)$amData['Hou_'.$AY]*$u);
			}
			array_push($sectors,$row13);

			$row14['item']='Service';
			$row14['css']='';
			$row14['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row14[$AY]= formatnumber((double)$amData['Ser_'.$AY]*$u);
			}
			array_push($sectors,$row14);

			$row15['item']='Total - Final Energy Demand by Sector';
			$row15['css']='readonly';
			$row15['chart']=true;
			$series[]='Total - Final Energy Demand by Sector';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row15[$AY]= formatnumber((double)$amData['Tot_'.$AY]*$u);
			}
			array_push($sectors,$row15);

			$row16['item']='By Sector';//$By_Sector; 
			$row16['css']='readonly1';
			$row16['chart']=false;
			for($y = 0; $y < count($AllYear); $y++){ $row16[$AllYear[$y]]=""; }
			array_push($sectors,$row16);

			foreach($pentype as $pentypes){ 
				$pid = 	$pentypes['id'];
				$pvalue=$pentypes['value'];
				if ($pid!='CG'){

					if($pid=='CK'){
						$pvalue="Coke & Steam Coal";
					}

					$row161['item']=$pvalue;
					$row161['css']='readonly1';
					$row161['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ $row161[$AllYear[$y]]=""; }
					array_push($sectors,$row161);

				
					$row17['item']='Industry';
					$row17['css']='';
					$row17['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row17[$AY]= formatnumber((double)$amData['Ind_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row17);
		
					$row18['item']='    Manufacturing';
					$row18['css']='';
					$row18['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row18[$AY]= formatnumber((double)$amData['Man_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row18);
		
					$row19['item']='    ACM';
					$row19['css']='';
					$row19['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row19[$AY]= formatnumber((double)$amData['ACM_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row19);
		
					$row20['item']='Transportation';
					$row20['css']='';
					$row20['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row20[$AY]= formatnumber((double)$amData['Trp_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row20);
		
					$row21['item']='    Freig. transp.';
					$row21['css']='';
					$row21['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row21[$AY]= formatnumber((double)$amData['Fre_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row21);
		
					$row22['item']='    Pass. transp';
					$row22['css']='';
					$row22['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row22[$AY]= formatnumber((double)$amData['Pass_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row22);
		
					$row23['item']='Household';
					$row23['css']='';
					$row23['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row23[$AY]= formatnumber((double)$amData['Hou_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row23);
		
					$row24['item']='Service';
					$row24['css']='';
					$row24['chart']=false;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row24[$AY]= formatnumber((double)$amData['Ser_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row24);
		
					$row25['item']='Total - '.$pvalue; //$TOTAL;
					$row25['css']='readonly';
					$row25['chart']=true;
					$series[]='Total - '.$pvalue;
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row25[$AY]= formatnumber((double)$amData['Tot_'.$pid.'_'.$AY]*$u);
					}
					array_push($sectors,$row25);
				}
			}

			$results['result']=$sectors;
			$results['series']=$series;
			$results['allyears']=$AllYear;
			$results['unit']=$unit;
			echo (json_encode($results));

			break;

	}

	function formatnumber($number){
		if($number=="NAN" || $number=="INF"){
		$num=0.000000000000000;
	}else{
		if(is_nan($number)){
			$num=number_format(0,15,'.','');
		}else{
		$num=number_format((double)$number,15,'.','');
		}
	}
		return $num;
	}
	
	?>