﻿<?
// Если пришла ссылка типа t.me//..?start=123456789
if (strpos($text, "/start ")!==false) $text = str_replace ("/start ", "", $text);

// проверяем если пришло сообщение
if ($text) {

	if ($reply_to_message) {	
	
			
			
	}elseif ($text=='инфо') {
		
              _info();
		
	}else {

		$bot->forwardMessage($admin_group, $chat_id, $message_id);

    }	
       
}elseif ($photo) {

    
}elseif ($video) {
	
	
}




?>
