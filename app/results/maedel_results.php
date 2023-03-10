<?php 
require_once "../../config.php";
require_once CLASS_PATH."Data.class.php";
require_once BASE_PATH."general.php";

    $results['maintype'] =  $maintype;
    $results['allyear'] =  $AllYear;
    $results['casestudyid'] = $caseStudyId;  
    $results['defaultunit'] =  $DefaultUnit; 
    $results['user_path']=USER_CASE_PATH;

    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];
    $results['datanotes']=$dataNotes[$action];
    switch($id){
        case 'result_summary':
            if($action=='get'){
                $xml= new Data($caseStudyId,$cdxml);
                $cdData = $xml->getByField(1,'SID');
                $results['cdData']=$cdData;
                $xmlce= new Data($caseStudyId,$cexml);
                $ceData = $xmlce->getByField(1,'SID');
                $results['ceData']=$ceData;
                $xmlcr= new Data($caseStudyId,$crxml);
                $crData = $xmlcr->getByField(1,'SID');
                $results['crData']=$crData;
                $xmlcq= new Data($caseStudyId,$cqxml);
                $cqData = $xmlcq->getByField(1,'SID');
                $results['cqData']=$cqData;
                }
        break;

        case 'result_hourly_load':
            if($action=='get'){
                $sectors= array();
                $xmlcr= new Data($caseStudyId,$crxml);
                $crData = $xmlcr->getByField(1,'SID');
                for($k=0; $k<count($AllYear); $k++){
                    $hours[$AllYear[$k]]=$crData['yr_'.$AllYear[$k]];
                }
                foreach($maintype as $maintypes){ 
                    $abxml = $maintypes['id'];
                    $abd = new Data($caseStudyId,'sectors_data');
                    $abData = $abd->getByField($abxml,'SID');
                    $TypeChunk = explode(",",$abData[$abxml.'_A']);
                    $row['id']=$maintypes['id'];
                    $row['autoid']=$abData['id'];
                    $row['item']=$maintypes['value'];
                    $subsectors=array();
                    for($j = 0; $j < count($TypeChunk); $j++){ 
                        $row1=array();
                        $row1['id']=$TypeChunk[$j];
                        $row1['clientname']=$abData[$TypeChunk[$j]];
                        array_push($subsectors,$row1);
                    } 
                    $row['clients']=$subsectors;
                    array_push($sectors,$row);
                }
                $results['sectors']=$sectors;
                $results['hours']=$hours;
                $path=USER_CASE_PATH.$caseStudyId."/result/cal_HD.json";
                $results['data']=[];
                if(file_exists($path)){
                    $str = file_get_contents($path);
                    $json = json_decode($str, true);
                    $results['data']=$json;
                }
                }
        break;

        case 'result_chart':
            if($action=='get'){
                $xmlcr= new Data($caseStudyId,$crxml);
                $crData = $xmlcr->getByField(1,'SID');
                $xmlcr= new Data($caseStudyId,$crxml);
                $crData = $xmlcr->getByField(1,'SID');
                for($k=0; $k<count($AllYear); $k++){
                    $hours[$AllYear[$k]]=$crData['yr_'.$AllYear[$k]];
                }
                $results['hours']=$hours;
                $path=USER_CASE_PATH.$caseStudyId."/result/cal_electricitydemand.json";
                $results['data']=[];
                if(file_exists($path)){
                    $str = file_get_contents($path);
                    $json = json_decode($str, true);
                    $results['data']=$json;
                }
                }
        break;

        case 'result_wasp':
            $cdd = new Data($caseStudyId,$cdxml); //calendar_data
            $cdData = $cdd->getByField(1,'SID');  //calendar_data
            $cqd = new Data($caseStudyId,$cqxml); //summary data
            $cqData = $cqd->getByField(1,'SID');  //summary data
            $crd = new Data($caseStudyId,$crxml); //cal_calendar
            $crData = $crd->getByField(1,'SID');  //cal_calendar
            
            $myfile = fopen(DATA_FILE_PATH.'wasp/' . $caseStudyId . ".dat", "w") or die("Unable to open file!");
            $line1 = $caseStudyId. "\r\n";
            
            //number of season
            $numberofseasons=$cdData['nseason'];
            $numberofseasons_=str_pad($numberofseasons, 4, " ", STR_PAD_LEFT);
            $line2=$numberofseasons_."  50   1\r\n";
            fwrite($myfile, $line1);
            fwrite($myfile, $line2);
            
            for($j = 0; $j < count($AllYear); $j++){
                $cod = new Data($caseStudyId,$coxml); //electricity demand
                $coData = $cod->getByField($AllYear[$j],'SID');  //electricity demand
                //line3
                $max_load=number_format((double) $cqData['MD_'.$AllYear[$j]],1,'.','');
                $max_load_=str_pad($max_load, 8, " ", STR_PAD_LEFT);
                $year=str_pad($AllYear[$j], 6, " ", STR_PAD_LEFT);
                fwrite($myfile, $max_load_.$year."\r\n");
                //line4
                fwrite($myfile,"   2"."\r\n");
                
                $line5="";
                for($l = 1; $l <=$numberofseasons ; $l++){
                    $line5.=str_pad(number_format((double) $cqData['RelAP_'.$AllYear[$j].'_'.$l],4,'.',''),8," ",STR_PAD_LEFT);
                }
            
                $line5_=chunk_split($line5, 80, "\r\n");
                fwrite($myfile, $line5_);
                fwrite($myfile, "   4"."\r\n");
                fwrite($myfile, $numberofseasons_."\r\n");
            
                for($l = 1; $l <=$numberofseasons ; $l++){
                    $start_season_hh=$crData['S_HH_'.$AllYear[$j].'_season_'.$l];
                    $end_season_hh=$crData['E_HH_'.$AllYear[$j].'_season_'.$l];
                    $totalhoursinseason=$crData['HH_'.$AllYear[$j].'_'.$l];
                    unset($coData['id']);
                    unset($coData['SID']);
                    $electricitydemand_season=array_slice($coData,$start_season_hh-1,$totalhoursinseason);
                    rsort($electricitydemand_season,1);
                            
                    $minv= $electricitydemand_season[$totalhoursinseason-1];
                    $maxv=$electricitydemand_season['0'];
                    $step=($maxv-$minv)/100;
                    $tresh=(float)$electricitydemand_season['0'];
                    $k=0;
                    $srtldc[$k]=$electricitydemand_season['0'];
                    $srthrs[$k]=1;
                    for ($e=1; $e<count($electricitydemand_season); $e++)
                    {
                        //echo $electricitydemand_season[$e]."<br/>";
                        $val=(float)$electricitydemand_season[$e];
                        $val1=(float)$tresh - (float)$step;
                         If ( $val<$val1)  {
                            $k = $k + 1;
                            $srtldc[$k] = (float)$electricitydemand_season[$e];
                            $srthrs[$k] = (int)$srthrs[$k - 1] + 1;
                            $tresh = (float)$srtldc[$k];
                         }else{
                            $srtldc[$k] =((float)$srtldc[$k] + (float)$electricitydemand_season[$e])/2;
                            $srthrs[$k] = (int)$srthrs[$k] + 1;
                        }  
                    }
                            
                    $fld2[0]=0.00000;
                    $fld1[0]=1.00000;
            
                     for ($n = 0; $n<count($srtldc); $n++) {
                            $fld1[$n+1]=$srtldc[$n]/$srtldc[0];
                            $fld2[$n+1]=$srthrs[$n]/$srthrs[count($srtldc)-1];
                        }

                    $number=count($fld2);
                    $nocof=str_pad($number,4," ",STR_PAD_LEFT);
                    fwrite($myfile, $nocof."\r\n");
                    for($n=0; $n<count($fld2);$n++)
                    {
                        $ld=str_pad(number_format((double)$fld1[$n],5,'.',''),10," ", STR_PAD_LEFT);
                        $dur=str_pad(number_format((double)$fld2[$n],5,'.',''),10," ",STR_PAD_LEFT);
                        fwrite($myfile, $ld.$dur."\r\n");
                    }
                    unset($fld1);
                    unset($fld2);
                    unset($srtldc);
                    unset($srthrs);
                }
            fwrite($myfile,"   1\r\n");
            }
            fclose($myfile);
            $filename=$caseStudyId.".dat";
            $filedate=date("d/m/Y H:i:s",filemtime(DATA_FILE_PATH."wasp/".$filename));
        break;
    }
    echo (json_encode($results));
?>
