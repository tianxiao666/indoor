<?php
/**
 * 系统字典
 * 
 * @author  mary
 * @package CDict
 */

/**
 * 缩略图规格数字Master Dimension
 */
define ( 'Master_Dimension_NONE', 1 );
define ( 'Master_Dimension_AUTO', 2 );
define ( 'Master_Dimension_HEIGHT', 3 );
define ( 'Master_Dimension_WIDTH', 4 );
define ( 'Master_Dimension_EQUAL', 5 );
class CDict {
	public $BASEMENT = array (
			'Y' => '地上',
			'N' => '地下'
	);
	public $FLOOR_TYPE = array (
			'LOOBY' => '大堂',
			'FUEST' => '客房',
			'EFOOD' => '餐饮'
	);
	public $FLOOR_STATUS = array (
			'A' => '正常',
			'X' => '失效'
	);
	public $EQUT_STATUS = array (
			'A' => '正常',
			'E' => '编辑中',
			'X' => '禁用'
	);
	public $EQUT_TYPE = array (
		'PBX' => '交换机',
		'AP' => 'AP'
	);
	public $EQUT_BRANDS = array (
		'BRAN1' => '品牌1',
		'BRAN2' => '品牌2',
		'BRAN3' => '品牌3'
	);
	public $EQUT_FACTORY = array (
		'ZXING' => '中兴',
		'HUA_S' => '华三',
		'HUA_W' => '华为'
	);
	public $POI_TYPE = array (
			'DING' => '餐饮',
			'SHOP' => '购物',
			'FUNC' => '休闲娱乐'
	);
	public $POI_STATUS = array (
			'A' => '正常',
			'E' => '编辑中',
			'X' => '禁用'
	);
	public $BUILD_TYPE = array (
			"MALL_" => "大型商场",
			"OFFIC" => "写字楼",
			"LARGE" => "大型场馆",
			"TRAFF" => "交通枢纽" 
	);
	public $BUILD_STATUS = array (
			'A' => '正常',
			'E' => '编辑中',
			'X' => '失效' 
	);
	// <option value="px">Pixels</option>
	// <option value="cm">Centimeters</option>
	// <option value="mm">Millimeters</option>
	// <option value="in">Inches</option>
	// <option value="pt">Points</option>
	// <option value="pc">Picas</option>
	// <option value="em">Ems</option>
	// <option value="ex">Exs</option>
	public $PLANEGRAPH_UNIT = array (
			'px' => '像素',
			'cm' => '厘米',
			'mm' => '毫米',
			'in' => '英寸',
			'pt' => '点',
			'pc' => 'Picas',
			'em' => 'Ems',
			'ex' => 'Exs' 
	// 'M' => '米',
		);
	public $DEFAULT_PLANEGRAPH = array (
			'DW_SCALE' => 0.15, // 1像素对应0.15m
			'DW_UNIT' => 'px',
			'BACKGROUD_COLOR' => '#FFFFFF',
			'STATUS' => 'E' 
	);
	public $STATUS_OPTIONS_NAME = array (
			'A' => '正常',
			'X' => '失效',
			'E' => '过期' 
	);
	public $LSCONTRACT_STATUS = array (
			'YES' => 'Y',
			'NO' => 'N' 
	);
	public $LSCONTRACT_STATUS_NAME = array (
			'Y' => '正常',
			'N' => '停用' 
	);
	public $WIKI_TYPE_VALUES = array (
			'JIAOTONG' => 'JIAOTONG', // 交通
			'PIAOJIA' => 'PIAOJIA', // 票价
			'KAIFANGSHIJIAN' => 'KAIFANGSHIJIAN', // 开放时间
			'TEICHAN' => 'TEICHAN'  // 特产
		);
	
	// 员工状态
	var $SYS_SUBS_STATE = array (
			'A' => '正常',
			'X' => '禁用' 
	);
	var $SYS_LOG_TYPE = array (
			'ADM' => '登录后台',
			'OAD' => '退出后台',
			'PRV' => '权限管理',
			'USR' => '用户管理',
			'SZU' => '修改资料',
			'LOG' => '日志管理' 
	// 'REP' => '统计管理',
	// 'NOT' => '公告管理',
	// 'PRD' => '应用管理',
		);
	var $SYS_LOG_PRI = array (
			'SYS' => '系统管理',
			'USE' => '使用日志' 
	);
	var $USER_GENDER = array (
			'M' => '男',
			'W' => '女',
			'S' => '保密' 
	);
	var $MEDIA_TYPE = array (
			'IMG' => '图片',
			'VID' => '视频',
			'SND' => '音频',
			'DOC' => '文档',
			'ZIP' => '压缩文件',
			'UNK' => '其他' 
	);
	var $AUDIT_TYPE = array (
			'DIS' => '禁用',
			'PAS' => '通过',
			'DEL' => '删除' 
	);
	var $AUDIT_RESULT = array (
			'A' => '通过',
			'X' => '不通过',
			'P' => '禁止显示',
			'F' => '出错' 
	);
	var $SYS_ROLE_TYPE = array (
			'SYS' => '系统预设',
			'USR' => '用户自定义' 
	);
	var $SYS_PRIV_TYPE = array (
			'SYS' => '系统预设',
			'USR' => '用户自定义' 
	);
	var $SYS_USL_ROLE = array (
			'USER' 
	);
	var $SYS_COL_STATUS = array (
			'A' => '启用',
			'X' => '禁用' 
	// 'D' => '删除',
		);
	var $SYS_NOTICE_SER_CODE = array (
			'ART' => '文章',
			'MED' => '媒体' 
	);
	var $SYS_FEEDBACK_STATUS = array (
			'X' => '未审核',
			'A' => '处理中',
			'F' => '已处理',
			'D' => '删除' 
	);
	var $SYS_PROD_STATUS = array (
			'A' => '正常',
			// 'X' => '删除',
			'P' => '停用' 
	);
	var $SYS_TRY_STATUS = array (
			'A' => '开始',
			'P' => '暂停',
			'E' => '结束',
			'X' => '禁用' 
	);
	var $SYS_TRY_REPORT_STATUS = array (
			'A' => '正常',
			'E' => '结束' 
	// 'D' => '删除',
		);
	var $SYS_ECLIENT_STATUS = array (
			'A' => '正常',
			'X' => '禁用' 
	// 'D' => '删除',
		);
	
