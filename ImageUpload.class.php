<?php
/**
 * Images upload
 *
 * requere FileUpload class
 */

class ImageUpload extends FileUpload{
	
	private $iMaxWidth 	= 100;
	private $iMaxHeight = 100;
	private $iMinWidth 	= 100;
	private $iMinHeight = 100;
	
	/**
	 * @param Array $files
	 */
	public function __construct($files){
		parent::__construct($files);
		$this->setValidTypes(array('jpg','jpeg','png','gif'));
	}
	
	/**
	 * set available images sizes
	 * 
	 * @param int $iMinWidth
	 * @param int $iMinHeight
	 * @param int $iMaxWidth
	 * @param int $iMaxHeight
	 */
	public function setSizes($iMinWidth, $iMinHeight, $iMaxWidth, $iMaxHeight ){
		$this->iMinWidth = $iMinWidth;
		$this->iMinHeight = $iMinHeight;
		$this->iMaxWidth = $iMaxWidth;
		$this->iMaxHeight = $iMaxHeight; 
	}
	
	
	public function checkAllSizes(){
    	if (is_array($this->files) && !empty($this->files)){
    		$files = 0;
    		foreach ($this->files as $key => $item){
    			if (!empty($item['tmp_name'])){ 
	    			if ( !$this->checkSizes($item['tmp_name'])){
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
	
	public function getSizes(){
		return array(
			'minWidth' 	=> $this->iMinWidth,
			'minHeight' => $this->iMinHeight,
			'maxWidth' 	=> $this->iMaxWidth,
			'maxHeight' => $this->iMaxHeight
		);
	}
	
	private function checkSizes($image_path){
		$info = array();
		$sizes = getimagesize($image_path,$info);
		if ($sizes){
			if ( $sizes[0]>=$this->iMinWidth && $sizes[0]<=$this->iMaxWidth
				 && $sizes[1]>=$this->iMinHeight && $sizes[1]<=$this->iMaxHeight	 
				){
					return true;
			}
		}else {
			return false;
		}
	}
	
}
?>
