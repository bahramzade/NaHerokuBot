﻿<?

if ($тело == "Прива") {

	$массив = [
		"access_token" => $vk_token, 
		"peer_id" => $user_id_vk, 
		"message" => "Ну да, здравствуй)", 
		"v" => $vk_api_version
	];

	file_get_contents("https://api.vk.com/method/". "messages.send?". http_build_query($массив));


}elseif ($тело == "загрузи") {

    //$результат = $bot_vk->photosGetUploadServer($vk_album_id, $vk_group_id);
	$массив = [
		"access_token" => $vk_token, 
		"album_id" => $vk_album_id, 
		"group_id" => $vk_group_id, 
		"v" => '5.00'
	];
	$результат = file_get_contents("https://api.vk.com/method/". "photos.getUploadServer?". http_build_query($массив));
	
	$результат = json_decode($результат, true);
	
	$результат = $результат['response'];
		
	$bot_vk->messagesSend($user_id_vk, "upload_url: ".$результат['upload_url']);
		
	$результат = $bot_vk->upload($результат['upload_url'], "http://f0430377.xsph.ru/image/test5eccceaecbdc4.jpg");
		
	$server = $результат['server'];
	$photos_list = $результат['photos_list'];
	$hash = $результат['hash'];
		
	$bot_vk->messagesSend($user_id_vk, "server: {$server}, photos_list: {$photos_list}, hash: {$hash}");
		
	$результат = $bot_vk->photosSave($vk_album_id, $vk_group_id, $server, $photos_list, $hash);
		
	//echo "id фото: ".$результат['id'];
		
	$bot_vk->messagesSend($user_id_vk, "id фото: ".$результат['id']);

}else {

    $bot_vk->messagesSend($user_id_vk, "не пойму(");

} 

?>