﻿	<meta name="description" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="all,index,follow" />
	<meta name="distribution" content="global" />
	
	<!-- Adopt website to current screen -->
	<meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<link rel="stylesheet" href="/site_pzm/css/style.css">
	
	<link rel="stylesheet" href="/site_pzm/font-awesome/css/font-awesome.min.css">
	
	<!-- Here we add libs for jQuery, Ajax... -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	
	<script type="text/javascript">
		if(screen.width > 800) { // Animate navigation
			$(document).ready(function() {
			// функцию скролла привязать к окну браузера
				$(window).scroll(function(){
					var distanceTop = $('#slideMenu').offset().top;
					if ($(window).scrollTop() >= distanceTop)
						$ ('nav').attr ("id", "fixed");
					else //if ($(window).scrollTop() < distanceTop)
						$ ('nav').attr ("id", "nav");
				});
			});
		}
	</script>	
