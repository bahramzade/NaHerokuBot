﻿<?

if ($text=='Отмена ввода') {

	$bot->sendMessage($chat_id, "Ввод отменён.", null, $HideKeyboard);
	
	$result = _ожидание_ввода();	
	
	if ($result) {
	
		if ($result['last'] == 'kuplu_prodam') {			
			
			_очистка_таблицы_ожидание();
			
			_создать();
		
		}elseif ($result['last'] == 'nazvanie') {
		
			_очистка_таблицы_ожидание();
			
			_ссылка_в_названии();
		
		}elseif ($result['last'] == 'valuta') {
		
			_очистка_таблицы_ожидание();
			
			_выбор_валюты();
		
		}elseif ($result['last'] == 'gorod') {
		
			_очистка_таблицы_ожидание();
			
			_ввод_местонахождения();
		
		}elseif ($result['last'] == 'format_file') {
		
			_очистка_таблицы_ожидание();
			
			_отправьте_файл();
		
		}
		
	}else _start_AvtoZakazBota();
	

}else { 
	
	$result = _ожидание_ввода();
	
	if ($result) {
		
		if ($result['ojidanie'] == 'nazvanie') {
			
			if ($text) {
				
				_запись_в_таблицу_маркет('nazvanie', $text);
			
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_ссылка_в_названии();			
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'url_nazv') {
			
			if ($text) {
				
				_запись_в_таблицу_маркет('url_nazv', $text);			
			
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_выбор_валюты();
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'gorod') {
			
			if ($text) {
			
				$количество = substr_count($text, '#');
				
				if ($количество == 0) {
					
					$bot->sendMessage($chat_id, "Повторите ввод, но только теперь, обязательно поставьте хештег - #.");
					
					$bot->deleteMessage($chat_id, $message_id);		
					
				}elseif ($количество>3) {
					
					$bot->sendMessage($chat_id, "Повторите ввод, но, не больше трёх - #.");
					
					$bot->deleteMessage($chat_id, $message_id);		
				
				}else {
				
					_запись_в_таблицу_маркет('gorod', $text);			
				
					_очистка_таблицы_ожидание();
					
					$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
					
					_отправьте_файл();
				
				}
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'format_file') {			
			
			if ($photo||$video) {
				
				if ($photo) $format_file = 'фото';
			
				if ($video) {
					
					if ($file_size>'5242880') {
						
						$bot->sendMessage($chat_id, "Повторите ввод, а то Ваш файл размером больше 5 МБ, сократите его немного.");
					
						$bot->deleteMessage($chat_id, $message_id);
						
						exit('ok');
						
					}					
					
					$format_file = 'видео';					
					
				}
			
				_запись_в_таблицу_маркет('format_file', $format_file);

				_запись_в_таблицу_маркет('file_id', $file_id);
				
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_нужен_ли_фотоальбом();
			
			}else $bot->deleteMessage($chat_id, $message_id);
			
		}elseif ($result['ojidanie'] == 'foto_album') {						
			
			if ($photo) {

				$format_file = 'фото';			
				
				_запись_в_таблицу_маркет('foto_album', '1');

				_запись_в_таблицу_медиагрупа();
				
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_опишите_подробно();
				
			}else $bot->deleteMessage($chat_id, $message_id);	
			
			
		}elseif ($result['ojidanie'] == 'podrobno') {
		
			if ($text) {
				
				_запись_в_таблицу_маркет('podrobno', $text);

				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_ожидание_результата();
				
			}else $bot->deleteMessage($chat_id, $message_id);		
			
		}
		
	}else $bot->deleteMessage($chat_id, $message_id);
	

}
	

  
  




?>
