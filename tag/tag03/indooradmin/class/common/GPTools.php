<?php

class GPTools {
	const PI=3.14159265358979;//����Բ����
	const METER_PER_LNGMS=0.0309;//����ÿ����ľ��룬�����Լ�����
	const RADIUS = 6371004;//����ƽ���뾶�������Լ�����
	
	public static function ms2d($millsec){
		return $millsec/3600000;
	}
	
	public static function d2ms($d){
		return $d*3600000;
	}
	
	
	public static function lenthPerLatMs($lat){
		return self::RADIUS*2*self::PI*cos(deg2rad(self::ms2d($lat)))/(360*3600000);
	}
	
	public static function getLngDiff($len){
		return round($len/self::METER_PER_LNGMS);
	}
	
	public static function getLatDiff($len,$lat){
		return round($len/self::lenthPerLatMs(self::d2ms($lat)));
	}
	
	public static function getMapPoint($nepoint,$swpoint,$img,$point){
		$mapwidth = $nepoint["lng"]-$swpoint["lng"];
		$mapheight = $nepoint["lat"]-$swpoint["lat"];
		$x = $point["lng"]-$swpoint["lng"];
		$y = $nepoint["lat"]-$point["lat"];
		$position["x"] = round($img->width *($x / $mapwidth));
		$position["y"] = round($img->height * ($y / $mapheight));
		return $position;
	}
	
}
?>