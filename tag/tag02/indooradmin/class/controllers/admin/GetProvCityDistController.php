<?php
/**
 * 
 * @author yzb
 *
 */
class GetProvCityDistController extends AdminController {
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * get the province, city and district
	 * @param string $prov, if return city data from selected province
	 * @param string $city, if return district data from selected city
	 */
	public function actionGetAreaName($prov = false, $city = false, $country = false,$default_value='') {
		$prov = isset ( $_GET ['prov'] ) ? $_GET ['prov'] : $prov;
		$city = isset ( $_GET ['city'] ) ? $_GET ['city'] : $city;
		$country = isset ( $_GET ['country'] ) ? $_GET ['country'] : $country;
		$default_value = isset ( $_GET ['default_value'] ) ? trim($_GET ['default_value']) : $default_value;
		$return = $this->getAreaName ( $prov, $city, $country );
		if (isset ( $_GET ['returnvalue'] ) && ! empty ( $return [$_GET ['returnvalue']] )) {
			$get = '<option value=\'\'>- ' . iconv ( 'gbk', 'utf-8', '��ѡ��' ) . ' -</option>';
			foreach ( $return [$_GET ['returnvalue']] as $k => $v ) {
				if ($default_value == iconv ( 'gbk', 'utf-8', trim($k))){
					
					$get .= '<option value="' . iconv ( 'gbk', 'utf-8', $k ) . '" selected>' . iconv ( 'gbk', 'utf-8', $v ) . '</option>';				
				}else
					$get .= '<option value="' . iconv ( 'gbk', 'utf-8', $k ) . '">' . iconv ( 'gbk', 'utf-8', $v ) . '</option>';
			}
		} else {
			$get = '<option value=\'\'>- ' . iconv ( 'gbk', 'utf-8', '����ѡ��' ) . ' -</option>';
		}
		echo $get;
	}
	
	/*
	 * ajax��ѯ��������
	 * 
	 */
	public function actionajaxAreaList() {
		$dao_sys_region = new CDAOSYS_REGION();
		$continent_info = $dao_sys_region->getInfo("REGION_NAME = '����'");
		$country_info = $dao_sys_region->getInfo("REGION_NAME = '�й�'");
		$continent = isset ( $_GET ['continent'] ) ? $_GET ['continent'] : $continent_info['REGION_ID'];
		$country = isset ( $_GET ['country'] ) ? $_GET ['country'] : $country_info['REGION_ID'];
		$prov = isset ( $_GET ['prov'] ) ? $_GET ['prov'] : 0;
		$city = isset ( $_GET ['city'] ) ? $_GET ['city'] : 0;
		$district = isset ( $_GET ['district'] ) ? $_GET ['district'] : 0;
		$DAOLS_AREA = new CDAOLS_AREA ( );
		$code_type = $DAOLS_AREA->getAreaByPCD ($continent , $country, $prov , $city , $district );
		if ($code_type) {
			$html .= '<option value=\'\'>- ��ѡ�� -</option>';
			foreach ( $code_type as $value ) {
				$html .= '<option value="' . $value ['AREA_ID'] . '">' . $value ['AREA_NAME'] . '</option>';
			}
		} else {
			$html .= '<option value=\'\'>' . '- ����ѡ�� -' . '</option>';
		}
		echo iconv ( 'GBK', 'UTF-8', $html );
	}
	
    /*
	 * ajax��ѯ��������
	 * 
	 */
	public function actionajaxSpotList() {
//		$dao_sys_region = new CDAOSYS_REGION();
//		$continent_info = $dao_sys_region->getInfo("REGION_NAME = '����'");
//		$country_info = $dao_sys_region->getInfo("REGION_NAME = '�й�'");
//		$continent = isset ( $_GET ['continent'] ) ? $_GET ['continent'] : $continent_info['REGION_ID'];
//		$country = isset ( $_GET ['country'] ) ? $_GET ['country'] : $country_info['REGION_ID'];
//		$prov = isset ( $_GET ['prov'] ) ? $_GET ['prov'] : 0;
//		$city = isset ( $_GET ['city'] ) ? $_GET ['city'] : 0;
//		$district = isset ( $_GET ['district'] ) ? $_GET ['district'] : 0;
		$areaid = isset ( $_GET ['areaid'] ) ? $_GET ['areaid'] : 0;
		$DAOLS_SPOT = new CDAOLS_SPOT ( );
		$code_type = $DAOLS_SPOT->getAllScenicSpots ($areaid);
		if ($code_type) {
			$html .= '<option value=\'\'>- ��ѡ�� -</option>';
			foreach ( $code_type as $value ) {
				$html .= '<option value="' . $value ['SPOT_ID'] . '">' . $value ['SPOT_NAME'] . '</option>';
			}
		} else {
			$html .= '<option value=\'\'>' . '- ����ѡ�� -' . '</option>';
		}
		echo iconv ( 'GBK', 'UTF-8', $html );
	}
	
