<?php
/**
 * 手机缩略图尺寸
 */
 define('IMAGE_EQUAL_WIDTH',150);//width
 define('IMAGE_EQUAL_HEIGHT',150);//height

/**
 *  缩略图规格数字Master Dimension
 */
define('Master_Dimension_NONE',1);
define('Master_Dimension_AUTO',2);
define('Master_Dimension_HEIGHT',3);
define('Master_Dimension_WIDTH',4);
define('Master_Dimension_EQUAL',5);

/**
 * 图片上传类
 * 
 * @package CPhotoUpload
 */

class CPhotoUpload extends CFileUpload
{
    //成员
    var $allow_image_type = array(
        'image/gif'     => 'image/gif',
        'image/jpeg'    => 'image/jpeg',
        'image/png'     => 'image/png',
        'image/bmp'     => 'image/bmp'
		
    );
    
    //构造函数
    function CPhotoUpload($upload_path, $field_name,$subs_id=0)
    {
        parent::CFileUpload($upload_path, $field_name,$subs_id);
		
    }
	
	
	
	//$array_size=array('min'=>(30,30),'previews'=>(120,120),,'mediums'=>(650,650));
	function saveThumbnail($upload_result,$array_path_size,$quality=85){		
		//SF::log($array_path_size);
		if (!is_array($array_path_size) || !is_array($upload_result)) return false;
       // SF::log($upload_result);
		$total = count($upload_result);
        for ($i = 0; $i < $total; $i++)
        {
			$dest_file=$upload_result[$i]['dest_file'];
			if ($dest_file)
            {
				$file_name = pathinfo($dest_file, PATHINFO_BASENAME);
				SF::log('file_name------------'.$file_name );
				//list($pic_w, $pic_h) = getimagesize($dest_file);
				$image = SF::app()->getImage()->load($dest_file);
			
				foreach ($array_path_size as $path=>$size){
					$img_thumbnail_path=$this->getImagePath($path);					
					
					$width=$size[0];
					$height=$size[1];
					$master=$size[2]?$size[2]:2;
					if (!$width || !$height) return false;
				
					$image->resize($width, $height,$master)->quality($quality);
					
					 // or$image->save();
					$image->save($img_thumbnail_path.$file_name);
				}
				
				//图片大于2M则，改变图片的大小进行压缩
				if (filesize($dest_file)>2097152){
					$org_file=$this->getImagePath(PREVIEWS_ORG_PATH).$file_name;
					if (copy($dest_file,$org_file)){
						$image->resize(1024,1024)->quality($quality);
					 	$image->save($this->upload_path.$file_name);
						
					}
				}
			}
		}	
	}
	

	   
    function prepare()
    {
 $result = array();
        if (!$this->field_name || !array_key_exists($this->field_name, $_FILES))
        {
            return '错误！参数传递错误：field_name。';
        }
		//取得随机生成的目录地址
        $this->upload_path=$this->getMediaLib();
        if (!$this->upload_path || !is_dir($this->upload_path) || !is_dir($this->upload_path))
        {
            SF::log($this->upload_path);
			SF::log('错误！目录不存在或不可写。');
			return '错误！目录不存在或不可写。';
        }
        $files = $_FILES[$this->field_name];
        if (!$files['name'])
        {
            return '错误！没有文件被上传。';
        }
        if (!is_array($files['name']))
        {
            $temp_array = $files;
            $keys = array_keys($files);
            $files = array();
            foreach ($keys as $key)
            {
                $files[$key][0] = $temp_array[$key];
            }
        }
        $this->files = $files;
        $total = count($files['name']);
       for ($i = 0; $i < $total; $i++)
        {
            //$image_type = @exif_imagetype($files['tmp_name'][$i]);
            $image_type = $files['type'][$i];
           if ($image_type)
            {
                $mime_type = array_search($image_type, $this->allow_image_type);
            }
            $mime_type = $mime_type ? $mime_type : '';
			//如果上传的是BMP文件则需要处理
	    if ($mime_type == 'image/bmp')
        	{
				$imagesize = getimagesize($files['tmp_name'][$i]);
				$convert_file = str_replace('.bmp', '.jpg', $files['tmp_name'][$i]);
				system(CONVERT_PATH . " -sample {$imagesize[0]}X{$imagesize[1]} {$files['tmp_name'][$i]} {$convert_file}");
				unlink($files['tmp_name'][$i]);
				$files['tmp_name'][$i] = $convert_file;
				$mime_type = 'image/jpg';
				$fileExt = '.jpg';
	     } 
            $this->files['mime_type'][$i] = $mime_type;		
            if (!$files['name'][$i] || !$files['tmp_name'][$i])
            {
                $error = '错误！未选择要上传的文件。';
            }
            else if (!is_uploaded_file($files['tmp_name'][$i]))
            {
                $error = '错误！此文件不是通过HTTP POST上传的。';
            }
            else if ($files['error'][$i])
            {
                $error = $this->getErrorMessage($files['error'][$i]);
            }
            else if (!$mime_type)
            {
                $error = '错误！只允许上传 JPEG、GIF、PNG 或 BMP 图片。';
            }
            else if (is_numeric($this->allow_size) && ((int) $this->allow_size) > 0 && 
            $files['size'][$i] > ((int) $this->allow_size))
            {
                $error = '错误！文件大小超出限制。';
            }
            else
            {
                $error = '';
            }
           array_push($result, array('error' => $error));
            unset($error);
        }
        return $result;
    }
	