	// 禁用的人名
	var $FORBIDNAME = array (
			'0' => '温家宝',
			'1' => '毛泽东',
			'2' => '邓小平',
			'3' => '江泽民',
			'4' => '胡锦涛' 
	);
	
	// 密码强度
	var $INTENSTION = array (
			'1' => '极佳',
			'2' => '强',
			'3' => '一般',
			'4' => '差',
			'5' => '很差' 
	);
	var $DIAGNOSES_STATE = array (
			'A' => '正常',
			'S' => '结束',
			'D' => '删除' 
	);
	var $STATAU = array (
			'A' => '启用',
			'X' => '停用' 
	);
	
	// 设施点
	var $FPOINTTOTYPE = array (
			'卫生间' => 'WEISHENGJIAN',
			'游客服务' => 'YOUKEFUWU',
			'出入口' => 'CHURUKOU',
			'停车场' => 'TINGCHECHANG',
			'治安点' => 'ZHIANDIAN',
			'加油站' => 'JIAYOUZHAN',
			'其他' => 'QITA',
			'售票点' => 'SHOUPIAODIAN',
			'交通站点' => 'JIAOTONGZHANDIAN',
			'医务室' => 'YIWUSHI' 
	);
	var $int_error_code = array (
			'001' => '未登录',
			'002' => '验证失败',
			'003' => '操作失败',
			'004' => '参数不存在',
			'005' => '' 
	);
	var $prod_price_week = array (
			'1' => '周一',
			'2' => '周二',
			'3' => '周三',
			'4' => '周四',
			'5' => '周五',
			'6' => '周六',
			'0' => '周日' 
	);
	var $prod_price_hour = array (
			'00' => '00',
			'01' => '01',
			'02' => '02',
			'03' => '03',
			'04' => '04',
			'05' => '05',
			'06' => '06',
			'07' => '07',
			'08' => '08',
			'09' => '09',
			'10' => '10',
			'11' => '11',
			'12' => '12',
			'13' => '13',
			'14' => '14',
			'15' => '15',
			'16' => '16',
			'17' => '17',
			'18' => '18',
			'19' => '19',
			'20' => '20',
			'21' => '21',
			'22' => '22',
			'23' => '23',
			'24' => '24' 
	);
	var $prod_festival = array (
			'NEW' => '元旦节',
			'SPR' => '春节',
			'VAL' => '情人节',
			'LAN' => '元宵节',
			'WOM' => '妇女节',
			'TOM' => '清明节',
			'LAB' => '劳动节',
			'DRA' => '端午节',
			'MID' => '中秋节',
			'NAT' => '国庆节',
			'CHU' => '重阳节',
			'CHR' => '圣诞节' 
	);
	var $error_code = array (
			'000' => '成功',
			'-1101' => '未登录',
			'-1102' => '登录失败',
			'-1001' => '数据库连接错误',
			'-1002' => '数据库操作失败',
			'-1003' => '无数据',
			'-1004' => '数据操作失败',
			'-1005' => '调用接口失败',
			'-2001' => '缺少参数',
			'-2002' => '参数校验失败',
			'-2003' => '参数格式错误',
			'-10001' => '经纬度参数不存在',
			'-10002' => '标识不存在',
			'-10003' => '标识类型不存在',
			'-10101' => '新闻标识不存在',
			'-10201' => '地图标识不存在',
			'-11002' => '用户不存在',
			'-11003' => '不能添加自己为好友',
			'-11004' => '与用户的关联不存在',
			'-11005' => '与用户的关联已存在',
			'-11006' => '当前用户不允许改名',
			'-11007' => '用户名已存在且密码不正确',
			'-11008' => '无法解析密码方式',
			'-11009' => '无参数传入',
			'-11101' => '用户标识不存在',
			'-11102' => '用户名或密码为不存在',
			'-11201' => '图像路径不存在',
			'-12006' => '绑定号码参数不存在',
			'-13001' => '标识不存在',
			'-13002' => '标识类型不存在',
			'-13003' => '经纬度参数不存在' 
	);
	var $STRANGER = array (
			'STRANGER' => 'STRANGER' 
	);
	var $KEYWORD_STATE = array ( // 关键字状态
			'A' => '正常使用',
			'N' => '加入处理' 
	);
	var $KEYWORD_LEVEL = array ( // 关键字级别
			'A' => '绝对禁止',
			'B' => '需要警告' 
	);
	var $STAT_TRACK_TYPE = array (
			'SOUR' => '来源',
			'DEST' => '去向' 
	);
	var $OTHER_FUNC = array (
			"active" => "启动客户端",
			"login" => "登录次数统计" 
	);
	public $APP = array (
			'WEB' => 'WEB网站',
			'WAP' => 'WAP网站',
			'API' => '客户端' 
	);
	
	// 功能-url状态
	public $FUNC_URL_STATE = array (
			'A' => '正常',
			'X' => '禁用' 
	);
	public $MSG_STATUS = array (
			'A' => '已发送',
			'B' => '草稿',
			'C' => '定时发送中' 
	);
}
?>
