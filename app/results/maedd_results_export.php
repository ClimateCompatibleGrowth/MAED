<?php
	require_once "../../config.php";
	require_once BASE_PATH."general.php";
	require_once CLASS_PATH."Data.class.php";
	require_once CLASS_PATH."SimpleXLSXGen.php";
	
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
	$sectors= array();
	$row['Item']=$caseStudyId;
	$row['style']=8;
	array_push($sectors,$row);

	$row=[];
	$row['Item']="";
	array_push($sectors,$row);
		//-----GDP
		$row['Item']="1. GDP";
		$row['style']=9;
		array_push($sectors,$row);

		//1.1. GDP formation by sector/subsector (absolute values)
			$row=[];
			$row['Item']="1.1. GDP formation by sector/subsector (absolute values) [".$DefaultCurrency.' '.$labelUnit[$DefaultGdp]."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$series= array();
			foreach($maintype as $maintypes){ 
				$row=[];
				if($maintypes['gdp']=='Y') {
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					
					$row['item']=$maintypes['value'];

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData[$maintypes['id'].'_S_'.$AY]);
					}
					$row['style']=10;
					array_push($sectors,$row);

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AY]);			
						}		
						array_push($sectors,$row1);
					} 
				} 

			}

			$row2['item']='Total GDP';
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['G_'.$AY]);
			}
			$row2['style']=10;
			array_push($sectors,$row2);

		$row=[];
		$row['Item']="";
		array_push($sectors,$row);
			
		//1.2. Per Capita GDP by sector          
			$row=[];
			$row['Item']="1.2. Per Capita GDP by sector [".$DefaultCurrency." /Capita]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$row=[];
			$series= array();
			$row['item']='GDP/cap'; 
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['GC_'.$AY]);							
				}	
			$row['style']=10;
			array_push($sectors,$row);
			$series[]='GDP/cap'; //$GDP_cap; 
			foreach($maintype as $maintypes){ 
				if($maintypes['gdp']=='Y'){
					$row1['item']=$maintypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData[$maintypes['id'].'_'.$AY]);
					}
					array_push($sectors,$row1);
				}
			}
			
			//1.3. GDP formation by sector/subsectors (growth rates)
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="1.3. GDP formation by sector/subsectors (growth rates) [%]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			
			foreach($maintype as $maintypes){ 
				if($maintypes['gdp']=='Y') { 
					$row=[];
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['GR_'.$maintypes['id'].'_'.$AY]);
					}
					$row['style']=10;
					array_push($sectors,$row);
					$series[]=$maintypes['value'];

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber($amData['GR_'.$TypeChunk[$j].'_'.$AY]);			
						}		
						array_push($sectors,$row1);
					} 
				} 

			}

			$row2['item']='Total GDP';//$Total_GDP;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row2[$AY]=formatnumber($amData['GRT_'.$AY],15);
			}
			$row2['style']=10;
			array_push($sectors,$row2);

			$row3['item']='GDP/cap';//$Total_GDP;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row3[$AY]=formatnumber($amData['GRC_'.$AY]);
			}
			$row3['style']=10;
			array_push($sectors,$row3);
			//-----END GDP


			//INDUSTRY - Useful Energy
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="2.1. INDUSTRY - Useful Energy";
			$row['style']=9;
			array_push($sectors,$row);
			//2.1.1. Useful energy demand for Motive Power
			$row=[];
			$row['Item']="2.1.1. Useful energy demand for Motive Power [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			foreach($maintype as $maintypes){ 
				$row=[];
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
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
					$row['style']=10;
					array_push($sectors,$row);

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['MP_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['M_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='MP'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Others Motive Power';					 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['MO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							array_push($sectors,$row2);		 
						  }
					}
				}
			}		
				
			//2.1.2. Useful energy demand for Electricity specific uses
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.1.2. Useful energy demand for Electricity specific uses [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			foreach($maintype as $maintypes){ 
				$row=[];
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
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
					$row['style']=10;
					array_push($sectors,$row);

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['AP_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['E_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='AP'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Other Elec Spec use';						 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['EO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							
							array_push($sectors,$row2);		 
						  }
					}
				}
			}		
			
				
			//2.1.3. Useful energy demand for Thermal uses
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.1.3. Useful energy demand for Thermal uses [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');
			foreach($maintype as $maintypes){ 
				$row=[];
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];
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
					$row['style']=10;
					array_push($sectors,$row);

					 for($j = 0; $j < count($TypeChunk); $j++){
						if($abData[$TypeChunk[$j]] and $bjData['TU_'.$TypeChunk[$j]]=='Y'){
							if($abData[$TypeChunk[$j]]){
								$row1['item']=$abData[$TypeChunk[$j]];
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row1[$AY]=formatnumber($amData['T_'.$TypeChunk[$j].'_'.$AY]);			
									}		
								array_push($sectors,$row1);		
							}
						}

						if($abData[$TypeChunk[$j]] and $bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row2['item']=$abData[$TypeChunk[$j]].' - Other Thermal use';						 
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData['TO_'.$TypeChunk[$j].'_'.$AY]);	
								}
							array_push($sectors,$row2);		 
						  }
					}
				}
			}		
	
				
			//2.1.4. Industry: Total useful energy demand in Industry
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.1.4. Industry: Total useful energy demand in Industry [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(2,'SID');

			foreach($maintype as $maintypes){ 
				$row=[];
				if($maintypes['ind']=='Y') { 
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']);
					$row['item']=$maintypes['value'];

					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['ToT_'.$maintypes['id'].'_'.$AY]);
					 }				
					 $row['style']=10;	
					 array_push($sectors,$row);

					for($j = 0; $j < count($TypeChunk); $j++){ 
						$row1['item']=$abData[$TypeChunk[$j]];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['A_'.$TypeChunk[$j].'_'.$AY]);			
							}		
						array_push($sectors,$row1);		
					}
				}
			}		

			//INDUSTRY - Energy Demand ACM
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="2.2. INDUSTRY - Energy Demand ACM";
			$row['style']=9;
			array_push($sectors,$row);
			//2.2.1. Total final energy demand for thermal uses in Agriculture, Construction & Mining
			$row=[];
			$row['Item']="2.2.1. Total final energy demand for thermal uses in Agriculture, Construction & Mining [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
				$bjd = new Data($caseStudyId,$bjxml);
				$bjData = $bjd->getByField(1,'SID');
				$amData = $amd->getByField(4,'SID');
			
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['fac']=='Y') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);
						
						 for($j = 0; $j < count($TypeChunk); $j++){
							 
							if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
								$row1=[];
								$row1['item']=$abData[$TypeChunk[$j]];	
								for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
								array_push($sectors,$row1);		
								foreach($pentype as $pentypes){ 
									if($pentypes[$maintypes['id'].'_TU']=='Y'){	
										$row2=[];
										$row2['item']=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
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
								for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }					 
								array_push($sectors,$row3);	
	
								foreach($pentype as $pentypes){ 
									if($pentypes[$maintypes['id'].'_TU']=='Y'){	
										$row4['item']=$pentypes['value'];
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
	
				//2.2.2. Total final energy demand (absolute) in Agriculture, Construction & Mining
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="2.2.2. Total final energy demand (absolute) in Agriculture, Construction & Mining [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$bjd = new Data($caseStudyId,$bjxml);
				$bjData = $bjd->getByField(1,'SID');
				$amData = $amd->getByField(4,'SID');
				
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['fac']=='Y') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData[$maintypes['id'].'_'.$AllYear[$y]]);
						 }
						$row['style']=10;
						array_push($sectors,$row);
	
						$series[]=$maintypes['value'];	
						foreach($pentype as $pentypes){ 
							if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y' ){	
								$row1['item']=$pentypes['value'];
								for($y = 0; $y < count($AllYear); $y++){  
									$AY=$AllYear[$y];
									$row1[$AY]=formatnumber($amData[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]]);
								}
							array_push($sectors,$row1);		
							}	
						}
					}
				}		
	
				//2.2.3. Total final energy demand (shares) in Agriculture, Construction & Mining
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="2.2.3. Total final energy demand (shares) in Agriculture, Construction & Mining [%]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$bjd = new Data($caseStudyId,$bjxml);
				$bjData = $bjd->getByField(1,'SID');
				$amData = $amd->getByField(4,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['fac']=='Y') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);
	
						$series[]=$maintypes['value'];	
						foreach($pentype as $pentypes){
							if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y'){	
								$row1['item']=$pentypes['value'];
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
	
				//2.2.4. Total Final Energy Demand per Value Added in Agriculture, Construction & Mining
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="2.2.4. Total Final Energy Demand per Value Added in Agriculture, Construction & Mining [".$energyUnit[$DefaultEne].'/'.$DefaultCurrency."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$bjd = new Data($caseStudyId,$bjxml);
				$bjData = $bjd->getByField(1,'SID');
				$amData = $amd->getByField(4,'SID');
				
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['fac']=='Y') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$mainid = $maintypes['id'].'_'.$AllYear[$y];
							$row[$AY]=formatnumber($amData['T_'.$mainid]);
						 }
						 $row['style']=10;
						array_push($sectors,$row);
	
						$series[]=$maintypes['value'];	
						foreach($pentype as $pentypes){ 
							if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y'){	
								$row1['item']=$pentypes['value'];
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

			//INDUSTRY - Final Demand Manufacturing
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="2.3. INDUSTRY - Final Demand Manufacturing";
			$row['style']=9;
			array_push($sectors,$row);
			//2.3.1. Useful Thermal Energy Demand in Manufacturing
			$row=[];
			$row['Item']="2.3.1. Useful Thermal Energy Demand in Manufacturing [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(5,'SID');
		
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
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$total[$AY]=$total[$AY]+$amData[$TypeChunk[$j].'_'.$AllYear[$y]];
									$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AllYear[$y]]);		
								}		
							$row1['style']=10;
							array_push($sectors,$row1);		
						}

						foreach($facmtype as $facmtypes){ 
							if(($bjData['TU_'.$TypeChunk[$j]]=='Y') and $facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y'){
								$row2['item']=$facmtypes['value'];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row2[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);		
								}		
							array_push($sectors,$row2);	
							}
						}

						if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
							$row3['item']=$facmtypes['value'].' - Other Thermal Uses';
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
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row4[$AY]=formatnumber($total[$AY]);
			}
			$row4['style']=10;
			array_push($sectors,$row4);

			//2.3.2. Penetration of Energy Carriers into Useful Thermal Energy Demand in Manufacturing
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.3.2. Penetration of Energy Carriers into Useful Thermal Energy Demand in Manufacturing [%]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(6,'SID');
			$anData = $amd->getByField(7,'SID');
			
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
							
							for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
							$row1['style']=10;
							array_push($sectors,$row1);	

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if(($pentypes['Man_SWH']=='Y' or  $pentypes['Man_SG']=='Y' or $pentypes['Man_FDH']=='Y')){	
									$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
									$row2['item']=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									$series[]=$pentypes['value'].' ('.$abData[$TypeChunk[$j]].')';
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row2[$AY]=formatnumber($amData[$eid.'_'.$AllYear[$y]]);		
									}	
									array_push($sectors,$row2);	
									
									if($pentypes['id']=='EL' ){
										$row3['item']='Heat Pumps';
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
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$row5[$AY]=formatnumber($amData['OT_'.$eid.'_'.$AY]);			
											}		
											array_push($sectors,$row5);	

											if($pentypes['id']=='EL' and ($facmtypes['id']=='SG' or $facmtypes['id']=='SWH')){
												$row6['item']='(of which Heat Pumps)';
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
			

			//2.3.3. Total final energy demand for thermal uses in Manufacturing
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.3.3. Total final energy demand for thermal uses in Manufacturing [%]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$bjd = new Data($caseStudyId,$bjxml);
			$bjData = $bjd->getByField(1,'SID');
			$amData = $amd->getByField(8,'SID');

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
							$row1=[];
							$row1['item']=$abData[$TypeChunk[$j]];						
							for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
							$row1['style']=10;
							array_push($sectors,$row1);	
							foreach($facmtype as $facmtypes){ 
								if($facmtypes['PE']=='N' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y'){
									$row2=[];
									$row2['item']=$facmtypes['value'];
									for($y = 0; $y < count($AllYear); $y++){ $row2[$AllYear[$y]]=""; }
									$row2['style']=10;
									array_push($sectors,$row2);

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if($pentypes['Man_'.$cid]=='Y' and $pentypes['id']!='CG'){
									if(($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')){	
										$row3=[];
										$eid = $TypeChunk[$j].'_'.$pentypes['id'];
										$row3['item']=$pentypes['value'].' ('.$facmtypes['value'].')';
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
							$row4=[];
							$row4['item']=$pentypes['value'];
							for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
							array_push($sectors,$row4);	

							foreach($pentype as $pentypes){ 
								$cid = 	$facmtypes['id'];
								if($pentypes['Man_'.$cid]=='Y' and $pentypes['id']!='CG'){	
										$eid = $TypeChunk[$j].'_'.$pentypes['id'];
										$row5['item']=$pentypes['value'];
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

			//2.3.4. Total Final Energy Demand in Manufacturing (absolute)
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.3.4. Total Final Energy Demand in Manufacturing (absolute) [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);

			$amData = $amd->getByField(9,'SID');
			foreach($pentype as $pentypes){ 
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row=[];
					$row['item']=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData[$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total';//$TOTAL;
			
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['Y_'.$AY]);
			}
			$row1['style']=10;
			array_push($sectors,$row1);

			//2.3.5. Total Final Energy Demand in Manufacturing (shares)
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.3.5. Total Final Energy Demand in Manufacturing (shares) [%]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$amData = $amd->getByField(9,'SID');

			foreach($pentype as $pentypes){ 
				$row=[];
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['S_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			//2.3.6. Total Final Energy Demand per Value Added in Manufacturing
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			$row=[];
			$row['Item']="2.3.6. Total Final Energy Demand per Value Added in Manufacturing [".$energyUnit[$DefaultEne].'/'.$DefaultCurrency."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
			$amData = $amd->getByField(9,'SID');
			foreach($pentype as $pentypes){ 
				$row=[];
				$eid = 	$pentypes['id'];
				if ($eid!='CG'){
					$row['item']=$pentypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row[$AY]=formatnumber($amData['V_'.$eid.'_'.$AY]);
					}
					array_push($sectors,$row);
				}
			}

			$row1['item']='Total';//$TOTAL;
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row1[$AY]=formatnumber($amData['V_'.$AY]);
			}
			$row1['style']=10;
			array_push($sectors,$row1);

			//INDUSTRY - Demand Industry
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="2.4. INDUSTRY - Demand Industry";
			$row['style']=9;
			array_push($sectors,$row);

			//2.4.1. Total Final Energy Demand in Industry (absolute)
			$row=[];
			$row['Item']="2.4.1. Total Final Energy Demand in Industry (absolute) [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);
				$amData = $amd->getByField(9,'SID');
				foreach($pentype as $pentypes){ 
					$row=[];
					$eid = 	$pentypes['id'];
					if ($eid!='CG'){
						$row['item']=$pentypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['A_'.$eid.'_'.$AY]);
						}
						array_push($sectors,$row);
					}
				}
	
				$row1['item']='Total';//$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['A_'.$AY]);
				}
				$row1['style']=10;
				array_push($sectors,$row1);
	
				//2.4.2. Total Final Energy Demand in Industry (shares)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="2.4.2. Total Final Energy Demand in Industry (shares) [%]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(9,'SID');

				foreach($pentype as $pentypes){ 
					$row=[];
					$eid = 	$pentypes['id'];
					if ($eid!='CG'){
						$row['item']=$pentypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['F_'.$eid.'_'.$AY]);
						}
						array_push($sectors,$row);
					}
				}

	
				//2.4.3. Total Final Energy Demand Per Value Added in Industry
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="2.4.3. Total Final Energy Demand Per Value Added in Industry [".$energyUnit[$DefaultEne].'/'.$DefaultCurrency."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(9,'SID');
				foreach($pentype as $pentypes){ 
					$row=[];
					$eid = 	$pentypes['id'];
					if ($eid!='CG'){
						$row['item']=$pentypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['T_'.$eid.'_'.$AY]);
						}
						array_push($sectors,$row);
					}
				}
	
				$row1['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['TY_'.$AY]);
				}
				$row1['style']=10;
				array_push($sectors,$row1);


			//TRANSPORT - Freight
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="3.1. TRANSPORT - Freight";
			$row['style']=9;
			array_push($sectors,$row);
						//3.1.1. Energy Demand of Freight Transportation
			$row=[];
			$row['Item']="3.1.1. Energy Demand of Freight Transportation [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);

				$amData = $amd->getByField(10,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Trp') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);	
	
						for($j = 0; $j < count($TypeChunk); $j++){
							$row1=[];
							if($abData[$TypeChunk[$j].'_fr']=='Y'){
								$row1['item']=$abData[$TypeChunk[$j]];
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
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['MY_'.$AY]);
				}
				$row2['style']=10;
				array_push($sectors,$row2);

	
				//3.1.2. Energy Demand of Freight Transportation (by fuel) 
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);

				$row=[];
				$row['Item']="3.1.2. Energy Demand of Freight Transportation (by fuel) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(10,'SID');
				$abxml = 'Trp';
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
						$row=[];
						if (in_array($fueltypes['id'], $fuelss)){
							$row['item']=$fueltypes['value'];
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
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
						}
						array_push($sectors,$row1);						
					}	
				}
				$row2['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['TFu_'.$AY]);
				}
				$row2['style']=10;
				array_push($sectors,$row2);
	
				//3.1.3. Energy Demand of Freight Transportation (by fuel group)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);

				$row=[];
				$row['Item']="3.1.3. Energy Demand of Freight Transportation (by fuel group) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(10,'SID');
				$row=[];
				$row['item']='Electricity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['EL_'.$AY]);
				}
				array_push($sectors,$row);

	
				$row1['item']='Steam Coal';

				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['CK_'.$AY]);
				}
				array_push($sectors,$row1);
				$row2=[];
				$row2['item']='Motor Fuels';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['MF_'.$AY]);
				}
				array_push($sectors,$row2);
				$series[]='Motor Fuels';
	
				$row3['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
				}
				$row3['style']=10;
				array_push($sectors,$row3);

	
				//3.1.4. Energy intensities of freight transportation
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);

				$row=[];
				$row['Item']="3.1.4. Energy intensities of freight transportation [".$energyUnit[$DefaultEne]."/100tkm]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);	
				$amData = $amd->getByField(10,'SID');

				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Trp') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);	
	
						for($j = 0; $j < count($TypeChunk); $j++){
							if($abData[$TypeChunk[$j].'_fr']=='Y'){
								$row1['item']=$abData[$TypeChunk[$j]];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row1[$AY]=formatnumber($amData[$TypeChunk[$j].'_'.$AY]);
								}
								array_push($sectors,$row1);	
							}
						}
	
					}
				}

				//3.1.5. Total freight-kilometers
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);

				$row=[];
				$row['Item']="3.1.5. Total freight-kilometers [".$labelUnit[$Defaultfri]." tkm]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);	
				$amData = $amd->getByField(10,'SID');
				
				$row=[];
				$row['item']='Freight-km';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['F_'.$AY]*$multiple[$Defaultfri]);
				}
				array_push($sectors,$row);

	//3.2. TRANSPORT - Intercity
	$row=[];
	$row['Item']="";
	array_push($sectors,$row);

	$row=[];
	$row['Item']="3.2. TRANSPORT - Intercity";
	$row['style']=9;
	array_push($sectors,$row);
	//3.2.1. Intercity Intercity Transportation by mode	$row=[];
	$row['Item']="3.2.1. Intercity Intercity Transportation by mode [".$DefaultEne."]";
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=$AY;
	}
	$row['style']=8;
	array_push($sectors,$row);
	$amData = $amd->getByField(12,'SID');
	$row=[];
	$row['item']='Transportation';
	for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
	$row['style']=10;
	array_push($sectors,$row);

	$row1['item']='Cars';
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row1[$AY]=formatnumber($amData['C_'.$AY]*$multiple[$DefaultPas]);
	}
	array_push($sectors,$row1);

	$row2['item']='Public Transport';
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row2[$AY]=formatnumber($amData['P_'.$AY]*$multiple[$DefaultPas]);
	}
	array_push($sectors,$row2);
	$row3['item']='Total'; //$TOTAL;
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row3[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
	}
	$row3['style']=10;
	array_push($sectors,$row3);


	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp') { 
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']);
			$row4['item']=$maintypes['value'].' by Mode';
			for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
			$row4['style']=10;
			array_push($sectors,$row4);
			for($j = 0; $j < count($TypeChunk); $j++){
				if($abData[$TypeChunk[$j].'_ps']=='Y'){
					$row5['item']=$abData[$TypeChunk[$j]];
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
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row6[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
	}
	$row6['style']=10;
	array_push($sectors,$row6);

	//3.2.2. Energy Intensity of Intercity Passenger Transportation(energy units)
	$row=[];
	$row['Item']="";
	array_push($sectors,$row);
	$row=[];
	$row['Item']="3.2.2. Energy Intensity of Intercity Passenger Transportation(energy units) [".$DefaultEne."]";
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=$AY;
	}
	$row['style']=8;
	array_push($sectors,$row);
	$amData = $amd->getByField(12,'SID');
	foreach($maintype as $maintypes){ 
		$row=[];
		if($maintypes['id']=='Trp') { 
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']);
			$row['item']=$maintypes['value'];
			for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
			$row['style']=10;
			array_push($sectors,$row);
			for($j = 0; $j < count($TypeChunk); $j++){
				if($abData[$TypeChunk[$j].'_ps']=='Y'){
					$row1['item']=$abData[$TypeChunk[$j]];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData['EI_'.$TypeChunk[$j].'_'.$AY]);
					}
					array_push($sectors,$row1);	
				}
			}
		}
	}

	//3.2.3. Energy Demand of Intercity Passenger Transportation(by mode)
	$row=[];
	$row['Item']="";
	array_push($sectors,$row);
	$row=[];
	$row['Item']="3.2.3. Energy Demand of Intercity Passenger Transportation(by mode) [".$DefaultEne."]";
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=$AY;
	}
	$row['style']=8;
	array_push($sectors,$row);
	$amData = $amd->getByField(12,'SID');
	foreach($maintype as $maintypes){ 
		$row=[];
		if($maintypes['id']=='Trp') { 
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']);
			$row['item']=$maintypes['value'];
			for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
			$row['style']=10;
			array_push($sectors,$row);
			for($j = 0; $j < count($TypeChunk); $j++){
				if($abData[$TypeChunk[$j].'_ps']=='Y'){
					$row1['item']=$abData[$TypeChunk[$j]];
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
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
	}
	$row2['style']=10;
	array_push($sectors,$row2);


	//3.2.4. Energy Demand of Intercity Passenger Transportation (by fuel)
	$row=[];
	$row['Item']="";
	array_push($sectors,$row);
	$row=[];
	$row['Item']="3.2.4. Energy Demand of Intercity Passenger Transportation (by fuel) [".$DefaultEne."]";
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=$AY;
	}
	$row['style']=8;
	array_push($sectors,$row);
	$amData = $amd->getByField(12,'SID');
	if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
	{
		foreach($fueltype as $fueltypes){$row=[];
			$row['item']=$fueltypes['value'];
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=formatnumber($amData['Fu_'.$fueltypes['id'].'_'.$AY]);
			}
			array_push($sectors,$row);
		}
	}
	else
	{
		$row1=[];
		$row1['item']=$fueltype['value'];
		for($y = 0; $y < count($AllYear); $y++){ 
			$AY=$AllYear[$y];
			$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
		}
		array_push($sectors,$row1);
	}

	$row2['item']='Total'; //$TOTAL;
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
	}
	$row2['style']=10;
	array_push($sectors,$row2);


	//3.2.5. Energy Demand of Intercity Passenger Transportation (by fuel group)
	$row=[];
	$row['Item']="";
	array_push($sectors,$row);
	$row=[];
	$row['Item']="3.2.5. Energy Demand of Intercity Passenger Transportation (by fuel group) [".$DefaultEne."]";
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=$AY;
	}
	$row['style']=8;
	array_push($sectors,$row);
	$amData = $amd->getByField(12,'SID');
	$row=[];
	$row['item']='Electricity';
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row[$AY]=formatnumber((double)$amData['EL_'.$AY]);
	}
	array_push($sectors,$row);
	$row1=[];
	$row1['item']='Steam Coal';
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row1[$AY]=formatnumber((double)$amData['CK_'.$AY]);
	}
	array_push($sectors,$row1);
	$row2=[];
	$row2['item']='Motor Fuels';
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row2[$AY]=formatnumber($amData['MF_'.$AY]);
	}
	array_push($sectors,$row2);

	$row3=[];
	$row3['item']='Total'; //$TOTAL;
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
	}
	$row3['style']=10;
	array_push($sectors,$row3);

