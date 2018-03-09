<?php
/**
 * ϵͳ�ֵ�
 * 
 * @author samlinye <samlinye@hotmail.com>
 * @package CDict
 */

class CDict
{
    
    var $AREA_CODE_GD = array(
        '0'     => 'GD',
        '020'   => 'GZ', 
        '0755'  => 'SZ',
        '0769'  => 'DG',
        '0757'  => 'FS',
        '0759'  => 'ZJ',
        '0756'  => 'ZH',
        '0754'  => 'ST',
        '0762'  => 'HY',
        '0752'  => 'HZ',
        '0760'  => 'ZS',
        '0750'  => 'JM',
        '0662'  => 'YJ',
        '0663'  => 'JY',
        '0763'  => 'QY',
        '0751'  => 'SG',
        '0753'  => 'MZ',
        '0758'  => 'ZQ',
        '0660'  => 'SW',
        '0668'  => 'MM',
        '0768'  => 'CZ',
		'0766'  => 'YF',
    );
    
    var $AREA_CODE_GD_NAME = array(
        '0'     => 'ȫʡ',
        '020'   => '����',
        '0755'  => '����',
        '0769'  => '��ݸ',
        '0757'  => '��ɽ',
        '0759'  => 'տ��',
        '0756'  => '�麣',
        '0754'  => '��ͷ',
        '0762'  => '��Դ',
        '0752'  => '����',
        '0760'  => '��ɽ',
        '0750'  => '����',
        '0662'  => '����',
        '0663'  => '����',
        '0763'  => '��Զ',
        '0751'  => '�ع�',
        '0753'  => '÷��',
        '0758'  => '����',
        '0660'  => '��β',
        '0668'  => 'ï��',
        '0768'  => '����',
		'0766'  => '�Ƹ�',
    );
    
  var $CITY = array(
        'GZ'   => '����',
        'SZ'  => '����',
        'DG'  => '��ݸ',
        'FS'  => '��ɽ',
        'ZJ'  => 'տ��',
        'ZH'  => '�麣',
        'ST'  => '��ͷ',
        'HY'  => '��Դ',
        'HZ'  => '����',
        'ZS'  => '��ɽ',
        'JM'  => '����',
        'YJ'  => '����',
        'JY'  => '����',
        'QY'  => '��Զ',
        'SG'  => '�ع�',
        'MZ'  => '÷��',
        'ZQ'  => '����',
        'SW'  => '��β',
        'MM'  => 'ï��',
        'CZ'  => '����',
		'YF'  => '�Ƹ�',
		 '0'  => '����',
    );

	 var $SP_COMP = array(
	 	'RONDI'		 => 'E199%WI32@#SH_6_',
	 	'SF'	 => 'E1#7%WI32@#SH88_',
	 	'21CN'		 => 'E199%21CNDF@#SH_6_'
	 );
 
	function getAreaCodeGDName($area_code)
    {
      	if ($area_code)
    	{
              $area_code = ($area_code{0} != '0') ? "0{$area_code}" : $area_code;
        }
        else 
        {
              $area_code = '0';
        }

        return $this->AREA_CODE_GD_NAME[$area_code];
    }
    
}
?>