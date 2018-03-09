<?php 
class CTreeData{
	 /*
	 * ar是需要得到树形结构的数组,$idname是数组中每条数据的标识(数据库字段名)
	 * $parentname是每条数据的父标识(数据库字段名)
	 * 例如:array(id=>3,pid=>2,name=>3)这条数据中id是标识,pid是父标识,则$idname='id',$parentname='pid'
	 */
  function getTreeData($ar,$idname,$parentname){
  	if($ar){//数组不为空
		    foreach($ar as $key=>$info){
			 	$parent[$key] = $info[$parentname];
			 }
			//排序，为避免数据中父节点在子节点后面出现，这种情况在多次修改数据后经常会发生的
			//排序的目的就是防止这种情况造成的混乱
			array_multisort($parent, SORT_ASC,$ar);
			$d = array();
			//定义索引数组，用于记录节点在目标数组的位置
			$ind = array();
		
			foreach($ar as $v) {
				$v[child] = array(); //给每个节点附加一个child项
				if($v[$parentname] == 0) {
					$i = count($d);
					$d[$i] = $v;
					$ind[$v[$idname]] =& $d[$i];
				}else {
					$i = count($ind[$v[$parentname]][child]);
					$ind[$v[$parentname]][child][$i] = $v;
					$ind[$v[$idname]] =& $ind[$v[$parentname]][child][$i];
				}
			}
		   return $d;
  	}
  	else{
  		   return;
  	}
}
}

?>