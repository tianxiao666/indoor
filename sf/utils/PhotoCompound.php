<?php

/**
 * @desc 图片合成类，支持类型gif,jpeg,jpg。源文件类型jpeg/jpg、png/gif
 * @author tujk 2010-01-11
 */


class PhotoCompound extends CBaseService
{
	var $dstFile;//底图
	var $srcFile;//源图

	//目标文件左上角原点定位及合成区大小
	var $dstX;
	var $dstY;
	var $dstW;
	var $dstH;
	var $resize;

	//源文件大小
	var $srcW;
	var $srcH;

	//源文件左上角原点定位，默认为0
	var $srcX;
	var $srcY;

	//wap贺卡大小设置
	var $wapCardWidth;
	var $wapCardHeight;


	public function __construct()
	{
	}

	/**
	 * 设置底图属性
	 *
	 * @param string $dstFile
	 * @param bool $resize
	 * @param int $dstX
	 * @param int $dstY
	 * @param int $dstW
	 * @param int $dstH
	 */
	public function setDstPhotoInfo($dstFile,$resize = false, $dstW = 0,$dstH = 0,$dstX = 0,$dstY = 0)
	{
		$this->dstFile = $dstFile;
		$this->dstX	   = $dstX;
		$this->dstY	   = $dstY;
		$this->dstW	   = $dstW;
		$this->dstH    = $dstH;
		$this->resize  = $resize;
	}

	/**
	 * 设置原图属性
	 *
	 * @param string $srcFile
	 * @param int $srcW
	 * @param int $srcH
	 * @param int $srcX
	 * @param int $srcY
	 */
	public function setSrcPhotoInfo($srcFile,$srcW,$srcH,$srcX = 0,$srcY = 0)
	{
		$this->srcFile = $srcFile;
		$this->srcX	   = $srcX;
		$this->srcY    = $srcY;
		$this->srcW	   = $srcW;
		$this->srcH    = $srcH;
	}

	/**
	 * 设置wap贺卡图片大小
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function setWapCardSize($width,$height)
	{
		$this->wapCardWidth  = $width;
		$this->wapCardHeight = $height;
	}


	/**
	 * 合成gif图片,底图gif,原图jpg
	 *
	 * @param bool $wap  是否生成wap小图片
	 * @return array
	 */
	/*
	public function compoundGif($resize = false,$wap = true)
	{
		if(!$this->checkType('gif')){
			return false;
		}

		//gif图片失真处理
		list($dstWidth,$dstHeight) = getimagesize($this->dstFile);
		$dstImage = imagecreatetruecolor($dstWidth,$dstHeight);
		$gifIm = imagecreatefromgif($this->dstFile);
		imagecopyresampled($dstImage,$gifIm,0,0,0,0,$dstWidth,$dstHeight,$dstWidth,$dstHeight);

		//缩放
		if($resize){
			$srcImage = $this->resize($this->srcFile,$this->srcWidth,$this->srcH);	
		}else{
			$srcImage = imagecreatefromjpeg($this->srcFile);
		}
		
		//合成图片
		if(imagecopyresampled($dstImage,$srcImage,$this->dstX,$this->dstY,$this->srcX,$this->srcY,$this->dstW,$this->dstH,$this->srcW,$this->srcH))
		{
			$time = time();
			$webCardPhoto = COMPOUND_WEB_PHOTO_PATH.$time.'web.jpg';
			if(imagejpeg($dstImage,$webCardPhoto)){

				$photo = array('webCard'=> $webCardPhoto);
				if($wap){

					$wapCard          = $this->createWapCardPhoto($webCardPhoto,'jpg',$this->wapCardWidth,$this->wapCardHeight);
					$photo['wapCard'] = $wapCard;
				}

				return $photo;
			}
		}

		return false;
	}
	*/

