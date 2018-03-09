<?php
/**
 * 
 * @author tangqian
 * @CreatedTime 2011-01-20
 * �����ֵ�����
 */

class DictMgrController extends AdminController {
	const PAGE_SIZE = 15;
	public function __construct() {
		parent::__construct ();
		$this->checkPermission ( 'NOT' );
	}
	
	/*
	 * ��ʾ���з�����Ϣ
	 */
	function actionIndex() {
		$DAOType = new CDAOSYS_TYPE_CODE ();
		$p = new page ( $DAOType->getCodeCount () );
		$p->baseUrl = 'ea.php?r=DictMgr';
		$p->pagesize = self::PAGE_SIZE;
		$page = $p->generate ();
						
		$pageData ['user_role'] = $this->user ['ROLE'];
		$pageData ['code_list'] = $DAOType->getCodePageData ( $p->pagesize, $page ['page'] );
		$pageData ['page'] = $page;
		$this->render ( "dictmgr_index", $pageData );
	}
	
	/*
     * ��ת����������ҳ��
     */
	function actionAddView() {
		$this->checkAdminPermission ();
		$modelClass = 'CDAOSYS_TYPE_CODE';
		$ceditor = new CEditor ();
		$pageData ['editor'] = $ceditor;
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		//$pageData ['sys_area_code'] = $CDict->AREA_CODE_GD_NAME;
		$this->render ( "dictmgr_addview", $pageData );
	}
	
	/*
     * ִ���������ݲ���
     */
	function actionDoAdd() {
		$this->checkAdminPermission ();
		
		$modelClass	= 'CDAOSYS_TYPE_CODE';
		$modelData 	= $_POST[$modelClass];
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		
		if(!empty($modelData) && $oValidate->validate($modelData)){
			
			$DAOType = new CDAOSYS_TYPE_CODE ();
			if ($DAOType->codeExists ( $modelData['CODE_TYPE'] )) {
				$this->jsAlert ( '���������Ѿ����ڣ�' );
				return false;
			}
			$modelData['MAX_LENGTH'] = intval($modelData['MAX_LENGTH']);
			if ($DAOType->addCode ( $modelData )) {
				$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} ���ϵͳ���ࡰ{$modelData['CODE_TYPE']}��" );
				$this->jsAlert ( '��ӳɹ���' );
			} else {
				$this->jsAlert ( '���ʧ�ܣ�' );
			}
			
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
		/*$codeData = $this->checkRequestFormat ();
		$DAOType = new CDAOSYS_TYPE_CODE ();
		if ($DAOType->codeExists ( $codeData ['CODE_NAME'] )) {
			$this->jsAlert ( '���������Ѿ����ڣ�' );
			return false;
		}
		
		if ($DAOType->addCode ( $codeData )) {
			$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} ������ϡ�{$codeData['CODE_NAME']}��" );
			$this->jsAlert ( '��ӳɹ���' );
		} else {
			$this->jsAlert ( '���ʧ�ܣ�' );
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );*/
	}
	
	/**
	 * \breif get the from validate model
	 * @param $formId, the id of form
	 * @return form model object
	 */
	private function &getCDAOLS_AREAFormModel($formId)
	{
		$model  		= new stdClass();
		$model->formId 	= $formId;
		//$model->options	= array('errorElement'=>'span', 'errorClass'=>'invalid');
		$model->options	=
		array(
		'errorContainer'		=> 'div.errorSummary',
		'wrapper' 				=> 'li',
		'errorLabelContainer' 	=> 'div.errorSummary ul',
		'errorClass' 			=> 'invalid'
	);
		
		// GBK Chinese need to urlencode for json
		$model->rules[]	= array('CODE_TYPE', 'required','message'=>urlencode('����������'));
		$model->rules[]	= array('CODE_NAME', 'required','message'=>urlencode('�������Ʊ���'));
		$model->rules[]	= array('MAX_LENGTH', 'numerical','allowEmpty' => false,'max'=>255,'min'=>1,'message'=>urlencode('��󳤶ȱ���Ϊ����,��Χ1-255'));



		
		return $model;
	}

	
	
	/*
     * ��ѯ��Ӧ���ݲ���ת���޸�ҳ��
     */
	function actionEditView() {
		$this->checkAdminPermission ();
		$ceditor = new CEditor ();
		$pageData ['editor'] = $ceditor;
		$DAOType = new CDAOSYS_TYPE_CODE ();
		$pageData ['codeinfo'] = $DAOType->codeExists($_GET['CODE_TYPE']);
		$pageData['codeType']  = $_GET['CODE_TYPE'];
		$modelClass = 'CDAOSYS_TYPE_CODE';
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		$this->render ( 'dictmgr_editview', $pageData );
	}
	
	/*
     * ִ�������޸Ĳ���
     */
	function actionDoEditView() {
		$this->checkAdminPermission ();
		$modelClass	= 'CDAOSYS_TYPE_CODE';
		$modelData 	= $_POST[$modelClass];
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		if(!empty($modelData) && $oValidate->validate($modelData)){
			$modelData['MAX_LENGTH'] = intval($modelData['MAX_LENGTH']);
			$DAOType	= new CDAOSYS_TYPE_CODE();
			if ($DAOType->editCode ( $modelData )) {
				$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} �޸�ϵͳ���ࡰ{$modelData['CODE_TYPE']}��" );
				$this->jsAlert ( '�޸ĳɹ���' );
			} else {
				$this->jsAlert ( '�޸�ʧ�ܣ�' );
			}	
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
	}
	
	/*
	 * ִ��ɾ������
	 */
	function actionDeleteCode() {
		$this->checkAdminPermission ();
		
		$code_name = $this->qstr ( $_GET ['CODE_TYPE'] );
		$DAOType = new CDAOSYS_TYPE_CODE ();
		if ($DAOType->deleteCode ( $code_name )) {
			$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} ɾ��ϵͳ���ࡰ{$code_name}��" );
			$this->jsAlert ( 'ɾ���ɹ���' );
		} else {
			$this->jsAlert ( 'ɾ��ʧ�ܣ�' );
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
	}
	
	
}
?>