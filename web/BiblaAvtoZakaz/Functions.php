﻿<?
/* Список всех функций:
**
** _start_AvtoZakazBota
** _инфо_автоЗаказБота
** _есть_ли_лоты
** _последняя_публикация
** exception_handler
** _existence
** _дай_айди
**
** _создать
** _вывод_списка_лотов
** _повтор
** _отправка_лота
** _отправить_на_повтор
** _установка_времени
** _удаление
** _удалить_выбранный_лот
** _узнать_имя_по_номеру_лота
** _куплю
** _продам
** _продам_куплю
** _запись_в_таблицу_маркет
** _ожидание_ввода
** _очистка_таблицы_ожидание
** _очистка_таблицы_медиа
** _ссылка_в_названии
** _да_нужна
** _не_нужна
** _выбор_категории
** _выбор_валюты
** _рубль
** _доллар
** _евро
** _юань
** _гривна
** _фунт
** _призм
** _когда_валюта_выбрана
** _ввод_местонахождения
** _отправьте_файл
** _нужен_ли_фотоальбом
** _нужен_альбом
** _есть_ли_у_клиента_альбом
** _есть_ли_такой_медиа_альбом
** _запись_в_таблицу_медиагрупа
** _не_нужен_альбом
** _опишите_подробно
** _предпросмотр_лота
** _на_публикацию
** _изменить_подробности
** _ожидание_результата
** _отправка_лота_админам
** _редактировать_фото
** _вывод_лота_на_каналы
** _публикация_на_канале_медиа
** _отправка_сообщений_инфоботу
** _доверяет
** _отказать
** _удалить_лот
** 
** 
** _возврат_лотов_для_инлайн
**
** 
*/

// функция старта бота ИНФОРМАЦИЯ О ПОЛЬЗОВАТЕЛЯХ
function _start_AvtoZakazBota() {		

	global $bot, $chat_id, $callback_from_first_name, $from_first_name, $HideKeyboard;
	
	if (!$callback_from_first_name) $callback_from_first_name = $from_first_name;
	
	$bot->sendMessage($chat_id, "Добро пожаловать, *".$callback_from_first_name."*!", markdown, $HideKeyboard);

    _инфо_автоЗаказБота();	
	
	exit('ok');
	
}

// Краткая информация, перед началом работы с ботом
function _инфо_автоЗаказБота() {

	global $bot, $chat_id, $тех_поддержка;
	
	$клавиатура = [
		[
			[		
				'text' => 'Создать заявку',
				'callback_data' => 'создать'		
			]
		]
	];
	
	if (_есть_ли_лоты()) {
	
		$клавиатура = array_merge($клавиатура, [
			[
				[
					'text' => 'Повтор публикации',
					'callback_data' => 'повторить'
				],
				[
					'text' => 'Удалить публикацию',
					'callback_data' => 'удалить'
				]
			]
		]);
		
	}
		
	$inLine = [ 'inline_keyboard' => $клавиатура ];
	
	$reply = "Это Бот для подачи заявки на публикацию вашего лота на канале [Покупки на PRIZMarket]".
		"(https://t.me/prizm_market)\n\nДля подачи заявки на публикацию пошагово пройдите".
		" по всем пунктам. Начните с нажатия кнопки 'Создать заявку'.\nДля повтора уже имеющейся заявки - нажмите 'Повторить'.{$тех_поддержка}";

	$bot->sendMessage($chat_id, $reply, markdown, $inLine, null, true);

}



// Проверка наличия у клиента лотов в базе
function _есть_ли_лоты() {
	
	global $mysqli, $from_id, $callback_from_id, $table_market, $callback_from_username, $from_username;
	global $bot, $master;
	
	if (!$callback_from_id) $callback_from_id = $from_id;	
	
	if (!$callback_from_username) $callback_from_username = $from_username;	
	
    $resonse = false;	

	$query = "SELECT * FROM {$table_market} WHERE id_client={$callback_from_id} AND id_zakaz>0";
	
	$result = $mysqli->query($query);
	
	if ($result) {
	
		if ($result->num_rows>0) {
        
            $response = true;

		}
	
	}else throw new Exception("Не смог узнать наличие лота у клиента {$callback_from_id}");
	
    return $response;

}




// Проверка давно ли была последняя публикация лота у данного клиента
function _последняя_публикация() {
	
	global $mysqli, $from_id, $callback_from_id, $table_market;
	
	if (!$callback_from_id) $callback_from_id = $from_id;	
	
    $resonse = false;	

	$query = "SELECT date FROM {$table_market} WHERE id_client={$callback_from_id}";
	
	$result = $mysqli->query($query);
	
	if ($result) {
	
		if ($result->num_rows>0) {
        
			$результат = $result->fetch_all(MYSQLI_ASSOC);
			
			$время = time()-80000; // примерно 22 часа, а точнее 22,22222222222
			
			$давно = true; // если публикация была давно
			
			foreach ($результат as $строка) {
				
				if ($строка['date']>$время) $давно = false;
				
			}
		
            if ($давно) $response = true;

		}else $response = true;
	
	}else throw new Exception("Не смог узнать наличие лота у клиента {$callback_from_id}");
	
    return $response;

}




// при возникновении исключения вызывается эта функция
function exception_handler($exception) {

	global $bot, $master;
	
	$bot->sendMessage($master, "Ошибка! ".$exception->getCode()." ".$exception->getMessage());	
  
	exit('ok');  
	
}

// Проверка наличия клиента в базе
function _existence($table) {
	
	global $mysqli, $from_id, $from_first_name, $from_last_name, $from_username;

    $resonse = false;
	
	$from_Uname = '';
	
    if ($from_username != '') $from_Uname = "@".$from_username;

	$query = "SELECT * FROM {$table} WHERE id_client={$from_id}";
	
	$result = $mysqli->query($query);
	
	if ($result) {
	
		if ($result->num_rows>0) {
                   
            $arrayResult = $result->fetch_all(MYSQLI_ASSOC);

			foreach ($arrayResult as $row) {

				if ($row['first_name']==$from_first_name&&$row['last_name']==$from_last_name&&$row['user_name']==$from_Uname) $response = true;

			}

		}		
	
	}else throw new Exception("Не смог узнать наличие клиента в таблице {$table}");
	
    return $response;

}


// функция возвращает айди клиента по его юзернейму
function _дай_айди($Юнейм) {
	
	global $mysqli, $table_users;
		
    $ответ = false;	

	$запрос = "SELECT id_client FROM {$table_users} WHERE user_name='{$Юнейм}'";
	
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
	
		if ($результат->num_rows>0) {
			
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
        
            $ответ = $результМассив[0]['id_client'];
			
		}else throw new Exception("Не нашёл записей");
	
	}else throw new Exception("Не смог узнать айди клиента - {$Юнейм}");
	
    return $ответ;

}





