<?php  	
	//calculation.php
    //every step has three actions (seect data, calculate data and write data to xml file)
    //step 01 - cal_calendar.xml, calculate number of days per years and seasons
    //step 02 - cal_main.xml, electricity demand including transmission and distribution losses
    //step 02 - cal_main.xml, average electricity demand per hour for client
    //step 03 - cal_HD.xml, hourly demand for every client and every year
    //step 04 - cal_electricitydemand, electricity demand per hour
    //step 05 - cal_summary, calculation summary
	require_once "../../config.php";
	require_once CLASS_PATH."Data.class.php";
	require_once BASE_PATH."general.php";

	$filedir = USER_CASE_PATH.$caseStudyId."/result/";
	if (!file_exists($filedir)) {
		mkdir($filedir . $dirname, 0777);
	}
	$am = $filedir.$amxml.'.xml'; //geninf_data
	$cm = $filedir.$cmxml.'.xml'; //cal_main
	$cn = $filedir.$cnxml.'.xml'; //cal_hd
	$co = $filedir.$coxml.'.xml'; //cal_electricitydemand
	$cp = $filedir.$cpxml.'.xml'; //cal_energy
	$cq = $filedir.$cqxml.'.xml'; //cal_summary
	$cr = $filedir.$crxml.'.xml'; //cal_calendar
	
    //if calc xml files exists then delete
	if(is_file($am)){unlink($am);}
	if(is_file($cm)){unlink($cm);}
	if(is_file($cn)){unlink($cn);}
	if(is_file($co)){unlink($co);}
	if(is_file($cp)){unlink($cp);}
	if(is_file($cq)){unlink($cq);}
	if(is_file($cr)){unlink($cr);}
		
    //create empty files, and get calendar_data and calendardef_data from xml to arrays
	$amd = new Data($caseStudyId,$amxml); //geninf_data
	$cmd = new Data($caseStudyId,$cmxml); //cal_main
	$cnd = new Data($caseStudyId,$cnxml); //cal_hd
	$cod = new Data($caseStudyId,$coxml); //cal_electricitydemand
	$cpd = new Data($caseStudyId,$cpxml); //cal_energy
	$cqd = new Data($caseStudyId,$cqxml); //cal_summary
	$crd = new Data($caseStudyId,$crxml); //cal_calendar
    
	//select_01 -------------------------------------------------------------
    $ced = new Data($caseStudyId,$cexml); //calendardef_data
	$ceData = $ced->getByField(1,'SID');  //calendardef_data
	$cdd = new Data($caseStudyId,$cdxml); //calendar_data
	$cdData = $cdd->getByField(1,'SID');  //calendar_data
    
    //calculate_01 ----------------------------------------------------------
	for($j = 0; $j < count($AllYear); $j++){
		//Calculando horas y dias del año
		$year = DateTime::createFromFormat('Y', $AllYear[$j]); 
		$leap = $year->format('L');
		if($leap==1){
			$yrs_data['yr_'.$AllYear[$j]]=8784;	
			$yrs_data['dd_'.$AllYear[$j]]=366;	 	 
		}elseif($leap==0){
			$yrs_data['yr_'.$AllYear[$j]]=8760; 
			$yrs_data['dd_'.$AllYear[$j]]=365;
		}
		//Calculando inico y fin de season 		
		for($k = 1; $k <= $cdData['nseason']; $k++){
			if($k==$cdData['nseason']){
				$y = $AllYear[$j] +1;
				$season_end = $y.'-01-01';
			}else{
				$l=$k+1;
				$str2 = substr($ceData['season_'.$l], 4);
				$season_end = $AllYear[$j].$str2;
			}
			
			if($k==1){
				$str3 = substr($ceData['season_'.$k], 4);
				$season_start = $AllYear[$j].$str3;
			}else{
				$str3 = substr($ceData['season_'.$k], 4);
				$season_start1 = $AllYear[$j].$str3;
				$season_start = $season_start1;
			}
			$start = DateTime::createFromFormat('Y-m-d', $season_start);
			$end = DateTime::createFromFormat('Y-m-d', $season_end);
			$diff = date_diff($start, $end);

			$yrs_data['DD_'.$AllYear[$j].'_season_'.$k] = $diff->format('%a');
			
			if($k==1){
				$mday = DateTime::createFromFormat('Y-m-d', $season_start);
				$mday->modify('first monday');
				$firstday = $mday->format('d');
				$firstdays = 1;
				$yrs_data['Day_'.$AllYear[$j]] = $firstday;
				$yrs_data['DD_'.$AllYear[$j]] = $firstdays;
				$yrs_data['S_DD_'.$AllYear[$j].'_season_'.$k] = $firstdays;
				$yrs_data['E_DD_'.$AllYear[$j].'_season_'.$k] = $yrs_data['DD_'.$AllYear[$j].'_season_'.$k];
				$yrs_data['S_HH_'.$AllYear[$j].'_season_'.$k] = 1;
				$yrs_data['E_HH_'.$AllYear[$j].'_season_'.$k] = $yrs_data['E_DD_'.$AllYear[$j].'_season_'.$k] * 24;
			}else{
				$g=$k-1;
				$yrs_data['S_DD_'.$AllYear[$j].'_season_'.$k] = $yrs_data['E_DD_'.$AllYear[$j].'_season_'.$g]+1;//start day number of season
				$yrs_data['E_DD_'.$AllYear[$j].'_season_'.$k] = $yrs_data['E_DD_'.$AllYear[$j].'_season_'.$g]+$yrs_data['DD_'.$AllYear[$j].'_season_'.$k];//end day number of season
				$yrs_data['S_HH_'.$AllYear[$j].'_season_'.$k] = ($yrs_data['E_DD_'.$AllYear[$j].'_season_'.$g]*24)+1;//start hour number of season
				$yrs_data['E_HH_'.$AllYear[$j].'_season_'.$k] = $yrs_data['E_DD_'.$AllYear[$j].'_season_'.$k] * 24;////end hour number of season
			}
		
			$yrs_data['HH_'.$AllYear[$j].'_'.$k] = $yrs_data['E_HH_'.$AllYear[$j].'_season_'.$k] - $yrs_data['S_HH_'.$AllYear[$j].'_season_'.$k] +1;
		}
	}
    
	//write_01 cal_calendar.xml - add number of days and hours per season and year to xml file
    $yrs_data['SID'] = 1;
	$crd->add($yrs_data);

    //select_02 -----------------------------------------------------------------------------
	$ccd = new Data($caseStudyId,$ccxml); //final_demand
	$ccData = $ccd->getByField(1,'SID');  //final_demand
	$ckd = new Data($caseStudyId,$ckxml); //electricitysuppliedfromgrid_data
	$ckData = $ckd->getByField(1,'SID');  //electricitysuppliedfromgrid_data
	$cgd = new Data($caseStudyId,$cgxml); //transdistloss_data
	$cgData = $cgd->getByField(1,'SID');  //transdistloss_data
	$cbd = new Data($caseStudyId,$cbxml); //client_data
	$cbData = $cbd->getByField(1,'SID');  //client_data
    
    //calculate_02 --------------------------------------------------------------------------
    //Dem_Grid_S: Electricity demand including Transmission and Distribution losses 
	foreach($maintype as $maintypes){ //for every sector
		if($maintypes['id'] !=""){
			for($y = 0; $y < count($AllYear); $y++){ //for every year
                //electricitydemand=(final_demand*electricitysuppliedfromgrid/100)/((1-transloss/100)*(1-distloss/100))
				$main_data['Dem_Grid_S_'.$maintypes['id'].'_'.$AllYear[$y]]= 
                ($ccData[$maintypes['id'].'_'.$AllYear[$y]] * $ckData[$maintypes['id'].'_'.$AllYear[$y]]/100)/
                ((1-$cgData['transloss_'.$AllYear[$y]]/100)*(1-$cgData[$maintypes['id'].'_'.$AllYear[$y]]/100));
				//?Elec?_user
                //Elusr=SUM(Dem_Grid_S)
				$main_data['ELusr_'.$AllYear[$y]] = 
                $main_data['ELusr_'.$AllYear[$y]] + 
                $main_data['Dem_Grid_S_'.$AllYear[$y]] + 
                $main_data['Dem_Grid_S_'.$maintypes['id'].'_'.$AllYear[$y]];
				
			}
		}
	}

	//DemGrid?_C^': Average electricity demand per hour for client C
	foreach($maintype as $maintypes){ //for every sector
		if($maintypes['id'] !=""){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //get clients data for sector
			$tmtype = "";	
			if($abData[$abxml.'_H'] > 0){
				for($j = 0; $j < count($TypeChunk); $j++){ //for every client
					$tmtype = $tmtype.','.$TypeChunk[$j];	
					for($y = 0; $y < count($AllYear); $y++){ //for every year
                         //averageelectricitydemand=(electricitydemand/100*Dem_Grid_S)/(number of hours in year)
						 $main_data['Dem_Grid_C_'.$TypeChunk[$j].'_'.$AllYear[$y]] =
                         ($cbData[$TypeChunk[$j].'_'.$AllYear[$y]]/100 * $main_data['Dem_Grid_S_'.$maintypes['id'].'_'.$AllYear[$y]])/
                         ($yrs_data['dd_'.$AllYear[$y]]*24);
					}
				}
			}
		}
	}
	
	ini_set('memory_limit', '-1'); // set memory limit to unlimited
    
	//write_02 cal_main.xml ----------------------------------------------------------------
	$main_data['SID'] = 1;
	$cmd->add($main_data);
	
    //select_03 ----------------------------------------------------------------------------
	$chd = new Data($caseStudyId,$chxml); //week_data
	$cid = new Data($caseStudyId,$cixml); //daily_data
	$cjd = new Data($caseStudyId,$cjxml); //hourly_data
	$ced = new Data($caseStudyId,$cexml); //calendardef_data
	$ceData = $ced->getByField(1,'SID');  //calendardef_data
	$cfd = new Data($caseStudyId,$cfxml); //typedaydef_data
	$cfData = $cfd->getByField(1,'SID');  //typedaydef_data	
	
    ini_set('max_execution_time', 300); //set timeout for execution of php script to 5 minutes			
    
    //calculate_03 ---------------------------------------------------------------------------
	foreach($maintype as $maintypes){ //for every sector
		if($maintypes['id'] !=""){
			$abxml = $maintypes['id'];//Get ID to get the file name
			$sid = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');//get data for this file name
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //get the TYpes List for this Maintype						
			$TypeChunk = explode(",",$abData[$sid.'_A']); 

            for($l = 0; $l < count($TypeChunk); $l++){ //for every client in sector
				$SubType1 = Config2::getData('maintype',$sid,'sub1',$caseStudyId);
 
				for($a = 0; $a < count($AllYear); $a++){ //for every year
					unset($hd_data11);
					$fDay = $yrs_data['DD_'.$AllYear[$a]]-1;
					$hourly = $fDay * 24;
					if($SubType1=='Y'){
						$cYear = $AllYear[0];
					}else{
						$cYear = $AllYear[$a];
					}
					
                    $chData = $chd->getByField($TypeChunk[$l].'_'.$cYear,'SID'); // week_data, coefficients for client and year
					$ciData = $cid->getByField($TypeChunk[$l].'_'.$cYear,'SID'); // daily_data, coefficients for client and year
					$cjData = $cjd->getByField($TypeChunk[$l].'_'.$cYear,'SID'); // hourly_data, coefficients for client and year
	
					for($j = 1; $j <= $cdData['nseason']; $j++){ //for every season
						$mstart = $yrs_data['S_DD_'.$AllYear[$a].'_season_'.$j];
						$mend = $yrs_data['E_DD_'.$AllYear[$a].'_season_'.$j];
							
						for($m = $mstart; $m <= $mend; $m++){ //for every day in season
							$date = new DateTime();
							$date->setDate($AllYear[$a], 1, $m);
							$ddate = $date->format('Y-m-d');
							$week = (int)$date->format('W');
							$weeks = (int)date_format(DateTime::createFromFormat('Y-m-d', $AllYear[$a].'-01-01'),'W');
							$firstweek = date_format(DateTime::createFromFormat('Y-m-d', $AllYear[$a].'-1-'.$yrs_data['Day_'.$AllYear[$a]]),'Y-m-d');
										
							if($ddate < $firstweek and $weeks >=1){ $week = 1;
							}elseif($ddate >= $firstweek and $weeks ==1 and $week !=1){ $week = $week;
							}elseif($ddate >= $firstweek and $m >358 and $week ==1){ $week = 53;
							}else{ $week = $week+1; }

                            //Day from Date
							$timestamp = DateTime::createFromFormat('Y-m-d', $ddate);
							$day = $timestamp->format('D');
										
							for($y = 0; $y < 24; $y++){ //for every hour
							     // This should get you a DateTime object from the date and year.
								for($k = 1; $k <= $cdData['ntday']; $k++){
									$dcol = $ceData['daytype_'.$k];
									if($dcol=="Mo") { $dcol="Mon";}
                                    if($dcol=="Tu") { $dcol="Tue";}
                                    if($dcol=="We") { $dcol="Wed";}
									if($dcol=="Th") { $dcol="Thu";}
									if($dcol=="Fr") { $dcol="Fri";}
									if($dcol=="Sa") { $dcol="Sat";}
									if($dcol=="Su") { $dcol="Sun";}
												
									if($cfData[$dcol.'_'.$day]=='Y'){
									   //W_(C,S)=?_h�(?WeekCoef?_S*?DailyCoef?_S*?HourlyCoef?_(C,S,D,h) ) 
										$in_data1['W_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]] = 
                                        $chData[$TypeChunk[$l].'_'.$cYear.'_'.$week]*
                                        $ciData[$TypeChunk[$l].'_'.$cYear.'_'.$day.'_'.$week]*
                                        $cjData[$TypeChunk[$l].'_'.$cYear.'_'.$j.'_'.$k.'_'.$y];
                                        
										$in_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]] = 
                                        $chData[$TypeChunk[$l].'_'.$cYear.'_'.$week]*
                                        $ciData[$TypeChunk[$l].'_'.$cYear.'_'.$day.'_'.$week]*
                                        $cjData[$TypeChunk[$l].'_'.$cYear.'_'.$j.'_'.$k.'_'.$y]*
                                        $main_data['Dem_Grid_C_'.$TypeChunk[$l].'_'.$AllYear[$a]];
                                        
										$hd_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]] = $in_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]];
										$hd_data11['H'.$hourly] =  number_format($in_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]],10,'.','');
									}
								}
								$hourly++;
									// This should get you the day of the year and the year in a string.
									//date('z Y', strtotime('21 oct 2012'));
							}
						}
					}
					
					//v.k. adjustement
                    $dayinyear = $yrs_data['yr_'.$AllYear[$a]];
					for($b = 0; $b < $dayinyear; $b++){
						$in_data1['W_'.$TypeChunk[$l].'_'.$AllYear[$a]] = 
                        $in_data1['W_'.$TypeChunk[$l].'_'.$AllYear[$a]] + $in_data1['W_'.$TypeChunk[$l].'_'.$b.'_'.$AllYear[$a]];	
					}
					$hd_dat2['Wcs_'.$TypeChunk[$l].'_'.$AllYear[$a]] = $in_data1['W_'.$TypeChunk[$l].'_'.$AllYear[$a]]/($dayinyear);
					for($hourly = 0; $hourly < $dayinyear; $hourly++){ //for every hour
						if($hd_dat2['Wcs_'.$TypeChunk[$l].'_'.$AllYear[$a]]==0){
							$hd_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]]=0;
							$hd_data11['H'.$hourly]=0;
						}else{


					   	$hd_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]] = 
                       	$in_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]]/($hd_dat2['Wcs_'.$TypeChunk[$l].'_'.$AllYear[$a]]);
					   	$hd_data11['H'.$hourly] =  number_format(
					   	($in_data1['HD_'.$TypeChunk[$l].'_'.$hourly.'_'.$AllYear[$a]])/($hd_dat2['Wcs_'.$TypeChunk[$l].'_'.$AllYear[$a]]),10,'.','');
					   }
					   $hd_json[$TypeChunk[$l].'_'.$AllYear[$a].'_'.$hourly]=$hd_data11['H'.$hourly];
					}
                    //write_03, write hourly demand for every client and every year to xml file cal_HD ---------------------------------
					$hd_data11['SID'] = $TypeChunk[$l].'_'.$AllYear[$a];                                 
					$cnd->add($hd_data11);
				}
			}
		}
	}