//3.3. TRANSPORT - Urban
$row=[];
$row['Item']="";
array_push($sectors,$row);

$row=[];
$row['Item']="3.3. TRANSPORT - Urban";
$row['style']=9;
array_push($sectors,$row);
//3.3.1. Urban transport Passenger by mode
$row['Item']="3.3.1. Urban transport Passenger by mode [".$labelUnit[$DefaultPas]." pkm]";
for($y = 0; $y < count($AllYear); $y++){ 
	$AY=$AllYear[$y];
	$row[$AY]=$AY;
}
$row['style']=8;
array_push($sectors,$row);

				$amData = $amd->getByField(11,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Trp') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);
						for($j = 0; $j < count($TypeChunk); $j++){
							if($abData[$TypeChunk[$j].'_psi']=='Y'){
								$row1['item']=$abData[$TypeChunk[$j]];
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
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['T_'.$AY]*$multiple[$DefaultPas]);
				}
				$row2['style']=10;
				array_push($sectors,$row2);
	
	
				//3.3.2. Energy Intensity of Urban transport (energy units)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.3.2. Energy Intensity of Urban transport (energy units) [".$energyUnit[$DefaultEne]."/pkm]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(11,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Trp') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);
						for($j = 0; $j < count($TypeChunk); $j++){
							$row1=[];
							if($abData[$TypeChunk[$j].'_psi']=='Y'){
								$row1['item']=$abData[$TypeChunk[$j]];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row1[$AY]=formatnumber($amData['I_'.$TypeChunk[$j].'_'.$AY]);
								}
								array_push($sectors,$row1);	
							}
						}
					}
				}
	
				//3.3.3. Energy Demand of Urban transport (by mode)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.3.3. Energy Demand of Urban transport (by mode) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(11,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Trp') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						$row['item']=$maintypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
						$row['style']=10;
						array_push($sectors,$row);
						for($j = 0; $j < count($TypeChunk); $j++){
							$row1=[];
							if($abData[$TypeChunk[$j].'_psi']=='Y'){
								$row1['item']=$abData[$TypeChunk[$j]];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row1[$AY]=formatnumber($amData['C_'.$TypeChunk[$j].'_'.$AY]);
								}
								array_push($sectors,$row1);	
							}
						}
					}
				}
				
				$row=[];
				$row2['item']='Total';//$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['CY_'.$AY]);
				}
				$row2['style']=10;
				array_push($sectors,$row2);
	
				//3.3.4. Energy Demand of Urban transport (by fuel)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.3.4. Energy Demand of Urban transport (by fuel) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(11,'SID');
				if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
				{
					foreach($fueltype as $fueltypes){
						$row=[];
						$row['item']=$fueltypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['Fu_'.$fueltypes['id'].'_'.$AY]);
						}
						array_push($sectors,$row);
					}
				}
				else
				{
					$row1=[];
					$row1['item']=$fueltype['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData['Fu_'.$fueltype['id'].'_'.$AY]);
					}
					array_push($sectors,$row1);
				}
				
				$row2=[];
				$row2['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['ECY_'.$AY]);
				}
				$row2['style']=10;
				array_push($sectors,$row2);
	
				//3.3.5. Energy Demand of Urban transport (by fuel group)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.3.5. Energy Demand of Urban transport (by fuel group) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(11,'SID');
				$row=[];
				$row['item']='Electricity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['EL_'.$AY]);
				}
				array_push($sectors,$row);
				
				$row1=[];
				$row1['item']='Steam Coal';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['CK_'.$AY]);
				}
				array_push($sectors,$row1);

				$row2=[];
				$row2['item']='Motor Fuels';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['MF_'.$AY]);
				}
				array_push($sectors,$row2);
				
				$row3=[];
				$row3['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
				}
				$row3['style']=10;
				array_push($sectors,$row3);


			//3.4. TRANSPORT - Final Demand Transport
			$row=[];
			$row['Item']="";
			array_push($sectors,$row);

			$row=[];
			$row['Item']="3.4. TRANSPORT - Final Demand Transport";
			$row['style']=9;
			array_push($sectors,$row);
			//3.4.1. Final energy demand in Transportation sector (by fuels)
			$row=[];
			$row['Item']="3.4.1. Final energy demand in Transportation sector (by fuels) [".$DefaultEne."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);

				$amData = $amd->getByField(13,'SID');
				if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
				{
					foreach($fueltype as $fueltypes){
						$row=[];
						$fid = $fueltypes['id'];
						$row['item']=$fueltypes['value'];
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
					$row1=[];
					$row1['item']=$fueltype['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData['Fu_'.$fid.'_'.$AY]);
					}
					array_push($sectors,$row1);
				}
				$row2=[];
				$row2['item']='International Transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['Intl_military_'.$AY]);
				}
				array_push($sectors,$row2);
				
				$row3=[];
				$row3['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['Fu_'.$AY]);
				}
				$row3['style']=10;
				array_push($sectors,$row3);

	
				//3.4.2. Final energy demand in Transportation sector (shares)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.2. Final energy demand in Transportation sector (shares) [%]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(13,'SID');
				if (count($fueltype) !== count($fueltype, COUNT_RECURSIVE))
				{
					foreach($fueltype as $fueltypes){
						$row=[];
						$fid = $fueltypes['id'];
						$row['item']=$fueltypes['value'];
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row[$AY]=formatnumber($amData['SFu_'.$fid.'_'.$AllYear[$y]]);
						}
						array_push($sectors,$row);
					}
				}
				else
				{
					$row1=[];
					$fid = $fueltype['id'];
					$row1['item']=$fueltype['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$row1[$AY]=formatnumber($amData['SFu_'.$fid.'_'.$AY]);
					}
					array_push($sectors,$row1);
				}
	
				$row2=[];
				$row2['item']='International Transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['SFu_Mil_'.$AY]);
				}
				array_push($sectors,$row2);
	
				//3.4.3 Final energy demand in Transportation sector (by fuel groups)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.3 Final energy demand in Transportation sector (by fuel groups) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(13,'SID');
				
				$row=[];
				$row['item']='Electricity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['EL_'.$AY]);
				}
				array_push($sectors,$row);
				
				$row1=[];
				$row1['item']='Steam Coal';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['CK_'.$AY]);
				}
				array_push($sectors,$row1);
				
				$row2=[];
				$row2['item']='Motor Fuels';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['MF_'.$AY]);
				}
				array_push($sectors,$row2);
	
				$row3=[];
				$row3['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['TFu_'.$AY]);
				}
				$row3['style']=10;
				array_push($sectors,$row3);
	
				//3.4.4. Final energy demand in Transportation sector (shares by fuel groups)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.4. Final energy demand in Transportation sector (shares by fuel groups) [%]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(13,'SID');
				
				$row=[];
				$row['item']='Electricity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['S_EL_'.$AY]);
				}
				array_push($sectors,$row);
	
				$row1=[];
				$row1['item']='Steam Coal';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['S_CK_'.$AY]);
				}
				array_push($sectors,$row1);
				
				$row2=[];
				$row2['item']='Motor Fuels';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['S_MF_'.$AY]);
				}
				array_push($sectors,$row2);
	
				//3.4.5. Final energy demand in Transportation sector (by subsector)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.5. Final energy demand in Transportation sector (by subsector) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(13,'SID');
				
				$row=[];
				$row['item']='Freight';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['Freight_'.$AY]);
				}
				array_push($sectors,$row);
				
				$row1=[];
				$row1['item']='Pass Urban transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['Pass_intracity_'.$AY]);
				}
				array_push($sectors,$row1);
				
				$row2=[];
				$row2['item']='Pass Intercity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['Pass_intercity_'.$AY]);
				}
				array_push($sectors,$row2);
				
				$row3=[];
				$row3['item']='International Transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['Intl_military_'.$AY]);
				}
				array_push($sectors,$row3);
	
				$row4['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row4[$AY]=formatnumber($amData['TED_'.$AY]);
				}
				$row4['style']=10;
				array_push($sectors,$row4);
	
				//3.4.6. Final energy demand in Transportation sector (by subsector)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.6. Final energy demand in Transportation sector (by subsector) [%]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(13,'SID');
				$row=[];
				$row['item']='Freight';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['S_Freight_'.$AY]);
				}
				array_push($sectors,$row);
				
				$row1=[];
				$row1['item']='Pass Urban transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['S_Pass_intracity_'.$AY]);
				}
				array_push($sectors,$row1);
				
				$row2=[];
				$row2['item']='Pass Intercity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['S_Pass_intercity_'.$AY]);
				}
				array_push($sectors,$row2);
	
				$row3['item']='International Transport';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['S_Intl_military_'.$AY]);
				}
				array_push($sectors,$row3);	
	
				//3.4.7. Energy Demand of Urban + Intercity + International Passenger Transportation (by fuel group)
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="3.4.7. Energy Demand of Urban + Intercity + International Passenger Transportation (by fuel group) [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(12,'SID');
				
				$row=[];
				$row['item']='Electricity';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=formatnumber($amData['Mi_EL_'.$AY]);
				}
				array_push($sectors,$row);
				
				$row1=[];
				$row1['item']='Steam Coal';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row1[$AY]=formatnumber($amData['Mi_CK_'.$AY]);
				}
				array_push($sectors,$row1);
				
				$row2=[];
				$row2['item']='Motor Fuels';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber($amData['Mi_MF_'.$AY]);
				}
				array_push($sectors,$row2);
				
				$row3=[];
				$row3['item']='Total'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row3[$AY]=formatnumber($amData['TPass_'.$AY]);
				}
				$row3['style']=10;
				array_push($sectors,$row3);

				//4. HOUSEHOLD	
				$row=[];
