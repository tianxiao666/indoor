<?php
/**
 * 文 件 名: page.class.php
 * 创 建 人: simon(samlnye@hotmail.com)
 * 创建时间: 2007-12-19
 * 最后修改: 2007-12-19(by simon)
 * 文件描述: 分页类，根据给出的总记录数取得相关的分页信息
**/

//类定义
class page
{
    //属性定义
    var $total = 0;                             //总记录数
    var $pname = array('page' => 'page');       //页面传递的相关变量名
    var $pagesize = 10;                         //每页显示的记录数
    var $listnum = 10;                          //每页显示的页码个数
    var $baseUrl = '';
    
    //构造函数
    function page($total = 0)
    {
        $this->total = (preg_match("/^\d+$/", $total) && (int)$total > 0) ? (int)$total : 0;
    }
    
    //公共方法
    function generate()
    {
        //分页信息数组
        $p = array();
        //页面传递的相关变量名
        $p['pname'] = $this->pname;
        //每页显示的页码个数
        $p['listnum'] = $this->listnum;
        //总记录数
        $p['total'] = $this->total;
        //每页记录数
        $p['pagesize'] = $this->pagesize;
        if($p['pname']['pagesize'])
        {
            if(preg_match("/^\d+$/", $_GET[$p['pname']['pagesize']]))
            {
                $p['pagesize'] = (int)$_GET[$p['pname']['pagesize']];
            }
            else if(preg_match("/^\d+$/", $_POST[$p['pname']['pagesize']]))
            {
                $p['pagesize'] = (int)$_POST[$p['pname']['pagesize']];
            }
        }
        //总页数
        $p['totalpage'] = ($p['total'] % $p['pagesize'] == 0) ? $p['total'] / $p['pagesize'] : 
                          floor($p['total'] / $p['pagesize']) + 1;
        //当前页
        $p['page'] = 1;
        if($p['pname']['page'])
        {
            if(preg_match("/^\d+$/", $_GET[$p['pname']['page']]))
            {
                $p['page'] = (int)$_GET[$p['pname']['page']];
            }
            else if(preg_match("/^\d+$/", $_POST[$p['pname']['page']]))
            {
                $p['page'] = (int)$_POST[$p['pname']['page']];
            }
        }
        $p['page'] = ($p['page'] <= $p['totalpage']) ? $p['page'] : $p['totalpage'];
        $p['page'] = ($p['page'] >= 1) ? $p['page'] : 1;
        //URL地址(不包含pagesize和page变量)
        $p['baseurl'] = $this->getBaseUrl();
        //URL地址(包含pagesize和page变量)
        $p['url'] = $p['baseurl'];
        if($p['pname']['pagesize'])
        {
            $sep1 = strstr($p['url'], '?') ? '&' : '?';
            $p['url'] .= "{$sep1}{$p['pname']['pagesize']}={$p['pagesize']}";
        }
        if($p['pname']['page'])
        {
            $sep2 = strstr($p['url'], '?') ? '&' : '?';
            $p['url'] .= "{$sep2}{$p['pname']['page']}=";
        }
        //首页
        $p['firstpage'] = 1;
        //尾页
        $p['lastpage'] = ($p['totalpage'] > 0) ? $p['totalpage'] : 1;
        //上一页
        $p['prevpage'] = $p['page'] - 1;
        if($p['totalpage'] > 0)
        {
            $p['prevpage'] = ($p['prevpage'] >= 1) ? $p['prevpage'] : 1;
        }
        else
        {
            $p['prevpage'] = 1;
        }
        //下一页
        $p['nextpage'] = $p['page'] + 1;
        if($p['totalpage'] > 0)
        {
            $p['nextpage'] = ($p['nextpage'] <= $p['totalpage']) ? $p['nextpage'] : $p['totalpage'];
        }
        else
        {
            $p['nextpage'] = 1;
        }
        //用于生成选择页数的下拉菜单的数组
        $p['allpage'] = array();
        if($p['totalpage'] > 0)
        {
            for($i = 1;$i <= $p["totalpage"];$i++)
            {
                $p['allpage']["{$i}"] = $i;
            }
        }
        //上 $p['listnum'] 页和下 $p['listnum'] 页
        $p['ppage'] = 1;
        $p['npage'] = 1;
        if($p['totalpage'] < $p['listnum'])
        {
            $p['ppage'] = $p['prevpage'];
            $p['npage'] = $p['nextpage'];
        }
        else
        {
            if($p['page'] % $p['listnum'] == 0)
            {
                $num1 = $p['page'] / $p['listnum'] - 1;
                $num2 = $p['page'] / $p['listnum'];
            }
            else
            {
                $num1 = (int)($p['page'] / $p['listnum']);
                $num2 = (int)($p['page'] / $p['listnum']) + 1;
            }
            $p['ppage'] = $num1 * $p['listnum'];
            $p['ppage'] = ($p['ppage'] >= 1) ? $p['ppage'] : 1;
            $p['npage'] = $num2 * $p['listnum'] + 1;
            $p['npage'] = ($p['npage'] >= $p['totalpage']) ? $p['nextpage'] : $p['npage'];
        }
        //用于储存当前所有页面的数组
        $p['pagelist'] = array();
        if($p['totalpage'] > 0)
        {
            if($p['page'] % $p['listnum'] == 0)
            {
                $j = $p['page'] / $p['listnum'];
            }
            else
            {
                $j = (int)($p['page'] / $p['listnum']) + 1;
            }
            $k = ($j - 1) * $p['listnum'] + 1;
            $l = $j * $p['listnum'];
            for($i = $k;$i <= $l;$i++)
            {
                if($i > $p['totalpage']) break;
                array_push($p['pagelist'], $i);
            }
        }
        //数据库记录的起始位置
        $p['offset'] = ($p['page'] - 1) * $p['pagesize'];
        $p['offset'] = ($p['offset'] < 0) ? 0 : $p['offset'];
        
        $p['showPage'] = $this->getListNum($p['totalpage'], $p['page']);
        
        //返回     
        return $p;
    }
    
    
 	private function getListNum($totalPage, $page)
    {
    	if ($totalPage < 7)
    	{
    		return;
    	}
    	
    	$minPage = 2;
    	$maxPage = $totalPage - 1;
    	
    	switch (true)
    	{
    		// first 2 page case
    		case $page <= $minPage:
    			$startPage  = 2;
    			$endPage	= $startPage + 3;
    		break;
    		
    		// the last 2 page case
    		case $page >= $maxPage:
    			$endPage	= $maxPage;
    			$startPage  = $endPage - 3;
    		break;
    		
    		case ($page - 2 <= $minPage):
    			$startPage  = 2;
    			$endPage	= $page + 2;
    		break; 
    		
    		case ($page + 2 >= $maxPage):
    			$endPage	= $maxPage;
    			$startPage	= $page - 2;
    		break;
    		
    		default:
    			$startPage	= $page - 2;
    			$endPage	= $startPage + 4;
    	}
    	
    	$listNum['startPage']  	= $startPage;
    	$listNum['endPage']		= $endPage;
    	for ($i = $startPage; $i <= $endPage; $i++)
    	{
    		$listNum['num'][] = $i;
    	}
    	
    	return $listNum;
    }
    
    //私有方法
    function getBaseUrl()
    {
        if ($this->baseUrl) return $this->baseUrl;
        $url = "http://{$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}";
        $pname = $this->pname;
        if($pname['page'])
        {
            $url = preg_replace("/\?{$pname['page']}=[^&]*&/", '?', $url);
            $url = preg_replace("/[&\?]{$pname['page']}=[^&]*/", '', $url);
        }
        if($pname['pagesize'])
        {
            $url = preg_replace("/\?{$pname['pagesize']}=[^&]*&/", '?', $url);
            $url = preg_replace("/[&\?]{$pname['pagesize']}=[^&]*/", '', $url);
        }
        $rpos = strlen($url) - 1;
        if($url{$rpos} == '?' || $url{$rpos} == '&') $url{$rpos} = '';
        return $url;
    }
}
?>