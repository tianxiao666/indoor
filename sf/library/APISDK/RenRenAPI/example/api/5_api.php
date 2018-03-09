<?php
/**
 * һ��api�����ӣ���ȡ��ǰ��½�û���ǰ10λ���ѣ�
 *
 * friends.get
 * 
 * �����������ʽ��Ϊ��key=value����ʽ������k1=v1������k2=v2������k3=v3����
 * �����߸�ʽ���õĲ�����ֵ�ԣ����ֵ����������к�ƴ����һ�𣬼���k1=v1k2=v2k3=v3����
 * ����ƴ�Ӻõ��ַ���ĩβ׷����Ӧ�õ�Secret Key��
 * �����ַ�����MD5ֵ��Ϊǩ����ֵ��
 *
 * ע�⣺����sigʱ���ַ�����������UTF-8���롣
 * ע�⣺����sig��ʱ����Ҫ�Բ�������URLEncode����application/x-www-form-urlencoded�����룩�����Ƿ��������ʱ����Ҫ����URLEncode��
 * ע�⣺�кܶ࿪�����ڼ���ǩ����ʱ�򣬽��������Ͳ���ֵ��ʹ�á�application/x-www-form-urlencoded�����룬����ǩ����֤ʧ�ܡ�
 */
require_once '../../requires.php';

# api����ʱʵ����RenRenClient����oauth��Ȩʱʵ����RenRenOauth����
$client = new RenRenClient();

# �����Ӧ��ͨ������sdk�����Լ�ʵ�֣������session key����ô������ѡ��ֻʹ�ø�sdk�е�api����
# ������ͨ��setSessionKey�����������Ѿ���ȡ����session key��
$session_key = $_GET['session_key'];
$client->setSessionKey($session_key);

# $client->setCallId('12345678');

# ����apiʱ�ĵ�һ��������api��������
# �ڶ���������ο�config.inc.php�ļ��е����ý������á�
$friends = $client->POST('friends.getFriends', array('1', '10'));

foreach($friends as $friend) {
	echo "<img src=\"{$friend['tinyurl']}\" />&nbsp;&nbsp;{$friend['name']}<br/>";
}
?>