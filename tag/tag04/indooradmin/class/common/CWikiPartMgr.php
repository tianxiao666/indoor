<?php
/**
 * �ٿƷֶα���
 * @author binzhao
 * createTime 2011-11-10
 */
class CWikiPartMgr extends CComponent{
	
	public function __construct(){
		
	}
	
	/**
	 * ��ȡ������Ŀ¼������ҳ�����ݷ��룬���ֱ𱣴����
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function strProcess($str = '' , $wiki_id = 0 ,$topic = ''){
		if(!$wiki_id) return false;
		//����ȡ������Ŀ¼����==Ŀ¼==��
		$pattr   = "/={2}[\\d\\D][^=\n]+?={2}[^=]/";
		$str     = preg_replace("/\[\[Image.[\\d\\D]*?\]]/", "", $str);	    //ȥ��wikitextͼƬ��Ϣ
		$str     = preg_replace("/\[\[File.[\\d\\D]*?\]]/", "", $str);	        //ȥ��wikitext�ļ���Ϣ
		$str     = preg_replace("/<gallery>[\\d\\D]*?<\/gallery>/", "", $str);	//ȥ��wikitext�����е��ļ���Ϣ
		$str     = preg_replace("/{{.[\\d\\D]*?}}/", "", $str);	            //ȥ��wikitext�ļ���Ϣ
		
		//ƥ�����(==)Ŀ¼������
		preg_match_all($pattr, $str, $direArr); 
		$direArr = $direArr[0];
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
//		$title = $str;
		if($direArr){
//			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={2}/", "", $dire);		//��ȡ��������Ŀ¼����ȥ��==
					
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']     = $wiki_id;
					$direData['PART_NAME']   = trim($direName);
					$direData['P_PART_ID']   = '0';
					$direData['PART_LEVEL']  = 2;
					$direData['PART_SEQ']    = $key+1;
					
					//�������һ������Ŀ¼��ִ�����²���
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//��ȡĿ¼���ݿ�ʼʱ��λ��
							$startLeh = strripos($str , $dire)+strlen($dire);
							//��ȡ��Ŀ¼�µ�����
							$returnArr = substr($str,$startLeh , strrpos($str , $direArr[$key+1])-$startLeh);  
							$returnStr = $this->ProcessThree($returnArr , $wiki_id , $direData['PART_ID'] , $topic);     //�����и�
							$direData['PART_NOTE']  = $returnStr;
						}
					}else{
						//Ϊ���һ��Ŀ¼������ȡ��ʣ�ಿ������
						$noteArr = explode($dire, $str);			//��ȡ��Ŀ¼�µ�����
						$returnStr = $this->ProcessThree(end($noteArr) , $wiki_id , $direData['PART_ID'] , $topic);     //�����и�
						$direData['PART_NOTE']  = $returnStr;
					}
					//�������Ŀ¼��Ϣ���ٿ�Ŀ¼��
					$result = $DAOWkWikiDire->addWikiDire($direData);
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("��ɡ�".$topic."������Ŀ¼����\n");
			}
		}
	}
	
	
	/**
	 * ��ȡ������Ŀ¼
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function ProcessThree($str = '' , $wiki_id = 0 , $dire_id = 0 , $topic = ''){
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
		//����ȡ������Ŀ¼����===Ŀ¼===��
		$pattr   = "/={3}[\\d\\D][^=\n]+?={3}[^=]/";
		preg_match_all($pattr, $str, $direArr);  				//ƥ������(===)Ŀ¼������
		$direArr = $direArr[0];
		SF::log("==========����Ŀ¼================");
		SF::log($str);
		SF::log($direArr);
		$title = $str;
		if($direArr){
			//������Ŀ¼�����г���Ŀ¼���ݺ󷵻ظü�Ŀ¼����
			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={3}/", "", $dire);		//��ȡ�����ļ�Ŀ¼����ȥ��====
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']   = $wiki_id;
					$direData['PART_NAME'] = trim($direName);
					$direData['P_PART_ID']  = $dire_id;
					$direData['PART_LEVEL'] = 3;
					$direData['PART_SEQ']   = $key+1;
					
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//��ȡĿ¼���ݿ�ʼʱ��λ��
							$startLeh = strripos($str , $dire)+strlen($dire);
							$returnArr = substr($str , $startLeh , strrpos($str , $direArr[$key+1])-$startLeh);  //��ȡ��Ŀ¼�µ�����
							$returnStr = $this->ProcessFour($returnArr , $wiki_id , $direData['PART_ID'] , $topic);     //�����и�
							$direData['PART_NOTE']  = $returnStr;
						}
					}else{
						$noteArr = explode($dire, $str);			//��ȡ��Ŀ¼�µ�����
						$returnStr = $this->ProcessFour(end($noteArr) , $wiki_id , $direData['PART_ID'] , $topic);     //�����и�
						$direData['PART_NOTE']  = $returnStr;
					}
					//��������Ŀ¼���ݵ��ٿ�Ŀ¼��
					$result = $DAOWkWikiDire->addWikiDire($direData);
					
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("��ɡ�".$topic."������Ŀ¼����\n");
			}
		}else{
			$title = $this->ProcessFour($str , $wiki_id , $dire_id , $topic);
		}
		return $title;
	}
	
	
	/**
	 * ��ȡ���ļ�Ŀ¼
	 * Enter description here ...
	 * @param unknown_type $str
	 * @param unknown_type $wiki_id
	 * @param unknown_type $dire_id
	 */
	function ProcessFour($str = '' , $wiki_id = 0 , $dire_id = 0 , $topic = ''){
		$DAOWkWikiDire = new CDAOWK_WIKI_PART();
		//����ȡ���ļ�Ŀ¼����====Ŀ¼====��
		$pattr   = "/={4}[\\d\\D][^=\n]+?={4}[^=]/";
		preg_match_all($pattr, $str, $direArr);  				//ƥ���ļ�(====)Ŀ¼������
		$direArr = $direArr[0];
		SF::log("==========�ļ�Ŀ¼================");
		SF::log($str);
		SF::log($direArr);
		$title = $str;
		if($direArr){
			//������Ŀ¼�����г���Ŀ¼���ݺ󷵻ظü�Ŀ¼����
			$title   = substr($str,0,strrpos($str,$direArr[0]));
			try {
				foreach ($direArr as $key=>$dire){
					$direData = array();
					$direName = preg_replace("/={4}/", "", $dire);		//��ȡ�����ļ�Ŀ¼����ȥ��====
					$direData['PART_ID']     = $DAOWkWikiDire->DB()->nextId("SEQ_WK_WIKI_PART_ID");
					$direData['WIKI_ID']   = $wiki_id;
					$direData['PART_NAME'] = trim($direName);
					$direData['P_PART_ID']  = $dire_id;
					$direData['PART_LEVEL'] = 4;
					$direData['PART_SEQ']   = $key+1;
					if($key+1<count($direArr)){
						if($dire && $direArr[$key+1]){
							//��ȡĿ¼���ݿ�ʼʱ��λ��
							$startLeh = strripos($str , $dire)+strlen($dire);
							 //��ȡ��Ŀ¼�µ�����
							$returnArr = substr($str , $startLeh , strrpos($str , $direArr[$key+1])-$startLeh); 
							$direData['PART_NOTE']  = $returnArr;
						}
					}else{
						//��ȡ��Ŀ¼�µ�����
						$noteArr = explode($dire, $str);			
						$direData['PART_NOTE']  = end($noteArr);
					}
					//�����ļ�Ŀ¼���ݵ��ٿ�Ŀ¼��
					$result = $DAOWkWikiDire->addWikiDire($direData);
				}
			}catch (Exception $e)
			{}
			if($topic){
				print_r("***********************************\n");
				print_r("��ɡ�".$topic."���ļ�Ŀ¼����\n");
			}
		}
		return $title;
	}
	
	
	
	
	
	
}
?>

