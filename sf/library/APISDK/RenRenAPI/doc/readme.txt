��SDK��Edison tsai��Tom Wang����������

һ��Ŀ¼��
D:.
|   RenRenClient.class.php		api�����࣬�������api��������Ӧ���ڸ����н���
|   requires.php				SDK�����ļ���ʹ�ø�SDKǰ������������Ŀ��������ļ�
|   RESTClient.class.php		rest�����࣬��RenRenClient��RenRenOauth�Ĺ�ͬ����
|   RenRenOauth.class.php		oauth������
|   config.inc.php				�����ļ���API_KEY��API_SECRET��CALLBACK�ڸ��ļ�������
|
+---example						ʵ��Ŀ¼������1_authorize.php����ҳ���е�����һ·�������
|   +---oauth
|   |       2_callback.php
|   |       4_sessionkey.php
|   |       1_authorize.php
|   |       3_accesstoken.php
|   |
|   \---api
|           5_api.php
|
\---doc							
        classes.uml				��ͼuml�ļ�
        classes.jpg				��ͼjpeg�ļ�
        readme.txt				

����ʵ��ʹ�÷���
��1��������hosts����ӡ�127.0.0.1       www.example.com example.com����
hostsλ�ã���C:/windows/system32/drivers/etc/hosts����windows������/etc/hosts����linux����
����hostsΪ���㱾�ص��ԡ�

��2��������Ӧ��
��ַ��http://app.renren.com/developers/createapp

��3��������Ӧ������
��ַ��http://app.renren.com/developers/app/130705/settings
�������ط���Ҫע�⣺
a�����߼����á��еġ���Ȩ�ص���ַ������Ϊ��http://www.example.com/renren-api-php-sdk/example/oauth/2_callback.php������������ʵ��������ã���
b������վ��Ϣ���еġ���վURL������Ϊ��http://www.example.com��������վ������������Ϊ��example.com������Щ���Ǹ���ǰ��hosts���ö����õģ����������ʵ��������ã���

��4�������������ļ�(config.inc.php)
�����ļ��д󲿷ֲ���Ҫ�Ķ�
a��$config->CALLBACK = 'http://www.example.com/renren-api-php-sdk/example/oauth/2_callback.php'; //�ص���ַ��ע����������Ӧ�õġ���Ȩ�ص���ַ��һ�¡�
b��$config->APIKey		= '9bbac42e58c844cd85c89aa7529*****';	//���API Key���뵽�������Ӧ���в鿴��
c��$config->SecretKey	= '7e099f5ebb8346c18453fd2539f*****';	//���API ��Կ���뵽�������Ӧ���в鿴��

��5��������ʵ��
��ַ��http://www.example.com/renren-api-php-sdk/example/oauth/1_authorize.php����������ʵ���������������ͬ��
��ҳ���ϵ�����һ��һ��������˽�oauth��api���õ��������̡�

Happy Programming!