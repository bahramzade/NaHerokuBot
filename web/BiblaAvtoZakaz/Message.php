﻿<?
// Если клиент шлёт сразу группу файлов
if ($media_group_id) {

	_запись_в_таблицу_медиагрупа();	

}

if ($reply_to_message && $chat_id == $admin_group) {
	
	if (!$reply_caption) $reply_caption = $reply_text;
	
	$номер_строки = strpos($reply_caption, '@');
	
	if ($номер_строки) {
		
		$строка = strstr($reply_caption, '@');
		
		$есть_ли_энтр = strpos($строка, 10);
		
		if ($есть_ли_энтр) {
			
			$юзер_нейм = strstr($строка, 10, true);
			
		}else {
			
			$юзер_нейм = $строка;
			
		}
		
		//$bot->sendMessage($master, $юзер_нейм);
		
		$id_client = _дай_айди($юзер_нейм);
		
		$главное_меню = "\n\n/start 👈🏻 в главное меню!";
		
		$bot->sendMessage($id_client, $text.$главное_меню);
		
	}

}elseif ($text=='Отмена ввода') {

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
		
		}elseif ($result['last'] == 'foto_album') {
		
			_очистка_таблицы_ожидание();
			
			_нужен_ли_фотоальбом();
		
		}
		
	}else _start_AvtoZakazBota();
	

}else { 
	
	$result = _ожидание_ввода();
	
	if ($result) {
		
		if ($result['ojidanie'] == 'замена_фото') {
			
			$айди_клиента = $result['last'];
			
			if ($photo) {
			
				_запись_в_таблицу_маркет($айди_клиента, 'file_id', $file_id);
				
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял. Заменил.", null, $HideKeyboard);				
				
				$Объект_файла = $bot->getFile($file_id);		
		
				$ссыль_на_файл = $bot->fileUrl . $bot->token;	
					
				$ссыль = $ссыль_на_файл . "/" . $Объект_файла['file_path'];		
					
				$результат = $imgBB->upload($ссыль);					
					
				if ($результат) {		
						
					$imgBB_url = $результат['url'];		

					_запись_в_таблицу_маркет($айди_клиента, 'url_tgraph', $imgBB_url);
						
				}else throw new Exception("Не смог сделать imgBB_url");					
						
			}			

			
		}elseif ($result['ojidanie'] == 'nazvanie') {
			
			if ($text) {				
				
				//$text = mysqli_real_escape_string($text);
				
				if (strlen($text) > 60) {
				
					$bot->sendMessage($chat_id, "Слишком длинное название.\nНапишите название, около 30 символов.");
					
					exit('ok');
				}
				
				$text = str_replace("'", "\'", $text);
				$text = str_replace('"', '\"', $text);
				$text = str_replace(';', '\;', $text);
				$text = str_replace('*', '\*', $text);
				$text = str_replace('%', '\%', $text);
				$text = str_replace('`', '\`', $text);
				$text = str_replace('?', '\?', $text);
				$text = str_replace('&', '\&', $text);
				$text = str_replace('$', '\$', $text);
				$text = str_replace('^', '\^', $text);
				
				$text = str_replace('_', '\_', $text);
				
				
				_запись_в_таблицу_маркет($from_id, 'nazvanie', $text);
			
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_ссылка_в_названии();			
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'url_nazv') {
			
			if ($text) {						
				
				//$text = mysqli_real_escape_string($text);
				
				//надо проверить есть ли в тексте http://
				_запись_в_таблицу_маркет($from_id, 'url_nazv', $text);			
			
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
				
				_выбор_категории();
				
				//_выбор_валюты();
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'gorod') {
			
			if ($text) {
				
				$text = str_replace("'", "", $text);
				$text = str_replace('"', '', $text);
				$text = str_replace(';', '', $text);
				$text = str_replace('*', '', $text);
				$text = str_replace('%', '', $text);
				$text = str_replace('`', '', $text);
				$text = str_replace('?', '', $text);
				$text = str_replace('&', '', $text);
				$text = str_replace('$', '', $text);
				$text = str_replace('^', '', $text);
				$text = str_replace('\\', '', $text);
				//$text = str_replace('_', '\_', $text);
				
				$количество = substr_count($text, '#');
				
				if ($количество == 0) {
					
					$bot->sendMessage($chat_id, "Повторите ввод, но только теперь, обязательно поставьте хештег - #.");
					
					$bot->deleteMessage($chat_id, $message_id);		
					
				}elseif ($количество>3) {
					
					$bot->sendMessage($chat_id, "Повторите ввод, но, не больше трёх - #.");
					
					$bot->deleteMessage($chat_id, $message_id);		
				
				}else {
					
					// тут можно по entities достать только хештеги
					
					_запись_в_таблицу_маркет($from_id, 'gorod', $text);			
				
					_очистка_таблицы_ожидание();
					
					$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
					
					_отправьте_файл();
				
				}
				
			}else $bot->deleteMessage($chat_id, $message_id);			
			
		}elseif ($result['ojidanie'] == 'format_file') {			
			
			if ($photo||$video) {

				if ($video) {
					
					if ($file_size>'5242880') {
						
						$bot->sendMessage($chat_id, "Повторите ввод, а то Ваш файл размером больше 5 МБ, сократите его немного.");
					
						$bot->deleteMessage($chat_id, $message_id);
						
						exit('ok');
						
					}					
					
				}
			
				_запись_в_таблицу_маркет($from_id, 'format_file', $формат_файла);

				_запись_в_таблицу_маркет($from_id, 'file_id', $file_id);
				
				_очистка_таблицы_ожидание();
				
				if ($media_group_id) {
					
					$реплика = "Принял только ЭТОТ 👆🏻 файл.";
				
				}else $реплика = "Принял.";
				
				$bot->sendMessage($chat_id, $реплика, null, $HideKeyboard);
				
				_нужен_ли_фотоальбом();
			
			}else $bot->deleteMessage($chat_id, $message_id);
			
		}elseif ($result['ojidanie'] == 'foto_album') {						
			
			if ($формат_файла) {
			
				if ($media_group_id) {
				
					_очистка_таблицы_ожидание();

					//_запись_в_таблицу_медиагрупа();					
					
					$bot->sendMessage($chat_id, "Принял, ВСЕ.", null, $HideKeyboard);
					
					_опишите_подробно();
				
				}else {
					
					$bot->sendMessage($chat_id, "Пришлите все фото сразу, не по одному!!!");
					
					$bot->deleteMessage($chat_id, $message_id);	
					
				}
				
			}else $bot->deleteMessage($chat_id, $message_id);	
			
			
		}elseif ($result['ojidanie'] == 'podrobno') {
		
			if ($text) {
				
				$text = str_replace("'", "\'", $text);
				$text = str_replace('"', '\"', $text);
				$text = str_replace(';', '\;', $text);
				$text = str_replace('*', '\*', $text);
				$text = str_replace('%', '\%', $text);
				$text = str_replace('`', '\`', $text);
				$text = str_replace('_', '\_', $text);
				
				_запись_в_таблицу_маркет($from_id, 'podrobno', $text);				
				
				_очистка_таблицы_ожидание();
				
				$bot->sendMessage($chat_id, "Принял.", null, $HideKeyboard);
			
				_предпросмотр_лота();				
				
			}else $bot->deleteMessage($chat_id, $message_id);		
			
		}
		
	}elseif ($chat_type == 'private') $bot->deleteMessage($chat_id, $message_id);
	

}
	

  
  




?>
