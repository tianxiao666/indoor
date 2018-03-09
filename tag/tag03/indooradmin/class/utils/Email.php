<?php



class Email
{
	public function getEmailInfo($subject,$mailBody,$to_user)
	{
		$mail = new PHPMailer();
		$mail->IsSMTP();								    		// 使用SMTP方式发送
		$mail->CharSet='UTF-8';										// 设置邮件的字符编码
		$mail->Host     = SF::app()->params['mail']['mail_host']; 		// 您的企业邮局域名
		$mail->Port     = SF::app()->params['mail']['mail_port']; 		// set the SMTP port for the GMAIL server
		$mail->SMTPAuth = true; 									// 启用SMTP验证功能
		$mail->Username = SF::app()->params['mail']['mail_user']; 		// 邮局用户名(请填写完整的email地址)
		$mail->Password = SF::app()->params['mail']['mail_passwd']; 	// 邮局密码
		$mail->From     = SF::app()->params['mail']['mail_from'];		//邮件发送者email地址
		$mail->FromName = CIconv::gbkToUtf8("tripdata风景网");
		foreach ($to_user as $to_val) {
			$mailTo = $to_val;
			if($mailTo)
			{
				$mail->AddAddress("{$mailTo}", "");
			}
		}
		$mail->Subject = CIconv::gbkToUtf8($subject); 	//邮件标题
		$mail->Body    = CIconv::gbkToUtf8($mailBody); 					   //邮件内容
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		return $mail;
	}
}
?>