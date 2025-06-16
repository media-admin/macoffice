<div class="service-pricelist__container">

	<?php
		if( !empty($_GET['cat'])) {
				$selected = $_GET['cat'];
				echo $selected;
				die('da');
		} else {
				die('alles schlecht.');
		}
	?>

	<script>
		jQuery(document).ready(function(){
				jQuery("#service-pricelist-content").load('https://at.macoffice.localdev/cms/wp-content/themes/macoffice/classes/servicePricelist/quickprice.php');
		});
	</script>

	<div id="service-pricelist-content"></div>

</div>