$row['Item']="";
array_push($sectors,$row);

$row=[];
$row['Item']="4. HOUSEHOLD";
$row['style']=9;
array_push($sectors,$row);
//4.1. Final Energy Demand in Household Sector
$row['Item']="4.1. Final Energy Demand in Household Sector [".$DefaultEne."]";
for($y = 0; $y < count($AllYear); $y++){ 
	$AY=$AllYear[$y];
	$row[$AY]=$AY;
}
$row['style']=8;
array_push($sectors,$row);						

				$amData = $amd->getByField(15,'SID');
				$bjd = new Data($caseStudyId,$bjxml);
				$bjData = $bjd->getByField(1,'SID');
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Hou') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						for($j = 0; $j < count($TypeChunk); $j++){
							if($abData[$TypeChunk[$j].'_A']){
								$row['item']=strtoupper($abData[$TypeChunk[$j]]);
								for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
								$row['style']=10;
								array_push($sectors,$row);	
	
								foreach($houendtype as $houendtypes){
									if($houendtypes['id']!='LH'){
										$row1['item']=$houendtypes['value'];
										for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
										$row1['style']=10;
										array_push($sectors,$row1);
	
										foreach($houtype as $houtypes){  
											if($houtypes[$houendtypes['id']]=='Y' ){
												$row2['item']=$houtypes['value'];
												for($y = 0; $y < count($AllYear); $y++){ 
													$AY=$AllYear[$y];
													$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
													$row2[$AY]=formatnumber($amData[$IN]);
												}
												array_push($sectors,$row2);
											}
										}
										$row3['item']='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;
										for($y = 0; $y < count($AllYear); $y++){ 
											$AY=$AllYear[$y];
											$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
											$row3[$AY]=formatnumber($amData[$IN]);
										}
										$row3['style']=10;
										array_push($sectors,$row3);
									}
									elseif($houendtypes['id']=='LH' and $bjData['LH_'.$TypeChunk[$j]]=='Y'){
										$row4['item']=$houendtypes['value'];
										for($y = 0; $y < count($AllYear); $y++){ $row4[$AllYear[$y]]=""; }
										$row4['style']=10;
										array_push($sectors,$row4);
	
										foreach($houtype as $houtypes){  
											if($houtypes[$houendtypes['id']]=='Y' ){
												$row5['item']=$houtypes['value'];
												for($y = 0; $y < count($AllYear); $y++){ 
													$AY=$AllYear[$y];
													$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
													$row5[$AY]=formatnumber($amData[$IN]);
												}
												array_push($sectors,$row5);
											}
										}
										$row6['item']='Total - '.$houendtypes['value'].' ('.$abData[$TypeChunk[$j]].')'; //$TOTAL							
										for($y = 0; $y < count($AllYear); $y++){ 
											$AY=$AllYear[$y];
											$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
											$row6[$AY]=formatnumber($amData[$IN]);
										}
										$row6['style']=10;
										array_push($sectors,$row6);
									}
								}
							}
	
							$row7['item']='Total Final Energy Demand';
							for($y = 0; $y < count($AllYear); $y++){ $row7[$AllYear[$y]]=""; }
							$row7['style']=10;
							array_push($sectors,$row7);
	
							foreach($houtype as $houtypes){  
									$row8['item']=$houtypes['value'];
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$ED = $TypeChunk[$j].'_'.$houtypes['id'].'_'.$AY;
										$row8[$AY]=formatnumber($amData[$ED]);
									}
									array_push($sectors,$row8);
							}
							$row9['item']='Total ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;			
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$ED = $TypeChunk[$j].'_'.$AY;
								$row9[$AY]=formatnumber($amData[$ED]);
							}
							$row9['style']=10;
							array_push($sectors,$row9);
	
	
						}
					}
				}
	
				//4.2. Useful Energy Demand in Household Sector
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="4.2. Useful Energy Demand in Household Sector [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(14,'SID');
				$bjd = new Data($caseStudyId,$bjxml);
				foreach($maintype as $maintypes){ 
					$row=[];
					if($maintypes['id']=='Hou') { 
						$abxml = $maintypes['id'];
						$abd = new Data($caseStudyId,'sectors_data');
						$abData = $abd->getByField($abxml,'SID');
						$TypeChunk = explode(",",$abData[$abxml.'_A']);
						for($j = 0; $j < count($TypeChunk); $j++){
							$row['item']=strtoupper($abData[$TypeChunk[$j]]);
							for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
							$row['style']=10;
							array_push($sectors,$row);	
							if($abData[$TypeChunk[$j].'_A']){
								$row1=[];
								$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);
								for($k = 0; $k < count($SubChunk); $k++){
									$row1['item']=$abData[$SubChunk[$k]];
									for($y = 0; $y < count($AllYear); $y++){ $row1[$AllYear[$y]]=""; }
									$row1['style']=10;
									array_push($sectors,$row1);	
	
								foreach($houendtype as $houendtypes){
									if($houendtypes['id']=='LH'){
										$row2=[];
										$row2['item']='Electricity - Lighting';
										for($y = 0; $y < count($AllYear); $y++){ 
											$AY=$AllYear[$y];
											$IN = $SubChunk[$k].'_LH_'.$AY;
											$row2[$AY]=formatnumber($amData[$IN]);
										}
										array_push($sectors,$row2);
										$row3=[];
										$row3['item']='Fossil Fuels - Lighting';
										for($y = 0; $y < count($AllYear); $y++){ 
											$AY=$AllYear[$y];
											$IN = $SubChunk[$k].'_FF_'.$AY;
											$row3[$AY]=formatnumber($amData[$IN]);
										}
										array_push($sectors,$row3);
										}else{
										$row4=[];
										$row4['item']=$houendtypes['value'];
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
						for($y = 0; $y < count($AllYear); $y++){ $row5[$AllYear[$y]]=""; }
						$row5['style']=10;
						array_push($sectors,$row5);
	
						foreach($houendtype as $houendtypes){  
							if($houendtypes['id']=='LH'){
								$row6=[];
								$row6['item']='Electricity - Lighting';
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$EN = $TypeChunk[$j].'_LH_'.$AY;
									$row6[$AY]=formatnumber($amData[$EN]);
								}
								array_push($sectors,$row6);
								$row7=[];
								$row7['item']='Fossil Fuels - Lighting';
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$EN = $TypeChunk[$j].'_FF_'.$AY;
									$row7[$AY]=formatnumber($amData[$EN]);
								}
								array_push($sectors,$row7);
							}else{
								$row8=[];
								$row8['item']=$houendtypes['value'];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$EN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AY;
									$row8[$AY]=formatnumber($amData[$EN]);
								}
								array_push($sectors,$row8);
							}
						}
						$row9=[];
						$row9['item']='Total ('.$abData[$TypeChunk[$j]].')'; //$TOTAL;		
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row9[$AY]=formatnumber($amData[$TypeChunk[$j].'TFF_'.$AY]);
						}
						$row9['style']=10;
						array_push($sectors,$row9);
					}	
				}
			}

	
				//4.3. Total Final Energy Demand in Household
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				$row=[];
				$row['Item']="4.3. Total Final Energy Demand in Household [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);
				$amData = $amd->getByField(15,'SID');
				$bjd = new Data($caseStudyId,$bjxml);
	
				foreach($houendtype as $houendtypes){
					$row=[];
					$row['item']=$houendtypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
					$row['style']=10;
					array_push($sectors,$row);
	
					foreach($houtype as $houtypes){  
						if($houtypes[$houendtypes['id']]=='Y' ){
							$row1=[];
							$row1['item']=$houtypes['value'];
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$IN = $houendtypes['id'].'_'.$houtypes['id'].'_'.$AY;
								$row1[$AY]=formatnumber($amData[$IN]);
							}
							array_push($sectors,$row1);
						}
					}
					
					$row2=[];
					$row2['item']='Total - '.$houendtypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$IN = $houendtypes['id'].'_'.$AY;
						$row2[$AY]=formatnumber($amData[$IN]);
					}
					$row2['style']=10;
					array_push($sectors,$row2);
				}
				$row3=[];
				$row3['item']='Total Final Energy Demand';
				for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
				$row3['style']=10;
				array_push($sectors,$row3);
	
				foreach($houtype as $houtypes){  
					$row4=[];
					$row4['item']=$houtypes['value'];
					for($y = 0; $y < count($AllYear); $y++){ 
						$AY=$AllYear[$y];
						$FD = $houtypes['id'].'_'.$AY;
						$row4[$AY]=formatnumber($amData[$FD]);
					}
					array_push($sectors,$row4);
				}
				
				$row5=[];
				$row5['item']='Total - Total Final Energy Demand';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row5[$AY]=formatnumber($amData['T_'.$AY]);
				}
				$row5['style']=10;
				array_push($sectors,$row5);

				//5. SERVICES
				$row=[];
				$row['Item']="";
				array_push($sectors,$row);
				
				$row=[];
				$row['Item']="5. SERVICES";
				$row['style']=9;
				array_push($sectors,$row);
					//5.1. Useful Energy Demand in Service Sector
				$row['Item']="5.1. Useful Energy Demand in Service Sector [".$DefaultEne."]";
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row[$AY]=$AY;
				}
				$row['style']=8;
				array_push($sectors,$row);	
					
							$amData = $amd->getByField(16,'SID');
							$bjd = new Data($caseStudyId,$bjxml);

							$row12['item']='Useful energy demand for Space heating & air conditioning';
							for($y = 0; $y < count($AllYear); $y++){ $row12[$AllYear[$y]]=""; }
							$row12['style']=10;
							array_push($sectors,$row12);
							
							$row=[];
							$row['item']='Total area heated (Million m2)';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=formatnumber($amData['TA_'.$AY]);
							}
							array_push($sectors,$row);
				
							$row1=[];
							$row1['item']='Space heating';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row1[$AY]=formatnumber($amData['SH_'.$AllYear[$y]]);
							}
							array_push($sectors,$row1);
							
							$row2=[];
							$row2['item']='Air conditioning';
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row2[$AY]=formatnumber($amData['AC_'.$AY]);
							}
							array_push($sectors,$row2);
				
							foreach($maintype as $maintypes){ 
								$row3=[];
								if($maintypes['id']=='Ser' ){
									$abxml = $maintypes['id'];
									$abd = new Data($caseStudyId,'sectors_data');
									$abData = $abd->getByField($abxml,'SID');
									$TypeChunk = explode(",",$abData[$abxml.'_A']); 
									foreach($serendtype as $serendtypes){
										if($serendtypes['id']!='SH' and $serendtypes['id']!='AC'){
											$row3['item']='Useful energy demand for '.$serendtypes['value'];
											for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
											$row3['style']=10;
											array_push($sectors,$row3);
				
											for($j = 0; $j < count($TypeChunk); $j++){ 
												$row4['item']=$abData[$TypeChunk[$j]].'('.$serendtypes['value'].')';
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
							for($y = 0; $y < count($AllYear); $y++){ $row5[$AllYear[$y]]=""; }
							$row5['style']=10;
							array_push($sectors,$row5);
				
							foreach($maintype as $maintypes){ 
								$row6=[];
								if($maintypes['id']=='Ser' ){
									$abxml = $maintypes['id'];
									$abd = new Data($caseStudyId,'sectors_data');
									$abData = $abd->getByField($abxml,'SID');
									$TypeChunk = explode(",",$abData[$abxml.'_A']); 
									for($j = 0; $j < count($TypeChunk); $j++){
										$row6['item']=$abData[$TypeChunk[$j]];
										for($y = 0; $y < count($AllYear); $y++){ $row6[$AllYear[$y]]=""; }
										$row6['style']=10;
										array_push($sectors,$row6);
				
										foreach($serendtype as $serendtypes){
											$row7=[];
											if($serendtypes['id']!='SH' and $serendtypes['id']!='AC'){						
												$row7['item']=$serendtypes['value'];
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
									
									$row8=[];
									$row8['item']='Total'; //$TOTAL;
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row8[$AY]=formatnumber($amData['T_'.$AY]);
									}
									$row8['style']=10;
									array_push($sectors,$row8);
				
								}
							}
							$row9=[];
							$row9['item']='Total Useful energy demand';
							for($y = 0; $y < count($AllYear); $y++){ $row9[$AllYear[$y]]=""; }
							$row9['style']=10;
							array_push($sectors,$row9);		
							
							foreach($maintype as $maintypes){ 
								$row10=[];
								if($maintypes['id']=='Ser' ){
									foreach($serendtype as $serendtypes){
										$row10['item']=$serendtypes['value'];
										for($y = 0; $y < count($AllYear); $y++){ 
											$AY=$AllYear[$y];
											$IN = 'T_'.$serendtypes['id'].'_'.$AY;
											$row10[$AY]=formatnumber($amData[$IN]);
										}
										array_push($sectors,$row10);
				
									}
									
									$row11=[];
									$row11['item']='Total'; //$TOTAL;
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row11[$AY]=formatnumber($amData['T_'.$AY]);
									}
									$row11['style']=10;
									array_push($sectors,$row11);
								}
							}
				
							//5.2. Final Energy Demand in Service Sector
							$row=[];
							$row['Item']="";
							array_push($sectors,$row);
							$row=[];
							$row['Item']="5.2. Final Energy Demand in Service Sector [".$DefaultEne."]";
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=$AY;
							}
							$row['style']=8;
							array_push($sectors,$row);
							$amData = $amd->getByField(16,'SID');
							$bjd = new Data($caseStudyId,$bjxml);			
							foreach($serendtype as $serendtypes){
								$row=[];
								if($serendtypes['pen']=='Y' and $serendtypes['id']!='SH' or $serendtypes['id']=='AP'){					
									$row['item']=$serendtypes['value'];
									for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
									$row['style']=10;
									array_push($sectors,$row);
				
									foreach($sertype as $sertypes){  
										$row1=[];
										if($sertypes[$serendtypes['id']]=='Y'){
											$row1['item']=$sertypes['value'];
											for($y = 0; $y < count($AllYear); $y++){ 
												$AY=$AllYear[$y];
												$FN = 'P_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AY;
												$row1[$AY]=formatnumber($amData[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AY]);
											}
											array_push($sectors,$row1);
				
										}
									}
				
									$row2['item']='Total - '.$serendtypes['value'];
									for($y = 0; $y < count($AllYear); $y++){ 
										$AY=$AllYear[$y];
										$row2[$AY]=formatnumber($amData['ToF_'.$serendtypes['id'].'_'.$AY]);
									}
									$row['style']=10;
									array_push($sectors,$row2);
								}
							}
				
							//5.3. Total Final Energy Demand in Service Sector (by energy forms)
							$row=[];
							$row['Item']="";
							array_push($sectors,$row);
							$row=[];
							$row['Item']="5.3. Total Final Energy Demand in Service Sector (by energy forms) [".$DefaultEne."]";
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row[$AY]=$AY;
							}
							$row['style']=8;
							array_push($sectors,$row);
							$amData = $amd->getByField(16,'SID');
							$bjd = new Data($caseStudyId,$bjxml);
							foreach($sertype as $sertypes){  
								$row1=[];
								$row1['item']=$sertypes['value'];
								for($y = 0; $y < count($AllYear); $y++){ 
									$AY=$AllYear[$y];
									$row1[$AY]=formatnumber($amData['F_'.$sertypes['id'].'_'.$AY]);
								}
								array_push($sectors,$row1);
							}
							
							$row2=[];
							$row2['item']='Total'; //$TOTAL;						
							for($y = 0; $y < count($AllYear); $y++){ 
								$AY=$AllYear[$y];
								$row2[$AY]=formatnumber($amData['ToF_'.$AY]);
							}
							$row2['style']=10;
							array_push($sectors,$row2);

			//6. FINAL ENERGY DEMAND
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

			$row=[];
			$row['Item']="";
			array_push($sectors,$row);
			
			$row=[];
			$row['Item']="6. FINAL ENERGY DEMAND";
			$row['style']=9;
			array_push($sectors,$row);
			//6.1. Final Energy Demand
			$row['Item']="6.1. Final Energy Demand [".$unit."]";
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				$row[$AY]=$AY;
			}
			$row['style']=8;
			array_push($sectors,$row);	
				$amData = $amd->getByField(17,'SID');
				$bjd = new Data($caseStudyId,$bjxml);
				
				$row=[];
				$row['item']='Final Energy Demand by Energy Form'; //$Final_Energy_Demand_Energy_Form;
				for($y = 0; $y < count($AllYear); $y++){ $row[$AllYear[$y]]=""; }
				$row['style']=10;
				array_push($sectors,$row);
	
				foreach($pentype as $pentypes){
					$row1=[];
					$pid = 	$pentypes['id'];
					if ($pid!='CG'){
						if($pid=='CK'){ 
							$row1['item']="Coke & Steam Coal";
						}
						else
						{
							$row1['item']=$pentypes['value'];
						}
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row1[$AY]=formatnumber((double)$amData[$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row1);
					}
				}
				
				$row2=[];
				$row2['item']='Total - Final Energy Demand by Energy Form'; //$TOTAL;
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row2[$AY]=formatnumber((double)$amData['T_'.$AY]*$u);
				}
				$row2['style']=10;
				array_push($sectors,$row2);
			
				$row3=[];
				$row3['item']='Final Energy Demand per Capita & per GDP'; //$Final_Energy_Demand_Capita_GDP;
				for($y = 0; $y < count($AllYear); $y++){ $row3[$AllYear[$y]]=""; }
				$row3['style']=10;
				array_push($sectors,$row3);
				
				$row4=[];
				$row4['item']='FE per Capita ('.$unitpercapita[$unit].')';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row4[$AY]=formatnumber((double)$amData['FCAP_'.$AY]*$multiple[$DefaultPop."Population"]*$u);
				}
				array_push($sectors,$row4);
	
				$row5=[];
				$row5['item']='FE per GDP ('.$unitgdp[$unit].')';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row5[$AY]=formatnumber((double)$amData['FGDP_'.$AY]*$u);
				}
				array_push($sectors,$row5);
	
				$row6=[];
				$row6['item']='Final Energy Demand by Sector';//$Final_Energy_Demand_Sector;
				for($y = 0; $y < count($AllYear); $y++){ $row6[$AllYear[$y]]=""; }
				$row6['style']=10;
				array_push($sectors,$row6);
				
				$row7=[];
				$row7['item']='Industry';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row7[$AY]= formatnumber((double)$amData['Ind_'.$AY]*$u);
				}
				array_push($sectors,$row7);
	
				$row8=[];
				$row8['item']='    Manufacturing';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row8[$AY]= formatnumber((double)$amData['Man_'.$AY]*$u);
				}
				array_push($sectors,$row8);
	
				$row9=[];
				$row9['item']='    ACM';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row9[$AY]= formatnumber((double)$amData['ACM_'.$AY]*$u);
				}
				array_push($sectors,$row9);
	
				$row10=[];
				$row10['item']='Transportation';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row10[$AY]= formatnumber((double)$amData['Trp_'.$AY]*$u);
				}
				array_push($sectors,$row10);
	
				$row11=[];
				$row11['item']='    Freig. transp.';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row11[$AY]= formatnumber((double)$amData['Fre_'.$AY]*$u);
				}
				array_push($sectors,$row11);
	
				$row12=[];
				$row12['item']='    Pass. transp';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row12[$AY]= formatnumber((double)$amData['Pass_'.$AY]*$u);
				}
				array_push($sectors,$row12);
	
				$row13=[];
				$row13['item']='Household';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row13[$AY]= formatnumber((double)$amData['Hou_'.$AY]*$u);
				}
				array_push($sectors,$row13);
	
				$row14=[];
				$row14['item']='Service';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row14[$AY]= formatnumber((double)$amData['Ser_'.$AY]*$u);
				}
				array_push($sectors,$row14);
	
				$row15=[];
				$row15['item']='Total - Final Energy Demand by Sector';
				for($y = 0; $y < count($AllYear); $y++){ 
					$AY=$AllYear[$y];
					$row15[$AY]= formatnumber((double)$amData['Tot_'.$AY]*$u);
				}
				$row15['style']=10;
				array_push($sectors,$row15);
	
				$row16=[];
				$row16['item']='By Sector';//$By_Sector; 
				for($y = 0; $y < count($AllYear); $y++){ $row16[$AllYear[$y]]=""; }
				$row16['style']=10;
				array_push($sectors,$row16);
	
				foreach($pentype as $pentypes){ 
					$pid = 	$pentypes['id'];
					$pvalue=$pentypes['value'];
					if ($pid!='CG'){
	
						if($pid=='CK'){
							$pvalue="Coke & Steam Coal";
						}
	
						$row161['item']=$pvalue;
						for($y = 0; $y < count($AllYear); $y++){ $row161[$AllYear[$y]]=""; }
						$row161['style']=10;
						array_push($sectors,$row161);
	
					
						$row17['item']='Industry';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row17[$AY]= formatnumber((double)$amData['Ind_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row17);
			
						$row18['item']='    Manufacturing';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row18[$AY]= formatnumber((double)$amData['Man_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row18);
			
						$row19['item']='    ACM';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row19[$AY]= formatnumber((double)$amData['ACM_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row19);
			
						$row20['item']='Transportation';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row20[$AY]= formatnumber((double)$amData['Trp_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row20);
			
						$row21['item']='    Freig. transp.';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row21[$AY]= formatnumber((double)$amData['Fre_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row21);
			
						$row22['item']='    Pass. transp';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row22[$AY]= formatnumber((double)$amData['Pass_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row22);
			
						$row23['item']='Household';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row23[$AY]= formatnumber((double)$amData['Hou_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row23);
			
						$row24['item']='Service';
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row24[$AY]= formatnumber((double)$amData['Ser_'.$pid.'_'.$AY]*$u);
						}
						array_push($sectors,$row24);
			
						$row25['item']='Total - '.$pvalue; //$TOTAL;
						for($y = 0; $y < count($AllYear); $y++){ 
							$AY=$AllYear[$y];
							$row25[$AY]= formatnumber((double)$amData['Tot_'.$pid.'_'.$AY]*$u);
						}
						$row25['style']=10;
						array_push($sectors,$row25);
					}
				}


			$xlsx = SimpleXLSXGen::fromArray( $sectors);
			$xlsx->saveAs(USER_CASE_PATH.$caseStudyId."/result/Results.xlsx");

function formatnumber($number){
	if(is_nan($number) || $number==0){
		$num=0;
	}else
	{
		$num=number_format($number,15,'.','');
	}
		return $num;
}	
?>