//sh 20.07.2020.
$fp = fopen($filedir.'cal_HD.json', 'w');
fwrite($fp, json_encode($hd_json));
fclose($fp);



   //calculate_04, electricity demand------------------------------------------------------ 
	foreach($maintype as $maintypes){ //for every sector
		if($maintypes['id'] !=""){
			$abxml = $maintypes['id'];//Get ID to get the file name
			$sid = $maintypes['id'];
			$chData = $chd->getByField($sid,'SID');
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');//get data for this file name
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //get the TYpes List for this Maintype						
			$TypeChunk = explode(",",$abData[$sid.'_A']); 
			for($l = 0; $l < count($TypeChunk); $l++){ //for every client
				$SubType1 = Config2::getData('maintype',$sid,'sub1',$caseStudyId);
				for($a = 0; $a < count($AllYear); $a++){ //for very year
					$dayinyear = $yrs_data['yr_'.$AllYear[$a]];
					for($b = 0; $b < $dayinyear; $b++){
						//Electricity demand
						$hd_data2['HDh_'.$b.'_'.$AllYear[$a]] = $hd_data2['HDh_'.$b.'_'.$AllYear[$a]] +($hd_data1['HD_'.$TypeChunk[$l].'_'.$b.'_'.$AllYear[$a]]);
					}
				}			
			}				
		}
	}
	//
	for($j = 0; $j < count($AllYear); $j++){ //for every year
		for($k = 1; $k <= $cdData['nseason']; $k++){ //for every season
			$start = $yrs_data['S_HH_'.$AllYear[$j].'_season_'.$k]-1; //start of season
			$end =  $yrs_data['E_HH_'.$AllYear[$j].'_season_'.$k]-1;  //end of season
			for($c = $start; $c <= $end; $c++){ //for every day in season
				//$hd_datass[] = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000; for test
				$hd_data20['HDh_'.$c] = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]];

				//sh 20.07.2020.
				$hd_data20_json[$c][$AllYear[$j]] = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]];

				if($c==$start and $k == 1 ){
					$hd_data4['MDse_'.$AllYear[$j].'_'.$k] =  $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000;
					$hd_data4['MD_'.$AllYear[$j]] =  $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000;
				}else{
					$hvalue = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000;
					$mvalue = $hd_data4['MDse_'.$AllYear[$j].'_'.$k];
					$mdvalue = $hd_data4['MD_'.$AllYear[$j]];
					if( $hvalue >= $mvalue){
						$hd_data4['MDse_'.$AllYear[$j].'_'.$k] = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000;
					}else{
						$hd_data4['MDse_'.$AllYear[$j].'_'.$k] = $hd_data4['MDse_'.$AllYear[$j].'_'.$k];
					}
					if( $hvalue >= $mdvalue){
						$hd_data4['MD_'.$AllYear[$j]] = $hd_data2['HDh_'.$c.'_'.$AllYear[$j]]*1000;
					}else{
						$hd_data4['MD_'.$AllYear[$j]] = $hd_data4['MD_'.$AllYear[$j]];
					}
				}
				
				//Energy per season
				$hd_data4['ELse_'.$AllYear[$j].'_'.$k] =  $hd_data4['ELse_'.$AllYear[$j].'_'.$k] + $hd_data2['HDh_'.$c.'_'.$AllYear[$j]];
				//Annual Energy
				$hd_data4['EL_'.$AllYear[$j]] =  $hd_data4['EL_'.$AllYear[$j]] + $hd_data2['HDh_'.$c.'_'.$AllYear[$j]];
			}	
			//sort($hd_datass); for test
		}
        //write_04 cal_electricitydemand.xml - write data to xml file cal_electricitydemand -----------------------------------------------
		$hd_data20['SID'] = $AllYear[$j];
	//	$hdh_json[$AllYear[$j]]=$hd_data20_json;
		$cod->add($hd_data20);
	}

	//sh 20.07.2020.
