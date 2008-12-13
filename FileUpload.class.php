<?php
/**
 * File upload class
 *
 */
class FileUpload {
	
	const UPLOAD_ERROR_DIR_NOT_FOUND = 1;
	const UPLOAD_ERROR = 2;
	const UPLOAD_OK = 1;
	
	public $files = null;
	public $validtypes = array('jpg');
	
	public $uploaded_files = array();
	/**
	 * @param Array $file_ref
	 */
    public function __construct(&$file_ref) {
    	$this->files = $file_ref;
    }
    
    /**
     * set available files extensions
     *
     * @param Array $types
     */
    public function setValidTypes($types){
    	$this->validtypes = $types;
    }
    
    

    /**
     * check all files, i one file is invalid return false
     * 
     * @return boolean
     */
    public function checkAllFiles(){
    	if (is_array($this->files) && !empty($this->files)){
    		$files = 0;
    		foreach ($this->files as $key => $item){
    		
    			if (!empty($item['name'])){ 
	    			if ( !$this->checkFile($item['name'])){
	    				return false;
	    			}else {
	    				$files++;
	    			}
    			}
    		}
    		if ($files>0){
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * save all files to directory
     *
     * @param string $dir
     * @param boolean $replace
     * @return int
     */
    public function saveTo($dir, $replace = false){
    	$errors = array();
    	if (file_exists($dir)){
    		if (is_array($this->files)){
	    		foreach ($this->files as $key => $item){
	    			if ($this->checkFile($item['name'])){
	    				$item['name'] = $this->prepareFileName($item['name']);
	    				if (file_exists($dir.'/'.$item['name']) && !$replace){ // jei toks failas jau egsistuoja keiciam pavadinima
	    					$item['name'] = time().$item['name'];
	    				}
	    				
	    				if (move_uploaded_file($item['tmp_name'],$dir.'/'.$item['name'])){
	    					chmod($dir.'/'.$item['name'], 0666);
	    					$this->uploaded_files[] = array(
	    						'path' => realpath($dir.'/'.$item['name']),
	    						'name' => $item['name']
	    					);
	    				}else {
	    					$errors[] = FileUpload::UPLOAD_ERROR;
	    				}
	    			}			
	    		}
    		}
    		if (!empty($errors)){
    			return FileUpload::UPLOAD_ERROR;
    		}else {
    			return FileUpload::UPLOAD_OK;
    		}
    	}else {
    		return FileUpload::UPLOAD_ERROR_DIR_NOT_FOUND; 
    	}
    }
    
    /**
     * create directory if not exists
     */
    public function createDirIfNotExists($dir){
    	
        if (!file_exists($dir)){
    		if (mkdir ($dir,0755,true)){
    			return true;
    		}else {
    			return false;
    		}
    	}
    	return true; 
    }
    
    /**
     * get uploaded files info
     */
    public function getUploadedFilesInfo(){
    	return $this->uploaded_files;
    }
    
    
    /**
     * check one file
     */
    private function checkFile($name){
    	if (preg_match('/\.('.implode('|',$this->validtypes).')$/i',$name)){
    		return true;
    	}else {
    		return false;
    	}
    }

    /**
     *prepare file namee
     */
    private function prepareFileName($name){
    	  $matches = preg_split('/\./',$name);
    	  $filename_ext = $matches[count($matches)-1];
    	  unset($matches[count($matches)-1]);
    	  $filename_body = implode('_',$matches);
    	  $name = prepare_urlname($filename_body).'.'.$filename_ext;
    	  return $name;
    }
}
?>
