<?php
/**
 * 理想AP管理类
 * @author chao.xj
 * 
 */
class IdeaApMgrController extends AdminController {
	public $_page = 1; // 当前页
	public $_count =15; // 每页个数
	protected $pageSize = 15;
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 理想AP列表
	 * Enter description here .
	 */
	public function actionIndex() {
		$flag = $_GET ["flag"];
		$IDEALAPSEARCHINFO = array ();
		if ($flag == 2) {
			$IDEALAPSEARCHINFO = $_POST ["IDEALAPSEARCHINFO"];
			if (empty ( $IDEALAPSEARCHINFO )) {
				$IDEALAPSEARCHINFO = CUserSession::getSessionValue ( "IDEALAPSEARCHINFO" );
			} else {
				CUserSession::setSessionValue ( "IDEALAPSEARCHINFO", $IDEALAPSEARCHINFO );
			}
		} else {
			CUserSession::removeSessionValue ( "IDEALAPSEARCHINFO" );
		}
		$whereSql = " 1=1";
		$pageNum = $_GET ["page"];
		$baseUrlFlag = "";
		if (empty ( $pageNum )) {
			$pageNum = 1;
		}
		$Cdao_IdealAp = new CDAOCB_IDEALAP();
		if (! empty ( $IDEALAPSEARCHINFO )) {
			$baseUrlFlag = "&flag=2";
			$CITYNAME = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $IDEALAPSEARCHINFO ["CITYNAME"] ) ) );
			$startDate = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $IDEALAPSEARCHINFO ["startDate"] ) ) );
			$endDate = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $IDEALAPSEARCHINFO ["endDate"] ) ) );
			if (! empty ( $CITYNAME )) {
				$CITYNAME = strtolower ( $CITYNAME );
				$whereSql = $whereSql . " AND REGION_NAME LIKE '%{$CITYNAME}%'";
			}
			if (! empty ( $startDate ) && !empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate}','yyyy-mm-dd') and  mea_date < = to_date('{$endDate}','yyyy-mm-dd')";
			}elseif (! empty ( $startDate ) && empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate}','yyyy-mm-dd')";
			}elseif ( empty ( $startDate ) && !empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date < = to_date('{$endDate}','yyyy-mm-dd')";
			}
			$pageData ["IDEALAPSEARCHINFO"] = $IDEALAPSEARCHINFO;
		}
		$whereSql = $whereSql . " order by MEA_DATE desc";
		$result = $Cdao_IdealAp->getIdeaApPageData($whereSql, $pageNum, $this->pageSize);
		$total = $result->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = "ea.php?r=IdeaApMgr" . $baseUrlFlag;
		$p->pagesize = $this->pageSize;
		$page = $p->generate ();
		$pageData ["page"] = $page;
		$building_list = $result->_array;
		
		$pageData ["building_list"] = $building_list;
		$this->render ( "idealapmgr_index", $pageData );
	}
	
}