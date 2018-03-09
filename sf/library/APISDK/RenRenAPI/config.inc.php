<?php
/*
 * ���������ļ�������API Key, Secret Key���Լ�����������õ�API�б�
 * This file for configure all necessary things for invoke, including API Key, Secret Key, and all APIs list
 *
 * @Modified by Edison tsai on 16:34 2011/01/13 for remove call_id & session_key in all parameters.
 * @Created: 17:21:04 2010/11/23
 * @Author:	Edison tsai<dnsing@gmail.com>
 * @Blog:	http://www.timescode.com
 * @Link:	http://www.dianboom.com
 */

//����SESSION������
define('RENREN_KEY','renren_key');
define('RENREN_ACCESS','renren_access');

define('RENREN_UPENGYOU_URL','http://page.renren.com/600896813');//����������������ַ
define('RENREN_PAGE_ID',600896813);//��������������page_id����page_id��uid���Ӧ


global $renren_config;
$renren_config				= new stdClass;

# modify by tom.wang at 2011-05-12 : add relate url for oauth flow
$renren_config->AUTHORIZEURL = 'https://graph.renren.com/oauth/authorize';  //����������Ȩ�ĵ�ַ������Ҫ�޸�
$renren_config->ACCESSTOKENURL = 'https://graph.renren.com/oauth/token'; //��ȡaccess token�ĵ�ַ������Ҫ�޸�
$renren_config->SESSIONKEYURL = 'https://graph.renren.com/renren_api/session_key'; //��ȡsession key�ĵ�ַ������Ҫ�޸�
$renren_config->CALLBACK = SF_BASE_URL.'index.php?r=API/RENREN'; //�ص���ַ��ע����������Ӧ��һ��

$renren_config->APIURL		= 'http://api.renren.com/restserver.do'; //RenRen����API���õ�ַ������Ҫ�޸�
$renren_config->APP_ID		= SF::app ()->params ['RENREN_APP_ID'];
$renren_config->APIKey		= SF::app ()->params ['RENREN_APIKey'];	//���API Key������������
$renren_config->SecretKey	= SF::app ()->params ['RENREN_SecretKey'];	//���API ��Կ
$renren_config->APIVersion	= '1.0';	//��ǰAPI�İ汾�ţ�����Ҫ�޸�
$renren_config->decodeFormat	= 'json';	//Ĭ�ϵķ��ظ�ʽ������ʵ������޸ģ�֧�֣�json,xml
/*
 *@ ���½ӿ���������http://wiki.dev.renren.com/wiki/API����дʱ���������¹���
 *  key  (����)		: API��������ֱ��Copy�������ɣ������ִ�Сд
 *  value(��ֵ)		: �����еĲ���������required��optional������api_key,method,v,format����Ҫ��д֮�⣬
 *					  �����Ķ����Ը������ʵ�������������Ӣ�İ��״̬�µĶ������ָ����������
 */
$renren_config->APIMapping		= array( 
		'admin.getAllocation' => '',
		'connect.getUnconnectedFriendsCount' => '',
		'friends.areFriends' => 'uids1,uids2',
		'friends.get' => 'count,page',
		'friends.getFriends' => 'access_token,count,page',
		'notifications.send' => 'to_ids,notification',
		//��ȡ�û���Ϣ
		'users.getInfo'	=> 'uids,fields',
		//��ȡ������ҳ��Ϣ
		'pages.getInfo'	=> 'page_id,fields',
		//��ȡ������ҳ�б�
		'pages.getList'	=> 'access_token,uid,count,page,category',
		//��ȡ������ҳ�ķ�˿�б� 
		'pages.getFansList'	=> 'access_token,page_id,count,page',
		//����ǰ��¼�ߵĺ��ѻ�Ҳ��װ��ͬ��Ӧ�õ��û���֪ͨ�������ߣ�receiver���������û��ĺ��ѻ����ǰ�װӦ�õ��û���
		'notifications.send'	=> 'access_token,to_ids,notification',
		//����վ�����
		'share.publish'	=> 'access_token,type,ugc_id,user_id,url,comment',
		//�°淢��վ�����
		'share.share'	=> 'access_token,type,ugc_id,user_id,url,comment',
		//����Ӧ�õ��Զ���������
		//name 	string 	�����±��� ע�⣺���30���ַ� 
		//description 	string 	�������������� ע�⣺���200���ַ��� 
		//url 	string 	�����±����ͼƬָ������ӡ�  
		'feed.publishFeed'	=> 'access_token,name,description,url,image',
		/* ����ķ�������������� 
		   For more methods, please add by yourself.
		*/
);
?>