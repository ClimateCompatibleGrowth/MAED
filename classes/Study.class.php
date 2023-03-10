<?php 
class Study{
    private $maedtype;
    public function __construct($maedtype){    
        $this->maedtype=$maedtype;
	}

	public static function getAll(){
        if ($handle = opendir(USER_CASE_PATH)) {
            $caseStudies = array();
            $i=0;
            while (false !== ($studyName = readdir($handle))) {
                if ($studyName != '.' && $studyName != '..' && is_dir(USER_CASE_PATH."/".$studyName)) {
                    $createdDate=date("d-m-Y H:i:s",filemtime(USER_CASE_PATH."/".$studyName));
                    $caseStudies[$i]['studyName']=$studyName;
                    $caseStudies[$i]['createdDate']=$createdDate;
                    $i++;
                }
            }
            closedir($handle);
            return json_encode($caseStudies);
        }
    }

    public function create($data, $lang){
        if(isset($data)){      			               
            $xmlData = XML_FILE_HEAD;
            $xmlData .= '<row><id>1</id>';
            foreach($data as $key => $value){
                $xmlData .= "<$key>$value</$key>";
            }
            $xmlData .= '</row>';
            $destination = USER_CASE_PATH.$data['studyName'];
            if (!is_dir($destination)) { mkdir($destination,0777); }	
            $this->defaultFiles($this->maedtype, $lang, $destination);

            mkdir($destination.'/result',0777);
            $fileName = 'geninf_data';
            $fp = fopen($destination."/".$fileName.".".DATA_FILE_EXT,'w');
            fwrite($fp,$xmlData);
            fclose($fp);
        }
    }  

    public function backup($sourceName, $destinationName){
        $source = realpath(USER_CASE_PATH.$sourceName);
        $destination = BACKUP_PATH.$destinationName;
        $zip = new ZipArchive;
        $zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            $destionationNoExtension=str_replace('.zip', '', $destinationName);
            foreach ($files as $file){
                $file = realpath($file);
                if (is_dir($file) === true){
                    $zip->addEmptyDir( str_replace($source . '\\', '', $destionationNoExtension."/".$file . '\\'));
                }
                else if (is_file($file) === true){
                    $zip->addFromString( str_replace($source . '\\', '', $destionationNoExtension."/".$file), file_get_contents($file));
                }
            }
        return $zip->close();
    }

    public function restore($source, $destination){
        $zip = new ZipArchive;
        if ($zip->open($source) === TRUE) {
            $zip->extractTo($destination);
        }
        return $zip->close();
    }

    public function delete($path)
    {
        $dir=$path;
        if(is_file($path)){
            @unlink($path);
        }else{
        $dir = new RecursiveDirectoryIterator( 
            $dir, FilesystemIterator::SKIP_DOTS); 
        
        $dir = new RecursiveIteratorIterator( 
            $dir,RecursiveIteratorIterator::CHILD_FIRST); 
        
        foreach ( $dir as $file ) {  
            $file->isDir() ?  rmdir($file) : unlink($file); 
        } 
            if($path!=BACKUP_PATH && $path!="")
            rmdir($path);
        }
    }

    private function defaultFiles($maedtype, $lang, $destination){
        if($maedtype=='maedd'){
            copy(DATA_FILE_PATH."common/".$lang."/sectors_data.xml",$destination."/sectors_data.xml");
            copy(DATA_FILE_PATH."common/".$lang."/endtype.xml",$destination."/endtype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/enduse_data.xml",$destination."/enduse_data.xml");
            copy(DATA_FILE_PATH."common/".$lang."/dweltype.xml",$destination."/dweltype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/facmtype.xml",$destination."/facmtype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/fueltype.xml",$destination."/fueltype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/houendtype.xml",$destination."/houendtype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/houtype.xml",$destination."/houtype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/maintype.xml",$destination."/maintype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/pentype.xml",$destination."/pentype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/serendtype.xml",$destination."/serendtype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/sertype.xml",$destination."/sertype.xml");
            copy(DATA_FILE_PATH."common/".$lang."/unittype.xml",$destination."/unittype.xml");
        }
        else
        {
            copy(DATA_FILE_PATH."common/sectors_data.xml",$destination."/sectors_data.xml");
            copy(DATA_FILE_PATH."common/maintype.xml",$destination."/maintype.xml");
            copy(DATA_FILE_PATH."common/typedaydef_data.xml",$destination."/typedaydef_data.xml");
        }
    }

    public function copy($source, $destination)
    {
        if (is_file($source))
        {
            $c = copy($source, $destination);
            chmod($destination, 0777);
            return $c;
        }
        if (!is_dir($destination))
        {
            $oldumask = umask(0);
            mkdir($destination, 0777);
            umask($oldumask);
        }
        $dir = dir($source);
        while (false !== $entry = $dir->read())
        {
            if ($entry == "." || $entry == "..")
            {
                continue;
            }
            if ($dest !== "$source/$entry")
            {
                $this->copy("$source/$entry", "$destination/$entry");
            }
        }
        $dir->close();
        return true;
    }

    function allowedFiles($path)
    {
        $files = glob($path . '/*');
        $allowedFiles = array('xml', 'json', '');
        foreach ($files as $file)
        {
            if (!in_array(pathinfo($file, PATHINFO_EXTENSION) , $allowedFiles))
            {
                unlink($file);
            }
        }
    }
}
?>