	/**
	 * get the province, city and district
	 * @param string $prov, if return city data from selected province
	 * @param string $city, if return district data from selected city
	 */
	public function getAreaName($prov = false, $city = false, $country = false) {
		$CDAOOption = new CDAOSYS_OPTION_CODE ( );
		$COUNTS = $CDAOOption->findTypeByCode ( 'COUNTRY' );
		foreach ( $COUNTS as $key => $Info ) {
			$return ['country'] [$Info ['OPTION_VALUE']] = $Info ['OPTION_NAME'];
		}
		if ($country) {
			$data = $CDAOOption->findTypeByCode ( iconv ( 'utf-8', 'gbk', $country ), 'PARENT');
			foreach ( $data as $k => $v ) {
				$return ['prov'] [$v ['OPTION_VALUE']] = $v ['OPTION_NAME'];
			}
			if ($prov) {
				if (StringTools::is_utf8 ( $prov )) {
					$data_city = $CDAOOption->findTypeByCode ( iconv ( 'utf-8', 'gbk', $prov ), 'PARENT' );
				} else {
					$data_city = $CDAOOption->findTypeByCode ( $prov, 'PARENT' );
				}
				foreach ( $data_city as $kcity => $vcity ) {
					$return ['city'] [$vcity ['OPTION_VALUE']] = $vcity ['OPTION_NAME'];
				}
				if ($city) {
					if (StringTools::is_utf8 ( $city )) {
						$data_district = $CDAOOption->findTypeByCode ( iconv ( 'utf-8', 'gbk', $city ), 'PARENT' );
					} else {
						$data_district = $CDAOOption->findTypeByCode ( $city, 'PARENT' );
					}
					foreach ( $data_district as $kdistrict => $vdistrict ) {
						$return ['district'] [$vdistrict ['OPTION_VALUE']] = $vdistrict ['OPTION_NAME'];
					}
				}
			}
		
		}
		return $return;
	}
	
	
	/*
	 * ajaxģ����ѯ���������ݾ�������
	 */
	function actionAjaxGetAreas()
	{
		$name = isset($_GET['name'])?$_GET['name']:$prov;

		$DAOLS_AREA = new CDAOLS_AREA();
		$name	= iconv("UTF-8","GBK",$name);	
		$code_type = $DAOLS_AREA->getAll("AREA_NAME LIKE '%" .trim($this->inject_check($name)). "%' AND STATUS = 'A'");
		
		if ($code_type){
			$html .='<option value=\'\'>- ��ѡ�� -</option>';
			foreach ($code_type as $value){
				$html .='<option value="'.$value['AREA_ID'].'">'.$value['AREA_NAME'].'</option>';
			}
		}else{
			$html.='<option value=\'\'>'.'- ����ѡ�� -'.'</option>';
		}

		echo iconv('GBK','UTF-8',$html);
	}
	
	/**
	 * �����������
	 * @param $str
	 */
	function inject_check($str) {
		$tmp = preg_match ( '/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|%/i', $str ); // ���й���
		if ($tmp) {
			return "====================";
		} else {
			return $str;
		}
	}

