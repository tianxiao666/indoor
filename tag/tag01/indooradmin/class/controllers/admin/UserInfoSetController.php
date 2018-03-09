<?php
/**
 * �û������޸�
 * @pyf 2011.8.3
 */
class UserInfoSetController extends AdminController {
	public function __construct() {
		parent::__construct ();
	}
	function actionIndex() {
		$userid = $this->qstr ( $_GET ['subs_id'] );
		$flag = intval ( $_GET ['flag'] );
		if ($userid != $this->user ['SYS_SUBS_ID'])
			$this->checkAdminPermission ();
		$CDAOSubs = new CDAOSYS_SUBS ();
		$userInfo = $CDAOSubs->getUserById ( $userid );
		$Cdaosubs = new CDAOSYS_SUBS ();
		$role = new CDAORole ();
		$Cdaodept = new CDAOSYS_DEPT ();
		$Ctree = new CTreeData ();
		$Deptsubs = new CDAOSYS_DEPT_SUBS ();
		
		// �û���ɫ
		$roleInfo = $role->getRoleByid ( $userid );
		
		// ���н�ɫ
		$rolelist = $role->getAllRole ();
		$deptlist = $Cdaodept->getAllDept ();
		$dept_list = $Ctree->getTreeData ( $deptlist, 'DEPT_ID', 'P_DEPT_ID' );
		$userdept = $Deptsubs->getUserDeptByid ( $userid ); // ��ȡ�û�����
		if($flag){
			$pageData['editPass']   = "YES";
		}
		if ($userdept) {
			foreach ( $userdept as $ud ) {
				$userInfo ['POSITION'] = $ud ['POSITION'];
			}
		}
		$pageData ['flag'] = $flag;
		$pageData ['userrole'] = $this->user ['ROLE'];
		$pageData ['dept_list'] = $dept_list;
		$pageData ['depts'] = $userdept;
		$pageData ['role'] = $roleInfo;
		$BIRTHDAY = explode ( " ", $userInfo ['BIRTHDAY'] );
		$userInfo['BIRTHDAY']=$BIRTHDAY[0];
		$pageData ['user_info'] = $userInfo;
		$user_role= $this->user['ROLE'];
		$pageData ['user_role'] = $user_role;
		$pageData ['role_list'] = $rolelist;
		$this->render ( 'user_editview', $pageData );
	}
	
	/**
	 * ����༭�û�
	 * Enter description here .
	 * ..
	 */
	public function actionChangeUser() {
		$data = $this->checkRequestFormat ();
		if ($data ['subs_id'] != $this->user ['SYS_SUBS_ID'])
			$this->checkAdminPermission ();
		$Cdaosubs = new CDAOSYS_SUBS ();
		$role = new CDAORole ();
		$Cdaodept = new CDAOSYS_DEPT ();
		$Deptsubs = new CDAOSYS_DEPT_SUBS ();
		$Ctree = new CTreeData ();
		
		// �����û���ɫ
		if ($data ['ROLE']) {
			if ($this->user ['ROLE'] ['admin']) {
				$role->deleteAllUserRole ( $data ['subs_id'] ); // ɾ���û���ɫ����������Ա��ɫ
			} else {
				$role->deleteUserRole ( $data ['subs_id'] ); // ɾ���û���ɫ������������Ա
			}
			foreach ( $data ['ROLE'] as $r ) {
				$role->SetUserRole ( $data ['subs_id'], $r );
			}
		}
		
		// �����û�����
		if ($data ['DEPT']) {
			$Deptsubs->deleteAllUserDept ( $data ['subs_id'] );
			$data ['POSITION'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['POSITION'] ) ) );
			foreach ( $data ['DEPT'] as $dept ) {
				$Deptsubs->AddUserDept ( $data ['subs_id'], $dept, $data ['POSITION'] );
			}
		}
		// �����û�����
		if ($data ['PASSWD']) {
			$user ['PASSWD'] = md5 ( $data ['PASSWD'] );
		} else {
			$user ['NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['NAME'] ) ) );
			$user ['REAL_NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['REAL_NAME'] ) ) );
			$user ['EMAIL'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['EMAIL'] ) ) );
			$user ['MOBILE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['MOBILE'] ) ) );
			$user ['MSN'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['MSN'] ) ) );
			$user ['QQ'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $data ['QQ'] ) ) );
			$user ['GENDER'] = $data ['GENDER'];
			$user ['BIRTHDAY'] = $data ['BIRTHDAY'];
		}
		$result = $Cdaosubs->updateUserInfo ( $data ['subs_id'], $user );
		if ($result) {
			$this->jsAlert ( "�޸ĳɹ���" );
			$this->jsCall ( "parent.location.href = 'ea.php';" );
		} else {
			$this->jsAlert ( "�޸�ʧ��" );
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * ajax��֤��ǰ�����Ƿ���ȷ
	 */
	public function actionAjaxCheckPASSWD(){
		$data = $this->checkRequestFormat();
		$passwd = $data['passwd'];
		$name   = $data['username'];
		$Cdaosubs = new CDAOSYS_SUBS();
		$result = $Cdaosubs->isPWD($name,$passwd);
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
	 * ajax��֤�û����Ƿ����
	 */
	public function actionAjaxCheckNAME(){
		$data = $this->checkRequestFormat();
		$name = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['name'])));
		$subs_id = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['subs_id'])));
		$Cdaosubs = new CDAOSYS_SUBS();
		if($subs_id){
			$result = $Cdaosubs->ExitName($name,$subs_id);
		}else{
			$result = $Cdaosubs->adminExists($name);
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
	public function actionAjaxCheckMobile() {
		$data = $this->checkRequestFormat ();
		$mobile = $data ['mobile'];
		$userid = $data ['userid'];
		$Cdaosubs = new CDAOSYS_SUBS ();
		$result = $Cdaosubs->editMobileExist ( $mobile, $userid );
		ob_clean ();
		ob_start ();
		if ($result) {
			echo true;
		} else {
			echo false;
		}
		ob_end_flush ();
	}
	
	/**
	 * ajax��֤�����Ƿ�Ψһ
	 */
	public function actionAjaxCheckEMAIL(){
		$data = $this->checkRequestFormat();
		$email = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['email'])));
		$subs_id = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['subs_id'])));
		$Cdaosubs = new CDAOSYS_SUBS();
		if($subs_id){
			$result = $Cdaosubs->ExitEmail($email,$subs_id);
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
}
?>