//------------------------------------------
// Начало создания заявки на публикацию лота 
//------------------------------------------
function _создать() {

	global $bot, $from_id, $message_id, $callback_query_id, $callback_from_id;	
	
	$давно = _последняя_публикация();
	
	if ($давно) {
	
		if (!$callback_from_id) {
		
			$callback_from_id = $from_id;
			
		}else $bot->answerCallbackQuery($callback_query_id, "Начнём!");
	
	}else {
		
		if ($callback_query_id) {
			
			$bot->answerCallbackQuery($callback_query_id, "Безоплатно можно публиковать только раз в сутки один лот!", true);
			
		}
		
		exit('ok');
		
	}
	
	_запись_в_таблицу_маркет();
	
	_очистка_таблицы_медиа();
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => '#продам',
					'callback_data' => 'продам'
				],
				[
					'text' => '#куплю',
					'callback_data' => 'куплю'
				]
			]
		]
	];
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nВыберите необходимое действие:";
	
	$bot->sendMessage($callback_from_id, $reply, null, $inLine);

}



//-----------------------------------------------------------------------
function _вывод_списка_лотов($действие) {	
	global $bot, $table_market, $mysqli, $callback_from_id, $from_id;	
	if (!$callback_from_id) $callback_from_id = $from_id;			
	$запрос = "SELECT id_zakaz, kuplu_prodam, nazvanie FROM {$table_market} WHERE id_client={$callback_from_id} AND id_zakaz>0";	
	$результат = $mysqli->query($запрос);	
	if ($результат) {		
		if ($результат->num_rows>0) {			
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);			
			$кнопки = [];						
			foreach ($результМассив as $строка) {				
				$название = $строка['nazvanie'];
				$кнопки = array_merge($кнопки, [[[
					'text' => "{$строка['kuplu_prodam']} {$название}",
					'callback_data' => "{$действие}:{$строка['id_zakaz']}"
				]]]);			
			}					
			$кнопки = array_merge($кнопки, [[[
					'text' => "*** в Главное меню ***",
					'callback_data' => "старт"
				]]]);			
			$inLine = [			
				'inline_keyboard' => $кнопки				
			];			
			if ($действие == 'повтор') $реплика = "Выберите лот для повтора.";			
			elseif ($действие == 'удаление') $реплика = "Выберите лот для удаления.";			
			$bot->sendMessage($callback_from_id, $реплика, null, $inLine);				
		}else throw new Exception("Нет такой записи в БД");		
	}else throw new Exception("Не получился запрос к БД");
}
//--------------------------------------------------------------



// функция вывода на экран лота, который необходимо повторить
function _повтор($номер_лота) {
	
	global $callback_from_id, $bot, $callback_query_id;
	
	$давно = _последняя_публикация();
	
	if ($давно) {
	
		_отправка_лота($callback_from_id, $номер_лота);	
	
		$inLine = [
			'inline_keyboard' => [
				[
					[
						'text' => 'Да',
						'callback_data' => "отправить_на_повтор:".$номер_лота
					],
					[
						'text' => 'Нет',
						'callback_data' => "старт"
					]
				]
			]
		];				
		
		$bot->sendMessage($callback_from_id, "|\n|\n|\nПовторить? Если хотите повторить публикацию этого лота, нажмите 'Да'.", null, $inLine);
	
	}else {
		
		if ($callback_query_id) {
			
			$bot->answerCallbackQuery($callback_query_id, "Безоплатно можно публиковать только раз в сутки один лот!", true);
			
		}
		
		exit('ok');
		
	}	

}




// функция отправки лота 
function _отправка_лота($куда, $номер_лота) {
	
	global $table_market, $callback_from_id, $mysqli, $bot;
	
	$запрос = "SELECT * FROM {$table_market} WHERE id_client={$callback_from_id} AND id_zakaz='{$номер_лота}'";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows == 1) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			foreach ($результМассив as $строка) {
			
				$файлАйди = $строка['file_id'];		
				$формат_файла = $строка['format_file'];				
				$название = $строка['nazvanie'];
				$ссыль_в_названии = $строка['url_nazv'];
				$куплю_или_продам = $строка['kuplu_prodam'];								
				$валюта = $строка['valuta'];				
				$хештеги_города = $строка['gorod'];				
				$юзера_имя = $строка['username'];				
				$доверие = $строка['doverie'];
				$категория = $строка['otdel'];				
				//$подробности = $строка['podrobno'];	
				$ссыль_на_подробности = $строка['url_podrobno'];				
						
				$inLine = [
					'inline_keyboard' => [
						[
							[
								'text' => 'Подробнее',
								'url' => $ссыль_на_подробности
							]
						]
					]
				];				
				
				$хештеги = "{$куплю_или_продам}\n\n{$категория}\n▪️";				
				$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст_после_названия = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}";					
				$текст_после_названия = str_replace('_', '\_', $текст_после_названия);
					
				if ($доверие) $текст_после_названия .= "\n\n✅ PRIZMarket доверяет❗️"; 
					
				$текст = "{$хештеги}[{$название}]({$ссыль_в_названии}){$текст_после_названия}";
					
				if ($формат_файла == 'фото') {
					
					$публикация = $bot->sendPhoto($куда, $файлАйди, $текст, markdown, $inLine);
						
				}elseif ($формат_файла == 'видео') {
						
					$публикация = $bot->sendVideo($куда, $файлАйди, $текст, markdown, $inLine);
						
				}		
				
			}
		
		}else throw new Exception("Или нет заказа или больше одного..");
	
	}else throw new Exception("Нет такого заказа..");	

}



// функция вывода АДМИНАМ на экран лота, с просьбой о необходимости повторить
function _отправить_на_повтор($номер_лота) {
	
	global $admin_group, $bot, $callback_from_id, $callback_query_id;
	
	_отправка_лота($admin_group, $номер_лота);	
	
	_установка_времени($номер_лота);
	
	$юзер_неим = _узнать_имя_по_номеру_лота($номер_лота);		
	
	$bot->sendMessage($admin_group, $юзер_неим." просит: Повторите публикацию, будьте так любезны, заранее благодарю.");
	
	$bot->sendMessage($callback_from_id, "|\n|\n|\nОтправил, ожидайте ответ.");	
	
	$bot->answerCallbackQuery($callback_query_id, "Ожидайте!");
	
	//_инфо_автоЗаказБота();

}



