<?php
if ($_POST['login']) {
	
	setcookie("login", $_POST['login'], time()+60*60*24*365, '/');
	
	echo "<h4><label>Добро пожаловать ". $_POST['login'] ."!</label></h4>";	
	
}else echo "Не получилось войти(";
?>
