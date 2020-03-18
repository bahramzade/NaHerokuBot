<?php

	$OtladkaBota = getenv('OTLADKA');

	$aws_key_id = getenv('AWS_ACCESS_KEY_ID');
	$aws_secret_key = getenv('AWS_SECRET_ACCESS_KEY');
	$aws_region = getenv('AWS_REGION');
	$aws_bucket = getenv('AWS_BUCKET_NAME');

	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
	$host = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$dbname = substr($url["path"], 1);

	$tokenMARKET = getenv('TOKEN_MARKET');
	$tokenGARANT = getenv('TOKEN_GARANT');
	$tokenInfo = getenv("TOKEN_INFO");
    $tokenZakaz = getenv('TOKEN_ZAKAZ');
	$tokenAvtoZakaz = getenv('TOKEN_AVTOZAKAZ');
	$tokenTimer = getenv('TOKEN_TIMER');
	$tokenTimer2 = getenv('TOKEN_TIMER_2');
	
	$cmc_api_key = getenv('CMC_PRO_API_KEY');
	$tokenTGraph = getenv('TOKEN_TELEGRAPH');
	$api_key = getenv('API_KEY_IMGBB');
	
	$master = getenv('MASTER');
	$admin_group_market = getenv('ADMIN_GROUP_MARKET');
	$admin_group_garant = getenv('ADMIN_GROUP_GARANT');		
	$admin_group_Info = getenv('ADMIN_GROUP_INFO');
	$admin_group_Zakaz = getenv('ADMIN_GROUP_ZAKAZ');
	$admin_group_AvtoZakaz = getenv('ADMIN_GROUP_AVTOZAKAZ');	
	$test_group = getenv('TEST_GROUP');
	$channel_market = getenv('CHANNEL_MARKET');
	$channel_info = getenv('CHANNEL_INFO');
	$channel_podrobno = getenv('CHANEL_PODROBNO');
	$channel_media_market = getenv('CHANNEL_MEDIA_MARKET');
		
	$mail_api_key = getenv("MAILGUN_API_KEY");
	$mail_domain = getenv("MAILGUN_DOMAIN");
	$mail_public_key = getenv("MAILGUN_PUBLIC_KEY");
	$mail_smtp_login = getenv("MAILGUN_SMTP_LOGIN");
	$mail_smtp_pass = getenv("MAILGUN_SMTP_PASSWORD");
	$mail_smtp_port = getenv("MAILGUN_SMTP_PORT");
	$mail_smtp_server = getenv("MAILGUN_SMTP_SERVER");
	
?>
