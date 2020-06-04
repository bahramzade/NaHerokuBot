<?php

/* +----------------+
 * |  Class ICQnew  |
 * +----------------+
 *
 *  call
 *
 * 
 * +--------------------+
 * |  Список методов:   |
 * +--------------------+
 *
 *  getEvents
 *
 *------------
 *  messages 
 *------------
 *
 *  sendText
 *
 *  sendFile
 *
 *  sendVoice
 *
 *  editText
 *
 *  ​deleteMessages
 *
 *  ​answerCallbackQuery
 *
 *---------
 *  chats 
 *---------
 *
 *  ​sendActions
 *
 *  getInfo
 *
 *  ​getAdmins
 *
 *
 *
 */

class ICQnew
{
	// $token - созданный токен для нашего бота 
	public $token = null;
	
	// адрес для запросов к API
	public $apiUrl = "https://api.icq.net/bot/v1";
		
	/*
	** @param str $token
	*/
	public function __construct($token)
	{
		$this->token = $token;
	}    
		
		
	/* 
	** Отправляем запрос в ICQ
	**
	** @param str $method
	** @param array $data    
	**
	** @return mixed
	*/

	public function call(
		$method, 
		$data, 
		$file = null
	) {
		$result = null;

		$data['token'] = $this->token;

		if ($file == null) {

			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $this->apiUrl . $method);
			curl_setopt ($ch, CURLOPT_POST, count($data));
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);	           
			curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($data));
			$result = curl_exec($ch);
			curl_close($ch);
			
			/*
			$url = $this->apiUrl . $method . "?" . http_build_query($data);
			if ($method == "/messages/answerCallbackQuery") $this->call("/messages/sendText", [
			'chatId' => "752067062", 
			'text' => $url
			] );
			*/

		}else {

			$url = $this->apiUrl . $method . "?" . http_build_query($data);
			
			$mimetype = mime_content_type($file);
			$file_name = basename($file);
			$curl_file = new CURLFile($file, $mimetype, $file_name);
				
			$dataFile = ['file' => $curl_file];
				
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataFile);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$result = curl_exec($ch);
			curl_close($ch);

		} 

		$response = json_decode($result, true);

		return $response;
		
	}



	/* 
	** Отправляем запрос в ICQ методом GET
	**
	** @param str $method
	** @param array $data    
	**
	** @return mixed
	*/

	public function callGET(
		$method, 
		$data
	) {
		$result = null;		
		
		$query = "?token=". $this->token;
		
		foreach ($data as $key => $value) {
			if ($value) {
				if (is_bool($value)) $query .= "&{$key}=true";
				else $query .= "&" . http_build_query([$key => $value]); 
			}
		}
		
		$url = $this->apiUrl . $method . $query;

		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);	           
		$result = curl_exec($ch);
		curl_close($ch);
		
		/*
		$this->call("/messages/sendText", [
		'chatId' => "752067062", 
		'text' => $url
		] );
		*/
		
		$response = json_decode($result, true);

		return $response;
		
	}



	/*
	**  функция получения событий
	**
	**  @param str $lastEventId
	**  @param str $pollTime
	**  
	**  @return array
	*/

	public function getEvents(
		$lastEventId, 
		$pollTime
	) {
		
		$response = $this->call("/events/get", [
			'lastEventId' => $lastEventId,
			'pollTime' => $pollTime
		]);	
			
		if ($response['ok']) {
			$response = $response['events'];
		}else $response = false;
			
		return $response;
		
	}
		
		
		
	/*
	**  функция отправки сообщения 
	**
	**  @param str $chatId
	**  @param str $text
	**  @param array $inlineKeyboardMarkup
	**  @param array $replyMsgId	
	**  @param str $forwardChatId
	**  @param array $forwardMsgId
	**  
	**  @return int (msgId)
	*/

	public function sendText(
		$chatId, 
		$text,
		$inlineKeyboardMarkup = null,
		$replyMsgId = null,
		$forwardChatId = null,
		$forwardMsgId = null
	) {
	
		if ($inlineKeyboardMarkup) $inlineKeyboardMarkup = json_encode($inlineKeyboardMarkup);
			
		$response = $this->call("/messages/sendText", [
			'chatId' => $chatId,
			'text' => $text,
			'replyMsgId' => $replyMsgId,			
			'forwardChatId' => $forwardChatId,				
			'forwardMsgId' => $forwardMsgId,			
			'inlineKeyboardMarkup' => $inlineKeyboardMarkup
		]);	
			
		if ($response['ok']) {
			$response = $response['msgId'];
		}else $response = false;
			
		return $response;
		
	}
		

		
	/*
	**  функция отправки файла
	**
	**  @param str $chatId
	**  @param str $file or $file_id
	**  @param str $caption
	**  @param array $inlineKeyboardMarkup
	**  @param array $replyMsgId	
	**  @param str $forwardChatId
	**  @param array $forwardMsgId
	**  
	**  @return int or array
	*/

	public function sendFile(
		$chatId, 
		$file,
		$caption = null,
		$inlineKeyboardMarkup = null,
		$replyMsgId = null,
		$forwardChatId = null,
		$forwardMsgId = null
	) {

		if ($inlineKeyboardMarkup) $inlineKeyboardMarkup = json_encode($inlineKeyboardMarkup);
		
		if (strpos($file, "://")!==false) {

			$response = $this->call("/messages/sendFile", [
				'chatId' => $chatId,
				'caption' => $caption,
				'replyMsgId' => $replyMsgId,			
				'forwardChatId' => $forwardChatId,				
				'forwardMsgId' => $forwardMsgId,			
				'inlineKeyboardMarkup' => $inlineKeyboardMarkup
			], $file);	

			if ($response['ok']) {
				$response = true;
			}else $response = false;
			
		}else {

			$response = $this->call("/messages/sendFile", [
				'chatId' => $chatId,			
				'fileId' =>  $file, 
				'caption' => $caption,
				'replyMsgId' => $replyMsgId,			
				'forwardChatId' => $forwardChatId,				
				'forwardMsgId' => $forwardMsgId,			
				'inlineKeyboardMarkup' => $inlineKeyboardMarkup
			]);	
			
			if ($response['ok']) {
				$response = $response['msgId'];
			}else $response = false;
			
		}

		return $response;
		
	}


	
	/*
	**  функция отправки голосового сообщения
	**
	**  @param str $chatId
	**  @param str $file or $file_id
	**  @param array $inlineKeyboardMarkup
	**  @param array $replyMsgId	
	**  @param str $forwardChatId
	**  @param array $forwardMsgId
	**  
	**  @return int or array 
	*/

	public function sendVoice(
		$chatId, 
		$file,
		$inlineKeyboardMarkup = null,
		$replyMsgId = null,
		$forwardChatId = null,
		$forwardMsgId = null
	) {

		if ($inlineKeyboardMarkup) $inlineKeyboardMarkup = json_encode($inlineKeyboardMarkup);
		
		if (strpos($file, "://")!==false) {

			$response = $this->call("/messages​/sendVoice", [
				'chatId' => $chatId,
				'replyMsgId' => $replyMsgId,			
				'forwardChatId' => $forwardChatId,				
				'forwardMsgId' => $forwardMsgId,			
				'inlineKeyboardMarkup' => $inlineKeyboardMarkup
			], $file);	

			if ($response['ok']) {
				$response = true;
			}else $response = false;
			
		}else {

			$response = $this->call("/messages/sendFile", [
				'chatId' => $chatId,			
				'fileId' =>  $file, 
				'replyMsgId' => $replyMsgId,			
				'forwardChatId' => $forwardChatId,				
				'forwardMsgId' => $forwardMsgId,			
				'inlineKeyboardMarkup' => $inlineKeyboardMarkup
			]);	
			
			if ($response['ok']) {
				$response = $response['msgId'];
			}else $response = false;
			
		} 

		return $response;
		
	}



	/*
	**  функция редактирования сообщения 
	**
	**  @param str $chatId
	**  @param int $msgId
	**  @param str $text
	**  @param array $inlineKeyboardMarkup
	**  	
	**
	**  @return bool
	*/

	public function editText(
		$chatId, 
		$msgId,
		$text,
		$inlineKeyboardMarkup = null
	) {
	
		if ($inlineKeyboardMarkup) $inlineKeyboardMarkup = json_encode($inlineKeyboardMarkup);
			
		$response = $this->call("/messages/editText", [
			'chatId' => $chatId,
			'msgId' => $msgId,		
			'text' => $text,	
			'inlineKeyboardMarkup' => $inlineKeyboardMarkup
		]);	
			
		if ($response['ok']) {
			$response = true;
		}else $response = false;
			
		return $response;
		
	}
		


	/*
	**  функция удаления сообщения 
	**
	**  @param str $chatId
	**  @param int $msgId
	**  	
	**
	**  @return bool
	*/

	public function ​deleteMessages(
		$chatId, 
		$msgId
	) {
	
		$response = $this->call("/messages/deleteMessages", [
			'chatId' => $chatId,
			'msgId' => $msgId
		]);	
			
		if ($response['ok']) {
			$response = true;
		}else $response = false;
			
		return $response;
		
	}




	/*
	**  функция ответа на событие callbackQuery 
	**
	**  @param str $queryId
	**  @param str $text
	**  @param bool $showAlert
	**  @param str $url
	**  	
	**
	**  @return bool
	*/

	public function answerCallbackQuery(
		$queryId, 
		$text = null,
		$showAlert = false,
		$url = null
	) {
		/*
		$query = "?token=". $this->token;
		$query .= "&queryId=". $queryId;
		if ($text) $query .= "&". http_build_query(['text' => $text]);
		if ($showAlert) {
			if (is_bool($showAlert)) $query .= "&showAlert=true";
			else $query .= "&showAlert=" .$showAlert;
		}
		if ($url) $query .= "&url=". $url;
		*/

		$response = $this->callGET("/messages/answerCallbackQuery", [
			'queryId' => $queryId,
			'text' => $text,
			'showAlert' => $showAlert,
			'url' => $url
		]);

		return $response;
		
	}


	/*
	**  функция 
	**
	**  @param str $chatId
	**  @param arr[str] $actions
	**  	
	**
	**  @return bool
	*/
	public function ​sendActions($chatId, $actions) {
		$response = $this->call("/chats/sendActions", [
			'chatId' => $chatId,
			'actions' => $actions
		]);
		/*if ($response['ok']) {
			$response = true;
		}else $response = false;*/
		return $response;
	}


	/*
	**  функция возвращает информацию о чате
	**
	**  @param str $chatId
	**  	
	**
	**  @return array
	*/
	public function ​​getInfo($chatId) {
		$response = $this->call("/chats​/getInfo", [ 'chatId' => $chatId ]);
		if ($response['ok']) {
			return $response;
		}else return false;
	}


	/*
	**  функция возвращает информацию об админах в чате
	**
	**  @param str $chatId
	**  	
	**
	**  @return array[array] 
	*/
	public function ​​getAdmins($chatId) {
		$response = $this->call("/chats/getAdmins", [ 'chatId' => $chatId ]);
		/*if ($response['ok']) {
			return $response['admins'];
		}else return false;*/
		return $response;
	}
} 
?>
