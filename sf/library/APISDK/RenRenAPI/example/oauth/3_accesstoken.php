<?php
/**
 * ʹ��Authorization Code��ȡAccess Token
 *
 * Ĭ������£�Access Token����Ч��Ϊ1�졣
 *
 * Ӧ����Ҫ�ڽ���Authorization Code �ķ���˳����з��������Ƽ�post������Ȩ��������https://graph.renren.com/oauth/token����������5�����������
 * grant_type��ʹ��Authorization Code ��ΪAccess Grantʱ����ֵΪ��authorization_code���� 
 * code��Authorization Code�� 
 * client_id��Ӧ�õ�API Key�� 
 * client_secret��Ӧ�õ�Secret Key�� 
 * redirect_uri���������ȡAuthorization Codeʱ���ݵġ�redirect_uri������һ�¡�
 */
require_once '../../requires.php';

$oauth = new RenRenOauth();
$code = $_GET['code'];

/**
 * �������¸�ʽ������
 *array(
 *	'access_token' => '130705|5.a2bf7f751cc195cbb310ff15e3cd793a.86400.1305525600-223378553',
 *	'expires_in' => 87048,
 *);
 */
$token = $oauth->getAccessToken($code);
var_dump($token);
echo '<br/><br/>';
?>
<a href="4_sessionkey.php?access_token=<?php echo $token['access_token']; ?>">Session Key</a>