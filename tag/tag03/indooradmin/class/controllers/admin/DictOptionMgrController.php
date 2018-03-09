<?php
/**
 * 
 * @author tangqian
 * @CreatedTime 2011-01-20
 * �����ֵ�����
 */

class DictOptionMgrController extends AdminController {
	const PAGE_SIZE = 15;
	public function __construct() {
		parent::__construct ();
		$this->checkPermission ( 'NOT' );
	}
	
	/*
	 * ��ʾ���з�����Ϣ
	 */
	function actionIndex() {
		
		$DAOType 	= new CDAOSYS_OPTION_CODE ();
		$CODE_TYPE = CUserSession::getSessionValue('DictOpt_CODETYPE');
		if ($_GET['CODE_TYPE'])
		{
			$CODE_TYPE = $_GET['CODE_TYPE'];
			CUserSession::setSessionValue('DictOpt_CODETYPE', $CODE_TYPE);
		}
		$condition	= array('CODE_TYPE'=>$CODE_TYPE);
		$p = new page ( $DAOType->getCodeCount ($condition) );
		$p->baseUrl = 'ea.php?r=DictOptionMgr';
		$p->pagesize = self::PAGE_SIZE;
		$page = $p->generate ();
						
		$pageData ['user_role'] = $this->user ['ROLE'];
		$pageData ['CODE_TYPE'] = $CODE_TYPE;
		
		$pageData ['code_list'] = $DAOType->getCodePageData ($condition, $p->pagesize, $page ['page'] );
		$pageData ['page'] = $page;
		$this->render ( "dictoptionmgr_index", $pageData );
	}
	
	/*
     * ��ת����������ҳ��
     */
	function actionAddView() {
		$this->checkAdminPermission ();
		$modelClass = 'CDAOSYS_OPTION_CODE';
		$CODE_TYPE = CUserSession::getSessionValue('DictOpt_CODETYPE');
		$ceditor = new CEditor ();
		$pageData ['editor'] = $ceditor;
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		
		//��ѯ���з���
		$DaoTypeCode = new CDAOSYS_TYPE_CODE();
		$pageData['allCodeType'] = $DaoTypeCode->findAllType();
		
		$pageData['CODE_TYPE']	= $_GET['CODE_TYPE'];
		$oValidate	= new CJfValidate($formModel);
		$pageData ['CODE_TYPE'] = $CODE_TYPE;
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		//$pageData ['sys_area_code'] = $CDict->AREA_CODE_GD_NAME;
		$this->render ( "dictoptionmgr_addview", $pageData );
	}
	
	/*
     * ִ���������ݲ���
     */
	function actionDoAdd() {
		$this->checkAdminPermission ();
		
		$modelClass	= 'CDAOSYS_OPTION_CODE';
		SF::log($modelClass);
		$modelData 	= $_POST[$modelClass];
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
	
		if(!empty($modelData) && $oValidate->validate($modelData)){
			
			$DAOType = new CDAOSYS_OPTION_CODE ();
			if ($DAOType->codeExists ( $modelData['OPTION_VALUE'],$modelData['CODE_TYPE'] )) {
				$this->jsAlert ( 'ѡ������Ѿ����ڣ�' );
				return false;
			}
			if ($DAOType->addCode ( $modelData )) {
				$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} ���ϵͳ���ࡰ{$modelData['CODE_TYPE']}����ѡ����롰{$modelData['OPTION_VALUE']}��" );
				$this->jsAlert ( '��ӳɹ���' );
			} else {
				$this->jsAlert ( '���ʧ�ܣ�' );
			}
			
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictOptionMgr';" );
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
		
		$CODE_TYPE = CUserSession::getSessionValue('DictOpt_CODETYPE');
		$cdaoType = new CDAOSYS_TYPE_CODE();
		$data	  = $cdaoType->codeExists($CODE_TYPE);
		// GBK Chinese need to urlencode for json
		$model->rules[]	= array('OPTION_VALUE', 'length','max'=>$data['MAX_LENGTH'],'allowEmpty' => false,'message'=>urlencode("ѡ��������,��󳤶Ȳ�����{$data['MAX_LENGTH']}"));
		$model->rules[]	= array('OPTION_NAME', 'required','message'=>urlencode('ѡ�����Ʊ���'));
		$model->rules[]	= array('WEIGHT', 'numerical','allowEmpty' => false,'message'=>urlencode('Ȩ�ر���Ϊ����'));
		$model->rules[]	= array('PARENT_TYPE_VALUE', 'length','max' => 20,'allowEmpty' => true,'message'=>urlencode('��������󳤶Ȳ��ܳ���20λ'));
		$model->rules[]	= array('WEIGHT', 'length','max' => 5,'allowEmpty' => false,'message'=>urlencode('Ȩ�ر���Ϊ����,��󳤶Ȳ��ܳ���5λ'));
		$model->rules[]	= array('CODE_TYPE', 'required','message'=>urlencode('�����������'));
			/*echo "<pre>";
			print_r($model);
			echo "</pre>";
			die();
	*/


		
		return $model;
	}

	
	