$fp = fopen($filedir.'cal_electricitydemand.json', 'w');
fwrite($fp, json_encode($hd_data20_json));
fclose($fp);
	
    //calculation_05, calculate data for cal_summary table
	for($j = 0; $j < count($AllYear); $j++){ 
		for($k = 1; $k <= $cdData['nseason']; $k++){
			$start = $yrs_data['S_HH_'.$AllYear[$j].'_season_'.$k];
			$end =  $yrs_data['E_HH_'.$AllYear[$j].'_season_'.$k];
			$Seasonhours = ($end-$start)+1;
			//Relation to annual peak demand per season 
			$hd_data4['RelAP_'.$AllYear[$j].'_'.$k] = $hd_data4['MDse_'.$AllYear[$j].'_'.$k]/$hd_data4['MD_'.$AllYear[$j]];
			//Load factor per season
			$hd_data4['LFse_'.$AllYear[$j].'_'.$k] = ($hd_data4['ELse_'.$AllYear[$j].'_'.$k]/($hd_data4['MDse_'.$AllYear[$j].'_'.$k]*$Seasonhours))*100000;
		}
		//Annual load factor
		$hd_data4['LF_'.$AllYear[$j]] = ($hd_data4['EL_'.$AllYear[$j]]/($hd_data4['MD_'.$AllYear[$j]]*$yrs_data['yr_'.$AllYear[$j]]))*100000;
		//Diff to annual demand (DifAD):DifAD=Elec-?Elec?_user
		$hd_data4['DifAD_'.$AllYear[$j]] = $hd_data4['EL_'.$AllYear[$j]] - $main_data['ELusr_'.$AllYear[$j]];
		
		//% Diff to annual demand (%DifAD):
		//%DifAD=(Elec/?Elec?_user -1)*100
		$hd_data4['DifADp_'.$AllYear[$j]] = (($hd_data4['EL_'.$AllYear[$j]] /$main_data['ELusr_'.$AllYear[$j]])-1);
		
	}
	
    //write_05 cal_summary.xml - write data to xml file cal_summary--------------------------------------------------------------------
	$hd_data4['SID'] = 1;
	$cqd->add($hd_data4);

?>	
	

