<?php 
/**
 * 实现导航信息管理
 * 注册表模式
 * @author huanggz
 * @author Administrator
 *
 */
class CPageNav
{
	public static $_m;
	public static $_nav=array();
	

	/**
	 * 获取类实例（注册表模式）
	 * @author huanggz
	 * @param mixed $_class_name
	 */
    public static function getInstance()
    {
    	if (empty(self::$_m))
    	{
    		return (self::$_m=new self());
    	}
    	return self::$_m;
    }
	
    /**
     * 设置页面导航信息
     * @author huanggz
     * @param array $_data(
     *   'CONTROLLER'=>控制器的名称，即类的名称(class name),
     *   'ACTION'=>动作,即方法名称(action name)，
     *   'NAME'=>用于显示的名称
     * )
     */
    public static function addPageNav($_data)
	{
		if (substr($_data['CONTROLLER'], -10)=="Controller") $_data['CONTROLLER']=substr($_data['CONTROLLER'], 0, strlen($_data['CONTROLLER'])-10);
		if (substr($_data['ACTION'], 0, 6)=="action") $_data['ACTION']=substr($_data['ACTION'], 6);
		
	 	self::$_nav[]=array(
	 	  'CONTROLLER'=>$_data['CONTROLLER'],
	 	  'ACTION'=>$_data['ACTION'],
	 	  'NAME'=>$_data['NAME']
	 	);
	}
	
	/**
	 * 取得导航数据（字符串形式）
	 * @author huanggz
	 * @return string page_nav info
	 */
	public static function getNavData()
	{
		foreach (self::$_nav as $k=>$v)
		{
			$nav .= '<a href="'.basename($_SERVER['PHP_SELF']).(empty($v['CONTROLLER'])?'">'.$v['NAME'].'</a>>>':'?r='.$v['CONTROLLER'].'/'.$v['ACTION'].'">'.$v['NAME'].'</a>>>');
		}
		return substr($nav,0,strlen($nav)-2);
	}
	
	/**
	 * 取得导航信息（数组格式）
	 * @author huanggz
	 */
	public static function getPageNav()
	{
		return self::$_nav;
	}
	/**
	 * 初始化导航信息
	 * @author huanggz
	 */
	public static function initPageNav()
	{
	 	self::$_nav=array();
	 	self::$_nav[]=array(
	 	  'CONTROLLER'=>'',
	 	  'ACTION'=>'',
	 	  'NAME'=>'首页'
	 	);
	}
	/**
	 * 
	 * @author huanggz
	 * @param mixed $_controller
	 * @param mixed $_action
	 * @param mixed $_name
	 */
	public static function setPageNav($_controller, $_action, $_name)
	{
		$return['CONTROLLER']=$_controller;
		$return['ACTION']=$_action;
		$return['NAME']=$_name;
		self::addPageNav($return);
	}
}
?>