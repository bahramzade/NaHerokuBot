<?php

require('../vendor/autoload.php');


$body = file_get_contents('php://input'); //Получаем в $body json строку

$arr = json_decode($body, true); //Разбираем json запрос на массив в переменную $arr

  
//Сюда пишем токен, который нам выдал бот
$tg = new \TelegramBot\Api\BotApi('983003158:AAFT2RsLpFdKLjb7qeo12t8EPDus6-TB6YI');


//Получаем текст сообщения, которое нам пришло.
$text = $arr['message']['text']; 

  
//Сразу и id получим, которому нужно отправлять всё это назад
$chat_id = $arr['message']['chat']['id'];
  
  
//ИМЯ ОТ КОГО ПРИШЛО СООБЩЕНИЕ  
$first_name = $arr['message']['from']['first_name'];


//ПОСТРОЧНОЕ ЗАПОЛНЕНИЕ КНОПОК KeybordMarkup
$stroka1 = ["Кнопа1"];
$stroka2 = ["Кнопа2а"],["Кнопа2б"];
$stroka3 = ["Кнопа3"];
	
$stolb	= [$stroka1,$stroka2,$stroka3];

 
//СОЗДАНИЕ КЛАВИАТУРЫ reply_markup
$keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup($stolb, true);  


//ПРИРАВНИВАНИЕ РУССКОЯЗЫЧНЫХ КОМАНД ИНОСТРАННЫМ
if ($text=="/старт") $text="/start";
if ($text=="/помощь") $text="/help";
if ($text=="/меню") $text="/menu";
if ($text=="/стоп") $text="/stop";
  

//ОСНОВНАЯ РАБОТА БОТА, ВЫПОЛНЕНИЕ КОМАНД (РЕАКЦИЯ НА РЕПЛИКИ ПОЛЬЗОВАТЕЛЯ)
if ($text<>'') {
	
        if ($text == "/start") {
		 
                $reply = $first_name . "- золотце, здравствуй! \n Добро пожаловать! \n\n";
		
		$tg->sendMessage($chat_id, $reply);
		
	        $reply  = "Здесь можно много всего хорошего написать о боте о его работе, \n";
		$reply .= "извиняюсь за тавтологию, но куда теперь без неё? \n";
		$reply .= "Работает бот на работе работу рабскую но ему, роботу всё нипочём!  \n";
		
		$tg->sendMessage($chat_id, $reply);
		
		$reply = "Меню";
		
		$tg->sendMessage($chat_id, $reply, null, false, null, $keyboard);
            			
        }elseif ($text == "/help")  {
		
                $reply = "Информация с помощью.";
		
                $tg->sendMessage($chat_id, $reply);
		
		$reply  = "Команды: \n /старт или /start - для старта бота. \n";
		$reply .= "/помощь или /help - вывод информации с помощь \n";
		$reply .= "/меню или /menu - возврат в меню \n";
		$reply .= "/стоп или /stop - ничего не произойдёт.  \n";
		
                $tg->sendMessage($chat_id, $reply);
			
        }elseif ($text == "/menu")  {
		
		$reply = "Меню";
		
                $tg->sendMessage($chat_id, $reply, null, false, null, $keyboard);
			
        }elseif ($text == "Кнопа1") {
		
            //$url = "https://68.media.tumblr.com/6d830b4f2c455f9cb6cd4ebe5011d2b8/tumblr_oj49kevkUz1v4bb1no1_500.jpg";
            //$telegram->sendPhoto([ 'chat_id' => $chat_id, 'photo' => $url, 'caption' => "Описание." ]);
			
		      	$reply = "Ещё не придумал зачем тут эта кнопа)";
            $tg->sendMessage($chat_id, $reply);
			
        }elseif ($text == "Кнопа2") {
		
            //$url = "https://68.media.tumblr.com/bd08f2aa85a6eb8b7a9f4b07c0807d71/tumblr_ofrc94sG1e1sjmm5ao1_400.gif";
            //$telegram->sendDocument([ 'chat_id' => $chat_id, 'document' => $url, 'caption' => "Описание." ]);
			
	  	    	$reply = "Ещё не придумал зачем тут эта кнопа!";
            $tg->sendMessage($chat_id, $reply);
			
        }elseif ($text == "Кнопа3") {
		
            //$html=simplexml_load_file('http://netology.ru/blog/rss.xml');
            //foreach ($html->channel->item as $item) { 
	        //$reply .= "\xE2\x9E\xA1 ".$item->title." (<a href='".$item->link."'>читать</a>)\n";	}
            //$telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode' => 'HTML', 'disable_web_page_preview' => true, 'text' => $reply ]);
			
		      	$reply = "Ещё не придумал зачем тут третья кнопа.";
            $tg->sendMessage($chat_id, $reply);
			
        }
/*		
		else{
		
        	//$reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
        	//$telegram->sendMessage([ 'chat_id' => $chat_id, 'parse_mode'=> 'HTML', 'text' => $reply ]);
			
			$reply = "Мне не ясен Ваш "Французский".";
            $tg->sendMessage($chat_id, $reply);
			
        }  */
		
}
  

  
//Используем sendMessage для отправки сообщения в ответ
//$tg->sendMessage($tg_id, $sms_rev);

/*
//РЕАКЦИЯ БОТА НА КОМАНДУ /СТАРТ
if ($text=='/start') {    
	$tg->sendMessage($chat_id, 'Главная страница' . "\n");
	$tg->sendMessage($chat_id, '\XE2\x9C\X8A');	
	}
else $tg->sendMessage($chat_id, 'ФигВам' . "\n"); 
*/

//ОТПРАВКА ИФОРМАЦИИ О СООБЩЕНИИ МНЕ В ЛИЧКУ
if ($chat_id<>''&&$chat_id<>'-1001306245472'&&$chat_id<>'351009636') $tg->sendMessage('351009636', 'Он: ' . $chat_id . "\n" . 'пишет: ' . $text);
 

  
exit('ok'); //Обязательно возвращаем "ok", чтобы телеграмм не подумал, что запрос не дошёл


?>
