<?php
/**
 * ����ֵ������
 *
 * @author huanggz
 */
class CMoney
{
	public static $type = array(
		//�ԾƵ���е�����15�֡�
		'COMMENT_HOTEL'=>15,
		//�Գ��Ƶ����Ŀ�ĵؽ��е�����10�֡�
		'COMMENT_DEST'=>10,
		//�κε��������������50������ֻ��2�֡�
		'COMMENT_MIN'=>2,
	    //�κε�����������50���ֽ��ã���10�֡�
		'COMMENT_REMOVE'=>-10,
	    //�κε�����������50���ֵĽ��ã���2�֡�
	    'COMMENT_REMOVE_MIN'=>-2,
	    //�οɵ�����û�����Ľ��ã���1��
	    'COMMENT_REMOVE_NONE'=>-1,
		//ֻ������û�����ֵ�����ֻ��1�֡�
		'COMMENT_NONE'=>1,
		//����֧�У�����������������3��
		'RECOMMEND'=>3,
		//�ϴ���Ƭ����1��
		'UPLOAD_PHOTO'=>1,
		//ÿ�������վ����2��
		'VISIT_EVERYDAY'=>2,
		//�����û�ϲ���㷢���κ����ݣ���1��
		'LIKE'=>1,
		//����Ҹ��û��Ľ��飬�����û����мƻ��ڵģ������15�֡�
		'TRIP_ADD_SUGGEST'=>15,
		//����û��Ӽƻ����Ƴ��˽��飬���ȥ15�֡�
		'TRIP_REMOVE_SUGGEST'=>-15,
        //��̨�ѣ��ش�鵵���������
        'BACK_REMOVE_SUGGEST'=>-3,
	    
	   /**********duhw�õ�********************************/
	     //�������мƻ���10��
        'CREATE_PLAN' => 10,
	     //ɾ�����мƻ���10��
	    'DELETE_PLAN' => -10,
	    /**********end duhw�õ�********************************/
	
		/**********lisc�õ�********************************/
		//�ʺ�ÿ��һ��������ƽ̨��20�֡�
        'BING_API'=>20,
		//�ʺ�ÿ��һ��������ƽ̨��-20�֡�
        'BING_API_CANCEL'=>-20,
		//������ͬ������5�֡�
        'COMMENT_SHARE'=>5,
		//������ϲ����������+�����߾��ӷ֣���2�֡�
        'COMMENT_LIKE_SHARE'=>2,//�����ߵķ���
	    'BEI_COMMENT_LIKE_SHARE'=>2,//�������ߵķ���
		//��Ƭ������2�֡�
        'PHOTO_SHARE'=>2,
	    'BEI_PHOTO_SHARE'=>2,//�������ߵķ���	
		//֧�б�����2�֡�
        'PLAN_SUGGEST_SHARE'=>2,
	    'BEI_PLAN_SUGGEST_SHARE'=>2,//�������ߵķ���
		//���ʲᱻ����5�֡�
        'PASSPORT_SHARE'=>5,
	    'BEI_PASSPORT_SHARE'=>5,//�������ߵķ���
		//�������ԡ���2�֡�
        'SUGGEST_SHARE'=>2,
		//�����Լ������мƻ���2�֡�
        'TRIP_PLAN_SHARE'=>5,
		/**********lisc�õ�********************************/
	
		//***********ADD BY YZB***********
		//�״�ע��+50
		'REGISTER'=>50,
		//ÿ���¼ ???+2(ͬ��ÿ�������վ����2�֡�)
		//��(һ��ֻ�ܴ�һ��)
		'DAKA'=>10,
		//�״��ϴ�ͷ��+5
		'FIRST_UPLOAD_HEAD'	=>5,
		//�����Ա����С���ȫ���䣨������ӷ֣�+10
		'COMPLETE_PERSONAL_INFO'=>10,
		//��ճ��С���ȫ���䣨��������֣�-10
		'CLEAR_PERSONAL_INFO'=>-10,
	
		//�����û�ȡ��ϲ���㷢���κ����ݣ�-1��
		'UN_LIKE'=>-1,
		//ɾ����Ƭ��-1��
		'DEL_PHOTO'=>-1,
		//ɾ���ϴ���Ƭ��-1��
		//'DEL_UPLOAD_PHOTO'=>-1,
		//ȥ��/��ȥ����+1
		'ADD_BEEN_WANT'=>1,
		//ȡ��(ȥ��/��ȥ)����-1
		'CANCEL_BEEN_WANT'=>-1,
		//���ԣ�������⣩+1
		'ADD_SUGGEST'=>1,
		//ɾ�����ԣ�ɾ��������⣩-1
		'DEL_SUGGEST'=>-1,
		//�����˹�ע+1
		'ADD_CONTACT'=>2,
		//������ȡ����ע-2
		'DEL_CONTACT'=>-2,
		//***********ADD BY YZB***********
		
	    //�ͻ��˾���ֵ�ӷ�
	    //�ͻ���ע�ᾭ��ֵ
		'CLE_REGISTER'=>50,
	    //ÿ���һ���ÿͻ��˵�¼����ֵ
	    'CLE_LOGIN'   =>10,
	    //�ͻ��˵���(û������)
	    'CLE_COMMENT_MIN' => 2,
	    //�ͻ��˵���(������)
	    'CLE_COMMENT_DEST'=> 10,
	   //�ͻ���ɾ������(û������)
	    'CLE_DELETE_COMMENT_MIN' => -2,
	    //�ͻ���ɾ������(������)
	    'CLE_DELETE_COMMENT_DEST'=> -10,
	    //�ͻ������ȥ������ֵ
	    'CLE_ADD_BEEN' => 2,
	    //�ͻ���ɾ��ȥ������ֵ
	    'CLE_DELETE_BEEN' => -2,
	    //�ͻ��������ȥ����ֵ
	    'CLE_ADD_WANT' => 2,
	    //�ͻ���ɾ����ȥ����ֵ
	    'CLE_DELETE_WANT' => -2,
	    //�ͻ���ǩ��
	    'CLE_ADD_MARK' => 5,
	    //ɾ���ͻ���ǩ��
	    'CLE_DELETE_MARK' => -5,
	    //�ͻ����ϴ�ͼƬ
	    'CLE_UPLOAD_PHOTO' => 5,
	    //�����˹�ע
	    'CLE_ADD_CONTACT' => 2,
	    //������ȡ����ע
	    'CLE_DELETE_CONTACT' => -2,
	    //�״��ϴ�ͼ��
	    'CLE_FIRST_UPLOAD_HEAD' => 5
	   //*********ADD BY DHW**********
	);
	
/*	public static $new_type = array(
		//�״�ע�᣺50�֡�
		'FIRST_REGIST'=>50,
		//ÿ���¼��2�֡�
		'LOGIN'=>2,
		//�״��ϴ�ͷ��5�֡�
		'FIRST_UPLOAD_USERPHOTO'=>5,
	    //�����Ա����С���ȫ���䣨������ӷ֣���10�֡�
		'COMPLETE_USER_INFO'=>10,
	    //�ԾƵ���е�����15�֡�
	    'COMMENT_HOTEL'=>15,
	    //�Գ�Ŀ�ĵء�������������е���������50�֣�10�֡�
	    'COMMENT_MAX'=>10,
	 	//�Գ�Ŀ�ĵء�������������е���������50�֣�2�֡�
	    'COMMENT_MIN'=>2,
		//�Գ�Ŀ�ĵء�������������е��������֣�û�����ֵ�������1�֡�
		'COMMENT_NONE'=>1,
		//�����������û�ϲ����1�֡�
		'COMMENT_LIKE'=>1,
		//����ϲ����ȡ����-1�֡�
		'COMMENT_LIKE_CANCEL'=>-1,
		//�ϴ�Ŀ�ĵ���Ƭ��1�֡�
		'UPLOAD_DEST_PHOTO'=>1,
		//ɾ��Ŀ�ĵ���Ƭ��-1�֡�
		'DELETE_DEST_PHOTO'=>-1,
		//��Ƭ��ϲ����1�֡�
		'PHOTO_LIKE'=>1,
		//��Ƭϲ����ȡ����-1�֡�
		'PHOTO_LIKE_CANCEL'=>1,
		//�����˵����мƻ�֧�У�3�֡�
		'PLAN_SUGGEST'=>3,
		//�����˵����мƻ�֧�У�3�֡�
		'GIVE_PLAN_SUGGEST'=>3,
		//֧�б�������ϲ����1�֡�
		'PLAN_SUGGESTLIKE'=>1,
		//ȥ��/��ȥ������1�֡�
		'BEEN_WANT_AREA'=>1,
		//�ƶ����мƻ���20�֡�
		'MAKE_TRIP_PLAN'=>20,
		//ɾ�����мƻ���-20�֡�
		'DELETE_TRIP_PLAN'=>-20,
        //�ƻ�������ϲ����2�֡�
        'PLAN_LIKE'=>2,
		//�����˹�ע��2�֡�
		'BE_FANS'=>2,
        //�����˹�עȡ����-2�֡�
        'BE_FANS_CANCEL'=>-2
	);*/
	