// функция становки времени публикации
function _установка_времени($номер_лота) {
		
	global $table_market, $mysqli, $callback_from_id, $from_id;
	
	if (!$callback_from_id) $callback_from_id = $from_id;			
	
	$время = time();
	
	$query ="UPDATE {$table_market} SET date='{$время}' WHERE id_zakaz={$номер_лота}";
		
	$result = $mysqli->query($query);
			
	if (!$result) throw new Exception("Не смог обновить запись в таблице {$table_market}");	
	
	return true;

}






// функция вывода на экран лота, который необходимо удалить
function _удаление($номер_лота) {
	
	global $callback_from_id, $bot;
	
	_отправка_лота($callback_from_id, $номер_лота);	
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Да',
					'callback_data' => "удалить_выбранный_лот:".$номер_лота
				],
				[
					'text' => 'Нет',
					'callback_data' => "старт"
				]
			]
		]
	];				
	
	$bot->sendMessage($callback_from_id, "|\n|\n|\nУдалить? Если хотите удалить этот лот из базы нажмите 'Да'.", null, $inLine);

}




// функция удаления лота из базы данных
function _удалить_выбранный_лот($номер_лота) {
	
	global $table_market, $таблица_медиагруппа, $callback_query_id, $mysqli, $bot, $master;
	
	$запрос = "DELETE FROM {$table_market} WHERE id_zakaz='{$номер_лота}'";
	
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		_инфо_автоЗаказБота();
		
		$bot->answerCallbackQuery($callback_query_id, "Лот удалён из базы!");
		
		$запрос = "DELETE FROM {$таблица_медиагруппа} WHERE id='{$номер_лота}'";
	
		$результат = $mysqli->query($запрос);
	
	}else throw new Exception("Не смог удалить лот..");	

}




// функция, которая возвращает юзернейм клиента по номеру лота 
function _узнать_имя_по_номеру_лота($номер_лота) {
	
	global $table_market, $callback_from_id, $mysqli, $bot, $master;
	
	$запрос = "SELECT username FROM {$table_market} WHERE id_zakaz='{$номер_лота}'";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows > 0) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			return $результМассив[0]['username'];
			
		}else throw new Exception("Или нет заказа или больше одного..");
	
	}else throw new Exception("Нет такого заказа..");	

}




// Если клиент выбрал хештег КУПЛЮ
function _куплю() {

	_продам_куплю('#куплю');

}

// Если клиент выбрал хештег ПРОДАМ
function _продам() {

	_продам_куплю('#продам');

}

// Обработка выбранного действия ПРОДАМ/КУПЛЮ, с занесением в базу и ожиданием ввода НАЗВАНИЯ
function _продам_куплю($действие) {

	global $bot, $message_id, $callback_query_id, $callback_from_id;
	
	_запись_в_таблицу_маркет($callback_from_id, 'kuplu_prodam', $действие);
	
	_ожидание_ввода('nazvanie', 'kuplu_prodam');
	
	$bot->answerCallbackQuery($callback_query_id, "Ожидаю ввод названия!");	

	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "Введите название:";
	
	$bot->sendMessage($callback_from_id, $reply, null, $ReplyKey);	

}


// Функция для записи данных в таблицу маркет
function _запись_в_таблицу_маркет($номер_клиента = null, $имя_столбца = null, $действие = null) {

	global $table_market, $mysqli, $callback_from_id, $callback_from_username, $from_id, $from_username;
	
	if (!$callback_from_id) $callback_from_id = $from_id;		
	
	if (!$callback_from_username) $callback_from_username = $from_username;
	
	if (!$имя_столбца) {
	
		$query = "DELETE FROM {$table_market} WHERE id_client={$callback_from_id} AND status=''";
		
		$result = $mysqli->query($query);
		
		if ($result) {
			
			$query = "INSERT INTO {$table_market} (
			  `id_client`,
			  `id_zakaz`,
			  `kuplu_prodam`,
			  `nazvanie`,
			  `url_nazv`,
			  `valuta`,
			  `gorod`,
			  `username`,
			  `doverie`,
			  `otdel`,
			  `format_file`,
			  `file_id`,
			  `url_podrobno`,
			  `status`,
			  `podrobno`,
			  `url_tgraph`,
			  `foto_album`,
			  `url_info_bot`,
			  `date`
			) VALUES (
			  '{$callback_from_id}', '', '', '', '', '', '', '@{$callback_from_username}', '', '', '', '', '', '', '', '', '', '', ''
			)";
						
			$result = $mysqli->query($query);
			
			if (!$result) throw new Exception("Не смог добавить запись в таблицу {$table_market}");
			
		}else throw new Exception("Не смог удалить запись в таблице {$table_market}");
		
	}else {
		
		$query ="UPDATE {$table_market} SET {$имя_столбца}='{$действие}' WHERE id_client={$номер_клиента} AND status=''";
		
		$result = $mysqli->query($query);
			
		if (!$result) throw new Exception("Не смог обновить запись в таблице {$table_market}");
		
		
	}

}



// Функция для очистки содержания таблицы mediagroup
function _очистка_таблицы_медиа() {

	global $mysqli, $callback_from_id, $таблица_медиагруппа, $from_id;
	
	if (!$callback_from_id) $callback_from_id = $from_id;	
	
	$query = "DELETE FROM {$таблица_медиагруппа} WHERE id_client={$callback_from_id} AND id='0'";
		
	$result = $mysqli->query($query);
	
	if ($result) {
	
		return true;
	
	}else  throw new Exception("Не смог удалить запись в таблице {$таблица_медиагруппа}");	

}



// Функция, помечающая, что же клиент должен прислать
function _ожидание_ввода($имя_столбца = null, $последнее_действие = null) {
	
	global $mysqli, $callback_from_id, $таблица_ожидание, $from_id;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$response = false;
	
	if ($имя_столбца) {//если указан столбец, то надо сделать запись в таблице ожидания ввода
	
		$query = "SELECT id_client FROM {$таблица_ожидание} WHERE id_client={$callback_from_id}";
		
		$result = $mysqli->query($query);
		
		if ($result->num_rows>0) {
			
			$query = "UPDATE {$таблица_ожидание} SET ojidanie='{$имя_столбца}' WHERE id_client={$callback_from_id}";
		
			$result = $mysqli->query($query);
			
			if (!$result) throw new Exception("Не смог обновить запись в таблице {$таблица_ожидание}");
			
			$response = true;
			
		}else {
			
			$query = "INSERT INTO {$таблица_ожидание} (
				  `id_client`,
				  `ojidanie`,
				  `last`,
				  `flag`
			) VALUES ('{$callback_from_id}', '{$имя_столбца}', '{$последнее_действие}', '0')";	
		
			$result = $mysqli->query($query);
			
			if (!$result) throw new Exception("Не смог добавить запись в таблицу {$таблица_ожидание}");
			
			$response = true;
			
		}		
		
	}else {//если не указан столбец, то надо проверить, есть ли ожидание ввода ..
	
		$query = "SELECT * FROM {$таблица_ожидание} WHERE id_client={$callback_from_id}";
		
		$result = $mysqli->query($query);
		
		if ($result->num_rows>0) {
		
			$resultArray = $result->fetch_all(MYSQLI_ASSOC);
			
			$response = [
				'id_client' => $resultArray[0]['id_client'],
				'ojidanie' => $resultArray[0]['ojidanie'],
				'last' => $resultArray[0]['last'],
				'flag' => $resultArray[0]['flag']
			];
		
		}		
		
	}
	
	return $response; // boolean or array	
	
}


