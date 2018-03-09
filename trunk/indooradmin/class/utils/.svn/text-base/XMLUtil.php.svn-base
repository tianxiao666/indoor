<?php
/**
 * 解析xml和生成xml
 * document 1.0,utf-8
 * @author xiang.zc
 */
class XMLUtil {
	const tag = "tag";
	const attributes = "attributes";
	const value = "value";
	const elements = "elements";
	/**
	 * 获取XMLParseStruct关闭tag的index
	 *
	 * @param unknown $XMLParseStruct_Value        	
	 * @param unknown $OpenIndex        	
	 * @return number unknown NULL
	 */
	public function getXMLParseStructCloseTagIndex($XMLParseStruct_Value, $OpenIndex) {
		if (! empty ( $XMLParseStruct_Value )) {
			$svgTagValue = $XMLParseStruct_Value [$OpenIndex];
			if ($svgTagValue ["type"] == "open") {
				$svgTagLevel = $svgTagValue ["level"];
				$i = $OpenIndex + 1;
				$count = count ( $XMLParseStruct_Value );
				while ( $i < $count ) {
					if (($XMLParseStruct_Value [$i] ["type"] == "close") && ($svgTagLevel == $XMLParseStruct_Value [$i] ["level"])) {
						return ($i);
					}
					++ $i;
				}
			} else {
				if ($svgTagValue ["type"] == "complete") {
					return ($OpenIndex);
				}
			}
		}
		return (null);
	}
	/**
	 * 获取XMLParseStruct的结构信息
	 *
	 * @param unknown $XMLParseStruct_Value        	
	 * @param unknown $OpenIndex        	
	 * @param unknown $SvgInfo        	
	 * @return multitype:NULL unknown multitype: Ambigous <number, NULL, unknown>
	 */
	public function getXMLParseStructInfo($XMLParseStruct_Value, $OpenIndex, $SvgInfo = array()) {
		$closeIndex = $this->getXMLParseStructCloseTagIndex ( $XMLParseStruct_Value, $OpenIndex );
		if (! empty ( $closeIndex )) {
			$SvgInfo = array ();
			$SvgInfo ["open"] = $OpenIndex;
			$SvgInfo ["close"] = $closeIndex;
			$SvgInfo [XMLUtil::tag] = $XMLParseStruct_Value [$OpenIndex] ["tag"];
			$SvgInfo [XMLUtil::attributes] = $XMLParseStruct_Value [$OpenIndex] ["attributes"];
			$SvgInfo [XMLUtil::value] = $XMLParseStruct_Value [$OpenIndex] ["value"];
			$elements = array ();
			$i = $OpenIndex + 1;
			while ( $i < $closeIndex ) {
				$element = $this->getXMLParseStructInfo ( $XMLParseStruct_Value, $i );
				if (empty ( $element )) {
					++ $i;
				} else {
					array_push ( $elements, $element );
					$i = $element ["close"] + 1;
				}
			}
			if (! empty ( $elements )) {
				$SvgInfo [XMLUtil::elements] = $elements;
			}
		}
		return ($SvgInfo);
	}
	/**
	 * 通过XMLParseStruct结构生成xml
	 */
	public function getXML($XMLParseStructInfo, $dom = null, $parent = null) {
		if (! empty ( $XMLParseStructInfo )) {
			if ($dom === null) {
				$dom = new DomDocument ();
				$parent = $dom;
			}
			if ($parent === null) {
				$parent = $dom;
			}
			if (! empty ( $XMLParseStructInfo [XMLUtil::tag] )) {
				$XMLParseStructInfo [XMLUtil::tag] = CIconv::gbkToUtf8 ( $XMLParseStructInfo [XMLUtil::tag] );
				$element = $dom->createElement ( $XMLParseStructInfo [XMLUtil::tag] );
				$parent->appendChild ( $element );
				if (! empty ( $XMLParseStructInfo [XMLUtil::value] )) {
					$XMLParseStructInfo [XMLUtil::value] = CIconv::gbkToUtf8 ( $XMLParseStructInfo [XMLUtil::value] );
					$child = $dom->createTextNode ( $XMLParseStructInfo [XMLUtil::value] );
					$element->appendChild ( $child );
				}
				if (! empty ( $XMLParseStructInfo [XMLUtil::attributes] )) {
					foreach ( $XMLParseStructInfo [XMLUtil::attributes] as $k => $v ) {
						$k = CIconv::gbkToUtf8 ( $k );
						$v = CIconv::gbkToUtf8 ( $v );
						$attr = $dom->createAttribute ( $k );
						$element->appendChild ( $attr );
						$attr_value = $dom->createTextNode ( $v );
						$attr->appendChild ( $attr_value );
					}
				}
				if (! empty ( $XMLParseStructInfo [XMLUtil::elements] )) {
					foreach ( $XMLParseStructInfo [XMLUtil::elements] as $v ) {
						$this->getXML ( $v, $dom, $element );
					}
				}
				return (CIconv::utf8ToGBK ( $dom->saveXML () ));
			}
		}
		return ("");
	}
}
?>