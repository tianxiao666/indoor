<?php
/**
 *@desc keyword filter
 *@author tujk
 */

class KeyWordsFilter
{
	public function filter($data)
	{
		$filterStr = false;
		$oDAOSysKeyWord = new CDAOSysKeyWord();
		$keyWords = $oDAOSysKeyWord->getAllActiveKeywords();
		$serializeData = serialize($data);
	
		if(!empty($keyWords)){
			foreach ($keyWords as $val){
					if(strpos($serializeData,$val['WORD'])){
						$filterStr = $val['WORD'];
						break;
					}
			}
		}
		return $filterStr;
	}
}
?>