// Функция для очистки содержания таблицы ожидание
function _очистка_таблицы_ожидание() {

	global $mysqli, $callback_from_id, $таблица_ожидание, $from_id;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$response = false;
	
	$query = "DELETE FROM {$таблица_ожидание} WHERE id_client={$callback_from_id}";
		
	$result = $mysqli->query($query);
	
	if ($result) {
	
		$response = true;
	
	}else  throw new Exception("Не смог удалить запись в таблице {$таблица_ожидание}");
	
	return $response;

}


// После ввода клиентом НАЗВАНИЯ предлагается на выбор, нужнали ссылка вшитая в название
function _ссылка_в_названии() {

	global $bot, $chat_id;
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Да',
					'callback_data' => 'да_нужна'
				],
				[
					'text' => 'Нет',
					'callback_data' => 'не_нужна'
				]
			]
		]
	];
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nНужна ли Вам Ваша ссылка, вшитая в названии?\n\nЕсли не знаете или не поймёте о чём речь, нажмите 'НЕТ'.";
	
	$bot->sendMessage($chat_id, $reply, null, $inLine);

}


// Нужна ли Вам Ваша ссылка, вшитая в названии?
function _да_нужна() {

	global $bot, $chat_id, $callback_query_id;
	
	_ожидание_ввода('url_nazv', 'nazvanie');
	
	$bot->answerCallbackQuery($callback_query_id, "Ожидаю ввода Вашей ссылки!");	

	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "Пришлите мне ссылку, типа:\n\n  https://mysite.ru/supersite/";
	
	$bot->sendMessage($chat_id, $reply, null, $ReplyKey);

}


// Нужна ли Вам Ваша ссылка, вшитая в названии?
function _не_нужна() {
	
	_выбор_категории();
	
	//_выбор_валюты();

}




// выбор категории в которой будет находиться лот?
function _выбор_категории() {

	global $bot, $chat_id, $категории;		
	
	$список_категорий = [];			
			
	for ($i=0; $i<12; $i++) {
				
		$список_категорий = array_merge($список_категорий, [
			[
				[
					'text' => $категории[$i],
					'callback_data' => $категории[$i]
				],
				[
					'text' => $категории[$i+1],
					'callback_data' => $категории[$i+1]
				]
			]
		]);
		
		$i++;
		
	}
	
	$inLine = [
	
		'inline_keyboard' => $список_категорий
		
	];
	
	$reply = "Выберите категорию, к которой больше всего подходит Ваш товар/услуга.";
	
	$bot->sendMessage($chat_id, $reply, null, $inLine);


}



// Предлагаются на выбор разные валюты
function _выбор_валюты() {
	
	global $bot, $chat_id;
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => '₽',
					'callback_data' => 'рубль'
				],
				[
					'text' => '$',
					'callback_data' => 'доллар'
				],
				[
					'text' => '€',
					'callback_data' => 'евро'
				]
			],
			[
				[
					'text' => '¥',
					'callback_data' => 'юань'
				],
				[
					'text' => '₴',
					'callback_data' => 'гривна'
				],
				[
					'text' => '£',
					'callback_data' => 'фунт'
				]
			],
			[
				[
					'text' => 'Только PRIZM!',
					'callback_data' => 'призм'
				]
			]
		]
	];
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nВыберите валюту, с которой Вы работаете, помимо PRIZM.";
	
	$bot->sendMessage($chat_id, $reply, null, $inLine);

}


// Если выбрана валюта
function _рубль() {

	_когда_валюта_выбрана('₽');

}

// Если выбрана валюта
function _доллар() {

	_когда_валюта_выбрана('$');

}

// Если выбрана валюта
function _евро() {

	_когда_валюта_выбрана('€');

}

// Если выбрана валюта
function _юань() {

	_когда_валюта_выбрана('¥');

}

// Если выбрана валюта
function _гривна() {

	_когда_валюта_выбрана('₴');

}

// Если выбрана валюта
function _фунт() {

	_когда_валюта_выбрана('£');

}

// Если выбрана валюта
function _призм() {

	_когда_валюта_выбрана();

}

// Когда уже выбрана валюта
function _когда_валюта_выбрана($валюта = null) {	

	global $callback_from_id;
	
	if ($валюта) {
		
		$валюта.= " / PZM";
		
	}else {
		
		$валюта = "PZM";
		
	}
	
	_запись_в_таблицу_маркет($callback_from_id, 'valuta', $валюта);
	
	_ввод_местонахождения();	

}


//
function _ввод_местонахождения() {

	global $bot, $callback_query_id, $callback_from_id, $from_id;

	if (!$callback_from_id) {
	
		$callback_from_id = $from_id;
	
	}else $bot->answerCallbackQuery($callback_query_id, "Ожидаю ввода местонахождения!");	 
	
	_ожидание_ввода('gorod', 'valuta');

	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "Введите хештеги местонахождения: (не больше трёх)".
		"\n\nВНИМАНИЕ: вводите хештеги БЕЗ пробелов!\n".
		"(#Весь мир 👈🏻 это будет ошибка)\n".		
		"Можно использовать _ (подчёркивание) вместо пробела. Никакие другие ".
		"символы вводить нельзя. В случае неверного ввода команда PRIZMarket ".
		"может отклонить заявку.\n".
		"ОБЯЗАТЕЛЬНО ставьте пробел между двумя разными хештегами!\n".
		"(#Весь_мир#Россия 👈🏻 это будет ошибка)".
		"\n\nПример верного ввода:\n#Весь_мир #Россия #Ростов_на_Дону";
	
	$bot->sendMessage($callback_from_id, $reply, null, $ReplyKey);		

}


