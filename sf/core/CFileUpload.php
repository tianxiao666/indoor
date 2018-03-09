<?php
/**
 * PHP ���ļ��ϴ���
 * 
 * @package CFileUpload
 * @���ļ�������һ���Ż������ݶ�û����֧�ֶ��ļ��ϴ��ģ�ֻ���ǵ���ָ���ļ��ϴ�
 */

class CFileUpload extends CApplicationComponent
{
    //��Ա
    var $upload_path    = '';
    var $field_name     = '';
    var $allow_type     = array();
    var $allow_size     = 0;
    var $files          = array();
    var $result         = array();
    
    //���캯��
    function CFileUpload($upload_path, $field_name,$subs_id=0)
    {
        //if (@is_dir($upload_path) && @is_writeable($upload_path))
		$this->upload_path = $upload_path;
        $this->field_name = $field_name;
		$this->subs_id = $subs_id;
		
    }
    
    //����
    function setUploadPath($upload_path)
    {
        if (@is_dir($upload_path) && @is_writeable($upload_path)) $this->upload_path = $upload_path;
    }
    
    function setFieldName($field_name)
    {
        if ($field_name) $this->field_name = $field_name;
    }
    
    function setAllowType($allow_type)
    {
        if (is_array($allow_type) && count($allow_type)) $this->allow_type = $allow_type;
    }
    
    function setAllowSize($allow_size)
    {
        if (is_numeric($allow_size) && ((int) $allow_size) > 0) $this->allow_size = (int) $allow_size;
    }
    
    function save($filename = '')
    {
        $result = $this->prepare();
        if (!is_array($result)) return $result;
        $total = count($this->files['name']);
        for ($i = 0; $i < $total; $i++)
        {
            if ($result[$i]['error'])
            {
                $source_file = $this->files['name'][$i];
                $dest_file = '';
                $mime_type = '';
                $size = 0;
                $error = $result[$i]['error'];
                array_push($this->result, array('source_file' => $source_file, 'dest_file' => $dest_file, 'mime_type' => $mime_type, 'size' => $size, 'error' => $error));
                unset($source_file);
                unset($dest_file);
                unset($mime_type);
                unset($size);
                unset($error);
            }
            else
            {
                $dest_file_name =$filename? $filename: $this->generateFileName();
                $dest_file_name = $this->upload_path . $dest_file_name . '.' . 
                                  $this->getFileExtension($this->files['name'][$i]);
                if(!move_uploaded_file($this->files['tmp_name'][$i], $dest_file_name))
                {
                    $source_file = $this->files['name'][$i];
                    $dest_file = '';
                    $mime_type = '';
                    $size = 0;
                    $error = '�����ƶ��ļ�ʱ���ִ���';
                    array_push($this->result, array('source_file' => $source_file, 'dest_file' => $dest_file, 'mime_type' => $mime_type, 'size' => $size, 'error' => $error));
                    unset($source_file);
                    unset($dest_file);
                    unset($mime_type);
                    unset($size);
                    unset($error);
                }
                else
                {
                    $source_file = $this->files['name'][$i];
                    $dest_file = $dest_file_name;
                    $mime_type = $this->files['mime_type'][$i];
                    $size = $this->files['size'][$i];
                    $error = '';
                    array_push($this->result, array('source_file' => $source_file, 'dest_file' => $dest_file, 'mime_type' => $mime_type, 'size' => $size, 'error' => $error));
                    unset($source_file);
                    unset($dest_file);
                    unset($mime_type);
                    unset($size);
                    unset($error);
                }
            }
        }
        return $this->result;
    }
    
