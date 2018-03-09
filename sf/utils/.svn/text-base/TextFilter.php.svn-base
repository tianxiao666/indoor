<?php

	
    /**
	 * Implements some text filters, for example removing javascript
	 * code from a string or removing any non-allowed character.
	 */
	class TextFilter
	{

		var $htmlAllowedTags;
		var $removeJavaScript;
		var $_smileys;

        /**
         * Constructor.
         *
         * @param removeJavaScript By default, JavaScript code will also be removed
         * from texts
         */
		function TextFilter( $removeJavaScript = true )
		{

			$this->removeJavaScript = $removeJavaScript;

		}

		/**
		 * Original function from http://www.zend.com/tips/tips.php?id=124&single=1
         * @param text The text we want to filter
         * @return Returns the filtered text
		 * @static
		 */
        function filterJavaScript( $text )
        {
                // Strip all of the Javascript in script tags out...
                $text = preg_replace('/<SCRIPT*?<\/SCRIPT>/ims',"",$text);

				/** copied from the original function **/
                /* The following matches any on* events, followed by any amount of space, a
                 *' or " some script and then the matching ' or " (the \\2 matches the
                 *single or double quote).  Note that this regex is
                 * in single quotes to alleviate the problem of double quoting special
                 * chars, otherwise the backreferenced 2 would be \\\\2
                 * -- which is just silly...
				 */
                $text = preg_replace('/on(Load|Click|DblClick|DragStart|KeyDown|KeyPress|KeyUp|MouseDown|MouseMove|MouseOut|MouseOver|SelectStart|Blur|Focus|Scroll|Select|Unload|Change|Submit)\s*=\s*(\'|")*?\\2/smi',"",$text);

                $text = preg_replace('/(\'|")Javascript:*?\\1/smi','',$text);

                return $text;
        }

		/**
		 * It also removes the Javascript code if any.
         *
         * @param string The text we would like to filter
         * @return Returns the filtered text.
		 */
        function filterHTML( $string )
        {
			$tmp = strip_tags( $string, $this->htmlAllowedTags );
			// y luego eliminamos el javascript
			$filteredString = $this->filterJavaScript( $tmp );

			return $filteredString;
        }

		/**
		 * Works like the one above but it simply removes *all* html code
		 * from a string
         *
         * @param string The string we would like to filter
         * @return The filtered text.
		 * @static
		 */
        function filterAllHTML( $string )
        {
			$tmp = strip_tags( $string );
			// y luego eliminamos el javascript
			$filteredString = Textfilter::filterJavaScript( $tmp );

			return $filteredString;
        }

		/**
		 * Converts special characters to HTML entities
		 * Works as a wrapper to the htmlentities() function from the PHP API
         * @param string The string we would like to process
         * @return The same string but with the HTML/XML entities encoded to their representation
		 */
		function filterHTMLEntities( $string )
		{
			return htmlentities( $string );
		}

        /**
         * Alias for filterHTMLEntities
         */
		function filterXMLEntities( $string )
		{
			return $this->filterHTMLEntities($string);
		}
		
		/**
		 * removes characters from a string based on the input array
		 *
		 * @param string
		 * @param characters
		 * @returns the filtered string
		 */
		function filterCharacters( $string, $characters = Array()) 
		{
			foreach( $characters as $char ) {
				$string = str_replace( $char, "", $string );
			}
			
			return $string;
		}
		
        /**
         * Translates all the &gt;a href="..."&lt; tags of a text to include the
         * target="blank_" parameter so that they will open in a new window
         */
		function linksInNewWindow( $text )
		{
			$text = str_replace( "<a ", "<a target=\"blank_\" ", $text );

			return $text;
		}

		function translateSmileys( $text )
		{
			foreach( $this->_smileys as $symbol => $icon ) {
				$new_text = str_replace( $symbol, $icon, $text );
				$text = $new_text;
			}

			return $text;
		}

        /**
         * Texturize function borrowed from http://photomatt.net/tools/texturize
         *
         * Takes care of "beautifying" code typed by users.
         */
        function texturize($text)
        {
            $textarr = preg_split("/(<.*>)/U", $text, -1, PREG_SPLIT_DELIM_CAPTURE); // capture the tags as well as in between
            $stop = count($textarr); $next = true; // loop stuff
            for ($i = 0; $i < $stop; $i++) {
            	$curl = $textarr[$i];
                if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Gecko')) {
                	$curl = str_replace('<q>', '&#8220;', $curl);
                    $curl = str_replace('</q>', '&#8221;', $curl);
                }
                if ('<' != $curl{0} && $next) { // If it's not a tag
                	$curl = str_replace('---', '&#8212;', $curl);
                    $curl = str_replace('--', '&#8211;', $curl);
                    $curl = str_replace("...", '&#8230;', $curl);
                    $curl = str_replace('``', '&#8220;', $curl);

                    $curl = preg_replace("/'s/", "&#8217;s", $curl);
                    $curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $curl);
                    $curl = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $curl);
                    $curl = preg_replace("/(\d+)\"/", "$1&Prime;", $curl);
                    $curl = preg_replace("/(\d+)'/", "$1&prime;", $curl);
                    $curl = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $curl);
                    $curl = preg_replace('/"([\s.]|\Z)/', '&#8221;$1', $curl);
                    $curl = preg_replace('/(\s|\A)"/', '$1&#8220;', $curl);
                    $curl = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $curl);
                    $curl = preg_replace("/\(tm\)/i", '&#8482;', $curl);
                    $curl = preg_replace("/\(c\)/i", '&#169;', $curl);
                    $curl = preg_replace("/\(r\)/i", '&#174;', $curl);

                    $curl = str_replace("''", '&#8221;', $curl);
                    $curl = preg_replace('/&([^#])(?![a-z]{2,8};)/', '&#038;$1', $curl);

                    $curl = preg_replace('/(d+)x(\d+)/', "$1&#215;$2", $curl);
               } elseif (strstr($curl, '<code') || strstr($curl, '<pre') || strstr($curl, '<kbd' 
						   || strstr($curl, '<style') || strstr($curl, '<script'))) {
               		// strstr is fast
            		$next = false;
        	  } else {
            		$next = true;
        	  }
        	  $output .= $curl;
    	}

    	return $output;
	}

		
                
        /**
         * balanceTags
         *
         * Balances Tags of string using a modified stack.
         *
         * @param text      Text to be balanced
         * @return          Returns balanced text
         * @author          Leonard Lin (leonard@acm.org)
         */
        function balanceTags($text, $is_comment = 0) 
        {
            
            //if (get_settings('use_balanceTags') == 0) {
             //   return $text;
            //}
        
            $tagstack = array(); $stacksize = 0; $tagqueue = ''; $newtext = '';
        
            # WP bug fix for comments - in case you REALLY meant to type '< !--'
            $text = str_replace('< !--', '<    !--', $text);
            # WP bug fix for LOVE <3 (and other situations with '<' before a number)
            $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);
        
            while (preg_match("/<(\/?\w*)\s*([^>]*)>/",$text,$regex)) {
                $newtext = $newtext . $tagqueue;
        
                $i = strpos($text,$regex[0]);
                $l = strlen($tagqueue) + strlen($regex[0]);
        
                // clear the shifter
                $tagqueue = '';
                // Pop or Push
                if ($regex[1][0] == "/") { // End Tag
                    $tag = strtolower(substr($regex[1],1));
                    // if too many closing tags
                    if($stacksize <= 0) { 
                        $tag = '';
                        //or close to be safe $tag = '/' . $tag;
                    }
                    // if stacktop value = tag close value then pop
                    else if ($tagstack[$stacksize - 1] == $tag) { // found closing tag
                        $tag = '</' . $tag . '>'; // Close Tag
                        // Pop
                        array_pop ($tagstack);
                        $stacksize--;
                    } else { // closing tag not at top, search for it
                        for ($j=$stacksize-1;$j>=0;$j--) {
                            if ($tagstack[$j] == $tag) {
                            // add tag to tagqueue
                                for ($k=$stacksize-1;$k>=$j;$k--){
                                    $tagqueue .= '</' . array_pop ($tagstack) . '>';
                                    $stacksize--;
                                }
                                break;
                            }
                        }
                        $tag = '';
                    }
                } else { // Begin Tag
                    $tag = strtolower($regex[1]);
        
                    // Tag Cleaning
        
                    // Push if not img or br or hr
                    if($tag != 'br' && $tag != 'img' && $tag != 'hr') {
                        $stacksize = array_push ($tagstack, $tag);
                    }
        
                    // Attributes
                    // $attributes = $regex[2];
                    $attributes = $regex[2];
                    if($attributes) {
                        $attributes = ' '.$attributes;
                    }
                    $tag = '<'.$tag.$attributes.'>';
                }
                $newtext .= substr($text,0,$i) . $tag;
                $text = substr($text,$i+$l);
            }  
        
            // Clear Tag Queue
            $newtext = $newtext . $tagqueue;
        
            // Add Remaining text
            $newtext .= $text;
        
            // Empty Stack
            while($x = array_pop($tagstack)) {
                $newtext = $newtext . '</' . $x . '>'; // Add remaining tags to close      
            }
        
            // WP fix for the bug with HTML comments
            $newtext = str_replace("< !--","<!--",$newtext);
            $newtext = str_replace("<    !--","< !--",$newtext);
        
            return $newtext;
        }
        
        function urlize( $string )
        {
		    // remove unnecessary spaces and make everything lower case
		    $string = preg_replace( "/ +/", " ", strtolower($string) );

            // removing a set of reserved characters (rfc2396: ; / ? : @ & = + $ ,)
            $string = str_replace(array(';','/','?',':','@','&','=','+','$',','), '', $string);

            // replace some characters to similar ones
            /*$search  = array(' ', '?, '?, '?,'?,'?,'?,'?, '?, '?, '?, '?, '?, '?, '?, '?, '?, '?, '?, '?' );
            $replace = array('_', 'a','o','u','e','e','a','c', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'e', 'i' );
            $string = str_replace($search, $replace, $string);
            */
            $string = preg_replace( '/[^a-z0-9 _-]/', '', $string );        
            
            return $string;            
        }
		
		/**
		 * xhtml-izes a string
		 *
		 * @param string
		 * @return the xhtml-ized string
		 */
		function xhtmlize( $string )
		{
			return $string;

			/** NOW WE WILL NOT WANT TO THIS SUPPORT
		      $config =& CConfig::getConfig();
		      if( $config->getValue( "xhtml_converter_enabled" )) {
				  include_once( SF_CLASS_PATH."/data/kses.class.php" );
		          $kses = new kses( true, $config->getValue( "xhtml_converter_aggresive_mode_enabled"));
		          $result = $kses->Parse( $string );
		          
		          // if balanceTags wasn't broken, we could use it...
		          //$result = Textfilter::balanceTags( $result );		          
		      } else{
		          $result = $string;
			  }
		      return $result; */
		}
		
		/**
		 * tbd
		 */
		function checkboxToBoolean( $value )
		{
			if( $value == "1" || $value == "on" )
				return true;
			else
				return false;
		}

		/**
		  *  Fix the html's \r\n to <br>
		  */
		function makeup($tStr)
		{
			if(!$tStr)
				return $tStr;
			$strTb=chr(13).chr(10);
			$strHtml="<br>".$strTb;
			$tStr=preg_replace($strTb,$strHtml,$tStr);
			$strTb=chr(32);
			$strHtml="&nbsp;";
			$tStr=preg_replace($strTb,$strHtml,$tStr);
			//print_r($tStr);
			return $tStr;
		}
		
		function TagFilter($tags)
		{
			if (!$tags)
				return false;
			$tagarray=array();
			
			if (is_array($tags))
				$tagarray=$tags;
			else{
				
				$tagarray=explode(" ",trim($tags));
				$isstr=true;
			}
			
			if (!$tagarray)	
				return false;
				
			$dao_dict = new CDAOSysDict();
			$AllSysTag = $dao_dict->getValue('SYS.TAG');
			
			foreach ($tagarray as $k => $strtag){
				$strtag=trim($strtag);
				$tagarray[$k]=$strtag;
				if (strlen($strtag)>20){
					unset($tagarray[$k]);
					continue;
					
				}
				
				
				$i=0;	
				$total = strlen($strtag);
				while ($i<$total){
					//先处理英文半角
					if (($strtag[$i]>="\x20") && ($strtag[$i] <= "\x80")){
						if ( (($strtag[$i]>="\x30") && ($strtag[$i] <= "\x39")) || (($strtag[$i]>="\x41") && ($strtag[$i] <= "\x5a")) 
							|| (($strtag[$i]>="\x61") && ($strtag[$i] <= "\x7a")) ){
							$i++;
						}else{
							unset($tagarray[$k]);
							break;
						}	
						
					}else if (($strtag[$i]>="\x81") && ($strtag[$i] <= "\xff")){ 
						if (($strtag[$i]>="\xa1") && ($strtag[$i] <= "\xa9")){
							unset($tagarray[$k]);
							break;
						} 
						
						$i=$i+2;
					}
					
				}//while end
				//$tagarray[$k]=trim($strtag);
			}//foreach tag end
			
			
			if ($isstr==true)
				$filtertag=implode(" ", $tagarray);
			else
				$filtertag=$tagarray;
				
			return $filtertag;
		}
		function DealTag($new_tag,$old_tag){
			if (!$new_tag && !$old_tag)
				return false;
			$new_tag=Textfilter::TagFilter(CEditor::FixFullBlank($new_tag));
			$old_tag=Textfilter::TagFilter($old_tag);
			
			$Arynewtag=explode(" ",$new_tag);
			$AryoldTags=$old_tag?$old_tag:array();
			
			$dao_dict = new CDAOSysDict();
			$AllSysTag = $dao_dict->getValue('SYS.TAG');
			$j=0;
			foreach ($Arynewtag as $nk => $newstrtag){
				if (in_array($newstrtag,$AllSysTag)){
					if ($j>0){
						unset($Arynewtag[$nk]);
						continue;
					}
					$j++;
				}
			}
			
			
			foreach ($AryoldTags as $ok => $oldstrtag){
				if (in_array($oldstrtag,$AllSysTag)){
					if ($j>0){
						unset($AryoldTags[$ok]);
						continue;
					}
					$j++;
				}
			}
			
			
			//只取前20个标签
			
			$newcnt=count($Arynewtag);
			$oldcnt=count($AryoldTags);
			if (($newcnt+$oldcnt)>20){
				if ($newcnt>20){
					$Arynewtag=array_slice ($Arynewtag, 0,20 );  
					 $AryoldTags="";
				}else{
					$cnt=20-$newcnt+1;
					$AryoldTags=array_slice ($AryoldTags, 0,$cnt );   
				}
			}
			if ($Arynewtag)
				$tagarray[0]=implode(" ",$Arynewtag);
				
			$tagarray[1]=$AryoldTags;
			
			return $tagarray;

		}	
		
		/**
	 * 处理数组中的特殊字符 ,主要针对SQL like查询
	 * 类似100' or 1=1 --       100' ; drop table orders --      100' ; exec xp_cmdshell( 'fdisk.exe' )  攻击
	 * @param max $aData
	 *  @return m
	 */
    
	    public  function filterDangerWords($mData)
	    {
	    	$arr	= array("'","--");
	    	if (is_string($mData)) {
	    		$mData = trim($mData);
	    		return str_replace($arr,'',$mData);
	    	}
	    	if (is_array($mData)) {
	    		foreach ($mData as &$value) {
	    			$value = trim($value);
	    			$value = str_replace($arr,'',$value);
	    		}
	    	} 	
	    	return $mData;
	    }
	    
	    /**
	     *转义单引号 ,ORACLE中查询一个单引号时要转成两个单引号
	     *
	     * @param mix $mData
	     * @return mix
	     */
	    static   function encodeQuote($mData)
	    {
	    	$arr	= array("'");
	    	if (is_string($mData)) {
	    		$mData = trim($mData);
	    		return str_replace($arr,"''",$mData);
	    	}
	    	if (is_array($mData)) {
	    		foreach ($mData as &$value) {
	    			$value = trim($value);
	    			$value = str_replace($arr,"''",$value);
	    		}
	    	} 	
	    	return $mData;
	    }
	    
	    /**
	 * 处理数组中的特殊字符 ,主要针对SQL查询
	 *  转义特殊字符
	 * @param max $aData
	 *  @return m
	 */
    
	    public  function filterPartiWords($mData)
	    {
	    	if (is_string($mData)) {
	    		return addslashes($mData);
	    	}
	    	if (is_array($mData)) {
	    		foreach ($mData as &$value) {
	    			$value = addslashes($value);
	    		}
	    	} 	
	    	return $mData;
	    }
		
	
	/**
	 * 过滤数据，返回格式化数据
	 * Enter description here ...
	 */
	public static function  formatArrayData($arr)
	{
		foreach ($arr as $post_key=>$post_var)
		{
		    if (is_numeric($post_var))
		    {
		        $request[$post_key] = TextFilter::get_int($post_var);
		    }
		    else
		    {
		        $request[$post_key] = TextFilter::get_str($post_var);
		    }
		}
		return $request;
	}
	public static function formatData($data)
	{
		if (is_numeric($data))
	    {
	        $request = $data;//如果是数字不过滤 
	    }
	    else
	    {
	        $request = TextFilter::get_str($data);
	    }
	    return $request;
	}
	/**
	 * 过滤post或者get参数
	 * Enter description here ...
	 */
	public static function formatRequest($re = array())
	{
		$data = empty($re)?$_REQUEST:$re;
		foreach ($data as $k=>$v)
		{
			if (!is_numeric($v) && !is_array($v))
		    {
		        $v = TextFilter::get_str($v);
		       // ini_set('default_charset','gb2312');
                       // $request[$k] = htmlspecialchars($v,ENT_COMPAT,'');
			$request[$k] = $v;
		    }
		    elseif (is_array($v))
		    {
		    	$request[$k] = TextFilter::formatRequest($v);
		    }
		    else 
		    {
		    	$request[$k] = $v;
		    }
		}
		return $request;

	}
	/* 过滤函数 */
	//整型过滤函数
	public static function get_int($number)
	{
	    return intval($number);
	}
	//字符串型过滤函数
	public static function get_str($string)
	{
	    if (!get_magic_quotes_gpc())
	    {
	        return addslashes($string);
	    }
	    return $string;
	}
	public function guolvTopicNote($track_topic)
	{
		$track_topic = str_replace("'","‘",$track_topic);
		$track_topic = str_replace('"',"“",$track_topic);
		$track_topic = str_replace("(","（",$track_topic);
		$track_topic = str_replace(")","）",$track_topic);
		$track_topic = str_replace("#","#",$track_topic);
		$track_topic = str_replace("!","！",$track_topic);
		$track_topic = str_replace("%","%",$track_topic);
		$track_topic = str_replace("$","",$track_topic);
		$track_topic = str_replace("=","",$track_topic);
		$track_topic = str_replace(":","：",$track_topic);
		$track_topic = str_replace(";","；",$track_topic);
		$track_topic = str_replace(",","，",$track_topic);
		$track_topic = str_replace(".","。",$track_topic);
		$track_topic = preg_replace("{\r\n}","",$track_topic);
		return $track_topic;
	}	    
    }
?>