	public static $operation_type = array(//����ֵ�䶯����ز�������
		'CREATE_PLAN' => '�������мƻ�',
	    'DELETE_PLAN' => 'ɾ�����мƻ�'
	);
	public function __construct(){
		
	}
	
	/**
	 * ���¾���ֵ
	 * @param unknown_type $_subs_id �û�id
	 * @param unknown_type $_type ����
	 */
	public static function updateMoney($_subs_id,$_money)
	{
		if (!in_array($_money, self::$type))
		{
			$_money = 0;
		}
		$cdaoCCDAOCS_SUBS = new CCDAOCS_SUBS();
		$rs = $cdaoCCDAOCS_SUBS->updateMoney($_subs_id,$_money);
		return $rs?true:false;
	}
	
	/**
	 * ��������ֵ�����¼
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public static function AddMoneyDetail($data){
		if($data['SUBS_ID']=='' || $data['SRC_TYPE']=='' || $data['OPE_TYPE']=='' || $data['MONEY']=='' || $data['SERV_CODE']=='' || $data['SERV_ID']=='' || $data['OPE_SUBS_ID']==''){
			return false;
		}
		$data['CREATE_TIME'] = date("Y-m-d H:i:s");
		$data['MOD_TIME'] = date("Y-m-d H:i:s");
		$data['STATUS'] = 'A';
		$ccdaoCS_MONEY_DETAIL = new CCDAOCS_MONEY_DETAIL();
		$rs = $ccdaoCS_MONEY_DETAIL->doInsert($data,'SEQ_MONEY_ID');
		return $rs?true:false;
	}
}
?>
