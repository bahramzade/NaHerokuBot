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
 *  sendText
 *
 *  sendFile
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
	**  @return int (msgId)
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

		$pos = strpos($file, ":");
		
		if ($pos !== false) {

			$response = $this->call("/messages/sendFile", [
				'chatId' => $chatId,
				'caption' => $caption,
				'replyMsgId' => $replyMsgId,			
				'forwardChatId' => $forwardChatId,				
				'forwardMsgId' => $forwardMsgId,			
				'inlineKeyboardMarkup' => $inlineKeyboardMarkup
			], $file);	
			
			return $response;
			
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
			
			return $response;
		} 


	}




}

?>
