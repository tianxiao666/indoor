<?php
/**
 * This is the template for generating a controller class file.
 * The following variables are available in this template:
 * - $className: the class name of the controller
 * - $actions: a list of action names for the controller
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $className; ?> extends <?php if ($parentClass) echo "$parentClass\n"; else echo "Controller\n";?>
{
<?php foreach($actions as $action): ?>
	public function action<?php echo ucfirst($action); ?>()
	{
		$pageData = array();
		
		//set the pageData here
		
		$this->render('<?php echo str_replace('controller','',strtolower($className))."_".$action; ?>', $pageData);
	}

<?php endforeach; ?>
}