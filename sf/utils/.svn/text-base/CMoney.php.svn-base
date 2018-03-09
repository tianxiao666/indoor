<?php
/**
 * 经验值管理类
 *
 * @author huanggz
 */
class CMoney
{
	public static $type = array(
		//对酒店进行点评：15分。
		'COMMENT_HOTEL'=>15,
		//对除酒店外的目的地进行点评：10分。
		'COMMENT_DEST'=>10,
		//任何点评如果文字少于50个，则只给2分。
		'COMMENT_MIN'=>2,
	    //任何点评字数超过50个字禁用，扣10分。
		'COMMENT_REMOVE'=>-10,
	    //任何点评字数少于50个字的禁用，扣2分。
	    'COMMENT_REMOVE_MIN'=>-2,
	    //任可点评无没字数的禁用，扣1分
	    'COMMENT_REMOVE_NONE'=>-1,
		//只有评分没有文字点评，只给1分。
		'COMMENT_NONE'=>1,
		//我来支招，完成这个动作，即给3分
		'RECOMMEND'=>3,
		//上传照片，给1分
		'UPLOAD_PHOTO'=>1,
		//每天访问网站，给2分
		'VISIT_EVERYDAY'=>2,
		//其他用户喜欢你发的任何内容，给1分
		'LIKE'=>1,
		//如果我给用户的建议，是在用户旅行计划内的，则给出15分。
		'TRIP_ADD_SUGGEST'=>15,
		//如果用户从计划中移除了建议，则减去15分。
		'TRIP_REMOVE_SUGGEST'=>-15,
        //后台把，回答归档，则减三分
        'BACK_REMOVE_SUGGEST'=>-3,
	    
	   /**********duhw用的********************************/
	     //创建旅行计划加10分
        'CREATE_PLAN' => 10,
	     //删除旅行计划减10分
	    'DELETE_PLAN' => -10,
	    /**********end duhw用的********************************/
	
		/**********lisc用的********************************/
		//帐号每绑定一个第三方平台：20分。
        'BING_API'=>20,
		//帐号每绑定一个第三方平台：-20分。
        'BING_API_CANCEL'=>-20,
		//点评并同步分享：5分。
        'COMMENT_SHARE'=>5,
		//点评被喜欢（创作者+分享者均加分）：2分。
        'COMMENT_LIKE_SHARE'=>2,//分享者的分数
	    'BEI_COMMENT_LIKE_SHARE'=>2,//被分享者的分数
		//照片被分享：2分。
        'PHOTO_SHARE'=>2,
	    'BEI_PHOTO_SHARE'=>2,//被分享者的分数	
		//支招被分享：2分。
        'PLAN_SUGGEST_SHARE'=>2,
	    'BEI_PLAN_SUGGEST_SHARE'=>2,//被分享者的分数
		//集邮册被分享：5分。
        'PASSPORT_SHARE'=>5,
	    'BEI_PASSPORT_SHARE'=>5,//被分享者的分数
		//分享“求攻略”：2分。
        'SUGGEST_SHARE'=>2,
		//分享自己的旅行计划：2分。
        'TRIP_PLAN_SHARE'=>5,
		/**********lisc用的********************************/
	
		//***********ADD BY YZB***********
		//首次注册+50
		'REGISTER'=>50,
		//每天登录 ???+2(同“每天访问网站，给2分”)
		//打卡(一天只能打卡一次)
		'DAKA'=>10,
		//首次上传头像+5
		'FIRST_UPLOAD_HEAD'	=>5,
		//完善性别或城市、或安全邮箱（各项单独加分）+10
		'COMPLETE_PERSONAL_INFO'=>10,
		//清空城市、或安全邮箱（各项单独减分）-10
		'CLEAR_PERSONAL_INFO'=>-10,
	
		//其他用户取消喜欢你发的任何内容，-1分
		'UN_LIKE'=>-1,
		//删除照片，-1分
		'DEL_PHOTO'=>-1,
		//删除上传照片，-1分
		//'DEL_UPLOAD_PHOTO'=>-1,
		//去过/想去景区+1
		'ADD_BEEN_WANT'=>1,
		//取消(去过/想去)景区-1
		'CANCEL_BEEN_WANT'=>-1,
		//求攻略（提出问题）+1
		'ADD_SUGGEST'=>1,
		//删除求攻略（删除提出问题）-1
		'DEL_SUGGEST'=>-1,
		//被他人关注+1
		'ADD_CONTACT'=>2,
		//被他人取消关注-2
		'DEL_CONTACT'=>-2,
		//***********ADD BY YZB***********
		
	    //客户端经验值加分
	    //客户端注册经验值
		'CLE_REGISTER'=>50,
	    //每天第一次用客户端登录经验值
	    'CLE_LOGIN'   =>10,
	    //客户端点评(没有文字)
	    'CLE_COMMENT_MIN' => 2,
	    //客户端点评(有文字)
	    'CLE_COMMENT_DEST'=> 10,
	   //客户端删除点评(没有文字)
	    'CLE_DELETE_COMMENT_MIN' => -2,
	    //客户端删除点评(有文字)
	    'CLE_DELETE_COMMENT_DEST'=> -10,
	    //客户端添加去过经验值
	    'CLE_ADD_BEEN' => 2,
	    //客户端删除去过经验值
	    'CLE_DELETE_BEEN' => -2,
	    //客户端添加想去经验值
	    'CLE_ADD_WANT' => 2,
	    //客户端删除想去经验值
	    'CLE_DELETE_WANT' => -2,
	    //客户端签到
	    'CLE_ADD_MARK' => 5,
	    //删除客户端签到
	    'CLE_DELETE_MARK' => -5,
	    //客户端上传图片
	    'CLE_UPLOAD_PHOTO' => 5,
	    //被他人关注
	    'CLE_ADD_CONTACT' => 2,
	    //被他人取消关注
	    'CLE_DELETE_CONTACT' => -2,
	    //首次上传图像
	    'CLE_FIRST_UPLOAD_HEAD' => 5
	   //*********ADD BY DHW**********
	);
	
/*	public static $new_type = array(
		//首次注册：50分。
		'FIRST_REGIST'=>50,
		//每天登录：2分。
		'LOGIN'=>2,
		//首次上传头像：5分。
		'FIRST_UPLOAD_USERPHOTO'=>5,
	    //完善性别或城市、或安全邮箱（各项单独加分）：10分。
		'COMPLETE_USER_INFO'=>10,
	    //对酒店进行点评：15分。
	    'COMMENT_HOTEL'=>15,
	    //对城目的地、景区、景点进行点评，多于50字：10分。
	    'COMMENT_MAX'=>10,
	 	//对城目的地、景区、景点进行点评，少于50字：2分。
	    'COMMENT_MIN'=>2,
		//对城目的地、景区、景点进行点评，评分（没有文字点评）：1分。
		'COMMENT_NONE'=>1,
		//点评被其他用户喜欢：1分。
		'COMMENT_LIKE'=>1,
		//点评喜欢被取消：-1分。
		'COMMENT_LIKE_CANCEL'=>-1,
		//上传目的地照片：1分。
		'UPLOAD_DEST_PHOTO'=>1,
		//删除目的地照片：-1分。
		'DELETE_DEST_PHOTO'=>-1,
		//照片被喜欢：1分。
		'PHOTO_LIKE'=>1,
		//照片喜欢被取消：-1分。
		'PHOTO_LIKE_CANCEL'=>1,
		//给他人的旅行计划支招：3分。
		'PLAN_SUGGEST'=>3,
		//给他人的旅行计划支招：3分。
		'GIVE_PLAN_SUGGEST'=>3,
		//支招被其他人喜欢：1分。
		'PLAN_SUGGESTLIKE'=>1,
		//去过/想去景区：1分。
		'BEEN_WANT_AREA'=>1,
		//制定旅行计划：20分。
		'MAKE_TRIP_PLAN'=>20,
		//删除旅行计划：-20分。
		'DELETE_TRIP_PLAN'=>-20,
        //计划被他人喜欢：2分。
        'PLAN_LIKE'=>2,
		//被他人关注：2分。
		'BE_FANS'=>2,
        //被他人关注取消：-2分。
        'BE_FANS_CANCEL'=>-2
	);*/
	
	public static $operation_type = array(//经验值变动的相关操作类型
		'CREATE_PLAN' => '创建旅行计划',
	    'DELETE_PLAN' => '删除旅行计划'
	);
	public function __construct(){
		
	}
	
	/**
	 * 更新经验值
	 * @param unknown_type $_subs_id 用户id
	 * @param unknown_type $_type 类型
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
	 * 新增经验值详情记录
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