	/**
	 * ��ȡ��������
	 * @param  $city ����
	 * @param  $prov ʡ��
	 * @param  $country ����
	 * @param  $continent ��
	 */
	public function actionGetAdminRegion($city = false, $prov = false, $country = false, $continent = false, $return_value = FALSE)
	{
		$city = empty($_GET['city'])?$city:$_GET['city'];
		$prov = empty($_GET['prov'])?$prov:$_GET['prov'];
		$country = empty($_GET['country'])?$country:$_GET['country'];
		$continent = empty($_GET['continent'])?$continent:$_GET['continent'];
		
		$return_value = empty($_GET['returnvalue'])?$return_value:$_GET['returnvalue'];
		
		$model = new CDAOSYS_OPTION_CODE();
		//ȡ�����б�
		$country_options = $model->findTypeByCode ( 'COUNTRY' );
		foreach ( $country_options as $key => $Info ) 
		{
			$return ['country'] [$Info ['OPTION_CODE_ID']] = $Info ['OPTION_NAME'];
		}
		if ($country) 
		{
			//ȡʡ���б�
			$data = $model->findByCodeID (  $country , 'PROVINCE');
			foreach ( $data as $k => $v ) 
			{
				$return ['prov'] [$v ['OPTION_CODE_ID']] = $v ['OPTION_NAME'];
			}
			if ($prov) 
			{
				//ȡ�����б�
				$data_city = $model->findByCodeID (  $prov , 'CITY' );
				foreach ( $data_city as $kcity => $vcity ) 
				{
					$return ['city'] [$vcity ['OPTION_CODE_ID']] = $vcity ['OPTION_NAME'];
				}
				if ($city) 
				{
					//ȡ�����б�
					$data_district = $model->findByCodeID ( $city , 'DISTRICT' );
					foreach ( $data_district as $kdistrict => $vdistrict ) 
					{
						$return ['district'] [$vdistrict ['OPTION_CODE_ID']] = $vdistrict ['OPTION_NAME'];
					}
				}
			}
		}
		switch ($return_value)
		{
			case 'country':
				echo json_encode(StringUtils::convertToUtf8($return['country']));
				break;
			case 'prov':
				echo json_encode(StringUtils::convertToUtf8($return['prov']));
				break;
			case 'city':
				echo json_encode(StringUtils::convertToUtf8($return['city']));
				break;
			case 'district':
				echo json_encode(StringUtils::convertToUtf8($return['district']));
				break;
			default :
				echo json_encode(StringUtils::convertToUtf8($return));
		}
		
		return $return;
	}
/*
	 * @author:duhw
	 * ѡ��ʡ����(��������)�Ĵ����¼�
	 */
    public function actionAjaxRequest(){
    	$dao_sys_region = new CDAOSYS_REGION();
		$val = $_POST['val'];
		$flag = $_POST['flag'];//�Ƿ���Ҫ������ѡ�صĶ���������γ��
		if (empty($val)){
			$html.='<option value=\'\'>'.'����ѡ�� '.'</option>';
		}else{
			$val = $val?intval($val):0;
			$regionInfo = $dao_sys_region->getRegionByID($val);
			$code_type  = $dao_sys_region->getRegionListByParentId($val);
			if ($code_type){
				$html .='<option value=\'\'>-��ѡ�� -</option>';
				foreach ($code_type as $value){
					$html .='<option value="'.$value['REGION_ID'].'">'.$value['REGION_NAME'].'</option>';
				}
			}else{
				$html.='<option value=\'\'>'.'����ѡ�� '.'</option>';
			}
		}
	    $html = iconv('GBK','UTF-8',$html);
	    if($flag == 'true'){
			$array = array("e_longitude" => $regionInfo['E_LONGITUDE'],
			               "w_longitude" => $regionInfo['W_LONGITUDE'],
			               "n_latitude"  => $regionInfo['N_LATITUDE'],
			               "s_latitude"  => $regionInfo['S_LATITUDE'],
			               "longitude"   => $regionInfo['LONGITUDE'],
			               "latitude"   => $regionInfo['LATITUDE'],
			               "html" =>$html);  
	    } 
	    else{
	    	$array = array("html" =>$html);  
	    }
		echo json_encode($array);
	}
/*
	 * @author:duhw
	 * ѡ��ʡ����(Ŀ�ĵ�)�Ĵ����¼�
	 */
    public function actionAjaxDestRequest(){
    	$dao_ls_destinations = new CDAOLS_DESTINATIONS();
		$val = $_POST['val'];
		if (empty($val)){
			$html.='<option value=\'\'>'.'����ѡ�� '.'</option>';
		}else{
			$val = $val?intval($val):0;
			$code_type  = $dao_ls_destinations->getChildDestListByDest($val);
			if ($code_type){
				$html .='<option value=\'\'>-��ѡ�� -</option>';
				foreach ($code_type as $value){
					$html .='<option value="'.$value['DEST_ID'].'">'.$value['DEST_NAME'].'</option>';
				}
			}else{
				$html.='<option value=\'\'>'.'����ѡ�� '.'</option>';
			}
		}
	    $html = iconv('GBK','UTF-8',$html);
	    $array = array("html" =>$html);  
		echo json_encode($array);
	}
}
?>