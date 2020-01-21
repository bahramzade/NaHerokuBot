﻿<?
// Если пришла ссылка типа t.me//..?start=123456789
if (strpos($text, "/start ")!==false) $text = str_replace ("/start ", "", $text);

// проверяем если пришло сообщение
if ($text) {

	if ($reply_to_message) {	

		if ($reply_forward) {
			
			// $reply_forward_id - это айди клиента написавшего сообщение боту,
			// из базы надо будет достать message_id клиента по reply_forward_id (id клиента)
			// осуществив поиск reply_message_id,  которое в базе записано как
			// message_id_out
			
			$query = "SELECT message_id_in FROM {$table_message} WHERE message_id_out={$reply_message_id} AND client_id={$reply_forward_id}";
			if ($result = $mysqli->query($query)) {				
				if($result->num_rows>0){
					$arrayResult = $result->fetch_all(MYSQLI_ASSOC);				
					$message_id_in = $arrayResult[0]['message_id_in'];
				}				
			}else throw new Exception("Не смог узнать message_id_in в таблице {$table_message}");
			
			$result = $bot->sendMessage($reply_forward_id, $text, null, null, $message_id_in);
				
			
			if ($result) {
			
				// а после надо сохранить айди сообщения админа клиенту
				// для возможности его редактирования
			
				$query = "INSERT INTO {$table_message} VALUES ('{$reply_forward_id}',
						'{$message_id}', '{$result['message_id']}', '{$result['date']}')";
				$mysql_result = $mysqli->query($query);
					
				if (!$mysql_result) throw new Exception("Не смог сделать записать в таблицу {$table_message}");
			}
			
			
		}elseif ($reply_sender_name) {
			
			$bot->sendMessage($chat_id, "Профиль скрыт.");
			
		}elseif ($chat_type == 'private') {			

			$result = $bot->forwardMessage($admin_group, $chat_id, $message_id);
			
			if ($result) {			
			
				$query = "INSERT INTO {$table_message} VALUES ('{$chat_id}',
						'{$message_id}', '{$result['message_id']}', '{$result['date']}')";
				$mysql_result = $mysqli->query($query);
					
				if (!$mysql_result) throw new Exception("Не смог сделать записать в таблицу {$table_message}");
				
			}			
	
		}
	
	}elseif ($text=='инфо') {
		
        _info();
		
	}else {
		
		if ($chat_type == 'private') {
			
			$bot->add_to_database($table_users);
			
			_deleting_old_records($table_message, $day);
			
			// клиент написал, надо в базе сохранить его id, message_id_in, date 
			// (и id_message_out, которое будет найдено ниже)
			// что бы потом с базы доставать message_id по date зная id клиента)
			$result = $bot->forwardMessage($admin_group, $chat_id, $message_id);
					
			if ($result) {
				
				// номер сообщения, которое бот отправил в админку
				// по этому номеру будет находиться message_id клиента,
				// когда админ ответит на сообщение (reply_to_message)
				
				$query = "INSERT INTO {$table_message} VALUES ('{$chat_id}',
					'{$message_id}', '{$result['message_id']}', '{$result['date']}')";
				$mysql_result = $mysqli->query($query);
				
				if (!$mysql_result) throw new Exception("Не смог сделать записать в таблицу {$table_message}");
				
			}
		}
    }	
       
}elseif ($photo) {

    
}elseif ($video) {
	
	
}




?>
