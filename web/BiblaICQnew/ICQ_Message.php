﻿<?
//
if ($chatType == 'private' || $text=='/kurs' || $text=='курс' || $text=='Курс' || $chatType == 'group') {
//------------------------------------------

if ($text=='/start') {
	_старт();
	
}elseif ($text=='тест' || $text=='Тест') {
	$реплика = "Нажми кнопку!";
		
	$bot_icq->sendText($chatId, $реплика, $кнопа);
	
}elseif ($text=='/kurs' || $text=='курс' || $text=='Курс') {
	$курс = _kurs_PZM();
	$курс = str_replace("[CoinMarketCap](https://coinmarketcap.com/ru/currencies/prizm/)", "CoinMarketCap.com", $курс);
	$bot_icq->sendText($chatId, $курс);

}elseif ($text=='мат') {
	$реплика = "мат не дозволителен";
	$bot_icq->sendText($chatId, $реплика);
	
	$результат = $bot_icq->​deleteMessages($chatId, [$msgId]);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
}elseif ($text=='матюк') {
	$реплика = "Таких слов нельзя даже мыслить";
	$bot_icq->sendText($chatId, $реплика);
	$msgId1 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	$msgId2 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	$msgId3 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	
	$результат = $bot_icq->​deleteMessages($chatId, [$msgId1, $msgId2, $msgId3]);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
}elseif ($text=='шаг') {
	$реплика = "шаг";
	$bot_icq->sendText($chatId, $реплика);
	$msgId1 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	$msgId2 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	$msgId3 = $bot_icq->sendText($chatId, $реплика);
	$bot_icq->sendText($chatId, $реплика);
	
	$реплика = "и мат";	
	$результат = $bot_icq->editText($chatId, $msgId1, $реплика);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	$результат = $bot_icq->editText($chatId, $msgId2, $реплика);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	$результат = $bot_icq->editText($chatId, $msgId3, $реплика);
	if ($результат['ok'] == false) $bot_icq->sendText($chatId, "Ошибка: {$результат['description']}");
	
}elseif ($text=='/privet' || $text=='привет' || $text=='Привет') {
	$реплика = "Сам ты привет. И брат твой привет. И сестра твоя привет.";
	$bot_icq->sendText($chatId, $реплика);
	
}elseif ($text=='/KakDela' || $text=='Как дела?' || $text=='как дела?' || $text=='Как дела' || $text=='как дела') {
	$реплика = "Дааа норм чо, #сам_чо_как?";
	$bot_icq->sendText($chatId, $реплика);
	
}elseif ($text=='/eee' || $text=='Еее' || $text=='еее' || $text=='Eee' || $text=='eee' || $text=='Ееее' || $text=='ееее' || $text=='Eeee' || $text=='eeee') {
	$реплика = "Так держать хозяин!";
	$bot_icq->sendText($chatId, $реплика);
	
}elseif ($text=='/uf' || $text=='Уфь' || $text=='уфь') {
	$реплика = "Эт ты на каком языке? Уууфь, нет такой буквы в алфавите!";
	$bot_icq->sendText($chatId, $реплика);
	
}elseif ($text && $chatType == 'private') $bot_icq->sendText($chatId, "я не понимаю(");

//------------------------------------------
}
?>
