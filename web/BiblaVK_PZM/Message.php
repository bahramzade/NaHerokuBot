<?
$text = str_replace("[club190150616|@pzmarket] ", "", $text);
$text = str_replace("[club190150616|TesterPRIZMarket] ", "", $text);
$text = str_replace("[club188536519|@prizmarket_vk] ", "", $text);
$text = str_replace("[club188536519|Покупки на PRIZMarket] ", "", $text);

if ($text == "Прива") {	
	$random_id = time();	
	$массив = [
		"access_token" => $vk_token, 
		"random_id" => $random_id, 
		"peer_id" => $peer_id, 
		"message" => "Ну да, здравствуй)", 
		"v" => $vk_api_version
	];
	file_get_contents("https://api.vk.com/method/". "messages.send?". http_build_query($массив));

}elseif ($text=='курс' || $text=='Курс') {
    $курс = _kurs_PZM();
    $курс = str_replace("[CoinMarketCap](https://coinmarketcap.com/ru/currencies/prizm/)", "CoinMarketCap.com", $курс);
	$vk->messagesSend($peer_id, $курс);

}elseif ($text == "тест" || $text == "Тест") {	
	
	$action1 = [ 'type' => 'text', 'label' => 'Прива', 'payload' => [ 
		'button' => '1' ] ];
	$action2 = [ 'type' => 'text', 'label' => 'Закрепи', 'payload' => [ 
		'button' => '2' ] ];
	$action3 = [ 'type' => 'text', 'label' => 'Открепи', 'payload' => [ 
		'button' => '3' ] ];
	$action4 = [ 'type' => 'text', 'label' => 'Измени', 'payload' => [ 
		'button' => '4' ] ];
	$action5 = [ 'type' => 'text', 'label' => 'Удали', 'payload' => [ 
		'button' => '5' ] ];
	$action6 = [ 'type' => 'text', 'label' => 'Н.Беседа', 'payload' => [ 
		'button' => '6' ] ];
	$action7 = [ 'type' => 'text', 'label' => 'Удали', 'payload' => [ 
		'button' => '7' ] ];
	$action8 = [ 'type' => 'text', 'label' => 'Н.Беседа', 'payload' => [ 
		'button' => '8' ] ];
	$кнопки = [
	[	[ 	'action' => $action1,
			'color' => 'primary' ],
		[	'action' => $action2,
			'color' => 'secondary' ]
	],[	[	'action' => $action3,
			'color' => 'negative' ],
		[	'action' => $action4,
			'color' => 'positive' ]
	],[	[	'action' => $action5,
			'color' => 'secondary' ],
		[	'action' => $action6,
			'color' => 'primary' ]
	],[	[	'action' => $action7,
			'color' => 'positive' ],
		[	'action' => $action8,
			'color' => 'negative' ]
	] ];
	$клавиатура_в_сообщении = [
		'one_time' => false,
		'buttons' => $кнопки,
		'inline' => true
	];	
	$клавиатура = json_encode($клавиатура_в_сообщении);	
	$результат = $vk->messagesSend($peer_id, "Нажми на кнопку!", null, null, null, $клавиатура);
	
	
}elseif ($text == "закрепи" || $text == "Закрепи") {	
	
	$текст = "Текст для закрепа";	
	$message_id = $vk->messagesSend($peer_id, $текст);
	
	$результат = $vk->messagesPin($peer_id, $message_id);
	//$результJSON = json_encode($результат);
	//$vk->messagesSend($peer_id, $результJSON);
	
	
}elseif ($text == "открепи" || $text == "Открепи") {	
		
	$vk->messagesUnpin($peer_id, $group_id);
	
	
}elseif ($text == "измени" || $text == "Измени") {	
	
	$текст = "А";
	$vk->messagesSend($peer_id, $текст);
	sleep(1);
	$message_id = $vk->messagesSend($peer_id, $текст);
	sleep(1);
		
	$результат = $vk->messagesEdit($peer_id, $message_id, "Зачем?");
	//$результJSON = json_encode($результат);
	//$vk->messagesSend($peer_id, $результJSON);
	
	
}elseif ($text == "удали" || $text == "Удали") {	
		
	$vk->messagesSend($peer_id, "А");
	sleep(1);
	$message_id = $vk->messagesSend($peer_id, "Б");
	sleep(1);
	$vk->messagesSend($peer_id, "А");
	sleep(1);
	
	$результат = $vk->messagesDelete([$message_id], $group_id, true);
	//$результJSON = json_encode($результат);
	//$vk->messagesSend($peer_id, $результJSON);
	
	
}elseif ($text == "Н.Беседа") {	
		
	$результат = $vk->messagesCreateChat([119909267], "Автоматически созданная беседа", $group_id);
	$результJSON = json_encode($результат);
	$vk->messagesSend($peer_id, $результJSON);
	
	
}elseif ($text == "Пост") {	
	$результат = $vk2->wallPost(-$vk_group_id, "Тестовый текст");	 
	$vk->messagesSend($peer_id, "Отправил");
	$результJSON = json_encode($результат);
	$vk->messagesSend($peer_id, $результJSON);
	
}elseif ($text == "удалиПост") {	
	$vk2->wallPost(-$vk_group_id, "Тестовый текст");	 
	$vk->messagesSend($peer_id, "Отправил");
	sleep(3);
	$vk2->wallDelete(-$vk_group_id, $post_id);	 
	
	
}elseif ($text == "загрузи") {		
	$результат = $vk2->photosGetUploadServer($vk_album_id, $vk_group_id);	
	if ($результат['error_msg']) {		
		$vk->messagesSend($peer_id, "Ошибка: ".$результат['error_msg']);
		exit;		
	}	
	$vk->messagesSend($peer_id, "upload_url: ".$результат['upload_url']);	
	//$bot->sendMessage($master, "upload_url: ".$результат['upload_url']);		
	$результат = $vk2->upload($результат['upload_url'], "http://f0430377.xsph.ru/image/test5eccceaecbdc4.jpg");	
	$server = $результат['server'];
	$photos_list = $результат['photos_list'];
	$hash = $результат['hash'];	
	if ($photos_list == []) {		
		$vk->messagesSend($peer_id, "Ошибка: photos_list пуст");
		exit;		
	}		
	$vk->messagesSend($peer_id, "server: {$server}, photos_list: {$photos_list}, hash: {$hash}");		
	$результат = $vk2->photosSave($vk_album_id, $vk_group_id, $server, $photos_list, $hash);		
	if ($результат['error_msg']) {		
		$vk->messagesSend($peer_id, "Ошибка: ".$результат['error_msg']);
		exit;		
	}	
	//https://vk.com/photo-190150616_457239042
	$ссылка_на_фото_в_вк = "https://vk.com/photo".$результат[0]['owner_id']."_".$результат[0]['id'];
	$vk->messagesSend($peer_id, $ссылка_на_фото_в_вк);	
	foreach($результат[0]['sizes'] as $size) {		
		$ссылка_на_фото = $size['url'];			
	}		
	//https://sun9-52.userapi.com/c857324/v857324167/19ed96/BiXlvgG5oNw.jpg
	$vk->messagesSend($peer_id, $ссылка_на_фото);
	
}elseif ($text == "Пост") {	
	$vk2->wallPost(-$vk_group_id, "#куплю\n\n#еду за PRIZM\n\n#дорого", "photo-188536519_457239037");	 
	$vk->messagesSend($peer_id, "Отправил");
	
	
}elseif ($text == "еее" || $text == "Еее" || $text == "eee" || $text == "Eee" || $text == "ееее" || $text == "Ееее" || $text == "eeee" || $text == "Eeee") {	
	
	$текст = "Так держать, Мастер!";	
	$message_id = $vk->messagesSend($peer_id, $текст);
	
	
}

/*else {
    $vk->messagesSend($peer_id, "не пойму(");
}*/


?>
