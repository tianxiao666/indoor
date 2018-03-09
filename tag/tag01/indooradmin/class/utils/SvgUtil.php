<?php
/**
 * SVG
 * @author xiang.zc
 *
 */
class SvgUtil {
	/**
	 * SVG形状
	 *
	 * @var unknown
	 */
	const SVG_ELEMENT_ID_RECT = 0;
	const SVG_ELEMENT_ID_CIRCLE = 1;
	const SVG_ELEMENT_ID_ELLIPSE = 2;
	const SVG_ELEMENT_ID_LINE = 3;
	const SVG_ELEMENT_ID_POLYGON = 4;
	const SVG_ELEMENT_ID_POLYLINE = 5;
	const SVG_ELEMENT_ID_PATH = 6;
	const SVG_ELEMENT_ID_TEXT = 7;
	const SVG_ELEMENT_ID_IMAGE = 8;
	/**
	 * SVG Path属性
	 *
	 * @var unknown
	 */
	const SVG_ELEMENT_PATH_ID_M = 0;
	const SVG_ELEMENT_PATH_ID_L = 1;
	const SVG_ELEMENT_PATH_ID_H = 2;
	const SVG_ELEMENT_PATH_ID_V = 3;
	const SVG_ELEMENT_PATH_ID_C = 4;
	const SVG_ELEMENT_PATH_ID_S = 5;
	const SVG_ELEMENT_PATH_ID_Q = 6;
	const SVG_ELEMENT_PATH_ID_T = 7;
	const SVG_ELEMENT_PATH_ID_A = 8;
	const SVG_ELEMENT_PATH_ID_Z = 9;
	const SVG_ELEMENT_PATH_ID_X = 10;
	/**
	 * 获取SVG形状ID
	 *
	 * @param unknown $SvgElementTagName        	
	 * @return Ambigous <number>
	 */
	public static function getSvgElementId($SvgElementTagName) {
		$SVG_ELEMENT_LIST = array (
				"RECT" => SvgUtil::SVG_ELEMENT_ID_RECT,
				"CIRCLE" => SvgUtil::SVG_ELEMENT_ID_CIRCLE,
				"ELLIPSE" => SvgUtil::SVG_ELEMENT_ID_ELLIPSE,
				"LINE" => SvgUtil::SVG_ELEMENT_ID_LINE,
				"POLYGON" => SvgUtil::SVG_ELEMENT_ID_POLYGON,
				"POLYLINE" => SvgUtil::SVG_ELEMENT_ID_POLYLINE,
				"PATH" => SvgUtil::SVG_ELEMENT_ID_PATH,
				"TEXT" => SvgUtil::SVG_ELEMENT_ID_TEXT,
				"IMAGE" => SvgUtil::SVG_ELEMENT_ID_IMAGE 
		);
		return ($SVG_ELEMENT_LIST [strtoupper ( $SvgElementTagName )]);
	}
	/**
	 * 获取SVG Path属性ID
	 *
	 * @param unknown $SvgElementPathAttrName        	
	 * @return Ambigous <number>
	 */
	public static function getSvgElementPathAttrId($SvgElementPathAttrName) {
		$SVG_ELEMENT_PATH_ATTR_LIST = array (
				"M" => SvgUtil::SVG_ELEMENT_PATH_ID_M,
				"L" => SvgUtil::SVG_ELEMENT_PATH_ID_L,
				"H" => SvgUtil::SVG_ELEMENT_PATH_ID_H,
				"V" => SvgUtil::SVG_ELEMENT_PATH_ID_V,
				"C" => SvgUtil::SVG_ELEMENT_PATH_ID_C,
				"S" => SvgUtil::SVG_ELEMENT_PATH_ID_S,
				"Q" => SvgUtil::SVG_ELEMENT_PATH_ID_Q,
				"T" => SvgUtil::SVG_ELEMENT_PATH_ID_T,
				"A" => SvgUtil::SVG_ELEMENT_PATH_ID_A,
				"Z" => SvgUtil::SVG_ELEMENT_PATH_ID_Z,
				" " => SvgUtil::SVG_ELEMENT_PATH_ID_X 
		);
		return ($SVG_ELEMENT_PATH_ATTR_LIST [strtoupper ( $SvgElementPathAttrName )]);
	}
	/**
	 * 获取SVG形状坐标属性列表
	 *
	 * @param unknown $SvgElementTagName        	
	 * @return Ambigous <multitype:multitype:string >
	 */
	public static function getSvgElementCoordAttrList($SvgElementId) {
		$SVG_ELEMENT_COORD_ATTR_LIST = array (
				SvgUtil::SVG_ELEMENT_ID_RECT => array (
						"X" => "Y",
						"WIDTH" => "HEIGHT",
						"RX" => "RY" 
				),
				SvgUtil::SVG_ELEMENT_ID_CIRCLE => array (
						"CX" => "CY",
						"R" => null 
				),
				SvgUtil::SVG_ELEMENT_ID_ELLIPSE => array (
						"CX" => "CY",
						"RX" => "RY" 
				),
				SvgUtil::SVG_ELEMENT_ID_LINE => array (
						"X1" => "Y1",
						"X2" => "Y2" 
				),
				SvgUtil::SVG_ELEMENT_ID_TEXT => array (
						"X" => "Y" 
				),
				SvgUtil::SVG_ELEMENT_ID_IMAGE => array (
						"X" => "Y",
						"WIDTH" => "HEIGHT" 
				) 
		);
		return ($SVG_ELEMENT_COORD_ATTR_LIST [$SvgElementId]);
	}
	/**
	 * SVG形状的基本属性列表
	 *
	 * @param unknown $SvgElementId        	
	 */
	public static function getSvgElementBasicAttrList($SvgElementId) {
		$SVG_ELEMENT_BASIC_ATTR_LIST = array (
				"STROKE_WIDTH" => "STROKE-WIDTH",
				"STROKE_COLOR" => "STROKE",
				"FILL_COLOR" => "FILL",
				"ELEMENT_OPACITY" => "OPACITY",
				"FILL_OPACITY" => "FILL-OPACITY",
				"STROKE_OPACITY" => "STROKE-OPACITY",
				"FONT_SIZE" => "FONT-SIZE",
				"FONT_FAMILY" => "FONT-FAMILY",
				"FONT_WEIGHT" => "FONT-WEIGHT",
				"FONT_STYLE" => "FONT-STYLE",
				"ELEMENT_TEXT" => "XLINK:HREF" 
		);
		return ($SVG_ELEMENT_BASIC_ATTR_LIST);
	}
}
?>