//
function _отправьте_файл() {

	global $bot, $chat_id;
	
	_ожидание_ввода('format_file', 'gorod');
	
	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "Отправьте один файл. \n\nФото или видео. \n\nЕсли пришлёте больше, я приму только один файл,".
		" остальные проигнорирую.\nЕсли Вы хотите скинуть много фото, Вы это сможете сделать чуть позже!\n\n".
		"(учтите: видео должно быть коротким, не более 5 МБ)";
	
	$bot->sendMessage($chat_id, $reply, null, $ReplyKey);		

}


// Спрашивается, нужен ли клиенту фотоальбом, на отдельном канале?
function _нужен_ли_фотоальбом() {

	global $bot, $chat_id;
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Да',
					'callback_data' => 'нужен_альбом'
				],
				[
					'text' => 'Нет',
					'callback_data' => 'не_нужен_альбом'
				]
			]
		]
	];
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nНужен ли Вам фотоальбом, размещённый на отдельном канале?\n\nЕсли не знаете или не поймёте о чём речь, нажмите 'НЕТ'.";
	
	$bot->sendMessage($chat_id, $reply, null, $inLine);

}


function _нужен_альбом() {
	
	global $bot, $chat_id, $callback_from_id;
	
	_очистка_таблицы_медиа();
	
	_запись_в_таблицу_маркет($callback_from_id, 'foto_album', '1');
	
	_ожидание_ввода('foto_album', 'format_file');
	
	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nСкиньте мне разом все фото, которые должны оказаться в альбоме (НЕ по одной фотке)";
	
	$bot->sendMessage($chat_id, $reply, null, $ReplyKey);
	
}


// Есть ли в таблице avtozakaz_pzmarket запись о том что у клиента имеется фотоальбом
function _есть_ли_у_клиента_альбом($id_zakaz = '0') {

	global $table_market, $mysqli, $callback_from_id, $from_id;
	
	if (!$callback_from_id) {
	
		$callback_from_id = $from_id;
	
	}

	$query = "SELECT foto_album FROM {$table_market} WHERE id_client={$callback_from_id}".
		" AND id_zakaz={$id_zakaz} AND foto_album='1'";
		
	$result = $mysqli->query($query);
		
	if ($result->num_rows>0) {
	
		return true;
	
	}else return false;

}



// есть ли в таблице avtozakaz_mediagroup такой номер - медиа_айди
function _есть_ли_такой_медиа_альбом($медиа_айди) {

	global $таблица_медиагруппа, $mysqli, $callback_from_id, $from_id;
	
	if (!$callback_from_id) {
	
		$callback_from_id = $from_id;
	
	}

	$query = "SELECT media_group_id FROM {$таблица_медиагруппа} WHERE id_client={$callback_from_id} AND media_group_id={$медиа_айди}";
		
	$result = $mysqli->query($query);
		
	if ($result->num_rows>0) {
	
		return true;
	
	}else return false;

}




// функция записи в таблицу avtozakaz_mediagroup
function _запись_в_таблицу_медиагрупа($id_client = null, $id_zakaz = null, $url_media_group = null) {
	
	global $таблица_медиагруппа, $mysqli, $callback_from_id, $from_id, $media_group_id, $file_id, $формат_файла;
	
	if (!$callback_from_id) $callback_from_id = $from_id;		
	
	if ($id_client&&$id_zakaz&&$url_media_group) {
		
		$query ="UPDATE {$таблица_медиагруппа} SET id='{$id_zakaz}', url='{$url_media_group}' WHERE id_client={$id_client} AND id='0'";
		
		$result = $mysqli->query($query);
			
		if (!$result) throw new Exception("Не смог обновить запись в таблице {$таблица_медиагруппа}");
		
	}else {
	
		$query = "INSERT INTO {$таблица_медиагруппа} (
			`id`,
			`id_client`,
			`media_group_id`,
			`format_file`,
			`file_id`,
			`url`
		) VALUES (
			'0', '{$callback_from_id}', '{$media_group_id}', '{$формат_файла}', '{$file_id}', ''
		)";
		
		$result = $mysqli->query($query);
		
		if (!$result) throw new Exception("Не смог добавить запись в таблицу {$таблица_медиагруппа}");
				
		return true;
	
	}
	
}




function _не_нужен_альбом() {
	
	_очистка_таблицы_медиа();
	
	_опишите_подробно();
	
}


// функция предлагающая ввести ПОДРОБНую информацию о товаре/услуге
function _опишите_подробно() {

	global $bot, $chat_id;
	
	_ожидание_ввода('podrobno', 'foto_album');
	
	$ReplyKey = [
		'keyboard' => [
			[			
				[
					'text' => "Отмена ввода"
				]
			]
		],
		'resize_keyboard' => true,
		'selective' => true,
	];
	
	$reply = "Теперь опишите подробно Ваш товар/услугу для канала Подробности, на который будет ссылаться".
		" Ваш лот.\nСсылки на сайт или соцсети приветствуются.\n(ссылки не должны быть реферальными)\n\nДостаточно подробно. \n\nНо, не переусердствуйте, ведь Вы же не намерены переутомить своих".
		" потенциальных клиентов?\n\nКоличество вводимых символов должно быть не менее 100 и не более 2000.";
	
	$bot->sendMessage($chat_id, $reply, null, $ReplyKey);
	
}





// перед отправкой лота админам показывается клиенту вся введённая информация 
function _предпросмотр_лота() {

	global $from_id, $table_market, $bot, $mysqli, $master;

	$запрос = "SELECT * FROM {$table_market} WHERE id_client={$from_id} AND id_zakaz='0'";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows == 1) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			foreach ($результМассив as $строка) {
			
				$файлАйди = $строка['file_id'];		
				$формат_файла = $строка['format_file'];				
				$название = $строка['nazvanie'];
				$ссыль_в_названии = $строка['url_nazv'];						
				$название_для_подробностей = "[{$название}]({$ссыль_в_названии})";
				$куплю_или_продам = $строка['kuplu_prodam'];
				$валюта = $строка['valuta'];				
				$хештеги_города = $строка['gorod'];				
				$юзера_имя = $строка['username'];				
				$доверие = $строка['doverie'];
				$категория = $строка['otdel'];				
				$подробности = $строка['podrobno'];			
				
				$хештеги = "{$куплю_или_продам}\n\n{$категория}\n▪️";				
				$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}";				
				$текст = str_replace('_', '\_', $текст);				
				$текст = "{$хештеги}{$название_для_подробностей}{$текст}";
				
				$bot->sendMessage($from_id, $подробности);
				
				$inLine = [
					'inline_keyboard' => [
						[
							[
								'text' => 'Отправить на публикацию',
								'callback_data' => 'на_публикацию'
							]
						],
						[
							[
								'text' => 'Изменить подробности',
								'callback_data' => 'изменить_подробности'
							]
						],
						[
							[
								'text' => 'В начало',
								'callback_data' => 'старт'
							]
						]
					]
				];			
				
				//$bot->sendMessage($from_id, $текст, markdown, $inLine);		
				
				if ($формат_файла == 'фото') {
					
					$публикация = $bot->sendPhoto($from_id, $файлАйди, $текст, markdown, $inLine);
						
				}elseif ($формат_файла == 'видео') {
					
					$публикация = $bot->sendVideo($from_id, $файлАйди, $текст, markdown, $inLine);
					
				}
				
			}
		
		}else throw new Exception("Или нет заказа или больше одного..");			
	
	}else throw new Exception("Нет такого заказа..");

}




