<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta charset="utf-8">
<body style="background-color:#feeff3;">
	<div style="font:14px/1.7 tahoma,arial,'Hiragino Sans GB','\005b8b\004f53',sans-serif;width:800px;margin: auto;min-height:100%;">
		<div style="color:#666;font-size:14px;margin:0 auto;">
			<div style="background: #fff; border: 1px solid #e3e3e3; padding:85px 70px 54px 70px; min-height: auto;">
				<h1 style="color:#D65679;font-size:22px">聘爱 密码重置</h1>
				<div>
					{{ HTML::image('assets/images/email/email_logo.png', '', array('height'=>'89', 'width'=>'200')) }}
				</div>
				<div style="margin-top:35px">
					<h1 style="color:#333;font-size:16px">亲爱的用户： 您好！</h1>
					<p>请在{{ Config::get('auth.reminder.expire', 60) }}分钟内点击以下链接完成密码重置：</p>
					<p>
						<a href="{{ route('reset', $token) }}" target="_blank" style="color:#D65679;text-decoration:underline">{{ route('reset', $token) }}</a>
					</p>
				</div>
				<div style="border-top:1px solid #ebedf1;padding-top:45px;margin:30px 0 20px 0">聘爱<a href="{{ route('home') }}" title="聘爱" style="color:#D65679;margin:0 10px;text-decoration:none" target="_blank">pinai521.com</a>感谢您的访问，祝您使用愉快！</div>
			</div>
			<div style="clear:both;color:#757575;font-size:10px;padding:20px 0 80px;text-align:center">
				<p style="margin:0;padding:0">请注意：此封邮件发送地址只用于通知，不能够接收邮件，请不要直接回复。您不需要退订或进行其他进一步的操作。</p>
				<p style="margin:0;padding:0">Copyright &copy; 2013 - <?php echo date('Y') ?> <a href="http://www.pinai521.com" style="color:#D65679;text-decoration:none" target="_blank">pinai521.com</a>. All Rights Reserved.</p>
			</div>
		</div>
	</div>
</body>
</html>