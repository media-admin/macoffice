<div class="leasing-calculator__container">

	<form id="leasingForm" method="POST" action="https://www.macoffice.at/wp-content/themes/macoffice/classes/getLeasingResult.php?amount">

		<p class="leasing-amount">
			<label class="leasing-amount__label" for="amount"><strong>Gewünschter Leasing-Betrag</strong> (netto)</label><br/>
			<small>Mindestbetrag für Finanzierung: EUR 500,00</small><br/>
			EUR <input type="number" onchange="setTwoNumberDecimal(this)" class="leasing-amount__field" id="amount" name="amount" value="<?=$amount;?>" />
		</p>

		<script>
			function setTwoNumberDecimal(el) {
				el.value = parseFloat(el.value).toFixed(2);
			};
		</script>

		<p><input class="leasing-amount__btn btn btn--red" type="submit" name="compute" value="Rate berechnen" form="leasingForm" /></p>

		<div id="leasingResult"></div>

		 <script src="https://macoffice-maintain.dev/cms/wp-content/themes/macoffice/assets/vendor/jquery-1.4.3/jquery.min.js" type="text/javascript"></script>

		<script type="text/javascript">

			jQuery("#leasingForm").submit(function(e) {
				e.preventDefault();

				jQuery.ajax({
					type: "POST",
					url: "https://macoffice-maintain.dev/cms/wp-content/themes/macoffice/classes/getLeasingResult.php",
					data: jQuery(this).serialize(),
					success: function (data) {
						jQuery("#leasingResult").html(data);
					}
				});

				return false;

			});

		</script>

	</form>

</div>
