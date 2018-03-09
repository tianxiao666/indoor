<?php
/**
 * �û�������
 * @author 
 */


class AdminMgrController extends AdminController
{
	const PAGE_SIZE=15;
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('USEINO');
	}
  //Ⱥ���ʼ�
/*	public function actionSendEmail()
	{
		$this->render('email_much_send');
	}
*/
    /**
     * �û��б�
     * Enter description here ...
     */
    function actionIndex()
    {
    	if(!$_GET['Ser']){
    		CUserSession::removeSessionValue('conditions');      //�ж��Ƿ�����
    	}
        $condition = CUserSession::getSessionValue('conditions');
		$pageData['real_name'] 	= $CDict->REAL_NAME;
    	$DaoAdmin 		= new CDAOCS_SUBS();
		$CDAOCP_PROD    = new CDAOCP_PROD();
		$whereSql		= $this->genWhereSql($condition);
		$p 				= new page($DaoAdmin->getAdminCount($whereSql));
		$p->baseUrl 	= "ea.php?r=AdminMgr&Ser=1";
        $p->pagesize 	= self::PAGE_SIZE;
        $page 			= $p->generate(); 
		$adminInfo = $DaoAdmin->getPageAdmin($p->pagesize, $page['page'],$whereSql);
		//��CDict���л�ȡ״̬��Ϣ
		$cdict = new CDict();
    	if($adminInfo){
    		foreach($adminInfo as $key=>$val){
    			$cdao_api_subs = new CDAOCS_API_SUBSB();
    		    $adminInfo[$key]['STATE'] = $cdict->SUBS_STATE[$val['STATE']];
    		    $apiinfo = $cdao_api_subs->getAll(" SUBS_ID = {$val['SUBS_ID']} AND STATUS='A' ");
    		    if($apiinfo){
    		    	foreach($apiinfo as $k=>$api){
    		    		if($api['API_NAME'] == 'SINA'){
    		    			$apiinfo[$k]['APINAME'] = '����';
    		    		}
    		    	    if($api['API_NAME'] == 'QQ'){
    		    			$apiinfo[$k]['APINAME'] = 'QQ';
    		    		}
    		    	    if($api['API_NAME'] == 'RENREN'){
    		    			$apiinfo[$k]['APINAME'] = '����';
    		    		}
    		    	}
    		    	$adminInfo[$key]['API'] = $apiinfo;
    		    }
		    }
    	}
        $pageData['admin_list'] = $adminInfo;
		$pageData['page'] 			= $page;
		$pageData['user_role'] 	= $this->user['ROLE'];
		//��Ա�ȼ�
		$prod_name = array('AUTO'=>'�����û�');        //����һ����������ѡ���ѯ�����������ɵ��û�
      	$records 	= $CDAOCP_PROD->getCp_ProdInfo();
      	if ($records)
       	{
        	foreach ($records as $record)
           	{
            	$prod_name[$record['PROD_NAME']] = $record['PROD_NAME'];
          	}
      	}
      	
      	$pageData['prod_name']	= $prod_name;
		$pageData['condition']	 = $condition;
		$this->render("adminmgr_index", $pageData);	
    }

    function actionSearchUser(){
    	$condition = $this->checkRequestFormat();
   		CUserSession::setSessionValue('conditions', $condition);			
    	$this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");	
    }
    /**
     * �����û�ҳ��
     * Enter description here ...
     */    
    function actionAddView()
    {
    	$DAORole = new CDAORole();
    	$CDAOCP_PROD = new CDAOCP_PROD();
    	
    	$prod_name = array();
      	$records 	= $CDAOCP_PROD->getCp_ProdInfo();
      	if ($records)
       	{
        	foreach ($records as $record)
           	{
            	$prod_name[$record['PROD_ID']] = $record['PROD_NAME'];
          	}
      	}
      	
      	$pageData['prod_name']	= $prod_name;
      	
        $CDict 						= new CDict();
      	$pageData['sys_area_code']	= $CDict->AREA_CODE_GD_NAME;
        $this->render("adminmgr_addview", $pageData);
    }
    /**
     * �����û�����
     * Enter description here ...
     */
    function actionDoAdd()
    {
    	
    	$u = $this->checkRequestFormat();
        $u['PASSWD'] 		= md5($u['PASSWD']);
    	$u['VIS_TYPE'] 		= 'PUB';
    	$u['STATE'] 		= 'A';
    	$u['SRC_TYPE'] 		= 'BACKEND';
        $u['CREATE_TIME'] 	= date('Y-m-d H:i:s');
       
   		$DAOSubs = new CDAOCS_SUBS();
   		
        if ($DAOSubs->adminExists($u['NAME']))
       	{
        	$this->jsAlert('�û����Ѿ����ڣ�');
        	$this->jsJumpBack ();
          	return false;
       	}
        if ($DAOSubs->emailExists($u['EMAIL']))
       	{
        	$this->jsAlert('�����Ѿ����ڣ�');
          	$this->jsJumpBack ();
          	return false;
      	}
    	if($DAOSubs->addAdmin($u)){
	        $this->writeAdminLog('USR', 'SYS', "����Ա {$this->user['NAME']} ����û���{$u['NAME']}��");
	        $this->jsAlert('��ӳɹ���');
   	 	}else{
   	 		$this->jsAlert('���ʧ�ܣ�');
   	 	}
   	 	$this->jsCall("parent.location.href = 'ea.php?r=AdminMgr';");
    }
    /**
     * �༭�û�ҳ��
     * Enter description here ...
     */
    function actionEditView()
    {
    	$flag = intval($_GET['flag']);
    	$DAOSubs   	= new CDAOCS_SUBS();
   		$DAORole 	= new CDAORole();
   		$cdaoSOC 	= new CDAOSYS_OPTION_CODE();
   		$CDict = new CDict();
      	$pageData['sys_area_code']	= $CDict->AREA_CODE_GD_NAME;
		if($flag){
			$pageData['editPass']   = "YES";
		}
     	
     	$userinfo = $DAOSubs->getUserById($_GET['subs_id']);
     	$pageData['user_info']	= $userinfo[0];
        //������ƽ̨
      	$cdao_api_subs = new CDAOCS_API_SUBSB();
        $apiinfo = $cdao_api_subs->getAll(" SUBS_ID = {$pageData['user_info']['SUBS_ID']} AND STATUS='A' ");
    	if($apiinfo){
    	   	foreach($apiinfo as $k=>$api){
    	   		if($api['API_NAME'] == 'SINA'){
    	   			$apiinfo[$k]['APINAME'] = '����';
    	   		}
    	   	    if($api['API_NAME'] == 'QQ'){
    	   			$apiinfo[$k]['APINAME'] = 'QQ';
    	   		}
    	   	    if($api['API_NAME'] == 'RENREN'){
    	   			$apiinfo[$k]['APINAME'] = '����';
    	   		}
    	   	}
    	   	$pageData['API'] = $apiinfo;
    	}      	     	
    	//����û�������Ϊ�գ�Ĭ��Ϊ1970-01-01
		$birthDay = $pageData['user_info'] ['BIRTHDAY'];
		if ($birthDay) {
			$pageData['user_info'] ['year']  = date ( 'Y', strtotime ( $birthDay ) );
			$pageData['user_info'] ['month'] = date ( 'm', strtotime ( $birthDay ) );
			$pageData['user_info'] ['day']   = date ( 'd', strtotime ( $birthDay ) );
		} else {
			$pageData['user_info'] ['year']  = 1970;
			$pageData['user_info'] ['month'] = 01;
			$pageData['user_info'] ['day']   = 01;
		}
		//ʡ�����б�
		$pageData['allProv'] = AdminController::getInstance('CDAOSYS_REGION')->findOptions(array('REGION_TYPE'=>'N','REGION_GRADE'=>1,'PARENT_ID'=>1));
		if($pageData['user_info']['PROV'])
		{
			$pageData['allCity'] = AdminController::getInstance('CDAOSYS_REGION')->findOptions(array('REGION_TYPE'=>'N','REGION_GRADE'=>2,'PARENT_ID'=>$pageData['user_info']['PROV']));
		}
		
        //����û����е���Ƭ��Ų�Ϊ�գ�������Ƭ���ȥý������Ƭ
		if ($pageData['user_info'] ['PHOTO']) {
			$DaoMedia = new CDAOCS_MEDIA ( );
			$photo = $DaoMedia->getMedia ( $pageData['user_info'] ['PHOTO'], 'A' );
			$pageData ['imgUrl'] = $photo ['PATH'] . EQUAL_PATH . $photo ['FILENAME'];
		}
		
      	$this->render('adminmgr_editview', $pageData);
    }
    
    /**
     * ����༭�û�
     * Enter description here ...
     */
    function actionDoEdit()
    {
    	$user_info	= $this->checkRequestFormat();
    	
    	if ($user_info['PASSWD'])
    	{
    		 $user_info['PASSWD'] = md5($user_info['PASSWD']);
    		 $flag =1;
    	}
    	else 
    	{
    		unset($user_info['PASSWD']);
    		$month = $user_info ['month'] < 10 ? '0' . $user_info ['month'] : $user_info ['month'];
		    $day = $user_info ['day'] < 10 ? '0' . $user_info ['day'] : $user_info ['day'];
		    $user_info['BIRTHDAY'] = $user_info ['year'] . '-' . $month . '-' . $day;
		
    	}
    	$DAOSubs  = new CDAOCS_SUBS();
       	
       	//�ж������Ƿ�Ψһ
        $emailExist = $DAOSubs->emailExist($user_info['EMAIL'],$user_info['user_name']);
       	if (!empty($emailExist)){
        	$this->jsAlert('�����Ѿ����ڣ�');
          	$this->jsJumpBack ();
          	return false;
      	}
        //���ϴ�ͷ���򱣴�ͷ��
		if($user_info['pname'] && $user_info['ptype'] && $user_info['photo_name']){
			$photo_name = SF_ROOT.trim($user_info['photo_name']);
			$pname = trim($user_info['pname']);
			$ptype = trim($user_info['ptype']);
			//Ҫ��ԭͼ�ϼ���ͼƬ����˼��п�Ҫ�Ի�ԭͼ/����ͼ
		    $image_size   =   getimagesize($pname);
			//�ж��ļ���͸�
			$scl   = intval($image_size[0])/intval($user_info['minw']);
			
			$x1 = intval($user_info['x1'])*$scl;
			$y1 = intval($user_info['y1'])*$scl;
			$w  = intval($user_info['w'])*$scl;
			$h  = intval($user_info['h'])*$scl;
			$upload = new CPhotoUpload(MEDIALIB_USERPHOTO_PATH, 'PIC',$user_info['subs_id']);
			$upload->upload_path=$upload->getMediaLib();
	
			$dest_file=$photo_name;
			$upload_result[0]=array('source_file' => $pname, 'dest_file' => $pname, 'mime_type' => $user_info['ptype'], 'size' => $user_info['psize'], 'error' => '');
			
			$array_path_size=array(MIN_PATH=>(array(50,50,Master_Dimension_EQUAL )),EQUAL_PATH=>(array(150,150,Master_Dimension_EQUAL)),CEL_PATH=>(array(200,200,Master_Dimension_EQUAL)));		//α���Ѿ��ϴ��������	
			$desc_result = $upload->CutImage($upload_result, $array_path_size,$x1,$y1,$w,$h);
			
			
			if($desc_result){
	    		$cdao_media = new CDAOCS_MEDIA();
				$subs_info = $DAOSubs->getUserById($user_info['subs_id']);
				
				if($subs_info[0]['PHOTO']){//�Ѿ���ͼƬ�����޸�
					$media_info = $cdao_media->findMediaId($subs_info[0]['PHOTO']);
					//�޸�media��
					$mediaInfo['PIC_SIZE'] = $user_info['psize'];
					$mediaInfo['PATH'] = $upload->upload_path;
					$mediaInfo['FILENAME'] = pathinfo($photo_name, PATHINFO_BASENAME);
					$res = $cdao_media->moditifyMediaByMediaId($subs_info[0]['PHOTO'],$mediaInfo);
				}else{//ûͼƬ�����
					$mediaInfo['SUBS_ID'] = $user_info['subs_id'];
					$mediaInfo['PIC_SIZE'] = $user_info['psize'];
					$mediaInfo['PATH'] = $upload->upload_path;
					$mediaInfo['FILENAME'] = pathinfo($photo_name, PATHINFO_BASENAME);
					$mediaInfo['MIME_TYPE'] = $user_info['ptype'];
					$mediaInfo['TYPE'] = 'IMG';
					$mediaInfo['MEDIA_TOPIC'] = $user_info['NICK_NAME'].'��ͷ��';
					$m_id = $cdao_media->addMedia($mediaInfo);
					$UInfo['PHOTO'] = $m_id;
					$res = $DAOSubs->updateUserInfo($user_info['subs_id'],$UInfo);
				}
			    if(!$res){
					$this->jsAlert('�޸�ʧ��');
		          	$this->jsJumpBack ();
		          	return false;
				}
			}else{
				$this->jsAlert('�޸�ʧ��');
	          	$this->jsJumpBack ();
	          	return false;
			}
		}
        $result = $DAOSubs->updateAdmin($user_info['subs_id'], $user_info ,$flag);
        if($result){
	        $this->writeAdminLog('USR', 'SYS', "����Ա {$this->user['NAME']} �޸��û���{$user_info['user_name']}��������");
	        $this->jsAlert('�޸ĳɹ���');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
        }else{
        	$this->jsAlert('�޸�ʧ��');
          	$this->jsJumpBack ();
          	return false;
        }
    }
	
    function actionDeleteAdmin()
    {
    	$subs_id	= $this->qstr($_GET['subs_id']);
    	$user_name	= $this->qstr($_GET['name']);
    	
        $DAOAdmin	= new CDAOCS_SUBS();
        $DAOAdmin->deleteAdmin($subs_id);
        $this->writeAdminLog('PRV', 'SYS', "����Ա {$this->user['NAME']} ɾ���û���{$user_name}��");
        $this->jsAlert('ɾ���ɹ���');
        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr';");
    }
    
    /**
     * �û��б���������
     * Enter description here ...
     * @param unknown_type $condition
     */
	private function genWhereSql($condition)
	{
		$condition['NAME'] = $this->inject_check($condition['NAME']);
		
		if ($condition['NAME'])
		{
			$whereSql .= " AND (NAME like '%{$condition['NAME']}%' OR REAL_NAME like '%{$condition['NAME']}%') ";
		}
		if($condition['EMAIL']){
			$whereSql .= " AND EMAIL like '%{$condition['EMAIL']}%' ";
		}
		if($condition['MOBILE']){
			$whereSql .= " AND MOBILE='{$condition['MOBILE']}' ";
		}
		if($condition['PROD_NAME']){
			if($condition['PROD_NAME']=='AUTO'){
				$whereSql .= " AND ROLE='{$condition['PROD_NAME']}' ";   //�������ɵ������û�
			}else{
			    $whereSql .= " AND PROD_NAME='{$condition['PROD_NAME']}' ";
			}
		}
	    return $whereSql;
	
    }

	 function actionAjaxRequest(){
			$val = $_POST['val'];
			if (empty($val)){
				return false;
			}
			$test = iconv("UTF-8","GBK",$val);
			$code_type = CPositionMgr::searchAllType($test,"PARENT");
			if ($code_type){
				$html .='<option value=\'\'>- ��ѡ�� -</option>';
				foreach ($code_type as $value){
					$html .='<option value="'.$value['OPTION_VALUE'].'">'.$value['OPTION_NAME'].'</option>';
				}
			}else{
				$html.='<option value=\'\'>'.'- ����ѡ�� -'.'</option>';
			}
	
			echo iconv('GBK','UTF-8',$html);
	}

	/**
	 * ���ò���
	 */
    function actionUserDisable(){
    	$subs_id = $this->qstr($_GET['subs_id']);
       	$DAOAdmin  = new CDAOCS_SUBS();
       	
      	if($DAOAdmin->userDisable($subs_id)){
	        $this->writeAdminLog('PRV', 'SYS', "����Ա {$this->user['NAME']} �����û���{$user_info['user_name']}���Ĳ���");
	        $this->jsAlert('�����ɹ���');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
       	}
       	else{
       		$this->jsAlert('����ʧ�ܣ�');
       	}
    }

	/**
	 * �������
	 */
    function actionUserActive(){
    	$subs_id = $this->qstr($_GET['subs_id']);
       	$DAOAdmin  = new CDAOCS_SUBS();
      	if($DAOAdmin->userActive($subs_id)){
	        $this->writeAdminLog('PRV', 'SYS', "����Ա {$this->user['NAME']} �����û���{$user_info['user_name']}���Ĳ���");
	        $this->jsAlert('�����ɹ���');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
       	}
       	else{
       		$this->jsAlert('����ʧ�ܣ�');
       	}
    }
    
    /**
	 * ajax��֤�û����Ƿ����
	 */
	public function actionAjaxCheckNAME(){
		$data = $this->checkRequestFormat();
		$name = $data['name'];
		$Cdaosubs = new CDAOCS_SUBS();
		$result = $Cdaosubs->adminExists($name);
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
    /**
	 * ajax��֤�����Ƿ�Ψһ
	 */
	public function actionAjaxCheckEMAIL(){
		$data = $this->checkRequestFormat();
		$email = $data['email'];
		$username = $data['name'];
		$Cdaosubs = new CDAOCS_SUBS();
		if($username){
		    $result = $Cdaosubs->emailExist($email,$username);
		}else{
			$result = $Cdaosubs->emailExists($email);
		}
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
	/**
	 * ajax��֤�ֻ���Ψһ
	 */
    public function actionAjaxCheckMobile(){
		$data = $this->checkRequestFormat();
		$mobile = $data['mobile'];
		$name = $data['name'];
		$Cdaosubs = new CDAOCS_SUBS();
		$result = $Cdaosubs->editMobileExist($mobile,$name);
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
	/**
	 * ���������û�
	 */
	public function actionBatchCreateUser(){
		if($_POST){
			$userCnt = intval($_POST['user_cnt']);
			$CDAOCR_CARD   = new CDAOCR_CARD();
			$daocs_subs    = new CDAOCS_SUBS();
			$cardstatusUnis = "DEAL";
			$cardstatusAtiv = 'ATIV';
			$userCount = 0;
			for ($i = 1; $i <= $userCnt; $i++) {
				$arrUser = array();
				$name = $this->generateUserName();
				$cardresult = $CDAOCR_CARD->getCardNbr("FRE" , $cardstatusUnis);
				$arrUser['NAME'] 		= $arrUser['EMAIL'] = $name."@tripdata.com";
				$arrUser['PASSWD'] 		= md5($name);
				$arrUser['NICK_NAME']   = $name;
				$arrUser['VIS_TYPE'] 	= 'PUB';
				$arrUser['ROLE'] 		= 'AUTO';
				$arrUser['STATE'] 		= 'A';
				$arrUser['SRC_TYPE'] 	= 'BACKEND';
				$arrUser['CREATE_TIME'] = $arrUser['MOD_TIME'] = date('Y-m-d H:i:s');
				$arrUser['MONEY'] 			= 0;
				if(!empty($cardresult)){
				    $arrUser['CARD_NO'] = $cardresult[0]['CARD_NBR'];
				    $arrUser['PROD_ID'] = $CDAOCR_CARD->getProdIdByProdCode($cardresult[0]['TYPE']);
				}
				$user = $daocs_subs->doInsert($arrUser , 'SEQ_CS_SUBS_SUBS_ID');
				if($user){
					if(!empty($cardresult)){
						$CDAOCR_CARD->UpdateCard_Status($cardresult[0]['CARD_ID'], $user , $cardstatusAtiv);
					}
					$userCount = $userCount+1;
				}
			}
			if($userCount){
				$text = "���������û��ɹ��������û�{$userCount}��";
			}else{
				$text = "���������û�ʧ��";
			}
			$this->jsCall("window.parent.showMessage('{$text}');");
			return false;
		}
		$this->render("adminmgr_ajaxadduser");
	}
	
	
	/**
	 * ��������û���
	 */
 	function generateUserName()
    {
    	$daocs_subs    = new CDAOCS_SUBS();
        mt_srand((double) microtime() * 1000000);
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($chars) - 1;
        for($i = 0; $i < 6; $i++)
        {
            $hash .= $chars{mt_rand(0, $max)};
        }
        $userInfo = $daocs_subs->getUserInfoByName($hash."@tripdata.com");
        if(!empty($userInfo)){
        	$hash = generateUserName();
        }
        return $hash;
    }
    
    /**
	 *������ʱͼƬ 
     * @author panyf
     */
	public function actionSavePhoto(){
		$max_width = "200";   
   		$type=array("jpg","gif","bmp","jpeg","png");//���������ϴ��ļ�������
   		$a=strtolower($this->fileext($_FILES['file']['name']));
	    //�ж��ļ�����
	    if(empty($a)){
	    	$this->jsCall("window.parent.Alert('����ѡ��һ��ͼƬ��');");
	    	return false;
	    }
   		if(!in_array(strtolower($this->fileext($_FILES['file']['name'])),$type)){
	        $text=implode(",",$type);
	        $this->jsCall("window.parent.Alert('������ʾ����Ƭ��ʽ����');");
	        return false;
     	}
     	if($_FILES['file']['size']>5242880){
     	    $this->jsCall("window.parent.Alert('������ʾ����Ƭ��С����5M');");
	        return false;
     	}
   		//����Ŀ���ļ����ļ���   
   		else{
   			
    		$filename=explode(".",$_FILES['file']['name']);
	        $SUBS_ID = $_GET['subs_id'] ? $_GET['subs_id'] : 1 ;
			$daocs_subs    = new CDAOCS_SUBS();
			$subs_info = $daocs_subs->getUserById($SUBS_ID);
			if($subs_info[0]['STATE']=='X'){
				$this->jsCall("window.parent.Alert('���û��ѽ��ã��ϴ���������ȼ��');");
	            return false;
			}
			$upload = new CPhotoUpload(MEDIALIB_USERPHOTO_PATH, $field_name='file',$SUBS_ID);
	        $medialib = $upload->upload_path=$upload->getMediaLib();
   			$upload_result = $upload->save();
   			
			$dest_file = (is_array($upload_result))?$upload_result[0]['dest_file']:'';
			if (!$dest_file)
				return false;
   		   	$image_size   =   getimagesize($dest_file);
			//�ж��ļ���͸�
			if($image_size[0] / $image_size[1] > (7/3)){
			   	$this->jsCall("window.parent.alert('������ʾ����Ƭ�����');");
			   	return false;
			}
			//��������ͼ
			$array_path_size=array(PREVIEWS_MEDIUMS_PATH=>(array($max_width,$max_width,Master_Dimension_WIDTH)));
			
			$upload->saveThumbnail($upload_result,$array_path_size);
			$path=pathinfo($dest_file,PATHINFO_DIRNAME);
			$uploaded = str_replace($path.'/','', $dest_file);
			$uploaded = $path.'/'.PREVIEWS_MEDIUMS_PATH.$uploaded;
			//$uploaded=str_replace($path, SF_ROOT.$path.'/'.PREVIEWS_MEDIUMS_PATH, $dest_file);
			$path = $path.'/';
			//$upload_url=str_replace($path, MEDIA_SERVER.$medialib.PREVIEWS_MEDIUMS_PATH, $dest_file);
			$upload_url=str_replace($path, MEDIA_SERVER.$medialib, $dest_file);
			
			$upload_url=str_replace(SF_ROOT, '', $upload_url);
			
		    $width = $this->getWidth($uploaded);
			$height = $this->getHeight($uploaded);
			//Delete the thumbnail file so the user can create a new one
	        $type = $_FILES['file']['type'];
	        $size = $_FILES['file']['size'];
	        $this->jsCall("window.parent.callback('{$upload_url}','{$uploaded}','{$width}','{$height}','{$type}','{$size}','{$image_size[0]}')");
	    }
   }
	
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}
	//You do not need to alter these functions
	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}
    /**
	 *��ȡ�ļ���׺������
     */
	function fileext($filename){
        return substr(strrchr($filename, '.'), 1);
    } 
}
?>
