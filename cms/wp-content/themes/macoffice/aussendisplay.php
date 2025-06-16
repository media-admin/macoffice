<?php
/*
* Template Name: Außendisplay
*/
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Diese 3 Meta-Tags oben *müssen* zuerst im head stehen; jeglicher sonstiger head-Inhalt muss *nach* diesen Tags kommen -->

		<meta name="description" content="Info-Display">
		<meta name="author" content="mac)office - Hillisch & Partner GmbH">

		<title>mac)office - Ihr Partner in allen Bereichen rund um Apple</title>

		<!-- Unterstützung für Media Queries und HTML5-Elemente in IE8 über HTML5 shim und Respond.js -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- Import Theme Styles -->
		<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/style-old.css">

		<!-- Besondere Stile für die Screen-Version -->
		<link href="<?php bloginfo('template_url');?>/screen.css" rel="stylesheet">

		<!-- FontAwesome
		<script src="https://use.fontawesome.com/d24c401ac1.js"></script>
		-->

		<!-- REFRESHING STORE HOURS -->

		<style type="text/css">
			html, body {
    		height: 100%;
				margin: 0;
				padding: 0;
				border: 0;
			}

			div {
    			margin: 0;
				border: 0;
			}

			section {
    		margin: 0;
				border: 0px solid black;
				padding: 0;
				height: 100%;
				width: 100%;
			}

			section.logo-geschlossen, section.logo-geoeffnet {
    		padding-top: 2% !important;
    		padding-bottom: 0% !important;
			}

			section.logo-geschlossen {
    		background-color: #000000 !important;
			}

			section.logo-geoeffnet {
    		background-color: #ffffff !important;
			}

			.content {
    		display: table;
				width: 100%;
				border-collapse: separate;
				height: 50% !important;
			}

			.col-geschlossen {
    			display: table-cell;
				height: 100% !important;
				width: 100% !important;
				background-color: #000000;
				color: #ffffff;
			}

			.col-geoeffnet {
    			display: table-cell;
				height: 100% !important;
				width: 100% !important;
				background-color: #ffffff;
				color: #000000;
			}

			#header, #footer{
    			width: 100% !important;
				position: relative;
				z-index: 1;
			}

			#header {
    			height: 75% !important;
			}

			h2.geschlossen {
				color: red;
				font-size: 2em;
			}

			h2.geoeffnet {
				color: green;
				font-size: 2em;
			}

			#footer {
    		height: 8%;
    		width: 100%;
				position: relative;
				z-index: 1;
				margin-bottom: 0px !important;
			}
		</style>

		<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>

		<script>
			$(document).ready(function(){
				setInterval(function(){
					$("#display-refresh").load('/wp-content/themes/macoffice/display-refresh.php')
    			}, 12000);
			});
		</script>
	</head>

	<body>
		<!-- PLATZHALTER FÜR DISPLAY REFRESH -->
		<div id="display-refresh"></div>
	</body>

</html>