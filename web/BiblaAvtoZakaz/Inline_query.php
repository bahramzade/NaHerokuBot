﻿<?

if ($inline_query) {

	$InlineQueryResultVideo = [
		[
		// обязательные
			'type' => 'video',
			'id' => '',
			'video_url' => '',
			'mime_type' => 'video/mp4', // или 'text/html'
			'thumb_url' => '',
			'title' => '',
		// необязательные
			//'caption' => '',
			//'parse_mode' => '',
			//'video_width' => '',
			//'video_height' => '',
			//'video_duration' => '',
			//'description' => '',
			//'reply_markup' => '',
			//'input_message_content' => ''
		]
	];

	$InlineQueryResultPhoto = [
		[
			'type' => 'photo',
			'id' => $from_id."z",
			'photo_url' => 'https://i.ibb.co/SRCv6Z7/file-23.jpg',
			'thumb_url' => 'https://i.ibb.co/SRCv6Z7/file-21.jpg',
			//'photo_width' => null,
			//'photo_height' => null,
			'title' => 'Продам',
			'description' => 'ассортимент',
			'caption' => '#продам',
			//'parse_mode' => null,
			//'reply_markup' => null,
			//'input_message_content' => null		
		]	
	];
	
	$InlineQueryResult = [
		[
			'type' => 'article',
			'id' => $from_id,
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],
			//'reply_markup' => null,
			//'url' => null,
			//'hide_url' => false,
			'description' => 'нажми её',
			//'thumb_url' => null,
			//'thumb_width' => null,
			//'thumb_height' => null
		],
		[
			'type' => 'article',
			'id' => $from_id."-1",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-2",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-3",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-4",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-5",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-6",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-7",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-8",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-9",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
		[
			'type' => 'article',
			'id' => $from_id."-10",
			'title' => 'Отличная кнопка',
			'input_message_content' => [ 'message_text' => 'любой тут текст' ],			
			'description' => 'нажми её',		
		],
	];
	
	$bot->answerInlineQuery($inline_query_id, $InlineQueryResult, null, false, null, "в бот", "s");

}

?>