    function prepare()
    {
        $result = array();
        if (!$this->field_name || !array_key_exists($this->field_name, $_FILES))
        {
            return '���󣡲������ݴ���field_name��';
        }
		//ȡ��������ɵ�Ŀ¼��ַ
		$this->upload_path=$this->getMediaLib();
		
        if (!$this->upload_path || !is_dir($this->upload_path) || !is_dir($this->upload_path))
        {
            return '����Ŀ¼�����ڻ򲻿�д��';
        }
        $files = $_FILES[$this->field_name];
        if (!$files['name'])
        {
            return '����û���ļ����ϴ���';
        }
        // �ļ�����У��
    	if (is_array($this->allow_type) && count($this->allow_type) && 
           		!in_array($this->getFileExtension($files['name']), $this->allow_type))
        {
        	return '�����ļ����Ͳ����ϡ�';
     	}
        
     	// ��һ�ϴ��ļ�ת�������飬��������ͳһ����
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
        
        // �����ϴ��ļ�
        $this->files = $files;
        $total = count($files['name']);
        //is a many array upload 
        for ($i = 0; $i < $total; $i++)
        {
            if (!$files['name'][$i] || !$files['tmp_name'][$i])
            {
                $error = '����δѡ��Ҫ�ϴ����ļ���';
            }
            else if (!is_uploaded_file($files['tmp_name'][$i]))
            {
                $error = '���󣡴��ļ�����ͨ��HTTP POST�ϴ��ġ�';
            }
            else if ($files['error'][$i])
            {
                $error = $this->getErrorMessage($files['error'][$i]);
            }
            else if (is_array($this->allow_type) && count($this->allow_type) && 
                     !in_array($this->getFileExtension($files['name'][$i]), $this->allow_type))
            {
                $error = '�����ļ����Ͳ����ϡ�';
            }
            else if (is_numeric($this->allow_size) && ((int) $this->allow_size) > 0 && 
            $files['size'][$i] > ((int) $this->allow_size))
            {
                $error = '�����ļ���С�������ơ�';
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
    
    function getErrorMessage($errno)
    {
        switch($errno)
        {
            case 1:
                return '�����ϴ����ļ������� php.ini �� upload_max_filesize ѡ�����Ƶ�ֵ��';
                break;
            case 2:
                return '�����ϴ��ļ��Ĵ�С������ HTML ���� MAX_FILE_SIZE ѡ��ָ����ֵ��';
                break;
            case 3:
                return '�����ļ�ֻ�в��ֱ��ϴ���';
                break;
            case 4:
                return '����û���ļ����ϴ���';
                break;
            default:
                return '����δ֪����';
                break;
        }
    }
    
    function getFileExtension($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }
    
    function generateFileName()
    {
        mt_srand((double) microtime() * 1000000);
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ012345789';
        $max = strlen($chars) - 1;
        for($i = 0; $i < 14; $i++)
        {
            $hash .= $chars{mt_rand(0, $max)};
        }
        return $hash . date('His');
    }
	
	/**
	 * �ļ������Ŀ¼���ɺ���
	 * 
	 * @author mary<mary@tripdata.com>
	 * 
	 */
    function getMediaLib()
    {
        if (intval($this->subs_id)>0){
 			$daoSubs = new CCDAOCS_SUBS();
			$medialib = $daoSubs->getUserMediaLib($this->subs_id);
		}
	
		 
        if (!$medialib)
        {
            $dir1 = $this->getRandomString(2, 3);
            if (!is_dir($this->upload_path  . "$dir1"))
            {
                mkdir($this->upload_path  . "$dir1", 0777);
            }
            $dir2 = $this->getRandomString(2, 3);
            if (!is_dir($this->upload_path  . "$dir1/$dir2"))
            {
                mkdir($this->upload_path  . "$dir1/$dir2", 0777);
            }
            $dir3 =($this->subs_id)?$this->subs_id:$this->getRandomString(2, 3);
            SF::log($this->upload_path .$dir1."/".$dir2."/".$dir3);
            if (!is_dir($this->upload_path  . "$dir1/$dir2/$dir3"))
            {
               mkdir($this->upload_path . "$dir1/$dir2/$dir3", 0777);
            }
            $medialib = $this->upload_path ."$dir1/$dir2/$dir3/";
            
	        if (intval($this->subs_id)>0)
	        {	SF::log($medialib.'----------------'.$this->subs_id);
	        	$user_medialib=str_replace(SF_ROOT, '', $medialib);
	        	$daoSubs->upUserMediaLib($this->subs_id,$user_medialib);
	        }
        }
    	if (!is_dir($medialib))
            {
               mkdir($medialib, 0777);
            }
		//SF::log($medialib);
        if ($medialib) return $medialib;
		//return $this->upload_path ."01/40/08/";
        return false;
    }
	
	function getRandomString($len, $type = 1)
	{
		if (!$len) return false;
		$types = array(
		'alnum'     => 1,
		'alpha'     => 2,
		'numeric'   => 3
		);
		$key = array_search($type, $types);
		$key = $key ? $key : 'alnum';
		switch ($key)
		{
			case 'alnum':
				$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			case 'alpha':
				$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			case 'numeric':
				$chars = '0123456789';
		}
		mt_srand((double) microtime() * 1000000);
		$max = strlen($chars) - 1;
		for($i = 0; $i < $len; $i++)
		{
			$hash .= $chars{mt_rand(0, $max)};
		}
		return $hash;
	}	
	
}
?>
