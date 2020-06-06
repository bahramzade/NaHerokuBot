﻿<?
/*
$событие = json_encode($event);
$bot_icq->sendText($userId, $событие);
*/
$chat = $message['chat'];
	$chatId = $chat['chatId'];
	$chatType = $chat['type'];
$from = $message['from'];
	$firstName = $from['firstName'];
	$nick = $from['nick'];
	$userId = $from['userId'];
$msgId = $message['msgId'];
$parts = $message['parts'];
	$parts_payload = $parts[0]['payload'];
	$parts_type = $parts[0]['type'];
$text = $message['text'];
$timestamp = $message['timestamp'];


if ($callbackData == "redaktor") {

	$результат = $bot_icq->answerCallbackQuery($queryId, "Ай молодец)))");	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	if ($text == "Нажми кнопку!") $реплика = "Хорошо, а ещё?";
	else $реплика = "Нажми кнопку!";
	
	$bot_icq->editText($chatId, $msgId, $реплика, $кнопа);	
	
	
}elseif ($callbackData == "obratnij_zapros") {

	$результат = $bot_icq->answerCallbackQuery($queryId, "Вот так он выглядит!", true);	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
}elseif ($callbackData == "dejstvie") {

	$результат = $bot_icq->answerCallbackQuery($queryId, "Он типа печатает))");	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	$результат = $bot_icq->​sendActions($chatId, "typing");
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
}elseif ($callbackData == "info_chata") {

	$результат = $bot_icq->getInfo($chatId);	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	else {
		if ($результат["type"] == 'private') {
			$реплика = "Имя: ".$результат["firstName"]."\nНик: ".$результат["nick"]."\nФото: ".$результат["photo"][0]['url'];
			
			$bot_icq->sendText($chatId, $реплика);
		}else {
			$реплика = "Название: ".$результат["title"]."\nОписание: ".$результат["about"]."\nСсылка: ".$результат["inviteLink"];
			
			$bot_icq->sendText($chatId, $реплика);
		}
	}
	
	
}elseif ($callbackData == "admini_chata") {

	$результат = $bot_icq->getAdmins($chatId);
	if ($результат['ok']) {
		$реплика = "Список администраторов чата:\n\n";
		foreach($результат['admins'] as $admins) {
			if ($admins['creator']) {
				$реплика .= "Создатель: ".$admins['userId']."\n";
			}else $реплика .= "Раймин: ".$admins['userId']."\n";
		}
		
		$bot_icq->sendText($chatId, $реплика);
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я");
	}else $bot_icq->sendText($chatId, "Ошибка: ".$результат['description']);
	
	
}elseif ($callbackData == "chleni_chata") {

	$результат = $bot_icq->getMembers($chatId);
	if ($результат['ok']) {
		$реплика = "Список членов чата:\n\n";
		foreach($результат['members'] as $members) {
			if ($members['creator']) {
				$реплика .= "Создатель: ".$members['userId']."\n";
			}elseif ($members['admin']) {
				$реплика .= "Раймин: ".$members['userId']."\n";
			}else $реплика .= "Пользователь: ".$members['userId']."\n";
		}
	
		$bot_icq->sendText($chatId, $реплика);
		
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я");
	}else $bot_icq->sendText($chatId, "Ошибка: ".$результат['description']);
	
}elseif ($callbackData == "zadlokirovanni") {

	$результат = $bot_icq->getBlockedUsers($chatId);
	if ($результат['ok']) {
		if ($результат['users']) {
			$реплика = "Список заблокированных пользователей:\n\n";
			foreach($результат['users'] as $users) {
				$реплика .= "Пользователь: ".$users['userId']."\n";
			}
			$bot_icq->sendText($chatId, $реплика);
		}else $bot_icq->answerCallbackQuery($queryId, "Список пуст");
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я");
	}else {
		$bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	}
	
}elseif ($callbackData == "udalenie") {

	$результат = $bot_icq->answerCallbackQuery($queryId, "До новых встреч!");	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	$результат = $bot_icq->​deleteMessages($chatId, [$msgId]);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
	
}elseif ($callbackData == "BBB") {

	$результат = $bot_icq->answerCallbackQuery($queryId, "Вот такой вот тут текст", true);	
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
}


?>