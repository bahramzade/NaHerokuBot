﻿<?

if ($callbackData == "BBB") {
	
	$bot_icq->sendText($userId, $queryId);
	
	$bot_icq->answerCallbackQuery($queryId, "Вот такой вот тут текст", true);
	
}

$bot_icq->sendText($userId, $queryId);

?>