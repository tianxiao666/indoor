<?php
/**
 * 移动终端信号管理类
 * @author chao.xj
 * 
 */
class MtSignalMgrController extends AdminController {
	public $_page = 1; // 当前页
	public $_count =15; // 每页个数
	protected $pageSize = 15;
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 移动终端信号列表
	 * Enter description here .
	 */
	public function actionIndex() {
		$flag = $_GET ["flag"];
		$MTSIGNALSEARCHINFO = array ();
		if ($flag == 2) {
			$MTSIGNALSEARCHINFO = $_POST ["MTSIGNALSEARCHINFO"];
			if (empty ( $MTSIGNALSEARCHINFO )) {
				$MTSIGNALSEARCHINFO = CUserSession::getSessionValue ( "MTSIGNALSEARCHINFO" );
			} else {
				CUserSession::setSessionValue ( "MTSIGNALSEARCHINFO", $MTSIGNALSEARCHINFO );
			}
		} else {
			CUserSession::removeSessionValue ( "MTSIGNALSEARCHINFO" );
		}
		$whereSql = " 1=1";
		$pageNum = $_GET ["page"];
		$baseUrlFlag = "";
		if (empty ( $pageNum )) {
			$pageNum = 1;
		}
		$Cdao_MtSignal = new CDAOCB_MTSIGNAL();
		if (! empty ( $MTSIGNALSEARCHINFO )) {
			$baseUrlFlag = "&flag=2";
			$CITYNAME = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $MTSIGNALSEARCHINFO ["CITYNAME"] ) ) );
			$startDate = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $MTSIGNALSEARCHINFO ["startDate"] ) ) );
			$endDate = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $MTSIGNALSEARCHINFO ["endDate"] ) ) );
			if (! empty ( $CITYNAME )) {
				$CITYNAME = strtolower ( $CITYNAME );
				$whereSql = $whereSql . " AND REGION_NAME LIKE '%{$CITYNAME}%'";
			}
			
			if (! empty ( $startDate ) && !empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate} 00:00:00','yyyy-mm-dd hh24:mi:ss') and  mea_date < = to_date('{$endDate} 23:59:59','yyyy-mm-dd hh24:mi:ss')";
			}elseif (! empty ( $startDate ) && empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate} 00:00:00','yyyy-mm-dd hh24:mi:ss')";
			}elseif ( empty ( $startDate ) && !empty( $endDate )) {
				$whereSql = $whereSql . " AND mea_date < = to_date('{$endDate} 23:59:59','yyyy-mm-dd hh24:mi:ss')";
			}
			$pageData ["MTSIGNALSEARCHINFO"] = $MTSIGNALSEARCHINFO;
		}
		$whereSql = $whereSql . " order by MEA_DATE desc";
		$result = $Cdao_MtSignal->getMtSignalPageData($whereSql, $pageNum, $this->pageSize);
		$total = $result->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = "ea.php?r=MtSignalMgr" . $baseUrlFlag;
		$p->pagesize = $this->pageSize;
		$page = $p->generate ();
		$pageData ["page"] = $page;
		$building_list = $result->_array;
		
		$pageData ["building_list"] = $building_list;
		$this->render ( "mtsignalmgr_index", $pageData );
	}
	
}