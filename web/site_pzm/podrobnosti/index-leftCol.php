﻿<?
if ($категория) {
	
	// здесь открывается mysqli и подключаются переменные
	include_once '../site_files/functions.php';
	
	if ($_POST['last_lot']) $сколь_уже_показано = $_POST['last_lot'];
	if ($_GET['last_lot']) $сколь_уже_показано = $_GET['last_lot'];
	
	$лот = _вывод_лотов_по_категории($категория, $сколь_уже_показано, null, $количество_лотов);
	
	foreach($лот as $публикация) {
		echo $публикация;
	}
	
	// закрывается подключение 
	$mysqli->close();
	
}else {

        include_once '../pzmarket.php';
		
	if ($показ_одного_лота) {
		echo $показ_одного_лота;
	}else {
		foreach($лот as $публикация) {
			echo $публикация;
		}
	}	
	
}
?>	
