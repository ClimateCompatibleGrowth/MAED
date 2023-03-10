<?php  	
	require_once "../../config.php";
	require_once CLASS_PATH."Data.class.php";
	require_once BASE_PATH."general.php";

	$filedir = USER_CASE_PATH.$caseStudyId."/result/";
	if (!file_exists($filedir)) {
		mkdir($filedir . $dirname, 0777);
	}
	//$facmtype = Config1::getData('facmtype',$caseStudyId, true);
	$am = $filedir.$amxml.'.xml';
	
	if(is_file($am)){//if  exists ,then delete
		unlink($am);
	}
	if($DefaultGdp=='Million'){
		$gdpmultiple = 1;
		$gdpcapmultiple = 1;
	}if($DefaultGdp=='Billion'){
		$gdpmultiple = 1;
		$gdpcapmultiple = 1000;
	}if($DefaultGdp=='Trillion'){
		$gdpmultiple = 1;
		$gdpcapmultiple = 1000000;
	}

	//units for population
	$popmultiple=1;
	if($DefaultPop=="Thousand"){
		$popmultiple=0.001;
	}

	$pasmultiple=1;
	if($DefaultPas=='Million'){
		$pasmultiple = 0.001;
	}if($DefaultPas=='Billion'){
		$pasmultiple = 1;
	}if($DefaultPas=='Trillion'){
		$pasmultiple = 1000;
	}

	$acd = new Data($caseStudyId,$acxml);
	$add = new Data($caseStudyId,$adxml);
	//$aed = new Data($caseStudyId,$aexml);
	$afd = new Data($caseStudyId,$afxml);
	//$agd = new Data($caseStudyId,$agxml);
	//$ahd = new Data($caseStudyId,$ahxml);
	//$aid = new Data($caseStudyId,$aixml);
	//$ajd = new Data($caseStudyId,$ajxml);
	//$akd = new Data($caseStudyId,$akxml);
	//$ald = new Data($caseStudyId,$alxml);
	$amd = new Data($caseStudyId,$amxml);
	$and = new Data($caseStudyId,$anxml);
	$aod = new Data($caseStudyId,$aoxml);
	$apd = new Data($caseStudyId,$apxml);
	$aqd = new Data($caseStudyId,$aqxml);
	$ard = new Data($caseStudyId,$arxml);
	$asd = new Data($caseStudyId,$asxml);
	$atd = new Data($caseStudyId,$atxml);
	$aud = new Data($caseStudyId,$auxml);
	$avd = new Data($caseStudyId,$avxml);
	$awd = new Data($caseStudyId,$awxml);
	$axd = new Data($caseStudyId,$axxml);
	$ayd = new Data($caseStudyId,$ayxml);
	$azd = new Data($caseStudyId,$azxml);
	$bad = new Data($caseStudyId,$baxml);
	$bbd = new Data($caseStudyId,$bbxml);
	$bcd = new Data($caseStudyId,$bcxml);
	$bdd = new Data($caseStudyId,$bdxml);
	$bed = new Data($caseStudyId,$bexml);
	//$bfd = new Data($caseStudyId,$bfxml);
	$bgd = new Data($caseStudyId,$bgxml);
	$bhd = new Data($caseStudyId,$bhxml);
	$bid = new Data($caseStudyId,$bixml);
	$bjd = new Data($caseStudyId,$bjxml);
	$bkd = new Data($caseStudyId,$bkxml);
	
	$acData = $acd->getByField(1,'SID');
	$adData = $add->getByField(1,'SID');
	//$aeData = $aed->getByField(1,'SID');
	$afData = $afd->getByField(1,'SID');
	//$agData = $agd->getByField(1,'SID');
	//$ahData = $ahd->getByField(1,'SID');
	//$aiData = $aid->getByField(1,'SID');
	//$ajData = $ajd->getByField(1,'SID');
	//$akData = $akd->getByField(1,'SID');
	//$alData = $ald->getByField(1,'SID');
	//$amData = $amd->getByField(1,'SID');
	$anData = $and->getByField(1,'SID');
	$aoData = $aod->getByField(1,'SID');
	$apData = $apd->getByField(1,'SID');
	$aqData = $aqd->getByField(1,'SID');
	$arData = $ard->getByField(1,'SID');
	$asData = $asd->getByField(1,'SID');
	$atData = $atd->getByField(1,'SID');
	$auData = $aud->getByField(1,'SID');
	$avData = $avd->getByField(1,'SID');
	$awData = $awd->getByField(1,'SID');
	$axData = $axd->getByField(1,'SID');
	$ayData = $ayd->getByField(1,'SID');
	$azData = $azd->getByField(1,'SID');
	$baData = $bad->getByField(1,'SID');
	$bbData = $bbd->getByField(1,'SID');
	$bcData = $bcd->getByField(1,'SID');
	$bdData = $bdd->getByField(1,'SID');
	$beData = $bed->getByField(1,'SID');
	//$bfData = $bfd->getByField(1,'SID');
	$bgData = $bgd->getByField(1,'SID');
	$bhData = $bhd->getByField(1,'SID');
	$biData = $bid->getByField(1,'SID');
	$bjData = $bjd->getByField(1,'SID');
	$bkData = $bkd->getByField(1,'SID');
	
	$gdp_data='';
	/*
	if($DefaultEne='GWyr'){
		$unittype = 1/8.76;
	}elseif($DefaultEne='PJ'){
		$unittype = 0.114155251141553;
	}elseif($DefaultEne='Tcal'){
		$unittype =0.000000114158921933086;
	}elseif($DefaultEne='Mtoe'){
		$unittype = 0.000000150060209424084;
	}elseif($DefaultEne='GBTU'){
		$unittype = 1000;
	}
	*/
	$unittypedefault=0.114155251141553;
	if($DefaultEne=='GWyr'){
        if($DefaultGdp=='Million'){$unittype = 0.000114155251141553;}
        if($DefaultGdp=='Billion'){$unittype = 0.114155251141553;} 
        if($DefaultGdp=='Trillion'){$unittype = 114.155251141553;}

		$ckfsunit = 1;
	}elseif($DefaultEne=='PJ'){
		$unittype = 1;
		$ckfsunit = 3.6;
	}elseif($DefaultEne=='Tcal'){
		$unittype = 1;
		$ckfsunit = 859.84533;
	}elseif($DefaultEne=='Mtoe'){
		$unittype = 1;
		$ckfsunit = 0.0859845;
	}elseif($DefaultEne=='GBTU'){
		$unittype = 1000;
		$ckfsunit = 3412.3223/1000;
	}
	
	$gdp_data = array();
	$ind_data=array();
	$ind_dats=array();
	$pen_data=array();
	$pen_dats=array();
	$acm_data=array();
	$acm_dats=array();
	$ar_data=array();
	$as_data=array();
	$as_dats=array();
	$eff_date=array();
	$eff_dats=array();
	$tmanf_data=array();
	$fkm_dat=array();
	$fkm_data=array();
	$fkm_dats=array();
	$dem_data=array();
	$inta_data=array();
	$inte_data=array();
	$trpt_data=array();
	$hou_data=array();
	$fhou_data=array();
	$serv_data=array();
	$final_data=array();
	
	//sh 15.02.2019
	$unittype_original=$unittype;
	foreach($maintype as $maintypes){ 
		if($maintypes['gdp']=='Y'){//If GDP is set to Y & Main type is ENERGY in maintype xml file
			$abxml = $maintypes['id'];//Get ID to get the file name
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');//get data for this file name
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //get the TYpes List for this Maintype
			for($y = 0; $y < count($AllYear); $y++){ 
				$AY=$AllYear[$y];
				
				$gdp_data[$maintypes['id'].'_S_'.$AY] = $adData['GDP_'.$AY] * ($adData[$maintypes['id'].'_'.$AY]/100); //GDP * MAIN SECTOR %
				for($j = 0; $j < count($TypeChunk); $j++){ //For each Type get the Name/Value
					$gdp_data[$TypeChunk[$j].'_'.$AY] = $gdp_data[$maintypes['id'].'_S_'.$AY] * ($adData[$TypeChunk[$j].'_'.$AY]/100);	// GDP formation by sector/subsector (absolute values)
					
				//gdp growth rate
				if ($y>0){
					$AYPREV=$AllYear[$y-1];
					$gdp_data['GR_'.$TypeChunk[$j].'_'.$AY]=(pow(($gdp_data[$TypeChunk[$j].'_'.$AY]/$gdp_data[$TypeChunk[$j].'_'.$AYPREV]),(1/($AY-$AYPREV)))-1)*100;
				}
				}	
				$gdp_data['G_'.$AY] = $gdp_data['G_'.$AY] + $gdp_data[$maintypes['id'].'_S_'.$AY];//Total GDP
				//sh 07.11.2017 population in thousand
				//$gdp_data[$maintypes['id'].'_'.$AY] = ($gdp_data[$maintypes['id'].'_S_'.$AY]/$acData['Pop_'.$AY])*$gdpcapmultiple;//GDP formation by sector
				$gdp_data[$maintypes['id'].'_'.$AY] = ($gdp_data[$maintypes['id'].'_S_'.$AY]/($acData['Pop_'.$AY]*$popmultiple))*$gdpcapmultiple;//GDP formation by sector

				//gdp growth rate
				//((D6/C6)^(1/(D5-C5))-1)*100
				if ($y>0){
					$AYPREV=$AllYear[$y-1];
					$gdp_data['GR_'.$maintypes['id'].'_'.$AY]=(pow(($gdp_data[$maintypes['id'].'_S_'.$AY]/$gdp_data[$maintypes['id'].'_S_'.$AYPREV]),(1/($AY-$AYPREV)))-1)*100;
				}
				
			} 
		} 
	}
	for($y = 0; $y < count($AllYear); $y++){ 
		$AY=$AllYear[$y];
		
		//sh 07.11.2017 population in thousand
		//$gdp_data['GC_'.$AY] = ($gdp_data['G_'.$AY]/$acData['Pop_'.$AY])*$gdpcapmultiple;//GDP/CAP =Total GDP/Population
		$gdp_data['GC_'.$AY] = ($gdp_data['G_'.$AY]/($acData['Pop_'.$AY]*$popmultiple))*$gdpcapmultiple;//GDP/CAP =Total GDP/Population

		//gdp growth rate
		if ($y>0){
			$AYPREV=$AllYear[$y-1];
			$gdp_data['GRT_'.$AY]=(pow(($gdp_data['G_'.$AY]/$gdp_data['G_'.$AYPREV]),(1/($AY-$AYPREV)))-1)*100;
			$gdp_data['GRC_'.$AY]=(pow(($gdp_data['GC_'.$AY] /$gdp_data['GC_'.$AYPREV] ),(1/($AY-$AYPREV)))-1)*100; //GDP/CAP growth rate
		}
	}
	$gdp_data['SID'] = 1;
	$amd->add(checkIsNaN($gdp_data));
	//Table 4-1 Useful energy demand for Motor fuels
	//Table 4-2 Useful energy demand for Electricity specific uses
	//Table 4-3 Useful energy demand for Thermal uses
	//Table 4-4 Total useful energy demand in Industry
	foreach($maintype as $maintypes){ 
		
		if($maintypes['ind']=='Y' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			
		    for($y = 0; $y < count($AllYear); $y++){ 
				for($j = 0; $j < count($TypeChunk); $j++){ 
					$mainid = $maintypes['id'].'_'.$AllYear[$y];
					if($bjData['OT_'.$TypeChunk[$j]]=='Y'){ // Other Types
						$bname= 'OT_'.$TypeChunk[$j].'_OT';
						if($bjData[$bname]=='AP'){
							$ind_data['EO_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
							
							$ind_data['EOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]= $bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]];
						}elseif($bjData[$bname]=='MP'){
							$ind_data['MO_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
							
							$ind_data['MOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]= $bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]];
						}elseif($bjData[$bname]=='TU'){
							$ind_data['TO_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
							
							$ind_data['TOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]= $bkData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]];
						}
					}
					if($bjData['MP_'.$TypeChunk[$j]]=='Y'){
						$ind_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($afData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
						
						$ind_dats['Tm_'.$mainid] = ($afData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$ind_dats['MT_'.$mainid] = NAN($ind_dats['MT_'.$mainid]) + NAN($ind_dats['Tm_'.$mainid]);
						$ind_dats['AMT_'.$mainid] = NAN($ind_dats['AMT_'.$mainid]) + NAN($ind_dats['Tm_'.$mainid]) + NAN($ind_data['MOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					
					}if($bjData['AP_'.$TypeChunk[$j]]=='Y'){
					
						$ind_data['E_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($anData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
						
						
						$ind_dats['Te_'.$mainid] = ($anData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$ind_dats['ET_'.$mainid] = NAN($ind_dats['ET_'.$mainid]) + NAN($ind_dats['Te_'.$mainid]);
						$ind_dats['AET_'.$mainid] = NAN($ind_dats['AET_'.$mainid]) + NAN($ind_dats['Te_'.$mainid]) + NAN($ind_data['EOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					}if($bjData['TU_'.$TypeChunk[$j]]=='Y'){
						
						$ind_data['T_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($aoData[$TypeChunk[$j].'_'.$AllYear[$y]] * $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]]/$gdpmultiple)* $unittype;
						
						$ind_dats['Tt_'.$mainid] = ($aoData[$TypeChunk[$j].'_'.$AllYear[$y]] * $adData[$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$ind_dats['TT_'.$mainid] = NAN($ind_dats['TT_'.$mainid]) + NAN($ind_dats['Tt_'.$mainid]);
						$ind_dats['ATT_'.$mainid] = NAN($ind_dats['ATT_'.$mainid]) + NAN($ind_dats['Tt_'.$mainid]) + NAN($ind_data['TOT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					}
									
										
					
					$ind_data['A_'.$TypeChunk[$j].'_'.$AllYear[$y]]= NAN($ind_data['A_'.$TypeChunk[$j].'_'.$AllYear[$y]]) + NAN($ind_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]]) + NAN($ind_data['E_'.$TypeChunk[$j].'_'.$AllYear[$y]]) + NAN($ind_data['T_'.$TypeChunk[$j].'_'.$AllYear[$y]]);				
									
				}
					$ind_data['MT_'.$mainid] = ($ind_dats['MT_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					$ind_data['ET_'.$mainid] = ($ind_dats['ET_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					$ind_data['TT_'.$mainid] = ($ind_dats['TT_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					
					//Total including Others
					$ind_data['AMT_'.$mainid] = ($ind_dats['AMT_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					$ind_data['AET_'.$mainid] = ($ind_dats['AET_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					$ind_data['ATT_'.$mainid] = ($ind_dats['ATT_'.$mainid]/100)*$gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]] * $unittype;
					
					
					$ind_data['EIM_'.$mainid] = ($ind_dats['MT_'.$mainid]/100);
					
					$ind_data['EIE_'.$mainid] = ($ind_dats['ET_'.$mainid]/100);
					
					$ind_data['EIT_'.$mainid] = ($ind_dats['TT_'.$mainid]/100);
					
					$ind_data['ToT_'.$mainid] = NAN($ind_data['MT_'.$mainid]) + NAN($ind_data['ET_'.$mainid]) + NAN($ind_data['TT_'.$mainid]);
					
			} 
		} 
	}
	 
	$ind_data['SID'] = 2;
	$amd->add(checkIsNaN($ind_data));

	foreach($maintype as $maintypes){ 
		if($maintypes['fac']=='Y' ){
		    
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			foreach($pentype as $pentypes){ 
				if($pentypes[$maintypes['id'].'_TU']=='Y' ){	
					for($y = 0; $y < count($AllYear); $y++){ 
					$mainid = $maintypes['id'].'_'.$AllYear[$y];
					$penname = $maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y];
					$pname = $pentypes['id'].'_'.$AllYear[$y];
					
					$pen_dats[$pname]= NAN($pen_dats[$pname]) + (($apData[$penname]/100)*(($adData[$mainid])/100) * $ind_data['EIT_'.$mainid]);
					$pen_dats['l_'.$pname]= NAN($pen_dats['l_'.$pname]) + (($adData[$mainid]/100) * $ind_data['EIT_'.$mainid]);
					}		
		     	}	
			} 
		}
	}
    
    

	foreach($pentype as $pentypes){ 
		for($y = 0; $y < count($AllYear); $y++){ 
			$pname = $pentypes['id'].'_'.$AllYear[$y];
            //vk 06092016 division by zero error
            if($pen_dats['l_'.$pname]!=0){
			 $pen_data[$pname]= ($pen_dats[$pname]/$pen_dats['l_'.$pname])*100;
             }
             else{
                $pen_data[$pname] = 0;
             }
		}		
	} 
	$pen_data['SID'] = 3;
	$amd->add(checkIsNaN($pen_data));

	foreach($maintype as $maintypes){ 
		if($maintypes['fac']=='Y' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){
				foreach($pentype as $pentypes){ 
					if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y' ){	
						for($y = 0; $y < count($AllYear); $y++){ 
							$fieldid = $TypeChunk[$j].'_'.$pentypes['id'].'_'.$AllYear[$y];
							$mainid = $maintypes['id'].'_'.$AllYear[$y];
							$pent = $apData[$fieldid];
							$acm = $aqData[$fieldid];
							$useeng = $ind_data['T_'.$TypeChunk[$j].'_'.$AllYear[$y]];
							
							if($pentypes['id']=='TF' or $pentypes['id']=='MB'){
								if ($acm>0){ //sh 07.11.2017 divide by zero
								$acm_data[$fieldid] = ($useeng/($acm/100))*($pent/100);
								}
								$acm_dats[$fieldid] = $acm_data[$fieldid];
							}elseif($pentypes['id']=='FF') {
								if ($acm>0){ //sh 07.11.2017 divide by zero
								$acm_data[$fieldid]= ($useeng/($acm/100))*$pent/100;
								}
								$acm_dats[$fieldid] = $acm_data[$fieldid];
							}elseif($pentypes['id']=='EL') {
								$acm_data[$fieldid]= $useeng*$pent/100;
								$acm_dats[$fieldid] = $acm_data[$fieldid];
							}elseif($pentypes['id']=='SO') {
								$acm_data[$fieldid]= $pent*$useeng/100;
								$acm_dats[$fieldid] = $acm_data[$fieldid];
							}elseif($pentypes['id']=='MF') {
								$acm_data[$fieldid]= $usemot;
								$acm_dats[$fieldid] = $acm_data[$fieldid];
							}
								 
							if($bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
								$ofieldid = 'OT_'.$TypeChunk[$j].'_'.$pentypes['id'].'_'.$AllYear[$y];
								$pent = $apData[$ofieldid];
								$acm = $aqData[$ofieldid];
								$useeng = $ind_data['TO_'.$TypeChunk[$j].'_'.$AllYear[$y]];
								if($pentypes['id']=='TF' or $pentypes['id']=='MB'){
									if ($acm>0){ //sh 07.11.2017 divide by zero
										$acm_data[$ofieldid] = ($useeng/($acm/100))*($pent/100);
									}
									$acm_dats[$fieldid] = NAN($acm_data[$ofieldid]) + NAN($acm_data[$fieldid]);
								}elseif($pentypes['id']=='FF') {
									if ($acm>0){ //sh 07.11.2017 divide by zero
									$acm_data[$ofieldid]= ($useeng/($acm/100))*$pent/100;
									}
									$acm_dats[$fieldid] = NAN($acm_data[$ofieldid]) + NAN($acm_data[$fieldid]);
								}elseif($pentypes['id']=='EL') {
									$useele = $ind_data['ET_'.$mainid];
									//$acm_data[$ofieldid]= $useele+($useeng*$pent/100);
									$acm_data[$ofieldid]= $useeng*$pent/100;
									$acm_dats[$fieldid] = NAN($acm_data[$ofieldid]) + NAN($acm_data[$fieldid]);
								}elseif($pentypes['id']=='SO') {
									$acm_data[$ofieldid]= $pent*$useeng/100;
								}elseif($pentypes['id']=='MF') {
									$usemot = $ind_data['MT_'.$mainid];
									$acm_data[$ofieldid]= $usemot;
									$acm_dats[$fieldid] = NAN($acm_data[$ofieldid]) + NAN($acm_data[$fieldid]);
								}
								//$acm_data[$fieldid] = $acm_data[$ofieldid] + $acm_data[$fieldid];
							}
						//	$acm_dats[$mainid] = $acm_dats[$mainid] + $acm_data[$fieldid];
							//Total final energy demand in ACM (absolute)
						//	$acm_data[$pentypes['id'].'_'.$AllYear[$y]] = $acm_data[$pentypes['id'].'_'.$AllYear[$y]]+$acm_data[$fieldid];
						$acm_data[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]] = NAN($acm_data[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]]) + NAN($acm_dats[$fieldid]);	
						}     
					}	
				} 	
			}
		}
	}
	

		foreach($maintype as $maintypes){ 
		if($maintypes['fac']=='Y' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			
			foreach($pentype as $pentypes){ 
				if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y' ){	
					for($y = 0; $y < count($AllYear); $y++){ 
						$fieldid = $maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y];
						$mainid = $maintypes['id'].'_'.$AllYear[$y];
						$useeng = $ind_data['T_'.$TypeChunk[$j].'_'.$AllYear[$y]];
						
						if($pentypes['id']=='EL') {
							$useele = $ind_data['AET_'.$mainid];
							$acm_data[$fieldid]= $useele + $acm_data[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]];
						}elseif($pentypes['id']=='MF') {
							$usemot = $ind_data['AMT_'.$mainid] ;
							$acm_data[$fieldid]= $usemot;
						}else{
							$acm_data[$fieldid]= $acm_data[$maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y]];
						}
						
						// Total final energy demand in each sector e.g.Agriculture
						$acm_data[$mainid] = $acm_data[$mainid] + $acm_data[$fieldid];
						//Total final energy demand in ACM (absolute)
						$acm_data[$pentypes['id'].'_'.$AllYear[$y]] = NAN($acm_data[$pentypes['id'].'_'.$AllYear[$y]]) + NAN($acm_data[$fieldid]);
												
						$acm_dats['T_'.$AllYear[$y]] = NAN($acm_dats['T_'.$AllYear[$y]]) + NAN($acm_data[$fieldid]);
						$acm_data['TACM_'.$AllYear[$y]] = NAN($acm_data['TACM_'.$AllYear[$y]]) + NAN($acm_data[$fieldid]);
					}     
				}	
			} 	
			
		}
	}
	
	
	foreach($maintype as $maintypes){ 
		if($maintypes['fac']=='Y' ){
			for($y = 0; $y < count($AllYear); $y++){ 
				$gdp[$AllYear[$y]] = NAN($gdp[$AllYear[$y]]) + NAN($gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]]);
				
			}     
		}
	}
	
	
	
	foreach($maintype as $maintypes){ 
		if($maintypes['fac']=='Y' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			foreach($pentype as $pentypes){ 
				if($pentypes[$maintypes['id'].'_TU']=='Y' or $pentypes[$maintypes['id'].'_MP']=='Y' or $pentypes[$maintypes['id'].'_SEL']=='Y' ){	
					for($y = 0; $y < count($AllYear); $y++){ 
					$fieldid = $maintypes['id'].'_'.$pentypes['id'].'_'.$AllYear[$y];
					$mainid = $maintypes['id'].'_'.$AllYear[$y];
					$gdpval = $gdp_data[$maintypes['id'].'_S_'.$AllYear[$y]];
					
					//Total final energy demand in ACM (shares)
					
						$acm_data['S_'.$fieldid] = ($acm_data[$fieldid]/$acm_data[$mainid])*100;
						
					//Total final energy demand per value added in Agriculture Construction Mining 	
						
						$acm_data['T_'.$fieldid] = (($acm_data[$fieldid])/$gdpval)/$unittype;
						$acm_data['T_'.$mainid] = NAN($acm_data['T_'.$mainid]) + NAN($acm_data['T_'.$fieldid]);
						
					//Total final energy demand in ACM (shares)
						$acm_data['TSh_'.$pentypes['id'].'_'.$AllYear[$y]] = $acm_data[$pentypes['id'].'_'.$AllYear[$y]]/$acm_dats['T_'.$AllYear[$y]] *100;
					//Total final energy demand per value added in ACM	
						$acm_data['Tot_'.$pentypes['id'].'_'.$AllYear[$y]]=($acm_data[$pentypes['id'].'_'.$AllYear[$y]]/$gdp[$AllYear[$y]])/$unittype;
						//echo $AllYear[$y].'_'.$gdp[$AllYear[$y]].'<br>';
					//Total of -Total final energy demand per value added in ACM		
						$acm_data['T_'.$AllYear[$y]] = ($acm_dats['T_'.$AllYear[$y]]/$gdp[$AllYear[$y]])/$unittype;
					}    
				}	
			} 	
		}
	}
	$acm_data['SID'] = 4;
	$amd->add(checkIsNaN($acm_data));
	
	//Adding Other to Type Chunk
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			$TypeChunkAdd ="";
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
				$TypeChunkAdd = $TypeChunkAdd.',OT_'.$TypeChunk[$j];
				}
			}
			$TypeChunkAdd = $abData[$abxml.'_A'].$TypeChunkAdd;
			
		}
	}
	
	// Useful thermal energy demand in Manufacturing
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){
				if(($bjData['TU_'.$TypeChunk[$j]]=='Y')){
					foreach($facmtype as $facmtypes){ 
						if(($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N')) {
						 
							for($y = 0; $y < count($AllYear); $y++){ 
								 //Table 7-5 .e.g.Basic Material_Steamgeneration
								$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] = $aoData[$TypeChunk[$j].'_'.$AllYear[$y]]*$gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $arData[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100 * $unittype  ;
								
								//Total for Each Sub category under Manuf
								$ar_data[$TypeChunk[$j].'_'.$AllYear[$y]] = NAN($ar_data[$TypeChunk[$j].'_'.$AllYear[$y]]) + NAN($ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
								
								//Steam generation Furnace/direct heat Space&water heating
								$ar_data[$facmtypes['id'].'_'.$AllYear[$y]] = NAN($ar_data[$facmtypes['id'].'_'.$AllYear[$y]]) + NAN($ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
							
								//Total MAN Table 7-9
								$ar_data['Y_'.$AllYear[$y]] = NAN($ar_data['Y_'.$AllYear[$y]]) + NAN($ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
							}
							
						}
						
					}
				
					for($y = 0; $y < count($AllYear); $y++){ 
						$ar_data[$TypeChunk[$j].'_'.$AllYear[$y]] = $aoData[$TypeChunk[$j].'_'.$AllYear[$y]]*$gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $arData[$TypeChunk[$j].'_'.$AllYear[$y]]/100 * $unittype  ;
						
						$ar_data['Y_'.$AllYear[$y]] = NAN($ar_data['Y_'.$AllYear[$y]]) + NAN($ar_data[$TypeChunk[$j].'_'.$AllYear[$y]]);
					}	
				}
				
				if($bjData['OT_'.$TypeChunk[$j]]=='Y' and   $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU'){
					
					foreach($facmtype as $facmtypes){ 
						if(($bjData['OT_'.$TypeChunk[$j]]=='Y' and $bjData['OT_'.$TypeChunk[$j].'_OT']=='TU' and $facmtypes['id']='SG')) {
						 
							for($y = 0; $y < count($AllYear); $y++){ 
								 //Table 7-5 .e.g.Basic Material_Steamgeneration
								$ar_data['OT_'.$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] = $bkData[$TypeChunk[$j].'_'.$AllYear[$y]]*$gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $arData['OT_'.$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100 * $unittype  ;
								
								//Total for Each Sub category under Manuf
								$ar_data['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]] = NAN($ar_data['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]]) + NAN($ar_data['OT_'.$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
								
								//Steam generation Furnace/direct heat Space&water heating
								$ar_data[$facmtypes['id'].'_'.$AllYear[$y]] = NAN($ar_data[$facmtypes['id'].'_'.$AllYear[$y]]) + NAN($ar_data['OT_'.$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
							
								//Total MAN Table 7-9
								$ar_data['Y_'.$AllYear[$y]] = NAN($ar_data['Y_'.$AllYear[$y]]) + NAN($ar_data['OT_'.$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
							}
							
						}
						
					}
				
					for($y = 0; $y < count($AllYear); $y++){ 
						$ar_data['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $bkData[$TypeChunk[$j].'_'.$AllYear[$y]]*$gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $arData['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]]/100 * $unittype  ;
						
						
						$ar_data['Y_'.$AllYear[$y]] = NAN($ar_data['Y_'.$AllYear[$y]]) + NAN($ar_data['OT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					}	
				}
			} 
		}
	}
	$ar_data['SID'] = 5;
	$amd->add(checkIsNaN($ar_data));
	
		
	
	
	//Penetrations of energy forms into useful thermal energy demand in Manufacturing
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$TypeChunkAdd); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if(($bjData['TU_'.$TypeChunk[$j]]=='Y')  or ($bjData[$TypeChunk[$j]]=='Y' and   $bjData[$TypeChunk[$j].'_OT']=='TU')){
					foreach($pentype as $pentypes){ 
						if($pentypes['Man_SWH']=='Y' or  $pentypes['Man_SG']=='Y' or $pentypes['Man_FDH']=='Y'){	
							$eid = $TypeChunk[$j].'_'.$pentypes['id'];
							foreach($facmtype as $facmtypes){ 
								if(($facmtypes['TY']=='TUM' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y') or (($bjData['TU_'.$TypeChunk[$j]]=='Y') 	and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData[		'SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG')) or ($bjData[$TypeChunk[$j]]=='Y' and   $bjData[$TypeChunk[$j].'_OT']=='TU') ){
									$cid = 	$facmtypes['id'];
									if($pentypes['Man_'.$cid]=='Y'){	
										for($y = 0; $y < count($AllYear); $y++){ 
										//Electricity      steam gen.
											if($pentypes['id']=='EL'){
												$as_data[$eid.'_'.$cid.'_'.$AllYear[$y]] = ($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]*(1-$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100))* $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												//echo $eid.'_'.$cid.'_'.$AllYear[$y].'-'.$as_data[$eid.'_'.$cid.'_'.$AllYear[$y]];
												
												if($facmtypes['id']=='SG' or $facmtypes['id']=='SWH'){
												
													$as_data['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]] = ($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]*$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100)* $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												
												}
												
												$as_dats[$eid.'_'.$AllYear[$y]] = $as_dats[$eid.'_'.$AllYear[$y]] + $as_data[$eid.'_'.$cid.'_'.$AllYear[$y]];
												
												$as_dats['H_'.$eid.'_'.$AllYear[$y]] = NAN($as_dats['H_'.$eid.'_'.$AllYear[$y]]) + NAN($as_data['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]);
												
											}elseif($pentypes['id']=='DH' or $pentypes['id']=='CG' or $pentypes['id']=='TF' or $pentypes['id']=='MB' or $pentypes['id']=='FF' or $pentypes['id']=='SO'){
											
												$as_data[$eid.'_'.$cid.'_'.$AllYear[$y]] = $asData[$eid.'_'.$cid.'_'.$AllYear[$y]] * $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												
												$as_dats[$eid.'_'.$AllYear[$y]] = NAN($as_dats[$eid.'_'.$AllYear[$y]]) + NAN($as_data[$eid.'_'.$cid.'_'.$AllYear[$y]]);
											}
										}	
									}
									
								}
							}
						}
					}
				}
			}
		}
	}
	//Penetrations of energy forms into useful thermal energy demand in Manufacturing
	//dividing by total
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$TypeChunkAdd); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				foreach($pentype as $pentypes){ 
					if($pentypes['Man_SWH']=='Y' or  $pentypes['Man_SG']=='Y' or $pentypes['Man_FDH']=='Y'){	
						$eid = $TypeChunk[$j].'_'.$pentypes['id'];
						for($y = 0; $y < count($AllYear); $y++){ 
							//sh 11.07.2017 division by zero
							if ($ar_data[$TypeChunk[$j].'_'.$AllYear[$y]]>0){
							$as_data[$eid.'_'.$AllYear[$y]] = $as_dats[$eid.'_'.$AllYear[$y]]/$ar_data[$TypeChunk[$j].'_'.$AllYear[$y]];
							$as_data['H_'.$eid.'_'.$AllYear[$y]] = $as_dats['H_'.$eid.'_'.$AllYear[$y]]/$ar_data[$TypeChunk[$j].'_'.$AllYear[$y]];
						}
						}
												
					}
				} 
				
			}
		}
	}
	
	
	$as_data['SID'] = 6;
	$amd->add(checkIsNaN($as_data));
	
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$TypeChunkAdd); 
			for($j = 0; $j < count($TypeChunk); $j++){
				if(($bjData['TU_'.$TypeChunk[$j]]=='Y')){
					foreach($pentype as $pentypes){ 
						if($pentypes['id']=='TF'){	
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							$carrier = $pentypes['value'];	
							foreach($facmtype as $facmtypes){ 
								if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
									$cid = 	$facmtypes['id'];
									if($pentypes['Man_'.$cid]=='Y'){
										for($y = 0; $y < count($AllYear); $y++){ 

											$eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]] = 
											(
												$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] 
												* $asData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100
											)
											/($atData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100);
											 
											$eff_dats[$eid.'_'.$AllYear[$y]] = NAN($eff_dats[$eid.'_'.$AllYear[$y]]) + NAN($eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]);										
										}
									}
								}
									
							}
						}if($pentypes['id']=='FF'){	
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							$carrier = $pentypes['value'];	
							foreach($facmtype as $facmtypes){
								if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
									if($facmtypes['TY']=='TUM' and $facmtypes['id']=='FDH' ){
									
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											for($y = 0; $y < count($AllYear); $y++){ 

												$eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]] = 
												(
													$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]
													*$asData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100
												)
												/($atData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100);
												
																					
												$eff_dats[$eid.'_'.$AllYear[$y]] = NAN($eff_dats[$eid.'_'.$AllYear[$y]]) + NAN($eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
																					
											}
										}
									}if($facmtypes['TY']=='TUM' and $facmtypes['id']!='FDH'){
											$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											for($y = 0; $y < count($AllYear); $y++){ 
																						
												//=D6*(('Fac. Pe. Eff. Manuf.'!D40+'Fac. Pe. Eff. Manuf.'!D37-('Fac. Pe. Eff. Manuf.'!D37*'Fac. Pe. Eff. Manuf.'!D193/100))/100)/('Fac. Pe. Eff. Manuf.'!D138/100)+'Fac. Pe. Eff. Manuf.'!D36*D6/'Fac. Pe. Eff. Manuf.'!D195*(1+1/'Fac. Pe. Eff. Manuf.'!D196)*(1-'Fac. Pe. Eff. Manuf.'!D197/100)
												
												//=+D7*(('Fac. Pe. Eff. Manuf.'!D49+'Fac. Pe. Eff. Manuf.'!D46-('Fac. Pe. Eff. Manuf.'!D46*'Fac. Pe. Eff. Manuf.'!D193/100))/100)/('Fac. Pe. Eff. Manuf.'!D142/100)+'Fac. Pe. Eff. Manuf.'!D45*D7/'Fac. Pe. Eff. Manuf.'!D195*(1+1/'Fac. Pe. Eff. Manuf.'!D196)*(1-'Fac. Pe. Eff. Manuf.'!D197/100)
												$ffa1=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												$ffa2=$asData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												$ffa3=$asData[$TypeChunk[$j].'_SO_'.$facmtypes['id'].'_'.$AllYear[$y]];
												$ffaa3=(is_nan($ffa3) ? 0 : $ffa3);
												$ffa4=$asData[$TypeChunk[$j].'_SO_'.$facmtypes['id'].'_'.$AllYear[$y]];
												$ffaa4=(is_nan($ffa4) ? 0 : $ffa4);
												$ffa5=$atData[$TypeChunk[$j].'_SOS_'.$AllYear[$y]]/100;
												$ffa6=$atData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100;
												$ffa=$ffa1*(($ffa2+$ffaa3-$ffaa4*$ffa5)/100)/$ffa6;
												$ffaa=(is_nan($ffa) ? 0 : $ffa);

												$ffb1=$asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]];
												$ffb2=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												$ffb3=$atData[$TypeChunk[$j].'_CGE_'.$AllYear[$y]];
												$ffb4=(1+1/$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]]);
												$ffb5=(1-$atData[$TypeChunk[$j].'_MBS_'.$AllYear[$y]]/100);
												$ffb=$ffb1*$ffb2/$ffb3*$ffb4*$ffb5;
												$ffbb=(is_nan($ffb) ? 0 : $ffb);

												$eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]] = $ffaa+$ffbb;
												$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]
												*
												(
													($asData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]
														+$asData[$TypeChunk[$j].'_SO_'.$facmtypes['id'].'_'.$AllYear[$y]]
														-($asData[$TypeChunk[$j].'_SO_'.$facmtypes['id'].'_'.$AllYear[$y]]
														*$atData[$TypeChunk[$j].'_SOS_'.$AllYear[$y]]/100)
													)/100
												)
												/ ($atData[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]/100)
												+
												$asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]]
												*$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]
												/$atData[$TypeChunk[$j].'_CGE_'.$AllYear[$y]]
												* (1+1/$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]])
												* (1-$atData[$TypeChunk[$j].'_MBS_'.$AllYear[$y]]/100);
												
												$eff_dats[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]] = $asData[$TypeChunk[$j].'_CG_'.$facmtypes['id'].'_'.$AllYear[$y]];
																					
												$eff_dats[$eid.'_'.$AllYear[$y]] = $eff_dats[$eid.'_'.$AllYear[$y]] + $eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]];
												
												
											}
										}
									}
								}
							}
						}elseif($pentypes['id']=='MB'){	
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							//=+D5*'Fac. Pe. Eff. Manuf.'!J12/100/('Fac. Pe. Eff. Manuf.'!P11/100)
								foreach($facmtype as $facmtypes){ 
									if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
										if($facmtypes['TY']=='TUM' and $facmtypes['id']=='FDH'){
												$cid = 	$facmtypes['id'];
											if($pentypes['Man_'.$cid]=='Y'){
												for($y = 0; $y < count($AllYear); $y++){ 
													$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]*$asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100/($atData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100);
																							
												}
											}
										}if($facmtypes['TY']=='TUM' and $facmtypes['id']!='FDH'){
												$cid = 	$facmtypes['id'];
											if($pentypes['Man_'.$cid]=='Y'){
												for($y = 0; $y < count($AllYear); $y++){ 
													//=+D7*'Fac. Pe. Eff. Manuf.'!J30/100/('Fac. Pe. Eff. Manuf.'!P19/100)+D7*'Fac. Pe. Eff. Manuf.'!J27*('Fac. Pe. Eff. Manuf.'!P40/100)/100/('Fac. Pe. Eff. Manuf.'!P38/100)/(1+1/'Fac. Pe. Eff. Manuf.'!P39)/100
													$mba1=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
													$mba2=($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100);
													$mba3=($atData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100);
													$mbb1=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] ;
													$mbb2=$asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]];
													$mbb3=$atData[$TypeChunk[$j].'_MBS_'.$AllYear[$y]]/$atData[$TypeChunk[$j].'_CGE_'.$AllYear[$y]];
													$mbb4=(1+1/$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]])/100;
													$mba=$mba1*$mba2/$mba3;
													$mbb=$mbb1*$mbb2*$mbb3*$mbb4;
													$mbaa=(is_nan($mba) ? 0 : $mba);
													$mbbb=(is_nan($mbb) ? 0 : $mbb);
													
													//=+D6*('Fac. Pe. Eff. Manuf.'!D39/100)/('Fac. Pe. Eff. Manuf.'!D137/100)
													//+D6*'Fac. Pe. Eff. Manuf.'!D36*'Fac. Pe. Eff. Manuf.'!D197/'Fac. Pe. Eff. Manuf.'!D195*(1+1/'Fac. Pe. Eff. Manuf.'!D196)/100
															$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $mbaa+$mbbb;
														//	($ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]
														//	*($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100)/($atData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100))
														//	+ ($ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] 
														//	* $asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]]
														//	* ($atData[$TypeChunk[$j].'_MBS_'.$AllYear[$y]]/$atData[$TypeChunk[$j].'_CGE_'.$AllYear[$y]])
														//	*(1+1/$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]])/100);
																					
												}
											}
										}
									}
								}
								
							
						}elseif($pentypes['id']=='EL'){
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							foreach($facmtype as $facmtypes){
								if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
									if($facmtypes['TY']=='TUM'){
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y' and $facmtypes['id'] =='FDH'){
											for($y = 0; $y < count($AllYear); $y++){ 
										//=+D5*('Fac. Pe. Eff. Manuf.'!J10/100)
												$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]*($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100);
												
												
											}
										}elseif($pentypes['Man_'.$cid]=='Y' and $facmtypes['id'] !='FDH'){
											for($y = 0; $y < count($AllYear); $y++){ 
		//=+D7*(('Fac. Pe. Eff. Manuf.'!J24+('Fac. Pe. Eff. Manuf.'!J25/'Fac. Pe. Eff. Manuf.'!P35))/100)-'Fac. Pe. Eff. Manuf.'!J27/100*D7/'Fac. Pe. Eff. Manuf.'!P39
		//=+('Fac. Pe. Eff. Manuf.'!D33+'Fac. Pe. Eff. Manuf.'!D34/'Fac. Pe. Eff. Manuf.'!D192)/100*D6-'Fac. Pe. Eff. Manuf.'!D36/100*D6/'Fac. Pe. Eff. Manuf.'!D196										
		//=(('Fac. Pe. Eff. Manuf.'!D42*(1-'Fac. Pe. Eff. Manuf.'!D43/100))+('Fac. Pe. Eff. Manuf.'!D42*'Fac. Pe. Eff. Manuf.'!D43/100)/'Fac. Pe. Eff. Manuf.'!D192)/100*D7-'Fac. Pe. Eff. Manuf.'!D45/100*D7/'Fac. Pe. Eff. Manuf.'!D196
		$ela1=$asData[$eid.'_'.$cid.'_'.$AllYear[$y]];
		$ela2=(1-$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100);
		$ela=$ela1*$ela2;
		$elaa=(is_nan($ela) ? 0 : $ela);

		$elb1=$asData[$eid.'_'.$cid.'_'.$AllYear[$y]];
		$elb2=$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100;
		$elb3=$atData[$TypeChunk[$j].'_HPC_'.$AllYear[$y]];
		$elb=($elb1*$elb2/$elb3);
		$elbb=(is_nan($elb) ? 0 : $elb);

		$elc1=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
		
		$eld1=$asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]]/100;
		$eld2=$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]];
		$eld3=$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]];
		$eld=$eld1*$eld2/$eld3;
		$eldd=(is_nan($eld) ? 0 : $eld);
		
		$el=($elaa+$elbb)/100*$elc1-$eldd;
		$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $el;
		// (
		// 	(
		// 		$asData[$eid.'_'.$cid.'_'.$AllYear[$y]] * (1-$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100)
		// 	)
		// 	+($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]*$asData['H_'.$eid.'_'.$cid.'_'.$AllYear[$y]]/100
		// 	)/
		// 	$atData[$TypeChunk[$j].'_HPC_'.$AllYear[$y]]
		// )/100 
		// * $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] 
		// - $asData[$TypeChunk[$j].'_CG_'.$cid.'_'.$AllYear[$y]]/100
		// *$ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]]
		// /$atData[$TypeChunk[$j].'_BD_'.$AllYear[$y]];
												
										
											}
										}
									}
								}
							}
						}elseif($pentypes['id']=='DH'){
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							foreach($facmtype as $facmtypes){
								if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
									if($facmtypes['TY']=='TUM'){
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											for($y = 0; $y < count($AllYear); $y++){ 
												//=D6*('Fac. Pe. Eff. Manuf.'!J17/100)
												$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] * $asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100;
												
												
											}
										}
									}
								}
							}
						}elseif($pentypes['id']=='SO'){
							$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
							foreach($facmtype as $facmtypes){
								if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
									if($facmtypes['TY']=='TUM'){
										$cid = 	$facmtypes['id'];
										if($pentypes['Man_'.$cid]=='Y'){
											for($y = 0; $y < count($AllYear); $y++){ 
												//=D6*('Fac. Pe. Eff. Manuf.'!J17/100) SOLAr =+D6*('Fac. Pe. Eff. Manuf.'!J19/100)
											//	=+D6*('Fac. Pe. Eff. Manuf.'!D37/100*'Fac. Pe. Eff. Manuf.'!D193/100)
											
												$eff_date[$eid.'_'.$cid.'_'.$AllYear[$y]] = $ar_data[$TypeChunk[$j].'_'.$facmtypes['id'].'_'.$AllYear[$y]] * ($asData[$eid.'_'.$cid.'_'.$AllYear[$y]]/100*$atData[$TypeChunk[$j].'_SOS_'.$AllYear[$y]]/100);
												
												
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	$eff_date['SID'] = 8;
	$amd->add(checkIsNaN($eff_date));

	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Man' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$TypeChunkAdd); 
			for($j = 0; $j < count($TypeChunk); $j++){
				foreach($pentype as $pentypes){ 
					$eid = 	$TypeChunk[$j].'_'.$pentypes['id'];
					$carrier = $pentypes['value'];	
					foreach($facmtype as $facmtypes){
						if(($facmtypes['TY']=='TUM' and $bjData['TU_'.$TypeChunk[$j]]=='Y' and ($facmtypes['PE']=='N' and $bjData[$facmtypes['id'].'_'.$TypeChunk[$j]]=='Y')) or (($bjData['TU_'.$TypeChunk[$j]]=='Y') and ($facmtypes['PE']=='N' and $bjData['FDH_'.$TypeChunk[$j]]!='Y' and $bjData['SG_'.$TypeChunk[$j]]!='Y' and $bjData['SWH_'.$TypeChunk[$j]]!='Y' and $facmtypes['id']=='SG'))){
							if($facmtypes['TY']=='TUM'){
									$cid = 	$facmtypes['id'];
								if($pentypes['Man_'.$cid]=='Y' and ($pentypes['id']=='TF' or $pentypes['id']=='MB' or $pentypes['id']=='DH' or $pentypes['id']=='SO' or $pentypes['id']=='FF' or $pentypes['id']=='EL')){
									for($y = 0; $y < count($AllYear); $y++){ 
										$eff_date[$pentypes['id'].'_'.$AllYear[$y]] = NAN($eff_date[$pentypes['id'].'_'.$AllYear[$y]]) + NAN($eff_date[$eid.'_'.$facmtypes['id'].'_'.$AllYear[$y]]);
										
									}
								}
							}
						}
					}
				}
			}
		}
	}
	
	//Table 9-1 Total final energy demand in Manufacturing (absolute)
	foreach($pentype as $pentypes){ 
		$eid = 	$pentypes['id'];	
		for($y = 0; $y < count($AllYear); $y++){ 
			if($pentypes['id']=='TF' or $pentypes['id']=='MB' or $pentypes['id']=='DH' or $pentypes['id']=='SO' or $pentypes['id']=='FF'){
				$tmanf_data[$eid.'_'.$AllYear[$y]] = $eff_date[$pentypes['id'].'_'.$AllYear[$y]];
			}
			elseif($pentypes['id']=='EL'){
				//=+'Useful energy Industry'!D35+'Useful energy Industry'!D40+'Auxiliar calculations for Manuf'!D41+'Auxiliar calculations for Manuf'!D46+'Auxiliar calculations for Manuf'!D53+'Auxiliar calculations for Manuf'!D61+'Auxiliar calculations for Manuf'!D68
				$tmanf_data[$eid.'_'.$AllYear[$y]] = NAN($ind_data['ET_'.'Man_'.$AllYear[$y]]) + NAN($eff_date[$pentypes['id'].'_'.$AllYear[$y]]);
											
			}elseif($pentypes['id']=='MF'){
			 	
				//='UsEne-D'!C19
								
				$tmanf_data[$eid.'_'.$AllYear[$y]] =  $ind_data['AMT_'.'Man_'.$AllYear[$y]];
				
			}elseif($pentypes['id']=='CK'){
			 	
				//=+'Fac. Pe. Eff. Manuf.'!P59*'Fac. Pe. Eff. Manuf.'!P54/100*'Fac. Pe. Eff. Manuf.'!P55/100*'Fac. Pe. Eff. Manuf.'!P56/100*F4/1000*H4*1000000000
								
				$tmanf_data[$eid.'_'.$AllYear[$y]] = $atData['CH_'.$AllYear[$y]]*$atData['CC_'.$AllYear[$y]]/100 * $atData['CD_'.$AllYear[$y]]/100*$atData['CE_'.$AllYear[$y]]/1000*8141/1000*$unittype*$ckfsunit;
					
								
			}elseif($pentypes['id']=='FS'){
			 	//+'Fac. Pe. Eff. Manuf.'!P60*J4*H4
								
				$tmanf_data[$eid.'_'.$AllYear[$y]] = $atData['CI_'.$AllYear[$y]] *(11630000000/1000000000)*$unittype*$ckfsunit;
								
			}	
				//Total MAN
				$tmanf_data['Y_'.$AllYear[$y]] = NAN($tmanf_data['Y_'.$AllYear[$y]]) + NAN($tmanf_data[$eid.'_'.$AllYear[$y]]);
			
		}
	}
	foreach($pentype as $pentypes){ 
		$eid = 	$pentypes['id'];	
		for($y = 0; $y < count($AllYear); $y++){ 
		
		//Table 9-2 Total final energy demand in Manufacturing (shares)
		$tmanf_data['S_'.$eid.'_'.$AllYear[$y]] = $tmanf_data[$eid.'_'.$AllYear[$y]]/$tmanf_data['Y_'.$AllYear[$y]]*100;
		
		//Table 9-3 Total final energy demand per value added in Manufacturing
		$tmanf_data['V_'.$eid.'_'.$AllYear[$y]] = $tmanf_data[$eid.'_'.$AllYear[$y]]/$gdp_data['Man_S_'.$AllYear[$y]]/$unittype;
		
		$tmanf_data['V_'.$AllYear[$y]] = NAN($tmanf_data['V_'.$AllYear[$y]]) + NAN($tmanf_data['V_'.$eid.'_'.$AllYear[$y]]);
		
		//Table 9-4  Total final energy demand in Industry (absolute) zz
		$tmanf_data['A_'.$eid.'_'.$AllYear[$y]] = NAN($tmanf_data[$eid.'_'.$AllYear[$y]]) + NAN($acm_data[$pentypes['id'].'_'.$AllYear[$y]]);
		
		$tmanf_data['A_'.$AllYear[$y]] = NAN($tmanf_data['A_'.$AllYear[$y]]) + NAN($tmanf_data['A_'.$eid.'_'.$AllYear[$y]]);
		
		//final
		$final_data[$eid.'_'.$AllYear[$y]] = NAN($final_data[$eid.'_'.$AllYear[$y]]) + NAN($tmanf_data['A_'.$eid.'_'.$AllYear[$y]]);
		
		}
	}
	
	//Table 9-5  Total final energy demand in Industry (shares)
	foreach($pentype as $pentypes){ 
		$eid = 	$pentypes['id'];	
		for($y = 0; $y < count($AllYear); $y++){ 
			$tmanf_data['F_'.$eid.'_'.$AllYear[$y]] = $tmanf_data['A_'.$eid.'_'.$AllYear[$y]]/$tmanf_data['A_'.$AllYear[$y]]*100;	
		}
	}
	
	//Table 9-6  Total final energy demand per value added in Industry
	foreach($pentype as $pentypes){ 
		$eid = 	$pentypes['id'];	
		for($y = 0; $y < count($AllYear); $y++){ 
			$tmanf_data['F_'.$eid.'_'.$AllYear[$y]] = $tmanf_data['A_'.$eid.'_'.$AllYear[$y]]/$tmanf_data['A_'.$AllYear[$y]]*100;	
		}
	}
	//'GDP-D'!C9+'GDP-D'!C10+'GDP-D'!C11+'GDP-D'!C12 GDP for Industry
	foreach($maintype as $maintypes){ 
		if($maintypes['ind']=='Y' ){
			for($y = 0; $y < count($AllYear); $y++){ 
				$gdp['g_'.$AllYear[$y]] = $gdp['g_'.$AllYear[$y]]+ $adData[$maintypes['id'].'_'.$AllYear[$y]];
				
			}     
		}
	}
	
	//Table 9-6  Total final energy demand per value added in Industry
	foreach($pentype as $pentypes){ 
		$eid = 	$pentypes['id'];	
		for($y = 0; $y < count($AllYear); $y++){ 
			$tmanf_data['T_'.$eid.'_'.$AllYear[$y]] = $tmanf_data['A_'.$eid.'_'.$AllYear[$y]]/($adData['GDP_'.$AllYear[$y]]*$gdp['g_'.$AllYear[$y]]/100)/$unittype;
			$tmanf_data['TY_'.$AllYear[$y]] = $tmanf_data['TY_'.$AllYear[$y]] + $tmanf_data['T_'.$eid.'_'.$AllYear[$y]];
		}
	}
	$tmanf_data['SID'] = 9;
	$amd->add(checkIsNaN($tmanf_data));
	
	//Transport
	//Table 10-2 Total freight-kilometers
	foreach($maintype as $maintypes){ 
		if($maintypes['gdp']=='Y' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				for($y = 0; $y < count($AllYear); $y++){ 
				//Freight-km
					$fkm_dats['F_'.$AllYear[$y]] = $gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $auData[$TypeChunk[$j].'_'.$AllYear[$y]];
					$fkm_dat['F_'.$AllYear[$y]] = $fkm_dat['F_'.$AllYear[$y]] + $fkm_dats['F_'.$AllYear[$y]];
				}   
			}	
		} 
	}
	for($y = 0; $y < count($AllYear); $y++){ 
		//Freight-km
		//$fkm_data['F_'.$AllYear[$y]] = $fkm_dat['F_'.$AllYear[$y]] + $auData['Base_'.$AllYear[$y]];
		$fkm_data['F_'.$AllYear[$y]] = $fkm_dat['F_'.$AllYear[$y]] + $auData['Base_'.$AllYear[$y]];
	}   
	$fueData = $abd->getByField('Trp','SID');
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 

			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_fr']=='Y'){//For each Type get the Name/Value
					for($y = 0; $y < count($AllYear); $y++){
						$fid = $fueData[$TypeChunk[$j].'_fr_fl'];
						$fval = Config2::getData('fueltype',$fid,'frvalue',$caseStudyId);
						//Table 10-5 Energy intensity of freight transportation (energy units)
						$fkm_data[$TypeChunk[$j].'_'.$AllYear[$y]] = $azData[$TypeChunk[$j].'_'.$AllYear[$y]];
						
						//Table 10-6 Energy consumption of freight transportation (by mode)
						$fkm_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]] = ($fkm_data['F_'.$AllYear[$y]] * $fkm_data[$TypeChunk[$j].'_'.$AllYear[$y]]*$avData[$TypeChunk[$j].'_'.$AllYear[$y]]/100)/100*$unittype;
						
						//Total 
						$fkm_data['MY_'.$AllYear[$y]] = $fkm_data['MY_'.$AllYear[$y]] + $fkm_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]];
						
						$fkm_data['Fu_'.$fid.'_'.$AllYear[$y]] = $fkm_data['Fu_'.$fid.'_'.$AllYear[$y]] + $fkm_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]];
					}      
				}
			}	
		} 
	}
	for($y = 0; $y < count($AllYear); $y++){
		foreach($fueltype as $fueltypes){ 
		
			if($fueltypes['ftype']=='EL'){
				$fkm_data['EL_'.$AllYear[$y]] = $fkm_data['EL_'.$AllYear[$y]]+ $fkm_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]];
			}elseif($fueltypes['ftype']=='MF'){
			
				$fkm_data['MF_'.$AllYear[$y]] = $fkm_data['MF_'.$AllYear[$y]] +$fkm_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]];
			}elseif($fueltypes['ftype']=='CK'){
			
				$fkm_data['CK_'.$AllYear[$y]] = $fkm_data['CK_'.$AllYear[$y]] +$fkm_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]];
			}
		}	
		$fkm_data['TFu_'.$AllYear[$y]] = $fkm_data['EL_'.$AllYear[$y]] + $fkm_data['MF_'.$AllYear[$y]] + $fkm_data['CK_'.$AllYear[$y]];
	}
	$fkm_data['SID'] = 10;
	$amd->add(checkIsNaN($fkm_data));
	
	
	//Demography Pop. inside lc
	for($y = 0; $y < count($AllYear); $y++){ 
		//respect unit for population
		//Pop. inside lc
		//$dem_data['P_'.$AllYear[$y]] = $acData['Pop_'.$AllYear[$y]] * $acData['SLP_'.$AllYear[$y]]/100;//
		$dem_data['P_'.$AllYear[$y]] = $acData['Pop_'.$AllYear[$y]]*$popmultiple * $acData['SLP_'.$AllYear[$y]]/100;//
		
		//Active LF
		//$dem_data['ALF_'.$AllYear[$y]] = $acData['Pop_'.$AllYear[$y]]*$acData['POL_'.$AllYear[$y]]/100*$acData['PAL_'.$AllYear[$y]]/100;
		$dem_data['ALF_'.$AllYear[$y]] = $acData['Pop_'.$AllYear[$y]]*$popmultiple*$acData['POL_'.$AllYear[$y]]/100*$acData['PAL_'.$AllYear[$y]]/100;
	}
	
	//Table 11-4 Intracity passenger transportation by mode
	for($y = 0; $y < count($AllYear); $y++){ 
		//Total
		$inta_data['T_'.$AllYear[$y]] = $axData['Dist_'.$AllYear[$y]]*365*$dem_data['P_'.$AllYear[$y]]/1000;
	}
				
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
		  	for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_psi']=='Y'){//For each Type get the Name/Value
					for($y = 0; $y < count($AllYear); $y++){ 
					$fid = $fueData[$TypeChunk[$j].'_psi_fl'];
					$fval = Config2::getData('fueltype',$fid,'psvalue',$caseStudyId);
					
					//Table 11-4 Intracity passenger transportation by mode
					$inta_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inta_data['T_'.$AllYear[$y]]*$awData[$TypeChunk[$j].'_'.$AllYear[$y]]/100;
					//$inta_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inta_data['T_'.$AllYear[$y]]*$awData[$TypeChunk[$j].'_'.$AllYear[$y]]*$pasmultiple/100;

					//Table 11-6 Energy intensity of intracity passenger transportation (energy units)
					$inta_data['I_'.$TypeChunk[$j].'_'.$AllYear[$y]]= ($ayData[$TypeChunk[$j].'_'.$AllYear[$y]]/100)/$axData[$TypeChunk[$j].'_'.$AllYear[$y]];
					
					}	
		      }
			}	
		} 
	}
	
	//Table 11-7 Energy consumption of intracity passenger transportation (by mode)
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
		  	for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_psi']=='Y'){//For each Type get the Name/Value
					for($y = 0; $y < count($AllYear); $y++){ 
						$fid = $fueData[$TypeChunk[$j].'_psi_fl'];
						$fval = Config2::getData('fueltype',$fid,'psvalue',$caseStudyId);
						
						$inta_data['C_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inta_data['I_'.$TypeChunk[$j].'_'.$AllYear[$y]] * 
						$inta_data['M_'.$TypeChunk[$j].'_'.$AllYear[$y]]*$unittype;

						$inta_data['CY_'.$AllYear[$y]]= NAN($inta_data['CY_'.$AllYear[$y]]) +NAN($inta_data['C_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$inta_data['Fu_'.$fid.'_'.$AllYear[$y]] = NAN($inta_data['Fu_'.$fid.'_'.$AllYear[$y]]) + NAN($inta_data['C_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$inta_data['ECY_'.$AllYear[$y]] = NAN($inta_data['ECY_'.$AllYear[$y]]) + NAN($inta_data['C_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					}	
		      }
			}	
		} 
	}
	
	for($y = 0; $y < count($AllYear); $y++){
		foreach($fueltype as $fueltypes){ 
			if($fueltypes['ftype']=='EL'){
				$inta_data['EL_'.$AllYear[$y]] = NAN($inta_data['EL_'.$AllYear[$y]]) + NAN($inta_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}elseif($fueltypes['ftype']=='MF'){
				$inta_data['MF_'.$AllYear[$y]] = NAN($inta_data['MF_'.$AllYear[$y]]) + NAN($inta_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}elseif($fueltypes['ftype']=='CK'){
				$inta_data['CK_'.$AllYear[$y]] = NAN($inta_data['CK_'.$AllYear[$y]]) + NAN($inta_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}
		}	
		$inta_data['TFu_'.$AllYear[$y]] = NAN($inta_data['EL_'.$AllYear[$y]]) + NAN($inta_data['MF_'.$AllYear[$y]]) + NAN($inta_data['CK_'.$AllYear[$y]]);
	}
	
	$inta_data['SID'] = 11;
	$amd->add(checkIsNaN($inta_data));
	
	//Intercity passenger transportation

	//Table 12-1  Distance travelled
	for($y = 0; $y < count($AllYear); $y++){ 
		//Total
		//$inte_data['T_'.$AllYear[$y]] = $baData['Dist_'.$AllYear[$y]]/1000*$acData['Pop_'.$AllYear[$y]];
		$inte_data['T_'.$AllYear[$y]] = $baData['Dist_'.$AllYear[$y]]/1000*$acData['Pop_'.$AllYear[$y]]*$popmultiple;
	}
	//Table 12-4 Modal split of intercity passenger transportation
	for($y = 0; $y < count($AllYear); $y++){ 
		//Total
		//$inte_data['C_'.$AllYear[$y]] = $acData['Pop_'.$AllYear[$y]]/$baData['CWN_'.$AllYear[$y]]*$baData['CKM_'.$AllYear[$y]]*$baData['Car_'.$AllYear[$y]]/1000;
		$inte_data['C_'.$AllYear[$y]] = ($acData['Pop_'.$AllYear[$y]]*$popmultiple)/$baData['CWN_'.$AllYear[$y]]*$baData['CKM_'.$AllYear[$y]]*$baData['Car_'.$AllYear[$y]]/1000;
		$inte_data['P_'.$AllYear[$y]] = NAN($inte_data['T_'.$AllYear[$y]]) - NAN($inte_data['C_'.$AllYear[$y]]);
	}
	
	//Table 12-6 Cars intercity passenger transportation by car type
   //Table 12-8 Public intercity passenger transportation by mode
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_ps']=='Y'){
					for($y = 0; $y < count($AllYear); $y++){
					
						if($abData[$TypeChunk[$j].'_car']=='Y'){
							$inte_data['CT_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inte_data['C_'.$AllYear[$y]] * $bbData[$TypeChunk[$j].'_'.$AllYear[$y]]/100;
						}else{
							$inte_data['CT_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inte_data['P_'.$AllYear[$y]] * $bbData[$TypeChunk[$j].'_'.$AllYear[$y]]/100;
						}
						$inte_data['TM_'.$AllYear[$y]] =  NAN($inte_data['TM_'.$AllYear[$y]]) + NAN($inte_data['CT_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
					}
				}
			}	
		} 
	}
	
	//Table 12-10 Energy intensity of intercity passenger transportation (energy units)
	
	 foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_ps']=='Y'){//For each Type get the Name/Value
					for($y = 0; $y < count($AllYear); $y++){ 
					$fid = $fueData[$TypeChunk[$j].'_ps_fl'];
					$fval = Config2::getData('fueltype',$fid,'psvalue',$caseStudyId);
						if($abData[$TypeChunk[$j].'_plane']=='Y'){
							$inte_data['EI_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $bcData[$TypeChunk[$j].'_'.$AllYear[$y]]/($baData[$TypeChunk[$j].'_'.$AllYear[$y]]/100)/1000;
						}elseif($abData[$TypeChunk[$j].'_car']=='Y')
						{
							$inte_data['EI_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $bcData[$TypeChunk[$j].'_'.$AllYear[$y]]/100/$baData['Car_'.$AllYear[$y]];
						}else{
							$inte_data['EI_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $bcData[$TypeChunk[$j].'_'.$AllYear[$y]]/100/$baData[$TypeChunk[$j].'_'.$AllYear[$y]];
							
						}
						
					}    
				}
			}	
		} 
	}
	
	//Table 12-11 Energy consumption of intercity passenger transportation (by mode)
	
	 foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Trp' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($abData[$TypeChunk[$j].'_ps']=='Y'){//For each Type get the Name/Value
					for($y = 0; $y < count($AllYear); $y++){ 
					$fid = $fueData[$TypeChunk[$j].'_ps_fl'];
					$fval = Config2::getData('fueltype',$fid,'psvalue',$caseStudyId);
						
						$inte_data['EC_'.$TypeChunk[$j].'_'.$AllYear[$y]] = $inte_data['CT_'.$TypeChunk[$j].'_'.$AllYear[$y]]*$inte_data['EI_'.$TypeChunk[$j].'_'.$AllYear[$y]]*$unittype;
						
						$inte_data['Fu_'.$fid.'_'.$AllYear[$y]] = NAN($inte_data['Fu_'.$fid.'_'.$AllYear[$y]]) + NAN($inte_data['EC_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
						
						$inte_data['ECY_'.$AllYear[$y]] = NAN($inte_data['ECY_'.$AllYear[$y]]) + NAN($inte_data['EC_'.$TypeChunk[$j].'_'.$AllYear[$y]]);
						
					}    
				}
			}	
		} 
	}
	//Table 12-13 Energy consumption of intercity passenger transportation intercity (by fuel group)
	for($y = 0; $y < count($AllYear); $y++){
		foreach($fueltype as $fueltypes){ 
			if($fueltypes['ftype']=='EL'){
				$inte_data['EL_'.$AllYear[$y]] = NAN($inte_data['EL_'.$AllYear[$y]]) + NAN($inte_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}elseif($fueltypes['ftype']=='MF'){
				$inte_data['MF_'.$AllYear[$y]] = NAN($inte_data['MF_'.$AllYear[$y]]) + NAN($inte_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}elseif($fueltypes['ftype']=='CK'){
				$inte_data['CK_'.$AllYear[$y]] = NAN($inte_data['CK_'.$AllYear[$y]]) + NAN($inte_data['Fu_'.$fueltypes['id'].'_'.$AllYear[$y]]);
			}
		}
		$inte_data['TFu_'.$AllYear[$y]] = NAN($inte_data['EL_'.$AllYear[$y]]) + NAN($inte_data['MF_'.$AllYear[$y]]) + NAN($inte_data['CK_'.$AllYear[$y]]);		
	}
	
	//Table 12-14 Energy consumption of international and military transportation
	for($y = 0; $y < count($AllYear); $y++){
		
		$inte_data['MiT_'.$AllYear[$y]] = NAN($bdData['CT_'.$AllYear[$y]]) + NAN($bdData['VA_'.$AllYear[$y]]) * NAN($adData['GDP_'.$AllYear[$y]])*$unittype;
		
	}
	
	//Table 12-15 Energy consumption of intra + inter passenger + international & military transportation (by fuel group)
	for($y = 0; $y < count($AllYear); $y++){
		
		$inte_data['Mi_EL_'.$AllYear[$y]] = NAN($inte_data['EL_'.$AllYear[$y]]) + NAN($inta_data['EL_'.$AllYear[$y]]);
	
		$inte_data['Mi_CK_'.$AllYear[$y]] = NAN($inte_data['CK_'.$AllYear[$y]]) + NAN($inta_data['CK_'.$AllYear[$y]]);
		
		$inte_data['Mi_MF_'.$AllYear[$y]] = NAN($inte_data['MF_'.$AllYear[$y]]) + NAN($inta_data['MF_'.$AllYear[$y]]) + NAN($inte_data['MiT_'.$AllYear[$y]]);
		
		$inte_data['TPass_'.$AllYear[$y]] = NAN($inte_data['Mi_EL_'.$AllYear[$y]]) + NAN($inte_data['Mi_CK_'.$AllYear[$y]]) + NAN($inte_data['Mi_MF_'.$AllYear[$y]]);
	}	
	
	
	$inte_data['SID'] = 12;
	$amd->add(checkIsNaN($inte_data));
	
	
	//Table 13-1 Final energy demand in Transportation sector (by fuel)
	for($y = 0; $y < count($AllYear); $y++){
		foreach($fueltype as $fueltypes){ 
			$fid = $fueltypes['id'];
			
			$trpt_data['Fu_'.$fid.'_'.$AllYear[$y]] = NAN($inte_data['Fu_'.$fid.'_'.$AllYear[$y]]) + NAN($inta_data['Fu_'.$fid.'_'.$AllYear[$y]]) + NAN($fkm_data['Fu_'.$fid.'_'.$AllYear[$y]]);
			
			$trpt_data['Fu_'.$AllYear[$y]] = NAN($trpt_data['Fu_'.$AllYear[$y]]) + NAN($trpt_data['Fu_'.$fid.'_'.$AllYear[$y]]);
		}
		$trpt_data['Fu_'.$AllYear[$y]] = NAN($trpt_data['Fu_'.$AllYear[$y]]) + NAN($inte_data['MiT_'.$AllYear[$y]]);
	}
	
	//Table 13-2 Share of fuels in Transportation sector
	for($y = 0; $y < count($AllYear); $y++){
		foreach($fueltype as $fueltypes){ 
			$fid = $fueltypes['id'];
			$trpt_data['SFu_'.$fid.'_'.$AllYear[$y]] = $trpt_data['Fu_'.$fid.'_'.$AllYear[$y]]/ $trpt_data['Fu_'.$AllYear[$y]]*100;
			
		}
		$trpt_data['SFu_Mil_'.$AllYear[$y]] = $inte_data['MiT_'.$AllYear[$y]]/$trpt_data['Fu_'.$AllYear[$y]]*100;
	}
	
	//Table 13-3 Final energy demand in Transportation sector (by fuel group) zz
	for($y = 0; $y < count($AllYear); $y++){
		
		$trpt_data['EL_'.$AllYear[$y]] = NAN($inte_data['EL_'.$AllYear[$y]]) + NAN($inta_data['EL_'.$AllYear[$y]]) + NAN($fkm_data['EL_'.$AllYear[$y]]);
		
		$trpt_data['CK_'.$AllYear[$y]] = NAN($inte_data['CK_'.$AllYear[$y]]) + NAN($inta_data['CK_'.$AllYear[$y]]) + NAN($fkm_data['CK_'.$AllYear[$y]]);
		
		$trpt_data['MF_'.$AllYear[$y]] = NAN($inte_data['MF_'.$AllYear[$y]]) + NAN($inta_data['MF_'.$AllYear[$y]]) + NAN($fkm_data['MF_'.$AllYear[$y]]) + NAN($inte_data['MiT_'.$AllYear[$y]]);
		
		$final_data['EL_'.$AllYear[$y]] = NAN($final_data['EL_'.$AllYear[$y]]) + NAN($trpt_data['EL_'.$AllYear[$y]]);
		$final_data['CK_'.$AllYear[$y]] = NAN($final_data['CK_'.$AllYear[$y]]) + NAN($trpt_data['CK_'.$AllYear[$y]]);
		$final_data['MF_'.$AllYear[$y]] = NAN($final_data['MF_'.$AllYear[$y]]) + NAN($trpt_data['MF_'.$AllYear[$y]]);
		
		//Total
		$trpt_data['TFu_'.$AllYear[$y]] = NAN($trpt_data['EL_'.$AllYear[$y]]) + NAN($trpt_data['CK_'.$AllYear[$y]]) + NAN($trpt_data['MF_'.$AllYear[$y]]);
	}
	
	//Table 13-4 Share of fuel groups in Transportation sector
	for($y = 0; $y < count($AllYear); $y++){
		
		$trpt_data['S_EL_'.$AllYear[$y]] =  $trpt_data['EL_'.$AllYear[$y]]/$trpt_data['TFu_'.$AllYear[$y]]*100;
		$trpt_data['S_CK_'.$AllYear[$y]] = $trpt_data['CK_'.$AllYear[$y]]/$trpt_data['TFu_'.$AllYear[$y]]*100;
		$trpt_data['S_MF_'.$AllYear[$y]] = $trpt_data['MF_'.$AllYear[$y]]/$trpt_data['TFu_'.$AllYear[$y]]*100;
		
	}
	
	//Table 13-5 Final energy demand in Transportation sector (by subsector)
	for($y = 0; $y < count($AllYear); $y++){
		
		$trpt_data['Freight_'.$AllYear[$y]] =  $fkm_data['TFu_'.$AllYear[$y]];
		$trpt_data['Pass_intracity_'.$AllYear[$y]] = $inta_data['TFu_'.$AllYear[$y]];
		$trpt_data['Pass_intercity_'.$AllYear[$y]] = $inte_data['TFu_'.$AllYear[$y]];
		$trpt_data['Intl_military_'.$AllYear[$y]] = $inte_data['MiT_'.$AllYear[$y]];
		
		// Total final energy demand per year
		$trpt_data['TED_'.$AllYear[$y]]	= NAN($trpt_data['Freight_'.$AllYear[$y]]) + NAN($trpt_data['Pass_intracity_'.$AllYear[$y]]) + NAN($trpt_data['Pass_intercity_'.$AllYear[$y]]) + NAN($trpt_data['Intl_military_'.$AllYear[$y]]);
	}
	
	//Table 13-6 Shares of final energy demand of Transportation subsectors 
	
	for($y = 0; $y < count($AllYear); $y++){
		
		$trpt_data['S_Freight_'.$AllYear[$y]] =  $trpt_data['Freight_'.$AllYear[$y]]/$trpt_data['TED_'.$AllYear[$y]]*100;
		$trpt_data['S_Pass_intracity_'.$AllYear[$y]] = $trpt_data['Pass_intracity_'.$AllYear[$y]]/$trpt_data['TED_'.$AllYear[$y]]*100;
		$trpt_data['S_Pass_intercity_'.$AllYear[$y]] = $trpt_data['Pass_intercity_'.$AllYear[$y]]/$trpt_data['TED_'.$AllYear[$y]]*100;
		$trpt_data['S_Intl_military_'.$AllYear[$y]] = $trpt_data['Intl_military_'.$AllYear[$y]]/$trpt_data['TED_'.$AllYear[$y]]*100;
				
	}
	$trpt_data['SID'] = 13;
	$amd->add(checkIsNaN($trpt_data));
	
	
	//House Hold Sector 
	
	//sh 15.02.2019
	$unittype=$unittypedefault;
	foreach($houendtype as $houendtypes){
		if($houendtypes['id']=='SH'){
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($j==0){
							$bed = new Data($caseStudyId,$bexml);
						}else{
							$bed = new Data($caseStudyId,$blxml);
						}
						$beData = $bed->getByField(1,'SID');
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
							for($k = 0; $k < count($SubChunk); $k++){ 
								for($y = 0; $y < count($AllYear); $y++){
								//=Demography!D10*Urban!D16/100*Urban!D4/100*Urban!D10*Urban!D22*Urban!D17*24*Urban!D19/100*F3/1000000
									$hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]] =  
									($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)* 
									$beData['Shr_'.$TypeChunk[$j].'_'.$AllYear[$y]]/100 * $beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100 * $beData[$SubChunk[$k].'_h_'.$AllYear[$y]] * $beData[$SubChunk[$k].'_j_'.$AllYear[$y]] * $beData['Deg_'.$TypeChunk[$j].'_'.$AllYear[$y]] * 24* $beData[$SubChunk[$k].'_i_'.$AllYear[$y]]/100*$unittype/1000000;
								}
							} 
						} 
					}
				}
			}
		}elseif($houendtypes['id']=='WH'){
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($j==0){
							$bed = new Data($caseStudyId,$bexml);
						}else{
							$bed = new Data($caseStudyId,$blxml);
						}
						$beData = $bed->getByField(1,'SID');
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
							for($k = 0; $k < count($SubChunk); $k++){ 
								for($y = 0; $y < count($AllYear); $y++){
									//=Urban!D78*Demography!D9*Urban!D80/100*(Demography!D10*Urban!D4/100)*F3/1000
									$hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = $beData[$TypeChunk[$j].'_c_'.$AllYear[$y]] * 
									$acData[$TypeChunk[$j].'_'.$AllYear[$y].'_Cap'] * 
									$beData[$SubChunk[$k].'_b_'.$AllYear[$y]]/100*(($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)*
									$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100)*$unittype/1000;		
								}
							}
						}
					}
				}
			}
		}elseif($houendtypes['id']=='CO'){
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($j==0){
							$bed = new Data($caseStudyId,$bexml);
						}else{
							$bed = new Data($caseStudyId,$blxml);
						}
						$beData = $bed->getByField(1,'SID');
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
							for($k = 0; $k < count($SubChunk); $k++){ 
								for($y = 0; $y < count($AllYear); $y++){
								//=Demography!D10*Urban!D4/100*Urban!D60*F3/1000
									$hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = 
									($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)*
									$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100*$beData[$SubChunk[$k].'_a_'.$AllYear[$y]]*$unittype/1000;		
								}
							}
						}
					}
				}
			}
		}elseif($houendtypes['id']=='AC'){
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($j==0){
							$bed = new Data($caseStudyId,$bexml);
						}else{
							$bed = new Data($caseStudyId,$blxml);
						}
						$beData = $bed->getByField(1,'SID');
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
							for($k = 0; $k < count($SubChunk); $k++){ 
								for($y = 0; $y < count($AllYear); $y++){
									
									//=Demography!D10*Urban!D4/100*Urban!D44/100*Urban!D47*F3/1000							
									$hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = 
									($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)*
									$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100*$beData[$SubChunk[$k].'_k_'.$AllYear[$y]]/100*$beData[$SubChunk[$k].'_l_'.$AllYear[$y]]*$unittype/1000;	
									
								}			
								
							}
						}
						
					} 
				} 
			}
		}elseif($houendtypes['id']=='AP'){
			foreach($maintype as $maintypes){ 
				if($maintypes['id']=='Hou' ){
					$abxml = $maintypes['id'];
					$abd = new Data($caseStudyId,'sectors_data');
					$abData = $abd->getByField($abxml,'SID');
					$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
					for($j = 0; $j < count($TypeChunk); $j++){ 
						if($j==0){
							$bed = new Data($caseStudyId,$bexml);
						}else{
							$bed = new Data($caseStudyId,$blxml);
						}
						$beData = $bed->getByField(1,'SID');
						if($abData[$TypeChunk[$j].'_A']){
							$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
							for($k = 0; $k < count($SubChunk); $k++){ 
							
								for($y = 0; $y < count($AllYear); $y++){
									//=Demography!D10*Urban!D4/100*Urban!D103*Urban!D101/100*F3/1000
									$hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = ($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)*
									$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100 * $beData[$SubChunk[$k].'_d_'.$AllYear[$y]]*  $beData[$TypeChunk[$j].'_e_'.$AllYear[$y]]/100*$unittype/1000;	
									//=Demography!D10*Urban!D4/100*Urban!D101/100*Urban!D115*F3/1000
									if($bjData['LH_'.$TypeChunk[$j]]=='Y'){
										$hou_data[$SubChunk[$k].'_LH_'.$AllYear[$y]] = ($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH']*$popmultiple)*
										$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100 * $beData[$SubChunk[$k].'_LH_d_'.$AllYear[$y]] * $beData[$TypeChunk[$j].'_e_'.$AllYear[$y]]/100*$unittype/1000;	
									//=Demography!D10*Urban!D4/100*(1-Urban!D101/100)*Urban!D118*F3/1000	
										$hou_data[$SubChunk[$k].'_FF_'.$AllYear[$y]] = ($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH'] *$popmultiple)* 
										$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100 * (1-$beData[$TypeChunk[$j].'_e_'.$AllYear[$y]]/100) * $beData[$SubChunk[$k].'_LH_f_'.$AllYear[$y]]*$unittype/1000;
										
									}else{
									//=Demography!D10*Urban!D4/100*(1-Urban!D101/100)*Urban!D118*F3/1000	
																				
										$hou_data[$SubChunk[$k].'_FF_'.$AllYear[$y]] = (($acData[$TypeChunk[$j].'_'.$AllYear[$y].'_NH'] * $popmultiple)*
										$beData[$SubChunk[$k].'_g_'.$AllYear[$y]]/100) *$beData[$SubChunk[$k].'_f_'.$AllYear[$y]]* (1-$beData[$TypeChunk[$j].'_e_'.$AllYear[$y]]/100) * $unittype/1000;
										//echo $SubChunk[$k].'_FF_'.$AllYear[$y].'='.$hou_data[$SubChunk[$k].'_FF_'.$AllYear[$y]];
										//echo '<br>';
										
									}
								}
							}
						}
					}
				}
			}
		}
	}
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Hou' ){
				$abxml = $maintypes['id'];
				$abd = new Data($caseStudyId,'sectors_data');
				$abData = $abd->getByField($abxml,'SID');
				$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
				for($j = 0; $j < count($TypeChunk); $j++){ 
					if($abData[$TypeChunk[$j].'_A']){
						$SubChunk = explode(",",$abData[$TypeChunk[$j].'_A']);//Housing Sub types
						for($k = 0; $k < count($SubChunk); $k++){ 
							foreach($houendtype as $houendtypes){
								for($y = 0; $y < count($AllYear); $y++){
									if($bjData['LH_'.$TypeChunk[$j]]=='Y' and $houendtypes['id']=='AP'){
										//=Demography!D10*Urban!D16/100*Urban!D4/100*Urban!D10*Urban!D22*Urban!D17*24*Urban!D19/100*F3/1000000
										$hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]) + NAN($hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]]);
										
										$hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]]) + NAN($hou_data[$SubChunk[$k].'_FF_'.$AllYear[$y]]);
									}elseif($bjData['LH_'.$TypeChunk[$j]]!='Y' and $houendtypes['id']=='AP'){
										$hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]]) + NAN($hou_data[$SubChunk[$k].'_FF_'.$AllYear[$y]]);
										$hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]) + NAN($hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]]);
									}else{
										 
										$hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]) + NAN($hou_data[$SubChunk[$k].'_'.$houendtypes['id'].'_'.$AllYear[$y]]);
									}
									
								}
							}
						} 
					} 
				}
			
		}
	}
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Hou' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
			for($j = 0; $j < count($TypeChunk); $j++){ 
				for($y = 0; $y < count($AllYear); $y++){
					foreach($houendtype as $houendtypes){  
						if($houendtypes['id']=='LH'){
							$EN = $TypeChunk[$j].'_LH_'.$AllYear[$y];
							$FN = $TypeChunk[$j].'_FF_'.$AllYear[$y];
							$ENV = NAN($hou_data[$EN]) + NAN($hou_data[$FN]);
								
						}else{
								
							$EN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y];
							$ENV = NAN($hou_data[$EN]);
								
						}
						 $hou_data[$TypeChunk[$j].'TFF_'.$AllYear[$y]] = NAN($hou_data[$TypeChunk[$j].'TFF_'.$AllYear[$y]]) + $ENV;
					}
				}
			}
		}
	}
	$hou_data['SID'] = 14;
	$amd->add(checkIsNaN($hou_data));
	
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Hou' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($j==0){
					$bed = new Data($caseStudyId,$bexml);
				}else{
					$bed = new Data($caseStudyId,$blxml);
				}
				$beData = $bed->getByField(1,'SID');
				if($abData[$TypeChunk[$j].'_A']){
					foreach($houendtype as $houendtypes){
						if($houendtypes['id']=='SH'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$FN = 'P_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$SN = 'E_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y' and ($houtypes['id']=='TF' or $houtypes['id']=='MB')){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/$beData[$SN];
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='EL'){
										$HN = 'PH_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]] * $beData[$FN]/100*(1-NAN($beData[$HN])/100*(1-NAN(1/$beData[$SN])));
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='DH'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100;
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='SO'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]*$beData[$SN]/100/100;
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='FF'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*($beData[$FN]+$beData['P_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]*(1-$beData['E_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]/100))/$beData[$SN];
									
									}
								}	
							}
						}elseif($houendtypes['id']=='WH'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$FN = 'P_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$SN = 'E_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y' and ($houtypes['id']=='TF' or $houtypes['id']=='MB')){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/$beData[$SN];
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='EL'){
										$HN = 'PH_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
										$fhou_data[$IN] = NAN($hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]) * NAN($beData[$FN]/100)*(1-NAN($beData[$HN])/100*(1-NAN(1/$beData[$SN])));
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='DH'){
										
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100;
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='SO'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100*$beData[$SN]/100;
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='FF'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*($beData[$FN]+$beData['P_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]*(1-$beData['E_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]/100))/$beData[$SN];
									}
								}	
							}
							
						}elseif($houendtypes['id']=='CO'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$FN = 'P_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$SN = 'E_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y' and ($houtypes['id']=='TF' or $houtypes['id']=='MB')){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/$beData[$SN];
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='EL'){
										$HN = 'PH_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100;
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='DH'){
										
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100;
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='SO'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100*$beData[$SN]/100;
									
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='FF'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*($beData[$FN]+$beData['P_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]*(1-$beData['E_'.$TypeChunk[$j].'_SO_'.$houendtypes['id'].'_'.$AllYear[$y]]/100))/$beData[$SN];
									}
								}	
							}
						}elseif($houendtypes['id']=='AC'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$FN = 'P_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$SN = 'E_'.$TypeChunk[$j].'_'.$houtypes['id'].'_'.$houendtypes['id'].'_'.$AllYear[$y];
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y' and ($houtypes['id']=='EL' or $houtypes['id']=='FF' )){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]]*$beData[$FN]/100/$beData[$SN];
									}
								}	
							}
						}elseif($houendtypes['id']=='AP'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y'  and $houtypes['id']=='EL'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y]];
										if($bjData['LH_'.$TypeChunk[$j]]!='Y'){
											$fhou_data[$TypeChunk[$j].'_'.$houendtypes['id'].'_FF_'.$AllYear[$y]] = $hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]];
										//$fhou_data[$TypeChunk[$j].'_LH_'.$houtypes['id'].'_'.$AllYear[$y]] = $hou_data[$TypeChunk[$j].'_LH_'.$AllYear[$y]] ;
										
										}
									}
								}	
							}
						}elseif($houendtypes['id']=='LH' and $bjData['LH_'.$TypeChunk[$j]]=='Y'){
							for($y = 0; $y < count($AllYear); $y++){
								foreach($houtype as $houtypes){  
									$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
									if($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='EL'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_LH_'.$AllYear[$y]] ;
									}elseif($houtypes[$houendtypes['id']]=='Y' and $houtypes['id']=='FF'){
										$fhou_data[$IN] = $hou_data[$TypeChunk[$j].'_FF_'.$AllYear[$y]] ;
									}
								}	
							}
						}
					}
				}
			}
		}
	}
	
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Hou' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); //Housing types
			for($j = 0; $j < count($TypeChunk); $j++){ 
				if($j==0){
					$bed = new Data($caseStudyId,$bexml);
				}else{
					$bed = new Data($caseStudyId,$blxml);
				}
					$beData = $bed->getByField(1,'SID');
				if($abData[$TypeChunk[$j].'_A']){
					for($y = 0; $y < count($AllYear); $y++){
						foreach($houtype as $houtypes){
							foreach($houendtype as $houendtypes){
								
								$IN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
								$YN = $TypeChunk[$j].'_'.$houendtypes['id'].'_'.$AllYear[$y];
								$ED = $TypeChunk[$j].'_'.$houtypes['id'].'_'.$AllYear[$y];
								$YD = $TypeChunk[$j].'_'.$AllYear[$y];
								$SD = $houendtypes['id'].'_'.$houtypes['id'].'_'.$AllYear[$y];
								$HD = $houendtypes['id'].'_'.$AllYear[$y];
								$FD = $houtypes['id'].'_'.$AllYear[$y];
								$fhouIN=NAN($fhou_data[$IN]);
								$fhou_data[$ED] = NAN($fhou_data[$ED]) + $fhouIN;
								$fhou_data[$YN] = NAN($fhou_data[$YN]) + $fhouIN;
								$fhou_data[$SD]	= NAN($fhou_data[$SD]) + $fhouIN;
								$fhou_data[$YD]	= NAN($fhou_data[$YD]) + $fhouIN;
								$fhou_data[$FD]	= NAN($fhou_data[$FD]) + $fhouIN;//zz
								$fhou_data[$HD]	= NAN($fhou_data[$HD]) + $fhouIN;
								$fhou_data['T_'.$AllYear[$y]] = NAN($fhou_data['T_'.$AllYear[$y]]) + $fhouIN;
								
							}
						}
					}
				}
			}
		}
	}
	//FINAL
	foreach($houtype as $houtypes){
		for($y = 0; $y < count($AllYear); $y++){
			$FD = $houtypes['id'].'_'.$AllYear[$y];
			$ha=NAN($final_data[$houtypes['id'].'_'.$AllYear[$y]]);
			$hb=NAN($fhou_data[$FD]);
			$final_data[$houtypes['id'].'_'.$AllYear[$y]] = $ha+$hb;
			//$final_data[$houtypes['id'].'_'.$AllYear[$y]] + $fhou_data[$FD];
		}
	}
	$fhou_data['SID'] = 15;
	$amd->add(checkIsNaN($fhou_data));
	
	//sh 15.02.2019
	$unittype=$unittype_original;
	
	//Table 17-7 Useful energy demand of Motor fuels
	foreach($maintype as $maintypes){ 
		if($maintypes['id']=='Ser' ){
			$abxml = $maintypes['id'];
			$abd = new Data($caseStudyId,'sectors_data');
			$abData = $abd->getByField($abxml,'SID');
			$TypeChunk = explode(",",$abData[$abxml.'_A']); 
			foreach($serendtype as $serendtypes){
				if($serendtypes['id']!='SH' and $serendtypes['id']!='AC'){
					for($j = 0; $j < count($TypeChunk); $j++){ 
						for($y = 0; $y < count($AllYear); $y++){ 
							$sid = $serendtypes['id'].'_'.$TypeChunk[$j].'_'.$AllYear[$y];
							$serv_data[$sid] = ($gdp_data[$TypeChunk[$j].'_'.$AllYear[$y]] * $bhData[$sid])*$unittype ;
							$serv_data[$serendtypes['id'].'_'.$AllYear[$y]] = $serv_data[$serendtypes['id'].'_'.$AllYear[$y]]+$serv_data[$sid];
						}
					}	
				}
			}
		} 
	}
	for($y = 0; $y < count($AllYear); $y++){ 
		$serv_data['LF_'.$AllYear[$y]] = $dem_data['ALF_'.$AllYear[$y]] * $bgData['B_LF_'.$AllYear[$y]]/100 ;
		$serv_data['FA_'.$AllYear[$y]] = $serv_data['LF_'.$AllYear[$y]] * $bgData['B_FA_'.$AllYear[$y]] ;
		$serv_data['TA_'.$AllYear[$y]] = $serv_data['FA_'.$AllYear[$y]] * $bgData['F_SA_'.$AllYear[$y]]/100*$bgData['F_AA_'.$AllYear[$y]]/100;
		//sh 15.02.2019
		$unittype=$unittypedefault;
		$serv_data['SH_'.$AllYear[$y]] = $serv_data['TA_'.$AllYear[$y]] * $bgData['F_SS_'.$AllYear[$y]]*$unittype/1000;
		$serv_data['AC_'.$AllYear[$y]] = $serv_data['FA_'.$AllYear[$y]] * $bgData['F_AC_'.$AllYear[$y]]/100*$bgData['F_SC_'.$AllYear[$y]]*$unittype/1000;
		//sh 15.02.2019
		$unittype=$unittype_original;
	}

	foreach($serendtype as $serendtypes){
		for($y = 0; $y < count($AllYear); $y++){ 
			$sid = $serendtypes['id'].'_'.$AllYear[$y];
			$serv_data['T_'.$sid] = $serv_data[$sid];
			$serv_data['T_'.$AllYear[$y]] = NAN($serv_data['T_'.$AllYear[$y]]) + NAN($serv_data['T_'.$sid]);
		}
	}

	foreach($serendtype as $serendtypes){
		if($serendtypes['id']=='TU'){
			foreach($sertype as $sertypes){
				for($y = 0; $y < count($AllYear); $y++){
					$AN = 'P_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y];	
					$BN = 'P_SH_'.$sertypes['id'].'_'.$AllYear[$y];	
					$CN = 'E_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y];	
					$CN1 = 'E_SH_'.$sertypes['id'].'_'.$AllYear[$y];	

					$sid = $serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y];	//TU_EL_2010
					if($serendtypes['id']=='TU' and( $sertypes['id']=='TF' or $sertypes['id']=='MB')){
						//s.h. 08.07.2019 add $CN1 
						//$serv_data[$sid] = (($serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]] * $biData[$AN])+($serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]))/$biData[$CN];
						$tutfa=$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						$tutfb=$biData[$AN];
						$tutfc=$biData[$CN];

						$shtfa=$serv_data['T_SH_'.$AllYear[$y]];
						$shtfb=$biData[$BN];
						$shtfc=$biData[$CN1];

						$tutf=($tutfa*$tutfb)/$tutfc;
						$shtf=($shtfa*$shtfb)/$shtfc;
						$tutf1=(is_nan($tutf) ? 0 : $tutf);
						$shtf1=(is_nan($shtf) ? 0 : $shtf);

						$tutf=$tutf1+$shtf1;
						$serv_data[$sid]=$tutf;
						// $serv_data[$sid] = (
						// 	$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]] * $biData[$AN]
						// 	)
						// 	/$biData[$CN]
						// 	+
						// 	(
						// 		$serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]
						// 	)
						// 	/$biData[$CN1];
					}
					elseif($serendtypes['id']=='TU' and $sertypes['id']=='EL'){
						//=+'Service-useful'!D28*Services!D40/100*(1-Services!D41/100*(1-1/Services!D50))+'Service-useful'!D32*Services!D60/100
						
						$HN = 'PH_SH_'.$sertypes['id'].'_'.$AllYear[$y];
						
                        //vk 06092016 COP of SH should be used in calculation of TU_El thermal use Electricity
                        //new variable $CN1 is created for that purpose	  
						
						//$serv_data[$sid] = ($serv_data['T_SH_'.$AllYear[$y]]*$biData[$BN]/100*(1-$biData[$HN]/100*(1-1/$biData[$CN])))+$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]]*$biData[$AN]/100;
						$tuela=$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						$tuelb=$biData[$AN]/100;

						$shela=$serv_data['T_SH_'.$AllYear[$y]];
						$shelb=$biData[$BN]/100;
						$shelc=(1-$biData[$HN]/100*(1-1/$biData[$CN1]));

						$tuel=($tuela*$tuelb);
						$shel=($shela*$shelb)*$shelc;
						$tuel1=(is_nan($tuel) ? 0 : $tuel);
						$shel1=(is_nan($shel) ? 0 : $shel);

						$tuel=$tuel1+$shel1;
						$serv_data[$sid]=$tuel;
						
						// // $serv_data[$sid] = 
						// // (
						// // 	$serv_data['T_SH_'.$AllYear[$y]]*$biData[$BN]/100*
						// // (
						// // 	1-$biData[$HN]/100*(1-1/$biData[$CN1])
						// // )
						// // )
						// // +
						// // $serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]]*$biData[$AN]/100;                 

                        //$serv_data[$sid] = ($serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]/100*(1-$biData[$HN]/100*(1-1/$biData[$CN])));		
					}
					elseif($serendtypes['id']=='TU' and $sertypes['id']=='DH'){
						$tudha=$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						$tudhb=$biData[$AN]/100;

						$shdha=$serv_data['T_SH_'.$AllYear[$y]];
						$shdhb=$biData[$BN]/100;

						$tudh=($tudha*$tudhb);
						$shdh=($shdha*$shdhb);
						$tudh1=(is_nan($tudh) ? 0 : $tudh);
						$shdh1=(is_nan($shdh) ? 0 : $shdh);

						$tushdh=$tudh1+$shdh1;
						$serv_data[$sid]=$tushdh;
						// // $serv_data[$sid] = (
						// // 	$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]] * $biData[$AN]/100
						// // 	)
						// // 	+
						// // 	(
						// // 		$serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]/100
						// // );
						
					}
					elseif($serendtypes['id']=='TU' and $sertypes['id']=='SO'){
						//s.h. 26.08.2020. 
						// $serv_data[$sid] = (
						// 	($serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]] * $biData[$AN]/100)
						// 		+($serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]/100)
						// 	)
						// 	*$biData['L_E_'.$serendtypes['id'].'_MF_'.$AllYear[$y]]/100*$biData[$CN]/100;
						$tusoa=$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						$tusob=$biData[$AN]/100;
						$tusoc=$biData['L_E_'.$serendtypes['id'].'_MF_'.$AllYear[$y]]/100;
						$tusod=$biData[$CN]/100;

						$shsoa=$serv_data['T_SH_'.$AllYear[$y]];
						$shsob=$biData[$BN]/100;
						$shsoc=$biData['L_E_SH_MF_'.$AllYear[$y]]/100;
						$shsod=$biData[$CN1]/100;

						$tuso=($tusoa*$tusob)*$tusoc*$tusod;
						$shso=($shsoa*$shsob)*$shsoc*$shsod;
						$tuso1=(is_nan($tuso) ? 0 : $tuso);
						$shso1=(is_nan($shso) ? 0 : $shso);

						$tushso=$tuso1+$shso1;
						$serv_data[$sid] = $tushso;
							// // $serv_data[$sid] =
							// // ( 
							// // 	($serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]] * $biData[$AN]/100)
							// // 	*$biData['L_E_'.$serendtypes['id'].'_MF_'.$AllYear[$y]]/100*$biData[$CN]/100
							// // )
							// // +
							// // (
							// // 	($serv_data['T_SH_'.$AllYear[$y]]* $biData[$BN]/100)
							// // 	*$biData['L_E_SH_MF_'.$AllYear[$y]]/100*$biData[$CN1]/100
							// // );
												
					}
					elseif($serendtypes['id']=='TU' and $sertypes['id']=='FF'){
						//=('US_SS-D'!D78*('SS_Fac-D'!D12/100+'SS_Fac-D'!D29/100*'SS_Fac-D'!D11/100*(1-'SS_Fac-D'!D30/100))/'SS_Fac-D'!D27*100)+('US_SS-D'!D82*('SS_Fac-D'!D21/100+'SS_Fac-D'!D29/100*'SS_Fac-D'!D20/100*(1-'SS_Fac-D'!D30/100))/'SS_Fac-D'!D27*100)
				
						$sh=($serv_data['T_SH_'.$AllYear[$y]]* 
							(
							// $biData[$BN]/100+
							// $biData['L_E_'.$serendtypes['id'].'_MF_'.$AllYear[$y]]/100*
							// $biData['P_SH_SO_'.$AllYear[$y]]/100*
							// (1-$biData['E_'.$serendtypes['id'].'_SO_'.$AllYear[$y]]/100)
							//s.h. 10.07.2020. $CN to $CN1 and SH variables not TU
							$biData[$BN]/100+
							$biData['L_E_SH_MF_'.$AllYear[$y]]/100* //Low rise buildings SH
							$biData['P_SH_SO_'.$AllYear[$y]]/100*  // Soft Solar Systems
							(1-NAN($biData['E_SH_SO_'.$AllYear[$y]])/100) //Thermal solar share
							)/$biData[$CN1]*100
						);
						$shh=NAN($sh);
					
						$tu=$serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]]*
							(
								$biData[$AN]/100+
								$biData['L_E_'.$serendtypes['id'].'_MF_'.$AllYear[$y]]/100*
								$biData['P_'.$serendtypes['id'].'_SO_'.$AllYear[$y]]/100*
								(1-NAN($biData['E_'.$serendtypes['id'].'_SO_'.$AllYear[$y]])/100)
							)
							/$biData[$CN]*100;
						$tuu=NAN($tu);
							
						$serv_data[$sid] = $tuu+$shh;
						
					}
				
				}
			}
		}
		elseif($serendtypes['id']=='AC'){
			foreach($sertype as $sertypes){
				if($sertypes[$serendtypes['id']]=='Y'){
				
					for($y = 0; $y < count($AllYear); $y++){
					$AN = 'P_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y];
					$BN = 'E_'.$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y];	
						$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]] = $serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]]*$biData[$AN]/100/$biData[$BN];
						
					}
				}
			}
		}
		elseif($serendtypes['id']=='AP'){
			foreach($sertype as $sertypes){
				if($sertypes[$serendtypes['id']]=='Y'){
					for($y = 0; $y < count($AllYear); $y++){
						$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]] = $serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						
					}
				}	
			}
		}
		elseif($serendtypes['id']=='MP'){
			foreach($sertype as $sertypes){
				if($sertypes[$serendtypes['id']]=='Y'){
					for($y = 0; $y < count($AllYear); $y++){
						$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]] = $serv_data['T_'.$serendtypes['id'].'_'.$AllYear[$y]];
						
					}
				}	
			}
		}
		
	}
	foreach($sertype as $sertypes){
		foreach($serendtype as $serendtypes){
			if($sertypes[$serendtypes['id']]=='Y'){
				for($y = 0; $y < count($AllYear); $y++){
					//zz
					$se1=$serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]];
					$see1=(is_nan($se1) ? 0 : $se1);
					$se2=$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]];
					$see2=(is_nan($se2) ? 0 : $se2);
					$serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]] = $see1+$see2;
					//$serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]] +$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]];
					
					$tofa=$serv_data['ToF_'.$serendtypes['id'].'_'.$AllYear[$y]]; 
					$tofb=$serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]];
					$tofaa=(is_nan($tofa) ? 0 : $tofa);
					$tofbb=(is_nan($tofb) ? 0 : $tofb);
					$serv_data['ToF_'.$serendtypes['id'].'_'.$AllYear[$y]] = $tofaa+$tofbb;
					//$serv_data['ToF_'.$serendtypes['id'].'_'.$AllYear[$y]] + $serv_data[$serendtypes['id'].'_'.$sertypes['id'].'_'.$AllYear[$y]];
					
					
				}
			}	
		}
	}
	
	foreach($sertype as $sertypes){
		for($y = 0; $y < count($AllYear); $y++){
			$tof1=$serv_data['ToF_'.$AllYear[$y]];
			$tof2=$serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]];	
			$tof11=(is_nan($tof1) ? 0 : $tof1);
			$tof22=(is_nan($tof2) ? 0 : $tof2);
			$serv_data['ToF_'.$AllYear[$y]] = $tof11+$tof22;
			//$serv_data['ToF_'.$AllYear[$y]] + $serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]];							
			//Final 
			$fin1=$final_data[$sertypes['id'].'_'.$AllYear[$y]];
			$fin2=$serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]];
			$fin11=(is_nan($fin1) ? 0 : $fin1);
			$fin22=(is_nan($fin2) ? 0 : $fin2);
			$final_data[$sertypes['id'].'_'.$AllYear[$y]] = $fin11+$fin22;
			//$final_data[$sertypes['id'].'_'.$AllYear[$y]] + $serv_data['F_'.$sertypes['id'].'_'.$AllYear[$y]];
			
					
		}
	}
	$serv_data['SID'] = 16;
	$amd->add(checkIsNaN($serv_data));
	
	foreach($pentype as $pentypes){ 
		for($y = 0; $y < count($AllYear); $y++){
		$final_data['T_'.$AllYear[$y]] = (is_nan($final_data['T_'.$AllYear[$y]]) ? 0 : $final_data['T_'.$AllYear[$y]]) + (is_nan($final_data[$pentypes['id'].'_'.$AllYear[$y]]) ? 0 : $final_data[$pentypes['id'].'_'.$AllYear[$y]]);
		}
	}
	
	for($y = 0; $y < count($AllYear); $y++){
		//$final_data['FCAP_'.$AllYear[$y]] = $final_data['T_'.$AllYear[$y]]/$acData['Pop_'.$AllYear[$y]]/$unittype;
		$final_data['FCAP_'.$AllYear[$y]] = $final_data['T_'.$AllYear[$y]]/($acData['Pop_'.$AllYear[$y]]*$popmultiple)/$unittype;
		$final_data['FGDP_'.$AllYear[$y]] = $final_data['T_'.$AllYear[$y]]/$adData['GDP_'.$AllYear[$y]]/$unittype;
	}
		
	for($y = 0; $y < count($AllYear); $y++){
		//Table 20-3 Final energy demand by sector
		$final_data['Ind_'.$AllYear[$y]] = $tmanf_data['A_'.$AllYear[$y]];
		$final_data['Man_'.$AllYear[$y]] = $tmanf_data['Y_'.$AllYear[$y]];
		$final_data['ACM_'.$AllYear[$y]] = $acm_data['TACM_'.$AllYear[$y]];
		$final_data['Trp_'.$AllYear[$y]] = $trpt_data['TED_'.$AllYear[$y]];
		$final_data['Fre_'.$AllYear[$y]] = $fkm_data['TFu_'.$AllYear[$y]];
		$final_data['Pass_'.$AllYear[$y]] = $inte_data['TPass_'.$AllYear[$y]];
		$final_data['Hou_'.$AllYear[$y]] = $fhou_data['T_'.$AllYear[$y]];
		$final_data['Ser_'.$AllYear[$y]] = $serv_data['ToF_'.$AllYear[$y]];
		$final_data['Tot_'.$AllYear[$y]] = 
		  (is_nan($final_data['Ind_'.$AllYear[$y]]) ? 0 : $final_data['Ind_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Trp_'.$AllYear[$y]]) ? 0 : $final_data['Trp_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Hou_'.$AllYear[$y]]) ? 0 : $final_data['Hou_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Ser_'.$AllYear[$y]]) ? 0 : $final_data['Ser_'.$AllYear[$y]]);
	
	}
	foreach($pentype as $pentypes){ 
		for($y = 0; $y < count($AllYear); $y++){
		//Table 20-4 Traditional fuels by sector
		$pid = $pentypes['id'];
		$final_data['Ind_'.$pid.'_'.$AllYear[$y]] = $tmanf_data['A_'.$pid.'_'.$AllYear[$y]]+0;
		$final_data['Man_'.$pid.'_'.$AllYear[$y]] = $tmanf_data[$pid.'_'.$AllYear[$y]]+0;
		$final_data['ACM_'.$pid.'_'.$AllYear[$y]] = $acm_data[$pid.'_'.$AllYear[$y]]+0;
		$final_data['Trp_'.$pid.'_'.$AllYear[$y]] = $trpt_data[$pid.'_'.$AllYear[$y]]+0;
		$final_data['Fre_'.$pid.'_'.$AllYear[$y]] = $fkm_data[$pid.'_'.$AllYear[$y]]+0;
		$final_data['Pass_'.$pid.'_'.$AllYear[$y]] = $inte_data['Mi_'.$pid.'_'.$AllYear[$y]]+0;
		$final_data['Hou_'.$pid.'_'.$AllYear[$y]] = $fhou_data[$pid.'_'.$AllYear[$y]]+0;
		$final_data['Ser_'.$pid.'_'.$AllYear[$y]] = $serv_data['F_'.$pid.'_'.$AllYear[$y]]+0;	
		
		$final_data['Tot_'.$pid.'_'.$AllYear[$y]] = 
		  (is_nan($final_data['Ind_'.$pid.'_'.$AllYear[$y]]) ? 0 : $final_data['Ind_'.$pid.'_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Trp_'.$pid.'_'.$AllYear[$y]]) ? 0 : $final_data['Trp_'.$pid.'_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Hou_'.$pid.'_'.$AllYear[$y]]) ? 0 : $final_data['Hou_'.$pid.'_'.$AllYear[$y]]) 
		+ (is_nan($final_data['Ser_'.$pid.'_'.$AllYear[$y]]) ? 0 : $final_data['Ser_'.$pid.'_'.$AllYear[$y]]);
		}
	}
	
	
			
	$final_data['SID'] = 17;
	$amd->add(checkIsNaN($final_data));

function logvar($varname, $varvalue, $isarray){
	$log = fopen("log.txt", "a") or die("Unable to open file!");
	if($isarray){
		fwrite($log, $varname.":\n");
		fwrite($log, print_r($varvalue, TRUE));
		fwrite($log,"\n");
	}else{
		fwrite($log, $varname.'='.$varvalue."\n");
	}
	
	fclose($log);
}

function checkIsNaN($array){
	// foreach ($array as $key => $value) {
	// 	if ((string)$value=="NAN" || (string)$value=="INF"){
	// 		$array[$key]=0;
	// 	}else{
	// 		$array[$key]=floatval(number_format($value,10));
	// 	}
	// }
	return $array;
}

function NAN($number){
	 $nan=(is_nan($number) ? 0 :  $number);
	 $inf=(is_infinite($nan) ? 0 :  $nan);
	 $null=(is_null($inf) ? 0 :  $inf);
	 return $null;
}

?>	
	