	/*
     * ��ѯ��Ӧ���ݲ���ת���޸�ҳ��
     */
	function actionEditView() {
		$this->checkAdminPermission ();
		$CODE_TYPE = CUserSession::getSessionValue('DictOpt_CODETYPE');
		$ceditor = new CEditor ();
		$pageData ['editor'] = $ceditor;
		
		
		$DAOType = new CDAOSYS_OPTION_CODE ();
		$typeData = $DAOType->codeIdExists($_GET['OPTION_CODE_ID']);
		$pageData['codeinfo'] = $typeData;
		$pageData['parent_value'] = $DAOType->getOptionByCode($typeData['PARENT_TYPE_VALUE']);
		
//		$pageData ['codeinfo'] = $DAOType->codeExists($_GET['OPTION_VALUE'],$CODE_TYPE);
		$modelClass = 'CDAOSYS_OPTION_CODE';
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		
 	    $DaoTypeCode = new CDAOSYS_TYPE_CODE();
		$pageData['allCodeType'] = $DaoTypeCode->findAllType();
		
		$pageData['OPTION_VALUE'] = $_GET['OPTION_VALUE'];
		$pageData['CODE_TYPE']  = $CODE_TYPE;
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		$this->render ( 'dictoptionmgr_editview', $pageData );
	}
	
	/*
     * ִ�������޸Ĳ���
     */
	function actionDoEditView() {
		$this->checkAdminPermission ();
		$modelClass	= 'CDAOSYS_OPTION_CODE';
		$modelData 	= $_POST[$modelClass];
		SF::log($modelData);
		$formModel	= $this->getCDAOLS_AREAFormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		if(!empty($modelData) && $oValidate->validate($modelData)){
			$DAOType	= new CDAOSYS_OPTION_CODE();
			if ($DAOType->editCode ( $modelData )) {
				$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} �޸�ϵͳ���ࡰ{$modelData['CODE_TYPE']}����ѡ����롰{$modelData['OPTION_VALUE']}��" );
				$this->jsAlert ( '�޸ĳɹ���' );
			} else {
				$this->jsAlert ( '�޸�ʧ�ܣ�' );
			}	
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictOptionMgr';" );
	}
	
	/*
	 * ִ��ɾ������
	 */
	function actionDeleteCode() {
		$this->checkAdminPermission ();
//		$CODE_TYPE = CUserSession::getSessionValue('DictOpt_CODETYPE');
//		$modelData = array('CODE_TYPE'=>$CODE_TYPE,'OPTION_VALUE'=>$_GET['OPTION_VALUE']);
		$DAOType = new CDAOSYS_OPTION_CODE ();
		if ($DAOType->deleteCode ( $_GET['OPTION_CODE_ID'] )) {
			$this->writeAdminLog ( 'PRV', 'SYS', "����Ա {$this->user['NAME']} ɾ��ϵͳ���ࡰ{$CODE_TYPE}����ѡ����롰{$modelData['OPTION_VALUE']}��" );
			$this->jsAlert ( 'ɾ���ɹ���' );
		} else {
			$this->jsAlert ( 'ɾ��ʧ�ܣ�' );
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictOptionMgr';" );
	}
	
	public function actionAjaxType(){

		$val = $_POST['val'];
		if (empty($val)){
			return false;
		}
		$test = iconv("UTF-8","GBK",$val);
		$DaoCode = new CDAOSYS_OPTION_CODE();
		$code_type = $DaoCode->getOptionByCode($test);
		if ($code_type){
			$html .='<option value=\'\'>- ��ѡ�� -</option>';
			foreach ($code_type as $value){
				$html .='<option value="'.$value['OPTION_VALUE'].'">'.$value['OPTION_NAME'].'</option>';
			}
		}else{
			$html.='<option value=\'\'>'.'- ����ѡ�� -'.'</option>';
		}

		SF::log($html);
		echo iconv('GBK','UTF-8',$html);
	}
}
?>