	/**
	 * jpg图片合成,底图jpg/jpeg,原图jpg
	 *
	 * @param bool $wap
	 * @return array
	 */
	/*
	public function compoundJpeg($resize = false,$wap = true)
	{
		if(!$this->checkType('jpg') && !$this->checkType('jpeg')){
			return false;
		}

		$dstImage = imagecreatefromjpeg($this->dstFile);
		
		if($resize){
			$srcImage = $this->resize($this->srcFile,$this->srcW,$this->srcY);
		}else {
			$srcImage = imagecreatefromjpeg($this->srcFile);
		}
		
		if(imagecopyresampled($dstImage,$srcImage,$this->dstX,$this->dstY,$this->srcX,$this->srcY,$this->dstW,$this->dstH,$this->srcW,$this->srcH))
		{
			$time = time();
			$webCardPhoto = COMPOUND_WEB_PHOTO_PATH.$time.'web.jpg';

			if(imagejpeg($dstImage,$webCardPhoto)){
				$photo = array('webCard'=> $webCardPhoto);
				if($wap){
					$wapCard	      = $this->createWapCardPhoto($webCardPhoto,'jpg',$this->wapCardWidth,$this->wapCardHeight);
					$photo['wapCard'] = $wapCard;
				}

				return $photo;
			}
		}

		return false;
	}
	*/

	
	/**
	 * 生成合成图片
	 *
	 * @param string $webPhotoName
	 * @param string $wapPhotoName
	 * @return bool
	 */
	public function createCompoundPhoto($webPhotoName,$wapPhotoName)
	{
		$canvas = $this->compoundPhoto();
		
		if(imagejpeg($canvas,$webPhotoName))
			{
				imagedestroy($canvas);
				return $this->createWapCardPhoto($webPhotoName,$wapPhotoName,$this->wapCardWidth,$this->wapCardHeight);
			}
		return false;
	}

	/**
	 * 透明图片合成，底图jpg,原图png/gif，返回2进制图片
	 *
	 * @param string $webPhotoName
	 * @param string $wapPhotoName
	 * @return imgae
	 */
	public function compoundPhoto()
	{
		$srcType = $this->checkTransparenceType();
		if(!$srcType){
			return false;
		}

		//初始画布
		$canvas = imagecreatetruecolor($this->srcW,$this->srcH);
		$white  = imagecolorallocate($canvas,255,255,255);
	    imagefill($canvas,0,0,$white);

	    $dstType = $this->getPhotoType($this->dstFile);
		//缩放
		if($this->resize){
			$dstImage = $this->resize($this->dstFile,$this->dstW,$this->dstH);
		}else {
			switch ($dstType)
			{
				case 'jpg'          : $dstImage = imagecreatefromjpeg($this->dstFile); break;
				case 'jpeg' 		: $dstImage = imagecreatefromjpeg($this->dstFile); break;
				case 'gif' 			: $dstImage = imagecreatefromgif($this->dstFile);  break;
			}
		}
		
		$sizeInfo = $this->calculatePosition($this->srcW,$this->srcH,imagesx($dstImage),imagesy($dstImage));
		
		//将用户照片贴在画布上
		imagecopyresampled($canvas,$dstImage,$sizeInfo['canvasX'],$sizeInfo['canvasY'],$this->dstX,$this->dstY,$sizeInfo['canvasW'],$sizeInfo['canvasH'],$sizeInfo['canvasW'],$sizeInfo['canvasH']);

		switch($srcType)
		{
			case 'png' : $srcImage = imagecreatefrompng($this->srcFile);	break;
			case 'gif' : $srcImage = imagecreatefromgif($this->srcFile);	break;
		}

		if(imagecopyresampled($canvas,$srcImage,0,0,0,0,$this->srcW,$this->srcH,$this->srcW,$this->srcH))
		{
			imagedestroy($dstImage);
			imagedestroy($srcImage);
			return $canvas;
		}

		return false;
	}


