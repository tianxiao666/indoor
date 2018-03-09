<?php
class CMgrFilKeyWord
{
	/**
	 * 去过和想去内容标红显示在页面函数
	 *
	 * @param unknown_type $text
	 * @return unknown
	 */
	
	function SetTextKeyWord(&$text)
	{
		$daosys_fil_KeyWord = new CCDAOSYS_FIL_KEYWORD();
		$aKeyWord = $daosys_fil_KeyWord->getKeyWordInfo();
		
		if($aKeyWord)
		{
			foreach ($aKeyWord as $key=>$val)
			{
				$coun=count($val['WORD']);
				$keyWord=str_replace("'",'', $val['WORD']);
				$domain_text = strstr($text, $keyWord);
				if($domain_text){
					$word = "/".$val['WORD']."/";
					$KeyWord_text="<b><font color='#FF0000' size='2'>".$keyWord."</font></b>";
					$text = preg_replace($word,$KeyWord_text,$text);
				}
				
			}
		}
		return $text;
	}
}
?>