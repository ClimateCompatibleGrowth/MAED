<?php

class Data {
	private $xmlFile; 
	private $objectName; 
	private $dom; 
	private $caseStudyId;
	private $autoIncrement; 
	
	public function __construct($caseStudyId, $objectName){
		$this->caseStudyId = $caseStudyId;
		$this->objectName = $objectName;
		$filechunk = explode("_", $objectName);
		$dir = USER_CASE_PATH.$this->caseStudyId;
		if (!is_dir($dir)) {
		mkdir($dir,0777);
		}
		if($filechunk[0] == 'cal'){
			$this->xmlFile =  USER_CASE_PATH.$this->caseStudyId.'/result/'.$this->objectName.'.'.DATA_FILE_EXT;
		}
		else{
			$this->xmlFile =  USER_CASE_PATH.$this->caseStudyId.'/'.$this->objectName.'.'.DATA_FILE_EXT;
		}

		if(!is_file($this->xmlFile)){
			$this->_create();
		}
		$this->dom = new DOMDocument();
		$this->dom->validateOnParse = true;
		$this->dom->load( $this->xmlFile);
		if($this->dom->getElementsByTagName('meta')->item(0)!=null)
			$this->autoIncrement = $this->dom->getElementsByTagName('meta')->item(0)->getElementsByTagName('autoIncrement')->item(0)->nodeValue;
	}

	private function _getById($id){
		$rows = $this->_getAll();
		foreach($rows as $row){
			$rowIds = $row->getElementsByTagName("id");
			$rowId = $rowIds->item(0)->nodeValue;
			if($id == $rowId){
				return $row;
			}
		}
		return false;
	}

	private function _getByField($id,$fld){
		$rows = $this->_getAll();
		foreach($rows as $row){
			$rowIds = $row->getElementsByTagName($fld);
			$rowId = $rowIds->item(0)->nodeValue;
			if($id == $rowId){
				return $row;
			}
		}
		return false;
	}

	private function _getAll(){
		return $this->dom->getElementsByTagName('row');
	}
	
	private function _getAutoIncrementId(){
		return $this->autoIncrement;
	}
	
	private function _setAutoIncrementId($id){
		$this->autoIncrement = $id;
		$oa = $this->dom->getElementsByTagName('meta')->item(0)->getElementsByTagName('autoIncrement')->item(0);
		//pr($oa->nodeValue);
		//echo $this->dom->getElementsByTagName('meta')->item(0)->nodeValue;exit;
		$na = $this->dom->createElement('autoIncrement');
		$na->appendChild($this->dom->createTextNode($id));
		$oa->parentNode->replaceChild($na, $oa);
	}
	
	private function _create(){
		$xmlData = XML_FILE_HEAD;
		$xmlData .= '<'.$this->objectName.'><meta><autoIncrement>0</autoIncrement></meta><data></data></'.$this->objectName.'>';
		$fp = fopen($this->xmlFile,'w');
		fwrite($fp,$xmlData);
		fclose($fp);
	}
	
	public function getByField($id,$fld){
		$row = $this->_getByField($id,$fld);
		$rowArr = array();
		
		$children = $row->childNodes; 
		for($i=0;$i<$children->length;$i++) {
			$child = $children->item($i);
			$rowArr[$child->nodeName] = $child->nodeValue;	
			if($child->nodeName == $fld){
				$id = $child->nodeValue;	
			}
		} 
		return $rowArr;
	}

	public function getRow(){
		$rows = $this->_getAll();
		foreach($rows as $row){
			$children = $row->childNodes; 
		}
		$rowArr = array();
		for($i=0;$i<$children->length;$i++) {
			$child = $children->item($i);
			$rowArr[$child->nodeName] = $child->nodeValue;	
			$id = $child->nodeValue;	
			
		} 
		return $rowArr;
	}

	/*
	@param - $data - associative array
	*/
	
	public function update($data){
		$row = $this->_getById($data['id']); //pr($row);
		if($row !== false){
			foreach($data as $key=>$value){
				$ns = $row->getElementsByTagName($key);
				$n = $ns->item(0);
				$a = $this->dom->createElement($key);  
				$a->appendChild($this->dom->createTextNode($value));
				if($n->parentNode!=null)
				$n->parentNode->replaceChild($a, $n)  ;
			}
		}
		$this->saveXML();
	}
	
	/*
	@param - $data - associative array
	*/	
	public function add($data){
		//get autoincrements id
		$id = $this->_getAutoIncrementId() + 1;
		$newRow = $this->dom->createElement("row");
		$n = $this->dom->createElement('id');  
		$n->appendChild($this->dom->createTextNode($id));
		$newRow->appendChild($n);

		foreach($data as $key=>$value){
			$n = $this->dom->createElement($key);  
			$n->appendChild($this->dom->createTextNode($value));
			$newRow->appendChild($n);
		}
		
		$data = $this->dom->getElementsByTagName('data');
		$data->item(0)->appendChild($newRow);
		$this->_setAutoIncrementId($id);
		$this->saveXML();
	}
	
	public function deleteByField($id,$fld){
		$n = $this->_getByField($id,$fld);
		if($n){
			$n->parentNode->removeChild($n);
		}
		$this->saveXML();
	}

	public function saveXML(){
		$this->dom->save($this->xmlFile);
	}
}

?>