	/**
	 * 图片缩放
	 *
	 * @param string $photo
	 * @param int $width
	 * @param int $height
	 * @return unknown
	 */
	public function resize($photo,$width,$height)
	{
		$type = $this->getPhotoType($photo);
		$image = imagecreatetruecolor($width,$height);
		
		list($srcWidth,$srcHeight) = getimagesize($photo);

		switch ($type)
		{
			case  'jpg'      :	$fromImage = imagecreatefromjpeg($photo);	break;
			case  'jpeg'	 : $fromImage = imagecreatefromjpeg($photo);	break;
			case  'gif' 	 : $fromImage = imagecreatefromgif($photo); 	break;
		}
		
		if(imagecopyresampled($image,$fromImage,0,0,0,0,$width,$height,$srcWidth,$srcHeight))
		{
			imagedestroy($fromImage);
			return $image;
		}

		return false;
	}
	
	
	/**
	 * 计算图片在画布上位置
	 *
	 * @param int $canvasW
	 * @param int $canvasH
	 * @param int $imageW
	 * @param int $imageY
	 */
	private function calculatePosition($canvasW,$canvasH,$imageW,$imageH)
	{
		if($imageW >= $canvasW){
			$x	   = 0;
			$width = $canvasW;
		}else{
			$x     = floor(($canvasW - $imageW)/2);
			$width = $imageW;
		}
		if($imageH >= $canvasH){
			$y      = 0;
			$height = $canvasH;
		}else{
			$y      = floor(($canvasH - $imageH)/2);
			$height = $imageH;
		}
		
		return array('canvasX' => $x,'canvasY' => $y,'canvasW' => $width,'canvasH' => $height);
	}

	/**
	 * 生成wap贺卡小图片
	 *
	 * @param string $photo
	 * @param string $wapPhotoName
	 * @param int $width
	 * @param int $height
	 * @return bool
	 */
	private function createWapCardPhoto($photo,$wapPhotoName,$width,$height)
	{
		$image = imagecreatetruecolor($width,$height);
		$type  = $this->getPhotoType($wapPhotoName);
		switch($type)
		{
			case 'jpg'  : $from = imagecreatefromjpeg($photo);  break;
			case 'jpeg'	: $from = imagecreatefromjpeg($photo);  break;
			case 'gif' 	: $from = imagecreatefromgif($photo);	break;
		}
		list($srcWidth,$srcHeight) = getimagesize($photo);

		if(imagecopyresampled($image,$from,0,0,0,0,$width,$height,$srcWidth,$srcHeight))
		{
			$result = imagejpeg($image,$wapPhotoName);	
			imagedestroy($from);
			imagedestroy($image);
			return $result;
		}
		
		return false;
	}

	/**
	 * 检查原图透明格式 gif/png
	 *
	 * @return string
	 */
	private function checkTransparenceType()
	{
		$dstType = $this->getPhotoType($this->dstFile);
		if($dstType != 'jpeg' && $dstType != 'jpg' && $dstType != 'gif'){
			return false;
		}

		$srcType = $this->getPhotoType($this->srcFile);
		if($srcType != 'png' && $srcType != 'gif'){
			return false;
		}

		return $srcType;
	}


	/**
	 * 检查底图格式,gif/jpg/jpeg
	 *
	 * @param string $type
	 * @return string
	 */
	private function checkType($type)
	{
		$dstType = $this->getPhotoType($this->dstFile);

		if($dstType != $type){
			return false;
		}

		$srcType = $this->getPhotoType($this->srcFile);
		if($srcType != 'jpeg' && $srcType != 'jpg'){
			return false;
		}

		return $type;
	}


	/**
	 * 获取图片格式
	 *
	 * @param string $fileName
	 * @return string
	 */
	private function getPhotoType($fileName)
	{
		$pos  = strrpos($fileName,'.');
		$type = substr($fileName,$pos+1);
	
		return strtolower($type);
	}
}

?>