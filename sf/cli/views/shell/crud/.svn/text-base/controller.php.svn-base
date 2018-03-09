<?php
/**
 * This is the template for generating the controller class file for crud.
 * The following variables are available in this template:
 * - $ID: the primary key name
 * - $controllerClass: the controller class name
 * - $modelClass: the model class name
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $controllerClass; ?> extends <?php if ($parentClass) echo "$parentClass\n"; else echo "Controller\n";?>
{
	const PAGE_SIZE=10;

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		// save search condition 
		$condition = CUserSession::getSessionValue('condition_<?php echo str_replace('controller','',strtolower($controllerClass)); ?>');
		if (!empty($_POST))
		{
			$condition = $_POST;
			CUserSession::setSessionValue('condition_<?php echo str_replace('controller','',strtolower($controllerClass)); ?>', $condition);
		}
				
		$model			= new <?php echo $modelClass; ?>();
		$whereSql		= $this->genWhereSql($condition);
        $p 				= new page($model->count($whereSql));
        $p->baseUrl 	= $this->getPhpSelf().'?r=<?php echo str_replace('controller','',strtolower($controllerClass)); ?>';
        $p->pagesize 	= self::PAGE_SIZE;
        $page 			= $p->generate();
        
		$pageData['modelList'] 	= $model->getPageData($p->pagesize, $page['page'], $whereSql);
		$pageData['page'] 		= $page;
		$pageData['condition']	= $condition;
		
		$this->render('<?php echo str_replace('controller','',strtolower($controllerClass))."_index"; ?>', $pageData);
	}
	
	private function genWhereSql($condition)
	{
		$whereSql = ' 1=1 ';
		
		// change this if you what
		if ($condition['<?php echo $ID; ?>'])
		{
			$whereSql .= " AND <?php echo $ID; ?> = {$condition['<?php echo $ID; ?>']} ";
		}
		
		if ($condition['startDate']) {
			$whereSql .= " AND CREATE_TIME >= '{$condition['startDate']}' ";
		}
		if ($condition['endDate']) {
			$endDate   = date("Y-m-d",strtotime('+1 day',strtotime($condition['endDate'])));
			$whereSql .= " AND CREATE_TIME < '{$endDate}' ";
		}
		
		return $whereSql;
	}


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$modelClass	= '<?php echo $modelClass; ?>';
		$model		= new $modelClass();
		$modelData 	= $_POST[$modelClass];

		$formModel	= $this->get<?php echo $modelClass; ?>FormModel($modelClass);
		$oValidate	= new CJfValidate($formModel);
		if(!empty($modelData) && $oValidate->validate($modelData))
		{
			if ($id = $model->doInsert($modelData))
				$this->redirect(array('<?php echo str_replace('controller','',strtolower($controllerClass)); ?>/update','id'=>$id));
		}

		$pageData['model'] 		= $model->GetAttributeNames();
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		$this->render('<?php echo str_replace('controller','',strtolower($controllerClass))."_create"; ?>', $pageData);
	}
	
	
	/**
	 * \breif get the from validate model
	 * @param $formId, the id of form
	 * @return form model object
	 */
	private function &get<?php echo $modelClass; ?>FormModel($formId)
	{
		$model  		= new stdClass();
		$model->formId 	= $formId;
		$model->options	= array('errorElement'=>'span', 'errorClass'=>'invalid');
		
		// GBK Chinese need to urlencode for json
		$model->rules[]	= array('NAME, PASSWD', 'required');
		
		return $model;
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model		= $this->loadModel();
		$modelData 	= $_POST['<?php echo $modelClass; ?>'];
		
		$formModel	= $this->get<?php echo $modelClass; ?>FormModel('<?php echo $modelClass; ?>');
		$oValidate	= new CJfValidate($formModel);
		if(!empty($modelData) && $oValidate->validate($modelData))
		{
			$model->setFrom($modelData);
			if($model->save())
				$this->redirect(array('<?php echo str_replace('controller','',strtolower($controllerClass)); ?>/update','id'=>$model->getPrimaryKeyValue()));
		}
		
		$pageData['model'] 		= $model->toArray();
		$pageData['validatJs'] 	= $oValidate->getJqueryValidate();
		$this->render('<?php echo str_replace('controller','',strtolower($controllerClass))."_update"; ?>',$pageData);
	}


	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		$this->loadModel()->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_POST['ajax']))
			$this->redirect(array('<?php echo str_replace('controller','',strtolower($controllerClass)); ?>/index'));
	}
	
	
	/**
	 * \brief export the search result
	 */
	public function actionExport()
	{
		// search condition 
		$condition 	= empty($_POST) ? CUserSession::getSessionValue('condition') : $_POST;
		$model		= new <?php echo $modelClass; ?>();
		$whereSql	= $this->genWhereSql($condition);
		$modelCnt	= $model->count($whereSql);
		if ($modelCnt <= 0)
		{
			CJsCall::jsAlert('找不到相关记录！');
			CJsCall::jsJumpUrl($this->getPhpSelf().'?r=<?php echo str_replace('controller','',strtolower($controllerClass)); ?>/index');
		}
		
		// export file
		$pageSize = 4000;
		$csv	  = '';
		<?php foreach($columns as $column): ?>
	    	$csv .= '"<?php echo $column; ?>",';
		<?php endforeach; ?>
		$csv .= "\n";
				
		for($pageNum = 0; $pageNum*$pageSize <= $modelCnt; $pageNum++)
		{
			$modelList = $model->getPageData($pageSize, $pageNum+1, $whereSql);
			foreach($modelList as $aModel)
			{
				<?php foreach($columns as $column): ?>
				$csv .=  $aModel['<?php echo $column; ?>'] . ',';
				<?php endforeach; ?>
				$csv .= "\n";
			}
		}
		
		$fileName = "export_file.csv";
		header("content-type:text/comma-separated-values;filename={$fileName}");
		header("content-disposition:attachment;filename={$fileName}");
		echo $csv;
	}


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			$modelId = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
			if($modelId)
			{
				$oModel			= new <?php echo $modelClass; ?>();
				if ($oModel->loadModelByPk($modelId))
					$this->_model = $oModel;
			}
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
