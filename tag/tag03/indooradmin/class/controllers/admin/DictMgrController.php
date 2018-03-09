<?php
/**
 * 
 * @author tangqian
 * @CreatedTime 2011-01-20
 * 数据字典管理表
 */

class DictMgrController extends AdminController {
	const PAGE_SIZE = 15;
	public function __construct() {
		parent::__construct ();
		$this->checkPermission ( 'NOT' );
	}
	
	/*
	 * 显示所有分类信息
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
     * 跳转到新增数据页面
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
     * 执行新增数据操作
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
				$this->jsAlert ( '编码名称已经存在！' );
				return false;
			}
			$modelData['MAX_LENGTH'] = intval($modelData['MAX_LENGTH']);
			if ($DAOType->addCode ( $modelData )) {
				$this->writeAdminLog ( 'PRV', 'SYS', "管理员 {$this->user['NAME']} 添加系统分类“{$modelData['CODE_TYPE']}”" );
				$this->jsAlert ( '添加成功！' );
			} else {
				$this->jsAlert ( '添加失败！' );
			}
			
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
		/*$codeData = $this->checkRequestFormat ();
		$DAOType = new CDAOSYS_TYPE_CODE ();
		if ($DAOType->codeExists ( $codeData ['CODE_NAME'] )) {
			$this->jsAlert ( '编码名称已经存在！' );
			return false;
		}
		
		if ($DAOType->addCode ( $codeData )) {
			$this->writeAdminLog ( 'PRV', 'SYS', "管理员 {$this->user['NAME']} 添加资料“{$codeData['CODE_NAME']}”" );
			$this->jsAlert ( '添加成功！' );
		} else {
			$this->jsAlert ( '添加失败！' );
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
		$model->rules[]	= array('CODE_TYPE', 'required','message'=>urlencode('分类编码必填'));
		$model->rules[]	= array('CODE_NAME', 'required','message'=>urlencode('分类名称必填'));
		$model->rules[]	= array('MAX_LENGTH', 'numerical','allowEmpty' => false,'max'=>255,'min'=>1,'message'=>urlencode('最大长度必需为数字,范围1-255'));



		
		return $model;
	}

	
	
	/*
     * 查询相应数据并跳转到修改页面
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
     * 执行数据修改操作
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
				$this->writeAdminLog ( 'PRV', 'SYS', "管理员 {$this->user['NAME']} 修改系统分类“{$modelData['CODE_TYPE']}”" );
				$this->jsAlert ( '修改成功！' );
			} else {
				$this->jsAlert ( '修改失败！' );
			}	
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
	}
	
	/*
	 * 执行删除操作
	 */
	function actionDeleteCode() {
		$this->checkAdminPermission ();
		
		$code_name = $this->qstr ( $_GET ['CODE_TYPE'] );
		$DAOType = new CDAOSYS_TYPE_CODE ();
		if ($DAOType->deleteCode ( $code_name )) {
			$this->writeAdminLog ( 'PRV', 'SYS', "管理员 {$this->user['NAME']} 删除系统分类“{$code_name}”" );
			$this->jsAlert ( '删除成功！' );
		} else {
			$this->jsAlert ( '删除失败！' );
		}
		$this->jsCall ( "parent.location.href = 'ea.php?r=DictMgr';" );
	}
	
	
}
?>