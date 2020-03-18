<?php
include_once '../../../vendor/autoload.php';	
include_once '../../a_conect.php';
//include_once '../pzmarket.php';


//exit('ok');

$token = $tokenMARKET;
$tg = new \TelegramBot\Api\BotApi($token);

$id_bota = strstr($token, ':', true);	

// Группа администрирования бота (Админка)
$admin_group = $admin_group_market;

try {
	$tg->sendMessage($admin_group, "йээ");
}catch ($e){
	echo "эээх";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />	
	<title>Регистрация на PRIZMarket!</title>
	<?include_once '../site_files/head.php';?>
	<style type="text/css"></style>
</head>
<body>
	<header>
		<?include_once '../site_files/header.php';?>
	</header>
	<nav>
		<?include_once '../site_files/nav.php';?>
	</nav>
	<div id="slideMenu">Моё детище, а не просто сайт!</div>
	<div id="wrapper">
		<div id="TopCol">		
			<?include_once '../site_files/wrapper-topCol.php';?>
		</div>
		<div id="leftCol">		
			<?include_once '../site_files/wrapper-leftCol-registraciya.php';?>
		</div>
		<div id="rightCol">
			<?include_once '../site_files/wrapper-rightCol.php';?>
		</div>
	</div>
	<footer>
		<?include_once '../site_files/footer.php';?>
	</footer>
</body>
</html>