// отправка клиентом введённой информации 
function _на_публикацию() {
	
	global $callback_query_id, $callback_from_id, $from_id, $bot;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$давно = _последняя_публикация();
	
	if ($давно) {
		
		_отправка_лота_админам();

		_ожидание_результата();

		_отправка_сообщений_инфоботу();	
		
		_запись_в_таблицу_маркет($callback_from_id, 'date', time());
		
	}else $bot->answerCallbackQuery($callback_query_id, "Безоплатно можно публиковать только раз в сутки один лот!", true);

}



// возврат к вводу подробной информации о лоте
function _изменить_подробности() {

	_опишите_подробно();

}



// КОНЕЦ - клиент ожидает решения администрации
function _ожидание_результата() {

	global $bot, $from_id, $callback_from_id;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$reply = "|\n|\n|\n|\n|\n|\n|\n|\nОжидайте результат.\n\n(После публикации Вашего лота".
		" Вы будете об этом уведомлены, в случае отказа Вас также, уведомят. Ожидайте..)";
	
	$bot->sendMessage($callback_from_id, $reply);

}




// отправка лота администрации на проверку
function _отправка_лота_админам() {
	
	global $table_market, $from_id, $bot, $mysqli, $imgBB, $admin_group;	
	global $callback_from_username, $from_username, $callback_from_id, $from_id;
	
	if (!$callback_from_username) $callback_from_username = $from_username;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Опубликовать',
					'callback_data' => 'опубликовать:'.$callback_from_id
				]
			],
			[
				[
					'text' => 'PRIZMarket доверяет',
					'callback_data' => 'доверяет:'.$callback_from_id
				]
			],
			[
				[
					'text' => 'Редактировать фото',
					'callback_data' => 'редактировать_фото:'.$callback_from_id
				]
			],
			[
				[
					'text' => 'ОТКАЗАТЬ',
					'callback_data' => 'отказать:'.$callback_from_id
				]
			],
		]
	];
		
	$запрос = "SELECT * FROM {$table_market} WHERE id_client={$callback_from_id} AND id_zakaz=0";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows == 1) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			foreach ($результМассив as $строка) {
			
				$файлАйди = $строка['file_id'];		
				$формат_файла = $строка['format_file'];				
				$название = $строка['nazvanie'];

				if ($строка['url_nazv']) {				
					$ссыль_в_названии = $строка['url_nazv'];						
					$название_для_подробностей = "[{$название}]({$ссыль_в_названии})";					
				}else $название_для_подробностей = str_replace('_', '\_', $название);
				
				$куплю_или_продам = $строка['kuplu_prodam'];								
				$валюта = $строка['valuta'];				
				$хештеги_города = $строка['gorod'];				
				$юзера_имя = $строка['username'];				
				$категория = $строка['otdel'];				
				
				$подробности = $строка['podrobno'];				
				
				$хештеги = "{$куплю_или_продам}\n\n{$категория}\n▪️";				
				$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}\n\n{$подробности}";
				
				$текст = str_replace('_', '\_', $текст);
				
				$текст = str_replace('-', '', $текст);
				$текст = str_replace(';', '', $текст);
				$текст = str_replace('*', '', $текст);
				$текст = str_replace('%', '', $текст);
				$текст = str_replace('`', '', $текст);
				//$текст = str_replace('?', '', $текст);
				$текст = str_replace('&', '', $текст);
				$текст = str_replace('$', '', $текст);
				$текст = str_replace('^', '', $текст);
				//$текст = str_replace('\\', '', $текст);
				$текст = str_replace('|', '', $текст);
				$текст = str_replace('/', '', $текст);
				$текст = str_replace('<', '', $текст);
				$текст = str_replace('>', '', $текст);
				$текст = str_replace('~', '', $текст);
				$текст = str_replace(':', '', $текст);
				
				$текст = "{$хештеги}{$название_для_подробностей}{$текст}";			
				
				if ($формат_файла == 'фото') {
				
					$Объект_файла = $bot->getFile($файлАйди);		
		
					$ссыль_на_файл = $bot->fileUrl . $bot->token;	
					
					$ссыль = $ссыль_на_файл . "/" . $Объект_файла['file_path'];		
					
					$результат = $imgBB->upload($ссыль);					
					
					if ($результат) {		
						
						$imgBB_url = $результат['url'];		

						_запись_в_таблицу_маркет($callback_from_id, 'url_tgraph', $imgBB_url);
						
					}else throw new Exception("Не смог выложить пост..");					
					
					$реплика = "[_________]({$imgBB_url})\n{$текст}";	
					
					$КаналИнфо = $bot->sendMessage($admin_group, $реплика, markdown, $inLine);	
				
				}else $КаналИнфо = $bot->sendMessage($admin_group, $текст, markdown, $inLine);
				
				if (!$КаналИнфо) throw new Exception("Не смог в админке опубликовать заказ..");
				
			}
		
		}else throw new Exception("Или нет заказа или больше одного..");
	
	}else throw new Exception("Нет такого заказа..");	

}



// замена админом фото у лота
function _редактировать_фото($id_client) {

	global $bot, $callback_query_id;
	
	//if (!$callback_from_id) $callback_from_id = $from_id;	
	
	_ожидание_ввода('замена_фото', $id_client);
	
	$bot->answerCallbackQuery($callback_query_id, "Пришли мне новое фото.", true);	
	
}



