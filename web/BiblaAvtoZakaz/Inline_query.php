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
			//'thumb_url' => 'https://i.ibb.co/SRCv6Z7/file-21.jpg',
			//'photo_width' => null,
			//'photo_height' => null,
			'title' => 'Продам молоток',
			'description' => 'молотки в ассортименте',
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
		]
	];
	
	$bot->answerInlineQuery($inline_query_id, $InlineQueryResultPhoto, 300, false, '', "in_bot", "s");
	//

}

?>
