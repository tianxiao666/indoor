<?php
/**
 * This is the template for generating a model class file.
 * The following variables are available in this template:
 * - $className: the class name
 * - $tableName: the table name
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $className; ?> extends CActiveRecord
{
	var $_table = '<?php echo $tableName; ?>';	//associated database table

	/**
	 * \brief build the construct.
	 */
    function __construct()
    {
        parent::__construct();
    }
}