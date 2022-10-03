<div class="leasing-calculator__container">

	<form id="leasingForm" method="POST" action="">

	<?php

		$amount=$interest_rate=$period=NULL;
		$total_interest=$total_payable=$monthly_payable=0;

		$months = array(15, 24, 30, 36, 42, 48, 54, 60, 72);

		$interests_15 = array(7.06, 7.04, 7.02, 7.00, 6.98, 6.96);
		$interests_24 = array(4.66, 4.59, 4.51, 4.47, 4.45, 4.43);
		$interests_30 = array(3.82, 3.76, 3.70, 3.66, 3.64, 3.62);
		$interests_36 = array(3.23, 3.17, 3.11, 3.06, 3.04, 3.02);
		$interests_42 = array(2.82, 2.76, 2.70, 2.66, 2.64, 2.62);
		$interests_48 = array(2.51, 2.45, 2.39, 2.37, 2.35, 2.33);
		$interests_54 = array(2.31, 2.25, 2.19, 2.15, 2.13, 2.11);
		$interests_60 = array(2.11, 2.04, 1.98, 1.96, 1.93, 1.91);
		$interests_72 = array(1.84, 1.78, 1.74, 1.70, 1.68, 1.66);

		$amount_range_01 = array(500, 2500);
		$amount_range_02 = array(2501, 5000);
		$amount_range_03 = array(5001, 10000);
		$amount_range_04 = array(10001, 25000);
		$amount_range_05 = array(25001, 50000);
		$amount_range_06 = array(50001, 100000);
		$amount_range_07 = array(100001, );

		$monthly_payable = array();

		if(isset($_POST['compute'])){

			$amount=$_POST['amount'];

			// Price Range #1
			if($amount > $amount_range_01[0] AND $amount < $amount_range_01[1]) {
				$total_interest_15=$amount*($interests_15[0]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[0]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[0]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[0]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[0]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[0]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[0]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[0]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[0]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #2
			} elseif($amount > $amount_range_02[0] AND $amount < $amount_range_02[1]) {
				$total_interest_15=$amount*($interests_15[1]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[1]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[1]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[1]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[1]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[1]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[1]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[1]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[1]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #3
			} elseif($amount > $amount_range_03[0] AND $amount < $amount_range_03[1]) {
				$total_interest_15=$amount*($interests_15[2]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[2]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[2]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[2]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[2]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[2]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[2]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[2]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[2]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #4
			} elseif($amount > $amount_range_04[0] AND $amount < $amount_range_04[1]) {
				$total_interest_15=$amount*($interests_15[3]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[3]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[3]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[3]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[3]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[3]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[3]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[3]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[3]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #5
			} elseif($amount > $amount_range_05[0] AND $amount < $amount_range_05[1]) {
				$total_interest_15=$amount*($interests_15[4]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[4]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[4]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[4]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[4]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[4]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[4]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[4]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[4]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #6
			} elseif($amount > $amount_range_06[0] AND $amount < $amount_range_06[1]) {
				$total_interest_15=$amount*($interests_15[5]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[5]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[5]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[5]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[5]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[5]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[5]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[5]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[5]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			// Price Range #7
			} elseif($amount > $amount_range_07[0] AND $amount < $amount_range_07[1]) {
				$total_interest_15=$amount*($interests_15[6]/100)*$months[0];
				$total_interest_24=$amount*($interests_24[6]/100)*$months[1];
				$total_interest_30=$amount*($interests_30[6]/100)*$months[2];
				$total_interest_36=$amount*($interests_36[6]/100)*$months[3];
				$total_interest_42=$amount*($interests_42[6]/100)*$months[4];
				$total_interest_48=$amount*($interests_48[6]/100)*$months[5];
				$total_interest_54=$amount*($interests_54[6]/100)*$months[6];
				$total_interest_60=$amount*($interests_60[6]/100)*$months[7];
				$total_interest_72=$amount*($interests_72[6]/100)*$months[8];

				$monthly_payable_15=($total_interest_15+$amount)/$months[0];
				$monthly_payable_24=($total_interest_24+$amount)/$months[1];
				$monthly_payable_30=($total_interest_30+$amount)/$months[2];
				$monthly_payable_36=($total_interest_36+$amount)/$months[3];
				$monthly_payable_42=($total_interest_42+$amount)/$months[4];
				$monthly_payable_48=($total_interest_48+$amount)/$months[5];
				$monthly_payable_54=($total_interest_54+$amount)/$months[6];
				$monthly_payable_60=($total_interest_60+$amount)/$months[7];
				$monthly_payable_72=($total_interest_72+$amount)/$months[8];
			}

		}
	?>

		<p class=""><strong>Gewünschter Leasing-Betrag</strong> (netto)<br/><br/>
			EUR <input type="text" name="amount" value="<?=$amount;?>" />
		</p><br/>

		<p><input class="btn btn--red" type="submit" name="compute" value="Rate berechnen" /></p>

</form>

<div id="leasingResult"></div>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js">

		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('#leasingForm').submit(function(f){
					f.preventDefault();
					$.ajax({
						url: "classes/getLeasingResult.php",
						type: "POST",
						data: $(this).serialize(),
						success: function(data){
							jQuery("#leasingResult").html(data);
						},
						error: function(){
							alert:("Berechnung ist fehlgeschlagen!");
						}
					});
				});
			});
		</script>







</div>