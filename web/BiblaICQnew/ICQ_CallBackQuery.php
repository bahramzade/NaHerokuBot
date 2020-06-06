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
			$реплика = "Название: ".$результат["title"]."\nОписание: ".$результат["about"]."\nПравила: ".$результат["rules"]."\nСсылка: ".$результат["inviteLink"];
			
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
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
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
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
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
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else {
		$bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	}
	
}elseif ($callbackData == "ojidajuschie") {

	$результат = $bot_icq->getPendingUsers($chatId);
	if ($результат['ok']) {
		if ($результат['users']) {
			$реплика = "Список ожидающих вступление в чат:\n\n";
			foreach($результат['users'] as $users) {
				$реплика .= "Пользователь: ".$users['userId']."\n";
			}
			$bot_icq->sendText($chatId, $реплика);
		}else $bot_icq->sendText($chatId, "Список ожидающих пуст");				
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else {
		$bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	}
	
}elseif ($callbackData == "prinyat_ojidajuschih") {
		
	$результат = $bot_icq->getPendingUsers($chatId);
	if ($результат['ok']) {
		if ($результат['users']) {	
		
			$результат = $bot_icq->resolvePending($chatId, true, null, true);
			if ($результат['ok']) {
				$bot_icq->answerCallbackQuery($queryId, "Все ожидающие добавлены!");	
			}elseif ($chatType == 'private') {
				$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
			}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
		}else $bot_icq->answerCallbackQuery($queryId, "Нет ожидающих!");
		
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	$bot_icq->resolvePending($chatId, true, null, true);
	
	
}elseif ($callbackData == "blokirovat") {

	$результат = $bot_icq->blockUser($chatId, "751967319");
	if ($результат['ok']) {
		$bot_icq->answerCallbackQuery($queryId, "Он заблокирован!");	
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
}elseif ($callbackData == "razblokirovat") {

	$результат = $bot_icq->unblockUser($chatId, "751967319");
	if ($результат['ok']) {
		$bot_icq->answerCallbackQuery($queryId, "Он разблокирован!");	
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
}elseif ($callbackData == "rename_title") {

	if (strpos($chatTitle, "Тестер")===0) {
		$newTitle = substr(strrchr($chatTitle, "Тестер"), 6);
	}else $newTitle = "Тестер".$chatTitle;
	
	$результат = $bot_icq->setTitle($chatId, $newTitle);
	if ($результат['ok']) {
		$bot_icq->answerCallbackQuery($queryId, "Сменил название!");	
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
	
}elseif ($callbackData == "rename_about") {

	$результат = $bot_icq->getInfo($chatId);
	if ($результат['ok']) {
	
		if (strpos($результат['about'], "Тестер")===0) {
			$newAbout = substr(strrchr($результат['about'], "Тестер"), 6);
		}else $newAbout = "Тестер".$результат['about'];
		
		$результат = $bot_icq->setAbout($chatId, $newAbout);
		if ($результат['ok']) {
			$bot_icq->answerCallbackQuery($queryId, "Сменил описание!");	
		}elseif ($chatType == 'private') {
			$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
		}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
		
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");

	
}elseif ($callbackData == "rename_rules") {

	$результат = $bot_icq->getInfo($chatId);
	if ($результат['ok']) {
	
		if (strpos($результат['rules'], "Тестер")===0) {
			$newRules = substr(strrchr($результат['rules'], "Тестер"), 6);
		}else $newRules = "Тестер".$результат['rules'];
		
		$результат = $bot_icq->setRules($chatId, $newRules);
		if ($результат['ok']) {
			$bot_icq->answerCallbackQuery($queryId, "Сменил правила!");	
		}elseif ($chatType == 'private') {
			$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
		}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
		
	}elseif ($chatType == 'private') {
		$bot_icq->sendText($chatId, "Тут только Ты и Я это не чат");
	}else $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");

	
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