	/**
	 * 生成不同规格图片时的文件保存的目录生成函数
	 * 
	 * @author mary<mary@tripdata.com>
	 * 
	 */
	 
	 function getImagePath($image_path){
	 
	 	//$medialib=$this->getMediaLib();
		if ($this->upload_path)
        {
            $media_image_path=$this->upload_path . $image_path;
			SF::log($media_image_path);
			if (!is_dir($media_image_path))
            {
                mkdir($media_image_path, 0777);
					//SF::log($media_image_path.'错误！目录不存在或不可写。');
					//return '错误！目录不存在或不可写。';
            }
			return $media_image_path;
        }
	 	return false;	 
	 }
	 
	 
	 
    function CutImage($upload_result,$array_path_size,$x,$y,$w,$h){		
		//SF::log($array_path_size);
		if (!is_array($array_path_size) || !is_array($upload_result)) return false;
        //SF::log($upload_result);
		$total = count($upload_result);
        for ($i = 0; $i < $total; $i++)
        {
			$dest_file=$upload_result[$i]['dest_file'];
			//$source_file=$upload_result[$i]['source_file'];
			SF::log($source_file);
			if ($dest_file)
            {
				$file_name = pathinfo($dest_file, PATHINFO_BASENAME);
				//SF::log('file_name------------'.$file_name );
				//list($pic_w, $pic_h) = getimagesize($dest_file);
				//$image = SF::app()->getImage()->load($dest_file);
				//SF::log($image);
				//return false;
				foreach ($array_path_size as $path=>$size){
					$img_thumbnail_path=$this->getImagePath($path);					
					SF::log($dest_file);
					$width=$size[0];
					$height=$size[1];
					$master=$size[2]?$size[2]:2;
					if (!$width || !$height) return false;
					$scale = $width/$w;	
					//$this->resizeThumbnailImage($dest_file, $source_file, $w,$h, $x, $y, $scale);
					$new_dest_file=$img_thumbnail_path.$file_name;
					$dest_result[]=$this->resizeThumbnailImage($new_dest_file, $dest_file, $w,$h, $x, $y, $scale);
					//$image->crop($width, $height, $top = $x, $left = $y,$w,$h)->resize($width, $height,$master)->quality($quality);
					
				    
					/*if(!$image->crop($width, $height, $top = $x, $left = $y,$w,$h)){
					
						return false;
					}*/
					//$image->save($img_thumbnail_path.$file_name);
					 // or$image->save();
				    
				}
				
						
			}
		}	
			return $dest_result;
	}
	function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
		}
		
		
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) {
			case "image/gif":
				imagegif($newImage,$thumb_image_name); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
		}
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}

	
	
	
	
		 
}
?>
