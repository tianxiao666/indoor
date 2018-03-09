<?php
/*
 * 总体配置文件，包括API Key, Secret Key，以及所有允许调用的API列表
 * This file for configure all necessary things for invoke, including API Key, Secret Key, and all APIs list
 *
 * @Modified by Edison tsai on 16:34 2011/01/13 for remove call_id & session_key in all parameters.
 * @Created: 17:21:04 2010/11/23
 * @Author:	Edison tsai<dnsing@gmail.com>
 * @Blog:	http://www.timescode.com
 * @Link:	http://www.dianboom.com
 */

//定义SESSION的名称
define('RENREN_KEY','renren_key');
define('RENREN_ACCESS','renren_access');

define('RENREN_UPENGYOU_URL','http://page.renren.com/600896813');//与人人网合作的网址
define('RENREN_PAGE_ID',600896813);//与人人网合作的page_id，此page_id与uid相对应


global $renren_config;
$renren_config				= new stdClass;

# modify by tom.wang at 2011-05-12 : add relate url for oauth flow
$renren_config->AUTHORIZEURL = 'https://graph.renren.com/oauth/authorize';  //进行连接授权的地址，不需要修改
$renren_config->ACCESSTOKENURL = 'https://graph.renren.com/oauth/token'; //获取access token的地址，不需要修改
$renren_config->SESSIONKEYURL = 'https://graph.renren.com/renren_api/session_key'; //获取session key的地址，不需要修改
$renren_config->CALLBACK = SF_BASE_URL.'index.php?r=API/RENREN'; //回调地址，注意和您申请的应用一致

$renren_config->APIURL		= 'http://api.renren.com/restserver.do'; //RenRen网的API调用地址，不需要修改
$renren_config->APP_ID		= SF::app ()->params ['RENREN_APP_ID'];
$renren_config->APIKey		= SF::app ()->params ['RENREN_APIKey'];	//你的API Key，请自行申请
$renren_config->SecretKey	= SF::app ()->params ['RENREN_SecretKey'];	//你的API 密钥
$renren_config->APIVersion	= '1.0';	//当前API的版本号，不需要修改
$renren_config->decodeFormat	= 'json';	//默认的返回格式，根据实际情况修改，支持：json,xml
/*
 *@ 以下接口内容来自http://wiki.dev.renren.com/wiki/API，编写时请遵守以下规则：
 *  key  (键名)		: API方法名，直接Copy过来即可，请区分大小写
 *  value(键值)		: 把所有的参数，包括required及optional，除了api_key,method,v,format不需要填写之外，
 *					  其它的都可以根据你的实现情况来处理，以英文半角状态下的逗号来分割各个参数。
 */
$renren_config->APIMapping		= array( 
		'admin.getAllocation' => '',
		'connect.getUnconnectedFriendsCount' => '',
		'friends.areFriends' => 'uids1,uids2',
		'friends.get' => 'count,page',
		'friends.getFriends' => 'access_token,count,page',
		'notifications.send' => 'to_ids,notification',
		//获取用户信息
		'users.getInfo'	=> 'uids,fields',
		//获取公共主页信息
		'pages.getInfo'	=> 'page_id,fields',
		//获取公共主页列表
		'pages.getList'	=> 'access_token,uid,count,page,category',
		//获取公共主页的粉丝列表 
		'pages.getFansList'	=> 'access_token,page_id,count,page',
		//给当前登录者的好友或也安装了同样应用的用户发通知。接收者（receiver）必须是用户的好友或者是安装应用的用户。
		'notifications.send'	=> 'access_token,to_ids,notification',
		//发布站外分享
		'share.publish'	=> 'access_token,type,ugc_id,user_id,url,comment',
		//新版发布站外分享
		'share.share'	=> 'access_token,type,ugc_id,user_id,url,comment',
		//发送应用的自定义新鲜事
		//name 	string 	新鲜事标题 注意：最多30个字符 
		//description 	string 	新鲜事主体内容 注意：最多200个字符。 
		//url 	string 	新鲜事标题和图片指向的链接。  
		'feed.publishFeed'	=> 'access_token,name,description,url,image',
		/* 更多的方法，请自行添加 
		   For more methods, please add by yourself.
		*/
);
?>