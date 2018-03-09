<?php
/**
 * 百科分段保存
 * @author binzhao
 * createTime 2011-11-10
 */
class CWikiPartMgr extends CComponent{
	
	public function __construct(){
		
	}
	
	/**
	 * 获取第三级目录，词条页面内容分离，并分别保存入库
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function strProcess($str = '' , $wiki_id = 0 ,$topic = ''){
		if(!$wiki_id) return false;
		//正则取出二级目录名（==目录==）
		$pattr   = "/={2}[\\d\\D][^=\n]+?={2}[^=]/";
		$str     = preg_replace("/\[\[Image.[\\d\\D]*?\]]/", "", $str);	    //去除wikitext图片信息
		$str     = preg_replace("/\[\[File.[\\d\\D]*?\]]/", "", $str);	        //去除wikitext文件信息
		$str     = preg_replace("/<gallery>[\\d\\D]*?<\/gallery>/", "", $str);	//去除wikitext内容中的文件信息
		$str     = preg_replace("/{{.[\\d\\D]*?}}/", "", $str);	            //去除wikitext文件信息
		
		//匹配二级(==)目录名集合
		preg_match_all($pattr, $str, $direArr); 
		$direArr = $direArr[0];
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
//		$title = $str;
		if($direArr){
//			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={2}/", "", $dire);		//获取完整二级目录名，去除==
					
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']     = $wiki_id;
					$direData['PART_NAME']   = trim($direName);
					$direData['P_PART_ID']   = '0';
					$direData['PART_LEVEL']  = 2;
					$direData['PART_SEQ']    = $key+1;
					
					//若非最后一个二级目录则执行以下操作
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//获取目录内容开始时的位置
							$startLeh = strripos($str , $dire)+strlen($dire);
							//获取该目录下的内容
							$returnArr = substr($str,$startLeh , strrpos($str , $direArr[$key+1])-$startLeh);  
							$returnStr = $this->ProcessThree($returnArr , $wiki_id , $direData['PART_ID'] , $topic);     //内容切割
							$direData['PART_NOTE']  = $returnStr;
						}
					}else{
						//为最后一个目录名，则取出剩余部分内容
						$noteArr = explode($dire, $str);			//获取该目录下的内容
						$returnStr = $this->ProcessThree(end($noteArr) , $wiki_id , $direData['PART_ID'] , $topic);     //内容切割
						$direData['PART_NOTE']  = $returnStr;
					}
					//保存二级目录信息到百科目录表
					$result = $DAOWkWikiDire->addWikiDire($direData);
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("完成“".$topic."”二级目录保存\n");
			}
		}
	}
	
	
	/**
	 * 获取第三级目录
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function ProcessThree($str = '' , $wiki_id = 0 , $dire_id = 0 , $topic = ''){
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
		//正则取出三级目录名（===目录===）
		$pattr   = "/={3}[\\d\\D][^=\n]+?={3}[^=]/";
		preg_match_all($pattr, $str, $direArr);  				//匹配三级(===)目录名集合
		$direArr = $direArr[0];
		SF::log("==========三级目录================");
		SF::log($str);
		SF::log($direArr);
		$title = $str;
		if($direArr){
			//若有子目录，则切除子目录内容后返回该级目录内容
			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={3}/", "", $dire);		//获取完整四级目录名，去除====
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']   = $wiki_id;
					$direData['PART_NAME'] = trim($direName);
					$direData['P_PART_ID']  = $dire_id;
					$direData['PART_LEVEL'] = 3;
					$direData['PART_SEQ']   = $key+1;
					
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//获取目录内容开始时的位置
							$startLeh = strripos($str , $dire)+strlen($dire);
							$returnArr = substr($str , $startLeh , strrpos($str , $direArr[$key+1])-$startLeh);  //获取该目录下的内容
							$returnStr = $this->ProcessFour($returnArr , $wiki_id , $direData['PART_ID'] , $topic);     //内容切割
							$direData['PART_NOTE']  = $returnStr;
						}
					}else{
						$noteArr = explode($dire, $str);			//获取该目录下的内容
						$returnStr = $this->ProcessFour(end($noteArr) , $wiki_id , $direData['PART_ID'] , $topic);     //内容切割
						$direData['PART_NOTE']  = $returnStr;
					}
					//保存三级目录内容到百科目录表
					$result = $DAOWkWikiDire->addWikiDire($direData);
					
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("完成“".$topic."”三级目录保存\n");
			}
		}else{
			$title = $this->ProcessFour($str , $wiki_id , $dire_id , $topic);
		}
		return $title;
	}
	
	
	/**
	 * 获取第四级目录
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function ProcessFour($str = '' , $wiki_id = 0 , $dire_id = 0 , $topic = ''){
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
		//正则取出四级目录名（====目录====）
		$pattr   = "/={4}[\\d\\D][^=\n]+?={4}[^=]/";
		preg_match_all($pattr, $str, $direArr);  				//匹配四级(====)目录名集合
		$direArr = $direArr[0];
		SF::log("==========四级目录================");
		SF::log($str);
		SF::log($direArr);
		$title = $str;
		if($direArr){
			//若有子目录，则切除子目录内容后返回该级目录内容
			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={4}/", "", $dire);		//获取完整四级目录名，去除====
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']   = $wiki_id;
					$direData['PART_NAME'] = trim($direName);
					$direData['P_PART_ID']  = $dire_id;
					$direData['PART_LEVEL'] = 4;
					$direData['PART_SEQ']   = $key+1;
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//获取目录内容开始时的位置
							$startLeh = strripos($str , $dire)+strlen($dire);
							 //获取该目录下的内容
							$returnArr = substr($str , $startLeh , strrpos($str , $direArr[$key+1])-$startLeh); 
							$direData['PART_NOTE']  = $returnArr;
						}
					}else{
						//获取该目录下的内容
						$noteArr = explode($dire, $str);			
						$direData['PART_NOTE']  = end($noteArr);
					}
					//保存四级目录内容到百科目录表
					$result = $DAOWkWikiDire->addWikiDire($direData);
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("完成“".$topic."”四级目录保存\n");
			}
		}
		return $title;
	}
	
	
	
	
	
	
}
?>

