﻿<article id="repeat_delete">
<?
$логин = $_POST['login'];
$токен = $_POST['token'];
$номер_лота = $_POST['id_lota'];
$повтор_или_удаление = $_POST['repeat_delete'];
if ($повтор_или_удаление == 'Повторить') {
	echo "<h4><br>Пока не работает эта функция</h4>";
}elseif ($повтор_или_удаление == 'Удалить') {
	//echo "<h4>Ещё не работает эта функция</h4>"; 
	
?>	
	<input type='hidden' name='login' id='login' value='<?=$логин;?>'>					
	<input type='hidden' name='token' id='token' value='<?=$токен;?>'>
	<input type='hidden' name='id_lota' id='id_lota' value='<?=$номер_лота;?>'>

	<h4><br>
	<label>Вы уверены что хотите удалить лот <?=$номер_лота;?>?</label><br><br>
	<input type='button' class='button' name='done' id='done'  value='Да'>	
	<input type='button' class='button' name='non' id='non'  value='Нет'>
	</h4>				
<?	
	
}
?>
</article>
