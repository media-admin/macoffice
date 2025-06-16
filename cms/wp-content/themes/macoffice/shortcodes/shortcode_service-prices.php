<div class="service-prices__container">

	<form id="servicePricesForm" method="POST" action="https://at.macoffice.localdev/cms/wp-content/themes/macoffice/classes/servicePrices/content_servicepreise_device.php?device">
		<p class="service-prices">
			<label class="service-prices__label" for="device"><strong>Bitte wählen Sie die Gerätekategorie aus</strong></label><br/>
			<br/>
			<!--
				<input class="service-prices__field" type="button" value="iPhone" id="iphone" name="iphone" form="servicePricesForm" />
				<input class="service-prices__field" type="button" value="iPad" id="ipad" name="ipad" form="servicePricesForm" />
				<input class="service-prices__field" type="button" value="Watch" id="watch" name="watch" form="servicePricesForm" />
				<input class="favorite styled" type="button" value="AirPods" id="airpods" name="airpods" form="servicePricesForm" />
				<input class="favorite styled" type="button" value="Mac" id="mac" name="mac" form="servicePricesForm" />
			-->
			<select class="service-prices__field" id="device" name="device" form="servicePricesForm">
				<option value="iphone">iPhone</option>
				<option value="ipad">iPad</option>
				<option value="watch">Watch</option>
				<option value="airpods">AirPods</option>
				<option value="mac">Mac</option>
			</select>
		</p>

		<p><input class="service-price__btn btn btn--red" type="submit" name="chooseModel" value="Modellauswahl" form="servicePricesForm" /></p>

		<div id="servicePricesSteps"></div>

		<script type="text/javascript">

			jQuery("#servicePricesForm").submit(function(e) {
				e.preventDefault();

				jQuery.ajax({
					type: "POST",
					url: "https://at.macoffice.localdev/cms/wp-content/themes/macoffice/classes/servicePrices/content_servicepreise_device.php",
					data: jQuery(this).serialize(),
					success: function (data) {
						jQuery("#servicePricesSteps").html(data);
					}
				});

				return false;

			});

		</script>

	</form>

</div>