// вывод на канал подробности уже готового лота
function _вывод_лота_на_каналы($id_client, $номер_лота = 0) {

	global $table_market, $bot, $chat_id, $mysqli, $imgBB, $channel_podrobno, $channel_market;
	global $таблица_медиагруппа, $channel_media_market, $master, $message_id, $admin_group, $три_часа;
	
//	$from_id = $id_client; // это для функции _запись_в_таблицу_маркет()
	
	$запрос = "SELECT * FROM {$table_market} WHERE id_client={$id_client} AND id_zakaz='{$номер_лота}'";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows == 1) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			foreach ($результМассив as $строка) {
			
				$файлАйди = $строка['file_id'];		
				$формат_файла = $строка['format_file'];				
				$название = $строка['nazvanie'];
				
				if ($строка['url_nazv']) {				
					$ссыль_в_названии = $строка['url_nazv'];						
					$название_для_подробностей = "[{$название}]({$ссыль_в_названии})";					
				}else $название_для_подробностей = str_replace('_', '\_', $название);
				
				$куплю_или_продам = $строка['kuplu_prodam'];								
				$валюта = $строка['valuta'];				
				$хештеги_города = $строка['gorod'];				
				$юзера_имя = $строка['username'];				
				$доверие = $строка['doverie'];
				$категория = $строка['otdel'];				
				$подробности = $строка['podrobno'];			
				
				$хештеги = "{$куплю_или_продам}\n\n{$категория}\n▪️";				
				$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}\n\n{$подробности}";				
				$текст = str_replace('_', '\_', $текст);	
				
				$текст = str_replace(';', '', $текст);
				$текст = str_replace('*', '', $текст);
				$текст = str_replace('%', '', $текст);
				$текст = str_replace('`', '', $текст);
				
				$текст = "{$хештеги}{$название_для_подробностей}{$текст}";
				
				$imgBB_url = $строка['url_tgraph'];	

				if ($imgBB_url) {								
					$реплика = "[_________]({$imgBB_url})\n{$текст}";					
				}else $реплика = $текст;		
				
				$ссылка_инфобота = $строка['url_info_bot'];				
				
				$кнопки = [
					[
						[
							'text' => 'О пользователе',
							'url' => $ссылка_инфобота
						],
						[
							'text' => 'ГАРАНТ',
							'url' => 'https://t.me/podrobno_s_PZP/1044'
						]
					]
				];
				
				if ($строка['foto_album']) {
	
					$ссылка_на_канал_медиа = _публикация_на_канале_медиа($id_client);					
					
					$кнопки = array_merge($кнопки, [
						[
							[
								'text' => 'Фото',
								'url' => $ссылка_на_канал_медиа
							]
						]
					]);					
				
				}
				
				$кнопки = array_merge($кнопки, [
					[
						[
							'text' => 'INSTAGRAM PRIZMarket',
							'url' => 'https://www.instagram.com/prizm_market_inst'
						],
						[
							'text' => 'PZMarket bot',
							'url' => 'https://t.me/Prizm_market_bot'
						]
					],
					[
						[
							'text' => 'Заказать пост',
							'url' => 'https://t.me/Zakaz_prizm_bot'
						],
						[
							'text' => 'Канал PRIZMarket',
							'url' => 'https://t.me/prizm_market/'
						]
					]
				]);
				
				$inLine = ['inline_keyboard' => $кнопки];				
				
				$КаналИнфо = $bot->sendMessage($channel_podrobno, $реплика, markdown, $inLine);		
				
			}
			
				
			if ($КаналИнфо) {				
					
				$id_zakaz = $КаналИнфо['message_id'];
				
				if ($ссылка_на_канал_медиа) {
				
					_запись_в_таблицу_медиагрупа($id_client, $id_zakaz, $ссылка_на_канал_медиа);
					
				}
					
				$ссыль_на_подробности = "https://t.me/{$КаналИнфо['chat']['username']}/{$id_zakaz}";
				
				_запись_в_таблицу_маркет($id_client, 'id_zakaz', $id_zakaz);

				if (!$ссыль_в_названии) {
					
					_запись_в_таблицу_маркет($id_client, 'url_nazv', $ссыль_на_подробности);			
						
					$ссыль_в_названии = $ссыль_на_подробности;
						
				}
					
				_запись_в_таблицу_маркет($id_client, 'url_podrobno', $ссыль_на_подробности);
					
				$inLine = [
					'inline_keyboard' => [
						[
							[
								'text' => 'Подробнее',
								'url' => $ссыль_на_подробности
							]
						]
					]
				];				
					
				$текст = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}";
					
				$текст = str_replace('_', '\_', $текст);
					
				if ($доверие) $текст .= "\n  лот {$id_zakaz}\n✅ PRIZMarket доверяет❗️"; 
				else $текст .= "\n   лот {$id_zakaz}";

				//$хештеги = "{$куплю_или_продам}\n лот {$id_zakaz}\n{$категория}\n▪️";		
				//$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст = "{$хештеги}[{$название}]({$ссыль_в_названии}){$текст}";
					
				if ($формат_файла == 'фото') {
					
					//$публикация = $bot->sendPhoto($channel_market, $файлАйди, $текст, markdown, $inLine);
					
					$публикация = $bot->sendPhoto($admin_group, $файлАйди, $текст, markdown, $inLine);
						
				}elseif ($формат_файла == 'видео') {
						
					//$публикация = $bot->sendVideo($channel_market, $файлАйди, $текст, markdown, $inLine);
					
					$публикация = $bot->sendVideo($admin_group, $файлАйди, $текст, markdown, $inLine);
					
				}
					
				if ($публикация) {					
					
					//$реплика = "Лот опубликован.\n\nДля продолжения работы с ботом жмите /start";
						
					//$bot->sendMessage($id_client, $реплика, markdown);
					
					_запись_в_таблицу_маркет($id_client, 'status', "одобрен");
						
				}else throw new Exception("Не смог отправить лот в админку.");	
					
			}else throw new Exception("Не отправился лот на канал Подробности..");			
			
			
		
		}else throw new Exception("Или нет заказа или больше одного..");			
	
	}else throw new Exception("Нет такого заказа..");
	
	
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Опубликованно в подробностях',
					'url' => $ссыль_на_подробности
				]
			]
		]
	];
	
	$bot->editMessageReplyMarkup($chat_id, $message_id, null, $inLine);

}



// публикация альбома с фотографиями на отдельном канале
function _публикация_на_канале_медиа($номер_клиента) {
	
	global $bot, $master, $таблица_медиагруппа, $mysqli, $channel_media_market;
	
	$запрос = "SELECT * FROM {$таблица_медиагруппа} WHERE id_client={$номер_клиента} AND id='0'";
		
	$результат = $mysqli->query($запрос);
		
	if ($результат) {
			
		if ($результат->num_rows > 1) {
			
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
				
			$файл_медиа = [];
				
			foreach ($результМассив as $строка) {
					
				if ($строка['format_file'] == 'фото') {
						
					$тип = 'photo';
						
				}elseif ($строка['format_file'] == 'видео') {
					
					$тип = 'video';
					
				}
						
				$медиа = $строка['file_id'];
						
				$файл_медиа = array_merge($файл_медиа, [						
					[			
						'type' => $тип,							
						'media' => $медиа			
					]							
				]);
					
			}
				
		}else throw new Exception("Или нет заказа или меньше одного..");
			
	}else throw new Exception("Нет такого заказа..");		

	$результат = $bot->sendMediaGroup($channel_media_market, $файл_медиа);
	
	//$bot->sendMessage($master, $bot->PrintArray($результат));
		
	if ($результат) {		
		
		$ссыль_на_группу_медиа = "https://t.me/{$результат[0]['chat']['username']}/{$результат[0]['message_id']}";	
			
		return $ссыль_на_группу_медиа;

	}else return false;

}


