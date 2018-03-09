<?php
/**
 * 用户操作类
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
  //群发邮件
/*	public function actionSendEmail()
	{
		$this->render('email_much_send');
	}
*/
    /**
     * 用户列表
     * Enter description here ...
     */
    function actionIndex()
    {
    	if(!$_GET['Ser']){
    		CUserSession::removeSessionValue('conditions');      //判断是否搜索
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
		//从CDict类中获取状态信息
		$cdict = new CDict();
    	if($adminInfo){
    		foreach($adminInfo as $key=>$val){
    			$cdao_api_subs = new CDAOCS_API_SUBSB();
    		    $adminInfo[$key]['STATE'] = $cdict->SUBS_STATE[$val['STATE']];
    		    $apiinfo = $cdao_api_subs->getAll(" SUBS_ID = {$val['SUBS_ID']} AND STATUS='A' ");
    		    if($apiinfo){
    		    	foreach($apiinfo as $k=>$api){
    		    		if($api['API_NAME'] == 'SINA'){
    		    			$apiinfo[$k]['APINAME'] = '新浪';
    		    		}
    		    	    if($api['API_NAME'] == 'QQ'){
    		    			$apiinfo[$k]['APINAME'] = 'QQ';
    		    		}
    		    	    if($api['API_NAME'] == 'RENREN'){
    		    			$apiinfo[$k]['APINAME'] = '人人';
    		    		}
    		    	}
    		    	$adminInfo[$key]['API'] = $apiinfo;
    		    }
		    }
    	}
        $pageData['admin_list'] = $adminInfo;
		$pageData['page'] 			= $page;
		$pageData['user_role'] 	= $this->user['ROLE'];
		//会员等级
		$prod_name = array('AUTO'=>'虚拟用户');        //新增一个搜索条件选项，查询的是批量生成的用户
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
     * 新增用户页面
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
     * 新增用户操作
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
        	$this->jsAlert('用户名已经存在！');
        	$this->jsJumpBack ();
          	return false;
       	}
        if ($DAOSubs->emailExists($u['EMAIL']))
       	{
        	$this->jsAlert('邮箱已经存在！');
          	$this->jsJumpBack ();
          	return false;
      	}
    	if($DAOSubs->addAdmin($u)){
	        $this->writeAdminLog('USR', 'SYS', "管理员 {$this->user['NAME']} 添加用户“{$u['NAME']}”");
	        $this->jsAlert('添加成功！');
   	 	}else{
   	 		$this->jsAlert('添加失败！');
   	 	}
   	 	$this->jsCall("parent.location.href = 'ea.php?r=AdminMgr';");
    }
    /**
     * 编辑用户页面
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
        //第三方平台
      	$cdao_api_subs = new CDAOCS_API_SUBSB();
        $apiinfo = $cdao_api_subs->getAll(" SUBS_ID = {$pageData['user_info']['SUBS_ID']} AND STATUS='A' ");
    	if($apiinfo){
    	   	foreach($apiinfo as $k=>$api){
    	   		if($api['API_NAME'] == 'SINA'){
    	   			$apiinfo[$k]['APINAME'] = '新浪';
    	   		}
    	   	    if($api['API_NAME'] == 'QQ'){
    	   			$apiinfo[$k]['APINAME'] = 'QQ';
    	   		}
    	   	    if($api['API_NAME'] == 'RENREN'){
    	   			$apiinfo[$k]['APINAME'] = '人人';
    	   		}
    	   	}
    	   	$pageData['API'] = $apiinfo;
    	}      	     	
    	//如果用户的生日为空，默认为1970-01-01
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
		//省市区列表
		$pageData['allProv'] = AdminController::getInstance('CDAOSYS_REGION')->findOptions(array('REGION_TYPE'=>'N','REGION_GRADE'=>1,'PARENT_ID'=>1));
		if($pageData['user_info']['PROV'])
		{
			$pageData['allCity'] = AdminController::getInstance('CDAOSYS_REGION')->findOptions(array('REGION_TYPE'=>'N','REGION_GRADE'=>2,'PARENT_ID'=>$pageData['user_info']['PROV']));
		}
		
        //如果用户表中的照片编号不为空，根据照片编号去媒体表查照片
		if ($pageData['user_info'] ['PHOTO']) {
			$DaoMedia = new CDAOCS_MEDIA ( );
			$photo = $DaoMedia->getMedia ( $pageData['user_info'] ['PHOTO'], 'A' );
			$pageData ['imgUrl'] = $photo ['PATH'] . EQUAL_PATH . $photo ['FILENAME'];
		}
		
      	$this->render('adminmgr_editview', $pageData);
    }
    
    /**
     * 保存编辑用户
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
       	
       	//判断邮箱是否唯一
        $emailExist = $DAOSubs->emailExist($user_info['EMAIL'],$user_info['user_name']);
       	if (!empty($emailExist)){
        	$this->jsAlert('邮箱已经存在！');
          	$this->jsJumpBack ();
          	return false;
      	}
        //有上传头像，则保存头像
		if($user_info['pname'] && $user_info['ptype'] && $user_info['photo_name']){
			$photo_name = SF_ROOT.trim($user_info['photo_name']);
			$pname = trim($user_info['pname']);
			$ptype = trim($user_info['ptype']);
			//要在原图上剪切图片，因此剪切框要乖回原图/缩略图
		    $image_size   =   getimagesize($pname);
			//判断文件宽和高
			$scl   = intval($image_size[0])/intval($user_info['minw']);
			
			$x1 = intval($user_info['x1'])*$scl;
			$y1 = intval($user_info['y1'])*$scl;
			$w  = intval($user_info['w'])*$scl;
			$h  = intval($user_info['h'])*$scl;
			$upload = new CPhotoUpload(MEDIALIB_USERPHOTO_PATH, 'PIC',$user_info['subs_id']);
			$upload->upload_path=$upload->getMediaLib();
	
			$dest_file=$photo_name;
			$upload_result[0]=array('source_file' => $pname, 'dest_file' => $pname, 'mime_type' => $user_info['ptype'], 'size' => $user_info['psize'], 'error' => '');
			
			$array_path_size=array(MIN_PATH=>(array(50,50,Master_Dimension_EQUAL )),EQUAL_PATH=>(array(150,150,Master_Dimension_EQUAL)),CEL_PATH=>(array(200,200,Master_Dimension_EQUAL)));		//伪造已经上传后的数据	
			$desc_result = $upload->CutImage($upload_result, $array_path_size,$x1,$y1,$w,$h);
			
			
			if($desc_result){
	    		$cdao_media = new CDAOCS_MEDIA();
				$subs_info = $DAOSubs->getUserById($user_info['subs_id']);
				
				if($subs_info[0]['PHOTO']){//已经有图片的则修改
					$media_info = $cdao_media->findMediaId($subs_info[0]['PHOTO']);
					//修改media表
					$mediaInfo['PIC_SIZE'] = $user_info['psize'];
					$mediaInfo['PATH'] = $upload->upload_path;
					$mediaInfo['FILENAME'] = pathinfo($photo_name, PATHINFO_BASENAME);
					$res = $cdao_media->moditifyMediaByMediaId($subs_info[0]['PHOTO'],$mediaInfo);
				}else{//没图片则插入
					$mediaInfo['SUBS_ID'] = $user_info['subs_id'];
					$mediaInfo['PIC_SIZE'] = $user_info['psize'];
					$mediaInfo['PATH'] = $upload->upload_path;
					$mediaInfo['FILENAME'] = pathinfo($photo_name, PATHINFO_BASENAME);
					$mediaInfo['MIME_TYPE'] = $user_info['ptype'];
					$mediaInfo['TYPE'] = 'IMG';
					$mediaInfo['MEDIA_TOPIC'] = $user_info['NICK_NAME'].'的头像';
					$m_id = $cdao_media->addMedia($mediaInfo);
					$UInfo['PHOTO'] = $m_id;
					$res = $DAOSubs->updateUserInfo($user_info['subs_id'],$UInfo);
				}
			    if(!$res){
					$this->jsAlert('修改失败');
		          	$this->jsJumpBack ();
		          	return false;
				}
			}else{
				$this->jsAlert('修改失败');
	          	$this->jsJumpBack ();
	          	return false;
			}
		}
        $result = $DAOSubs->updateAdmin($user_info['subs_id'], $user_info ,$flag);
        if($result){
	        $this->writeAdminLog('USR', 'SYS', "管理员 {$this->user['NAME']} 修改用户“{$user_info['user_name']}”的资料");
	        $this->jsAlert('修改成功！');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
        }else{
        	$this->jsAlert('修改失败');
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
        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 删除用户“{$user_name}”");
        $this->jsAlert('删除成功！');
        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr';");
    }
    
    /**
     * 用户列表搜索条件
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
				$whereSql .= " AND ROLE='{$condition['PROD_NAME']}' ";   //批量生成的虚拟用户
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
				$html .='<option value=\'\'>- 请选择 -</option>';
				foreach ($code_type as $value){
					$html .='<option value="'.$value['OPTION_VALUE'].'">'.$value['OPTION_NAME'].'</option>';
				}
			}else{
				$html.='<option value=\'\'>'.'- 暂无选项 -'.'</option>';
			}
	
			echo iconv('GBK','UTF-8',$html);
	}

	/**
	 * 禁用操作
	 */
    function actionUserDisable(){
    	$subs_id = $this->qstr($_GET['subs_id']);
       	$DAOAdmin  = new CDAOCS_SUBS();
       	
      	if($DAOAdmin->userDisable($subs_id)){
	        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 禁用用户“{$user_info['user_name']}”的操作");
	        $this->jsAlert('操作成功！');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
       	}
       	else{
       		$this->jsAlert('操作失败！');
       	}
    }

	/**
	 * 激活操作
	 */
    function actionUserActive(){
    	$subs_id = $this->qstr($_GET['subs_id']);
       	$DAOAdmin  = new CDAOCS_SUBS();
      	if($DAOAdmin->userActive($subs_id)){
	        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 激活用户“{$user_info['user_name']}”的操作");
	        $this->jsAlert('操作成功！');
	        $this->jsCall("parent.location.href = 'ea.php?r=AdminMgr&Ser=1';");
       	}
       	else{
       		$this->jsAlert('操作失败！');
       	}
    }
    
    /**
	 * ajax验证用户名是否存在
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
	 * ajax验证邮箱是否唯一
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
	 * ajax验证手机号唯一
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
	 * 批量生成用户
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
				$text = "批量生成用户成功，生成用户{$userCount}个";
			}else{
				$text = "批量生成用户失败";
			}
			$this->jsCall("window.parent.showMessage('{$text}');");
			return false;
		}
		$this->render("adminmgr_ajaxadduser");
	}
	
	
	/**
	 * 创建随机用户名
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
	 *保存临时图片 
     * @author panyf
     */
	public function actionSavePhoto(){
		$max_width = "200";   
   		$type=array("jpg","gif","bmp","jpeg","png");//设置允许上传文件的类型
   		$a=strtolower($this->fileext($_FILES['file']['name']));
	    //判断文件类型
	    if(empty($a)){
	    	$this->jsCall("window.parent.Alert('请先选择一张图片！');");
	    	return false;
	    }
   		if(!in_array(strtolower($this->fileext($_FILES['file']['name'])),$type)){
	        $text=implode(",",$type);
	        $this->jsCall("window.parent.Alert('错误提示：照片格式不对');");
	        return false;
     	}
     	if($_FILES['file']['size']>5242880){
     	    $this->jsCall("window.parent.Alert('错误提示：照片大小超过5M');");
	        return false;
     	}
   		//生成目标文件的文件名   
   		else{
   			
    		$filename=explode(".",$_FILES['file']['name']);
	        $SUBS_ID = $_GET['subs_id'] ? $_GET['subs_id'] : 1 ;
			$daocs_subs    = new CDAOCS_SUBS();
			$subs_info = $daocs_subs->getUserById($SUBS_ID);
			if($subs_info[0]['STATE']=='X'){
				$this->jsCall("window.parent.Alert('该用户已禁用，上传会出错，请先激活！');");
	            return false;
			}
			$upload = new CPhotoUpload(MEDIALIB_USERPHOTO_PATH, $field_name='file',$SUBS_ID);
	        $medialib = $upload->upload_path=$upload->getMediaLib();
   			$upload_result = $upload->save();
   			
			$dest_file = (is_array($upload_result))?$upload_result[0]['dest_file']:'';
			if (!$dest_file)
				return false;
   		   	$image_size   =   getimagesize($dest_file);
			//判断文件宽和高
			if($image_size[0] / $image_size[1] > (7/3)){
			   	$this->jsCall("window.parent.alert('错误提示：照片规格不行');");
			   	return false;
			}
			//生成缩略图
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
	 *获取文件后缀名函数
     */
	function fileext($filename){
        return substr(strrchr($filename, '.'), 1);
    } 
}
?>
