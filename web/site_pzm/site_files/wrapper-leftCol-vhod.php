<article id="vhod">
	<?php// session_start(); ?>
	
<form action="/site_pzm/vhod/testreg.php" method="post">
<br>
<p>
	<label>Ваш логин:<br></label>
	<input name="login" type="text" size="15" maxlength="15">
</p>
<p>
    <label>Ваш пароль:<br></label>
    <input name="password" type="password" size="15" maxlength="15">
</p>
<p>
    <input type="submit" name="submit" value="Войти">
	<br><br>
	
	<a href="/site_pzm/registraciya/index.php">Зарегистрироваться</a> 
</p>
</form>
<br>

<?php
// Проверяем, пусты ли переменные логина и id пользователя
if (empty($_SESSION['login']) or empty($_SESSION['id'])) {
    // Если пусты, то мы не выводим ссылку
    echo "<p>Вы вошли на сайт, как гость</p><br><p><a href='#'>Эта ссылка  доступна только зарегистрированным пользователям</a></p><br><br>";
}else {
	// Если не пусты, то мы выводим ссылку
	echo "<p>Вы вошли на сайт, как ".$_SESSION['login']."</p><br><p><a  href='http://tvpavlovsk.sk6.ru/'>Эта ссылка доступна только  зарегистрированным пользователям</a></p><br><br>";
}
?>
</article>