<?php
/**
 * �� �� ��: page.class.php
 * �� �� ��: simon(samlnye@hotmail.com)
 * ����ʱ��: 2007-12-19
 * ����޸�: 2007-12-19(by simon)
 * �ļ�����: ��ҳ�࣬���ݸ������ܼ�¼��ȡ����صķ�ҳ��Ϣ
**/

//�ඨ��
class page
{
    //���Զ���
    var $total = 0;                             //�ܼ�¼��
    var $pname = array('page' => 'page');       //ҳ�洫�ݵ���ر�����
    var $pagesize = 10;                         //ÿҳ��ʾ�ļ�¼��
    var $listnum = 10;                          //ÿҳ��ʾ��ҳ�����
    var $baseUrl = '';
    
    //���캯��
    function page($total = 0)
    {
        $this->total = (preg_match("/^\d+$/", $total) && (int)$total > 0) ? (int)$total : 0;
    }
    
    //��������
    function generate()
    {
        //��ҳ��Ϣ����
        $p = array();
        //ҳ�洫�ݵ���ر�����
        $p['pname'] = $this->pname;
        //ÿҳ��ʾ��ҳ�����
        $p['listnum'] = $this->listnum;
        //�ܼ�¼��
        $p['total'] = $this->total;
        //ÿҳ��¼��
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
        //��ҳ��
        $p['totalpage'] = ($p['total'] % $p['pagesize'] == 0) ? $p['total'] / $p['pagesize'] : 
                          floor($p['total'] / $p['pagesize']) + 1;
        //��ǰҳ
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
        //URL��ַ(������pagesize��page����)
        $p['baseurl'] = $this->getBaseUrl();
        //URL��ַ(����pagesize��page����)
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
        //��ҳ
        $p['firstpage'] = 1;
        //βҳ
        $p['lastpage'] = ($p['totalpage'] > 0) ? $p['totalpage'] : 1;
        //��һҳ
        $p['prevpage'] = $p['page'] - 1;
        if($p['totalpage'] > 0)
        {
            $p['prevpage'] = ($p['prevpage'] >= 1) ? $p['prevpage'] : 1;
        }
        else
        {
            $p['prevpage'] = 1;
        }
        //��һҳ
        $p['nextpage'] = $p['page'] + 1;
        if($p['totalpage'] > 0)
        {
            $p['nextpage'] = ($p['nextpage'] <= $p['totalpage']) ? $p['nextpage'] : $p['totalpage'];
        }
        else
        {
            $p['nextpage'] = 1;
        }
        //��������ѡ��ҳ���������˵�������
        $p['allpage'] = array();
        if($p['totalpage'] > 0)
        {
            for($i = 1;$i <= $p["totalpage"];$i++)
            {
                $p['allpage']["{$i}"] = $i;
            }
        }
        //�� $p['listnum'] ҳ���� $p['listnum'] ҳ
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
        //���ڴ��浱ǰ����ҳ�������
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
        //���ݿ��¼����ʼλ��
        $p['offset'] = ($p['page'] - 1) * $p['pagesize'];
        $p['offset'] = ($p['offset'] < 0) ? 0 : $p['offset'];
        
        $p['showPage'] = $this->getListNum($p['totalpage'], $p['page']);
        
        //����     
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
    
    //˽�з���
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