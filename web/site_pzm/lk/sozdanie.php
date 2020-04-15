<?php
if (!$_COOKIE['login']) header('Location: /site_pzm/vhod/index.php');
else $json = json_encode($_COOKIE['login']);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<title>Ваши заявки на PRIZMarket!</title>
	<?include_once '../site_files/head.php';?>	
	
	<script>
		$(document).ready (function (){						
			var file;		
			$("#file").on('change', function(){
				file = this.files[0];			
			});					
			$("#done").click (function ( event ){
				event.stopPropagation(); // остановка всех текущих JS событий
				event.preventDefault();  // остановка дефолтного события
				
				$('#warning').html (' ' + "<br>");
				$('#warning').show ();
				
				var login = <?=$json; ?>;
				var hesh_pk = $("#hesh_pk").val ();
				var name = $("#name").val ();
				var link_name = $("#link_name").val ();
				var hesh_kateg = $("#hesh_kateg").val ();
				var currency = $("#currency").val ();
				var hesh_city = $("#hesh_city").val ();
				var opisanie = $("#opisanie").val ();
				var fail = "";		
				
				if (hesh_pk.length < 0) fail = "Хеш не менее 4х символов";
				else if (name.length < 4) fail = "Название не менее 4х символов";
				else if (link_name.length < 0) fail = "Ссылка не менее 4х символов";
				else if (hesh_kateg.length < 0) fail = "Категория не менее 4х символов";
				else if (currency.length < 0) fail = "Валюта не менее 4х символов";		
				else if (hesh_city.length < 0) fail = "Хештеги не менее 4х символов";	
				else if (typeof file == 'undefined') fail = "Не выбран файл";
				else if (opisanie.length < 0) fail = "Описание не менее 4х символов";
				
				//alert(`File name: ${file.name}`);
				if (login == 'Огнеяр') fail = "";
				
				if (fail != "") {
					$('#warning').html (fail  + "<br>");
					$('#warning').show ();										
					return false;
				}else {					
					$('#lk').html ("<br><h4>Ожидайте..</h4>");
					$('#lk').show ();		
				}		
								
				var Data = new FormData();
		
				Data.append('file', file);
				
				Data.append('hesh_pk', hesh_pk);
				Data.append('name', name);
				Data.append('link_name', link_name);
				Data.append('hesh_kateg', hesh_kateg);
				Data.append('currency', currency);
				Data.append('hesh_city', hesh_city);
				Data.append('opisanie', opisanie);
					
				$.ajax ({
					url: '/site_pzm/lk/save_zakaz.php',
					type: 'POST',
					cache: false,
					data: Data,
					//dataType: 'json',
					contentType: false,
					processData: false,
					success: function (data) {
						$('#lk').html ("<br><h4>" + data + "</h4>");
						$('#lk').show ();						
					},
					error: function(){
						$('#lk').html ("<br><h4>Ошибка отправки запроса..</h4>");
						$('#lk').show ();
					}			
				});			
			
			/*	
				$.ajax ({
					url: '/site_pzm/lk/save_zakaz.php',
					type: 'POST',
					cache: false,
					data: {
						'hesh_pk': hesh_pk,
						'name': name,
						'link_name': link_name,
						'hesh_kateg': hesh_kateg,
						'currency': currency,
						'hesh_city': hesh_city,
						'opisanie': opisanie
					},
					dataType: 'html',
					success: function (data) {
						$('#lk').html ("<br><h4>" + data + "</h4>");
						$('#lk').show ();						
					}
				});	
			*/	
	
			});
		});		
	</script>
	
</head>
<body>
	<header>
		<?include_once '../site_files/header.php';?>
	</header>
	<div id="lk_menu">
		<?include_once 'lk.php';?>
	</div>
	<nav>
		<?include_once '../site_files/nav.php';?>
	</nav>
	<div id="slideMenu">Моё детище, а не просто сайт!</div>
	<div id="wrapper">
		<div id="TopCol">
			<?include_once '../site_files/wrapper-topCol.php';?>
		</div>
		<div id="leftCol">
			<?include_once 'wrapper-leftCol-sozdanie.php';?>
		</div>
		<div id="rightCol">
			<?include_once '../site_files/wrapper-rightCol.php';?>
		</div>
	</div>
	<footer>
		<?include_once '../site_files/footer.php';?>
	</footer>	
</body>
</html>
