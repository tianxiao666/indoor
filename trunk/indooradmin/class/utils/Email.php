<?php



class Email
{
	public function getEmailInfo($subject,$mailBody,$to_user)
	{
		$mail = new PHPMailer();
		$mail->IsSMTP();								    		// ʹ��SMTP��ʽ����
		$mail->CharSet='UTF-8';										// �����ʼ����ַ�����
		$mail->Host     = SF::app()->params['mail']['mail_host']; 		// ������ҵ�ʾ�����
		$mail->Port     = SF::app()->params['mail']['mail_port']; 		// set the SMTP port for the GMAIL server
		$mail->SMTPAuth = true; 									// ����SMTP��֤����
		$mail->Username = SF::app()->params['mail']['mail_user']; 		// �ʾ��û���(����д������email��ַ)
		$mail->Password = SF::app()->params['mail']['mail_passwd']; 	// �ʾ�����
		$mail->From     = SF::app()->params['mail']['mail_from'];		//�ʼ�������email��ַ
		$mail->FromName = CIconv::gbkToUtf8("tripdata�羰��");
		foreach ($to_user as $to_val) {
			$mailTo = $to_val;
			if($mailTo)
			{
				$mail->AddAddress("{$mailTo}", "");
			}
		}
		$mail->Subject = CIconv::gbkToUtf8($subject); 	//�ʼ�����
		$mail->Body    = CIconv::gbkToUtf8($mailBody); 					   //�ʼ�����
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //������Ϣ������ʡ��
		return $mail;
	}
}
?>