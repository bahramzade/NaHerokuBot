<?
$логин = $_COOKIE['login'];
include_once '../../../vendor/autoload.php';	
include_once '../../a_conect.php';
include_once '../../myBotApi/Bot.php';
//exit('ok');
$bot = new Bot($tokenAvtoZakaz);
$id_bota = strstr($tokenAvtoZakaz, ':', true);	
include_once '../../myBotApi/Variables.php';
include_once '../../BiblaAvtoZakaz/Functions_site.php';
$admin_group = $admin_group_AvtoZakaz;
$админка = $admin_group;
$мастер = $master;
$table_market = 'avtozakaz_pzmarket';
$mysqli = new mysqli($host, $username, $password, $dbname);
if (mysqli_connect_errno()) {
	echo "Чёт не выходит подключиться к MySQL<br><br>";	
	exit('ok');
}else { // начало
		
	$путь_к_фото = $_FILES['file']['tmp_name'];	

	// Подключение к Амазон
	$credentials = new Aws\Credentials\Credentials($aws_key_id, $aws_secret_key);
		
	$s3 = new Aws\S3\S3Client([
		'credentials' => $credentials, 
		'version'  => 'latest',
		'region'   => $aws_region
	]);
	
	// поиск уникального номера файла (uniqid)
	$запрос = "SELECT id_zakaz FROM {$table_market} WHERE id_client='7' AND username='{$логин}' AND status=''";		
		
	if ($результат = $mysqli->query($запрос)) {		
		if ($результат->num_rows == 1) {		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);			
			$uniqid = $результМассив[0]['id_zakaz'];
			if ($uniqid) {
				
				$key = "temp{$uniqid}.jpg"; //{$логин}-
				
				$result = $s3->deleteObjects([
					'Bucket' => $aws_bucket,			
					'Delete' => [
						'Objects' => [
							[
								'Key' => $key,						
							],            
						],				
					],
				]);
				
				if ($result['@metadata']['statusCode'] == '200') {
					$bot->sendMessage($мастер, "Старый Файл {$key} удалён с Амазон");
				}else {
					echo "Чего то не получается удалить лот {$key} из Amazon";	
					exit;
				}
				
			}
		}		
	}
		
 	$uniqid = uniqid();	

	//$key = "TEMP-{$логин}-{$uniqid}.jpg";
	$key = "temp{$uniqid}.jpg";

	$file = file_get_contents($путь_к_фото);
	  
	$upload = $s3->putObject([
		'Bucket' => $aws_bucket,
		'Key'    => $key, 
		'Body'   => $file,	
		'ACL'    => 'public-read'
	]);			
	// ---------------------------			

	if(!$upload) {
		echo "Не смог загрузить файл на Амазон";	
		exit;
	}else {
		//echo "Файл загружен на Амазон";
		$ссылка_на_амазон = "https://{$aws_bucket}.s3.{$aws_region}.amazonaws.com/" . $key;
		//echo "<br><br>" . $ссылка_на_амазон;	
		$bot->sendMessage($мастер, "Файл загружен на Амазон");
		//$bot->setMyCommands($BotCommand);
		
		$связь = _дай_связь($логин);
				
		if ($_POST['currency']) $валюта = $_POST['currency']." / PZM";
		else $валюта = "PZM";
		
		$время = time();
		
		$query = "DELETE FROM {$table_market} WHERE id_client='7' AND username='{$логин}' AND status=''";		
		if ($mysqli->query($query)) {			
			$query = "INSERT INTO {$table_market} (
			  `id_client`, `id_zakaz`, `kuplu_prodam`, `nazvanie`, `url_nazv`, `valuta`, 
			  `gorod`, `username`, `doverie`, `otdel`, `format_file`, `file_id`, `url_podrobno`, 
			  `status`, `podrobno`, `url_tgraph`, `foto_album`, `url_info_bot`, `date`
			) VALUES (
			  '7', '{$uniqid}', '{$_POST['hesh_pk']}', '{$_POST['name']}', '{$_POST['link_name']}', '{$валюта}', '{$_POST['hesh_city']}', '{$логин}', '0', '{$_POST['hesh_kateg']}', 'фото', '', '', '', '{$_POST['opisanie']}', '{$ссылка_на_амазон}', '', '{$связь}', '{$время}'
			)";							
			$result = $mysqli->query($query);			
			if (!$result) {
				echo "Не смог сделать запись в таблицу  (sozdanie-save_zakaz.php)";	
				exit;
			}else {
				_отправка_лота_админам_с_сайта(); 
			}
		}else {
			echo "Не смог удалить запись в таблице (sozdanie-save_zakaz.php)";	
			exit;
		}

		$array = [
			'login'   => $логин,
			'file' => $ссылка_на_амазон
		];	

		echo "Заявка отправлена РАЙминистрации.<br><br>";
		echo "Сообщение о решении будет отправленно Вам на email.<br><br>";
		echo "Все вопросы в <a href='https://teleg.link/Prizm_market_supportbot'>тех.поддержку.</a>";
		
		if ($tester == 'да') $array = array_merge($array, [ 'tester' => $tester ]);
		 
		$ch = curl_init('http://f0430377.xsph.ru');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $array); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$html = curl_exec($ch);
		curl_close($ch);	
		 
		echo $html;
		
	}


} // конец

?>
