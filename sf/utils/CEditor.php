<?php

include_once (SF_ROOT . "js/editor/ckeditor/ckeditor.php");
include_once (SF_ROOT . "js/editor/FCKeditor2.3b/fckeditor.php");
define ( "CK_BASE_PATH", SF_BASE_URL."js/editor/ckeditor/" );
define ( "FCK_BASE_PATH", SF_BASE_URL."js/editor/fckeditor/" );
define ( "NEW_VERSION", "vision_3_6_1" );

/**
 *  Class which will use fckeditor.
 * You can new a editor ,and set it to the view,so ,you can 
 *  call CEditor::GetEditor in the template to create a editor.
 */
class CEditor {
	var $base_path;
	var $oFCKeditor;
	var $editor;
	
	function CEditor($editor=0) {
		$this->editor = $editor;
		if($this->editor){
			$this->base_path = CK_BASE_PATH;
			$this->oFCKeditor = new CKEditor ( $this->base_path );
		}else{
			$this->base_path = FCK_BASE_PATH;
			$this->oFCKeditor = new FCKeditor ( "default" );
			$this->oFCKeditor->BasePath = $this->base_path;
			$this->oFCKeditor->Config ["SkinPath"] = FCK_BASE_PATH . "editor/skins/office2003/";
			$this->oFCKeditor->Config ["EditorAreaCSS"] = FCK_BASE_PATH . "editor/css/fck_editorarea.css";
			$this->oFCKeditor->Config ["SmileyPath"] = FCK_BASE_PATH . "editor/images/smiley/msn/";
		}
	}
	
	/**
	 *  $editor_name,the name you want to get the text value from the http post request.
	 *  $value,the editor init text.
	 *  $hight,editor hight
	 *  $width,editor width
	 */
	function GetEditor($editor_name, $value = "", $hight = 400, $width = "100%") {
		if($this->editor){
			$this->oFCKeditor->config=array('width'=>$width,'height'=>$hight,'toolbar'=>"Full");
			$this->oFCKeditor->editor($editor_name, $value, $this->config, '');
		}else{
			$this->oFCKeditor->Height = $hight;
			$this->oFCKeditor->Width = $width;
			$this->oFCKeditor->Value = $value;
			$this->setInstanceName ( $editor_name );
			$this->oFCKeditor->Create ();
		}
	}
	
	/**
	 *  Get Comment's simple editor.
	 */
	function GetCommentEditor($editor_name, $value = "", $hight = 200, $width = "90%") {
		if($this->editor){
			$this->oFCKeditor->config=array('width'=>$width,'height'=>$hight,'toolbar'=>"Basic");
			$this->oFCKeditor->editor($editor_name, $value, $this->config, '');
		}else{
			$this->oFCKeditor->Height = $hight;
			$this->oFCKeditor->Width = $width;
			$this->oFCKeditor->Value = $value;
			$this->oFCKeditor->ToolbarSet = "Basic";
			$this->setInstanceName ( $editor_name );
			$this->oFCKeditor->Create ();
		}
	}
	
	/**
	 *  Get Comment's simple editor.
	 */
	function GetNormalEditor($editor_name, $value = "", $hight = 200, $width = "90%") {
		if($this->editor){
			$this->oFCKeditor->config=array('width'=>$width,'height'=>$hight,'toolbar'=>"Normal");
			$this->oFCKeditor->editor($editor_name, $value, $this->config, '');
		}else{
			$this->oFCKeditor->Height = $hight;
			$this->oFCKeditor->Width = $width;
			$this->oFCKeditor->Value = $value;
			$this->oFCKeditor->ToolbarSet = "Normal";
			$this->setInstanceName ( $editor_name );
			$this->oFCKeditor->Create();
		}
	}
	
	/**
	 * Filter all java script and un balance tag.
	 * @return the new text.
	 */
	function FixHtml($html_text) {
		$text_filter = new TextFilter ( );
		$text_nojs = $text_filter->filterJavaScript ( $html_text );
		$text_noif = preg_replace ( '/<iframe.*?<\/iframe>/ims', "", $text_nojs );
		$text_noif = preg_replace ( '/<IFRAME.*?<\/IFRAME>/ims', "", $text_noif );
		//$text_bal = $text_filter->balanceTags($text_noif);
		

		return $text_noif;
	}
	/**
	 * try to remove the \xA3\xA0 and \xA1\xA1
	 * we will search the GBK char,do not use the str_replace,
	 * this is not safe,for two chinese char will have A3 A1 combin...
	 * NO 0X7F
	 */
	function FixFullBlank($string) {
		//process byte by byte
		$i = 0;
		$total = strlen ( $string );
		while ( $i < $total ) {
			if ((("\x81" <= $string [$i]) && ($string [$i] <= "\xFE")) && ((("\x40" <= $string [$i + 1]) && ($string [$i + 1] <= "\x7E")) || (("\x80" <= $string [$i + 1]) && ($string [$i + 1] <= "\xFE")))) {
				
				if ((($string [$i] == "\xA3") && ($string [$i + 1] == "\xA0")) || (($string [$i] == "\xA1") && ($string [$i + 1] == "\xA1"))) {
					$string [$i] = "\x20";
					$string [$i + 1] = "\x20";
				
				}
				$i = $i + 2;
			} else {
				//normal char,just ingore.
				$i ++;
			}
		}
		return $string;
	}
	
	/**
	 * set FCKeditor Instance name,adapt 2.3beta; 
	 * 06-05-30
	 * @access private
	 * @author pfcai@SF.com
	 * 
	 */
	
	private function setInstanceName($aInstanceName) {
		$this->oFCKeditor->InstanceName = $aInstanceName;
	}

}

?>
