<?php
/**
 * ����ͼ���ݹ���
 * Enter description here ...
 * @author panyf
 * 2012.2.16
 *
 */
class DrawMgrController extends AdminController
{

	public  $PxUnit= 28;
	
	
	public function __construct(){
		parent::__construct();
		//$this->checkPermission("EDT");
		define('FONT_FILE_YAHEI', SF_ROOT.'font/YAHEI.ttf');//�ź�
		define('FONT_FILE_HW_XW', SF_ROOT.'font/HW_XW.ttf');//������κ
		define('FONT_FILE_HW_KT', SF_ROOT.'font/HW_KT.ttf');//���Ŀ���
	}
	/**
	 * �����б���ʾ
	 *
	 */
	public function actionIndex()
	{
		
	}
	
	/**
	 * �޸ĵ���ͼ��״̬
	 *
	 */
	public function actionDrawMap()
	{
		
		 $floor_id = $_REQUEST['floor_id']?intval($_REQUEST['floor_id']):0;
		
		if($floor_id==0){
			echo "û��¥��������޷�����ƽ��ͼ��";
		}
		$dao_draw_map = new CDAODM_DRAW_MAP();
		$dao_plane_layer = new CDAODM_PLANE_LAYER();
		$dao_layer_point = new CDAODM_LAYER_POINT();
		
		$dao_ap = new CDAOAP_EQUIPMENT();
		
		$MapInfo = $dao_draw_map -> getdrawMapInfoByFloorId($floor_id);
		//print_r($MapInfo);
		$PlaneLayerList=$dao_plane_layer->getPlaneLayerListByFloorId($floor_id);
		//print_r($PlaneLayerList);
		$layer_points=array();
		foreach($PlaneLayerList as $key=>$value){
			
			$value['POINTS']=$dao_layer_point -> getLayerPointByLayerId($value['LAYER_ID']);
			
			$PlaneLayerList[$key]=$value;
		}
		 
		//ȡ��AP�����Ϣ���
		$ApList=$dao_ap->getApListByFloorId($floor_id);
		print_r($ApList);
		
		 
		 $image_width_px=intval($MapInfo['VIEWBOX_WIDTH'])*intval($this->PxUnit);
		 
		 //$image_width_px=intval(45)*intval(28.346);
		 $image_height_px=$MapInfo['VIEWBOX_HEIGHT']*$this->PxUnit;
		 
		 
		 if(!is_dir(MEDIALIB_PLANE_MAP_AUTO_PATH.$floor_id))//�����Ծ�����ʶΪĿ¼����Ŀ¼�ļ�
		 	mkdir(MEDIALIB_PLANE_MAP_AUTO_PATH.$floor_id);
		$map_url_s_tran = MEDIALIB_PLANE_MAP_AUTO_PATH.$floor_id.'/'.$floor_id.'_stran.png';//���ɵ�ͼƬ·��
		$map_url_s = MEDIALIB_PLANE_MAP_AUTO_PATH.$floor_id.'/'.$floor_id.'_s.png';//���ɵ�ͼƬ·��
		
		//header("Content-type: image/png");
		$im = @imagecreatetruecolor($image_width_px, $image_height_px)
				or die("Cannot Initialize new GD image stream");
		
		$trans_colour = imagecolorallocatealpha($im, 242, 239, 234,0);//���ñ�����ɫ
		imagefill($im, 0, 0, $trans_colour);
		
		$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
		$black = imagecolorallocate($im, 0, 0, 0);
		
		
		foreach($PlaneLayerList as $key=>$value){
			
			if ($value['SHAPE_TYPE']=='RECT')
			{
				
				
					$topL_x=$value['POINTS'][0]['POSITION_X']*$this->PxUnit;
					$topL_y=$value['POINTS'][0]['POSITION_Y']*$this->PxUnit;
					
					$btmR_x=$value['POINTS'][1]['POSITION_X']*$this->PxUnit;
					$btmR_y=$value['POINTS'][1]['POSITION_Y']*$this->PxUnit;
				
				
				imagerectangle($im, $topL_x, $topL_y, $btmR_x, $btmR_y, $black);
				
				$text_color = imagecolorallocate($im, 233, 14, 91);
				
				$text_x=$topL_x+($btmR_x-$topL_x)*0.3;
				$text_y=$topL_y+($btmR_y-$topL_y)*0.5;
				
				
				imagettftext($im, 10, 0 , $text_x, $text_y,  $text_color,FONT_FILE_YAHEI, iconv("GBK","UTF-8", $value['LAYER_TOPIC']));
				
			}	
			
		}
		
		foreach($ApList as $ap){
			
		
			$point_icon = 'images/draw/YUANDIAN.png';//Բ���ͼƬ·��
			$point_img = imagecreatefrompng($point_icon);
			$point_size = getimagesize($point_icon);//Բ��ͼƬ��С
			
			$ap_x=$ap['POSITION_X']*$this->PxUnit;
			$ap_y=$ap['POSITION_Y']*$this->PxUnit;
			
			
			imagecopy ($im,$point_img,$ap_x-$point_size[0]/2,$ap_y-$point_size[1]/2,0,0,$point_size[0],$point_size[1]);
				
			
			
			$text_color = imagecolorallocate($im, 0, 0, 0);
			
			$text_x=$ap_x+5;
			$text_y=$ap_y;
			
			
			imagettftext($im, 10, 0 , $text_x, $text_y,  $text_color,FONT_FILE_YAHEI, iconv("GBK","UTF-8", $ap['EQUT_SSID']));
		}
		
		imagepng($im,$map_url_s_tran);
		imagedestroy($im);
		
		print MEDIA_SERVER.str_replace(SF_ROOT, '', $map_url_s_tran);
		/* header("Content-type: image/png");
		$im = @imagecreate(100, 50)
			or die("Cannot Initialize new GD image stream");
		$background_color = imagecolorallocate($im, 255, 255, 255);
		$text_color = imagecolorallocate($im, 233, 14, 91);
		imagestring($im, 1, 5, 5,  "A Simple Text String", $text_color);
		imagepng($im);
		imagedestroy($im); */
		
		
		
	}
	
	
}

?>