// отправка сообщений инфоботу на его канал, для формирования ссылки "о клиенте"
function _отправка_сообщений_инфоботу() {
	
	global $bot, $channel_info;
	global $callback_from_username, $from_username, $callback_from_id, $from_id;
	
	if (!$callback_from_username) $callback_from_username = $from_username;
	
	if (!$callback_from_id) $callback_from_id = $from_id;
	
	$bot->sendMessage($channel_info, "@".$callback_from_username);
	
	$bot->sendMessage($channel_info, $callback_from_id);	
	
}




// отметка доверием (PRIZMarket доверяет)
function _доверяет($id) {

	global $bot, $callback_query_id, $chat_id, $message_id;
	
	_запись_в_таблицу_маркет($id, 'doverie', '1');
	
	$bot->answerCallbackQuery($callback_query_id, "Хорошо, отмечен доверием!");
	
	$inLine = [
		'inline_keyboard' => [
			[
				[
					'text' => 'Опубликовать',
					'callback_data' => 'опубликовать:'.$id
				]
			],
			[
				[
					'text' => 'ОТКАЗАТЬ',
					'callback_data' => 'отказать:'.$id
				]
			],
		]
	];
	
	$bot->editMessageReplyMarkup($chat_id, $message_id, null, $inLine);

}


// отметка доверием (PRIZMarket доверяет)
function _отказать($id) {

	global $bot, $callback_query_id, $chat_id, $message_id;

	$bot->sendMessage($id, "Вам отказанно. Читайте правила.\n\n/start 👈🏻 в главное меню!");
	
	if (_удалить_лот($id)) {
		
		$inLine = [
			'inline_keyboard' => [
				[
					[
						'text' => 'Отказанно',
						'callback_data' => 'отказанно'
					]
				]
			]
		];
		
		$bot->editMessageReplyMarkup($chat_id, $message_id, null, $inLine);
/*		
		$чат = $bot->getChat($id);
		
		$bot->sendMessage($chat_id, "Это сообщение для ответа клиенту @{$чат['username']}");
*/	
	}

}


// удаление лота
function _удалить_лот($айди_клиента, $номер_лота = '0') {
	
	global $bot, $mysqli, $table_market;
	
	$query = "DELETE FROM ".$table_market." WHERE id_client=".$айди_клиента." AND id_zakaz={$номер_лота}";	
	
	if ($result = $mysqli->query($query)) {
	
		return true;
		
	}else throw new Exception("Не смог изменить таблицу {$table_market}");	
	
}




// возврат лотов из базы по номеру клиента для инлайн режима
function _возврат_лотов_для_инлайн($id_client) {

	global $table_market, $bot, $mysqli, $master;
	
	$response = false;
	
	$запрос = "SELECT * FROM {$table_market} WHERE id_client={$id_client} AND id_zakaz>0";
		
	$результат = $mysqli->query($запрос);
	
	if ($результат) {
		
		if ($результат->num_rows > 0) {
		
			$результМассив = $результат->fetch_all(MYSQLI_ASSOC);
			
			$i = 1;
			
			$InlineQueryResult = [];
			
			foreach ($результМассив as $строка) {
			
				$файлАйди = $строка['file_id'];		
				$формат_файла = $строка['format_file'];				
				$название = $строка['nazvanie'];
				$ссыль_в_названии = $строка['url_nazv'];
				$куплю_или_продам = $строка['kuplu_prodam'];								
				$валюта = $строка['valuta'];				
				$хештеги_города = $строка['gorod'];				
				$юзера_имя = $строка['username'];				
				$доверие = $строка['doverie'];
				$категория = $строка['otdel'];				
				//$подробности = $строка['podrobno'];	
				$ссыль_на_подробности = $строка['url_podrobno'];
				
				$photo_url = $строка['url_tgraph'];	
						
				$inLine = [
					'inline_keyboard' => [
						[
							[
								'text' => 'Подробнее',
								'url' => $ссыль_на_подробности
							]
						]
					]
				];				
				
				$хештеги = "{$куплю_или_продам}\n\n{$категория}\n▪️";				
				$хештеги = str_replace('_', '\_', $хештеги);
				
				$текст_после_названия = "\n▪️{$валюта}\n▪️{$хештеги_города}\n▪️{$юзера_имя}";					
				$текст_после_названия = str_replace('_', '\_', $текст_после_названия);
					
				if ($доверие) $текст_после_названия .= "\n\n✅ PRIZMarket доверяет❗️"; 
					
				$текст = "{$хештеги}[{$название}]({$ссыль_в_названии}){$текст_после_названия}";
					
				if ($формат_файла == 'фото') {
				
					$InlineQueryResult = array_merge($InlineQueryResult, [
						[
							'type' => 'photo',
							'id' => $id_client."_".$i,
							'photo_url' => $photo_url,
							'thumb_url' => $photo_url,							
							'title' => $куплю_или_продам,
							'description' => $название,
							'caption' => $текст,
							'parse_mode' => 'markdown',
							'reply_markup' => $inLine,							
						],
					]);			
						
				}elseif ($формат_файла == 'видео') {
					
					$Объект_файла = $bot->getFile($файлАйди);		
		
					$ссыль_на_файл = $bot->fileUrl . $bot->token;	
					
					$ссыль = $ссыль_на_файл . "/" . $Объект_файла['file_path'];
					
					$InlineQueryResult = array_merge($InlineQueryResult, [
						[						
							'type' => 'video',
							'id' => $id_client."_".$i,
							'video_url' => $ссыль,
							'mime_type' => 'video/mp4', // или 'text/html'
							'thumb_url' => $ссыль,
							'title' => $куплю_или_продам,					
							'caption' => $текст,
							'description' => $название,
							'parse_mode' => 'markdown',
							'reply_markup' => $inLine,	
							
						],
					]);				
						
				}
				
				$i++;
				
			}
			
			$response = $InlineQueryResult;
		
		}else throw new Exception("Или нет заказа или больше одного..");			
	
	}else throw new Exception("Нет такого заказа..");	
	
	return $response;
	
}










?>
