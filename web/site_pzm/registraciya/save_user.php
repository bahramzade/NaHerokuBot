<?
include_once '../../a_conect.php';
include_once '../../myBotApi/Bot.php';
//exit('ok');
$token = $tokenMARKET;
$bot = new Bot($token);
$id_bota = strstr($token, ':', true);	
include '../../myBotApi/Variables.php';
$admin_group = $admin_group_market;

if ($_POST['email']) $bot->sendMessage($admin_group, $_POST['email']);

$логин = htmlspecialchars($_POST['login']);
$пароль = htmlspecialchars($_POST['password']);
$емаил = htmlspecialchars($_POST['email']);

//удаляем лишние пробелы
$логин = trim($логин);
$пароль = trim($пароль);
$емаил = trim($емаил);

$пароль_md5 = md5($пароль);


$ссылка_подтверждения = $путь_сервера . "?registration=1&login=" . $логин . "&pass=" . $пароль_md5;

include 'phpmailer.php';



?>