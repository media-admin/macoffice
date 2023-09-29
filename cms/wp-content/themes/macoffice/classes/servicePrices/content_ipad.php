<?php

$device = $_GET['device'];

switch($device){
    case 'pencil1': $device2 = 'Pencil (1. Generation)'; break;
    case 'pencil2': $device2 = 'Pencil (2. Generation)'; break;
    case 'pro12-6-wifi': $device2 = 'iPad Pro 12.9-inch Wi-Fi (6. Generation)'; break;
    case 'pro12-6-cell': $device2 = 'iPad Pro 12.9-inch Wi-Fi+Cellular (6. Generation)'; break;
    case 'pro12-5-wifi': $device2 = 'iPad Pro 12.9-inch Wi-Fi (5. Generation)'; break;
    case 'pro12-5-cell': $device2 = 'iPad Pro 12.9-inch Wi-Fi+Cellular (5. Generation)'; break;
    case 'pro12-4': $device2 = 'iPad Pro 12.9-inch (4. Generation)'; break;
    case 'pro12-3': $device2 = 'iPad Pro 12.9-inch (3. Generation)'; break;
    case 'pro12-2': $device2 = 'iPad Pro 12.9-inch (2. Generation)'; break;
    case 'pro12': $device2 = 'iPad Pro 12.9-inch'; break;
    case 'pro11-4-wifi': $device2 = 'iPad Pro 11-inch Wi-Fi (4. Generation)'; break;
    case 'pro11-4-cell': $device2 = 'iPad Pro 11-inch Wi-Fi+Cellular (4. Generation)'; break;
    case 'pro11-3-wifi': $device2 = 'iPad Pro 11-inch Wi-Fi (3. Generation)'; break;
    case 'pro11-3-cell': $device2 = 'iPad Pro 11-inch Wi-Fi+Cellular (3. Generation)'; break;
    case 'pro11-2': $device2 = 'iPad Pro 11-inch (2. Generation)'; break;
    case 'pro11': $device2 = 'iPad Pro 11-inch'; break;
    case 'pro10': $device2 = 'iPad Pro 10.5-inch'; break;
    case 'pro9': $device2 = 'iPad Pro 9.7-inch'; break;
    case 'mini6-wifi': $device2 = 'iPad mini Wi-Fi (6. Generation)'; break;
    case 'mini6-cell': $device2 = 'iPad mini Wi-Fi+Cellular (6. Generation)'; break;
    case 'air5-wifi': $device2 = 'iPad Air Wi-Fi (5. Generation)'; break;
    case 'air5-cell': $device2 = 'iPad Air Wi-Fi+Cellular (5. Generation)'; break;
    case 'air4': $device2 = 'iPad Air (4. Generation)'; break;
    case 'air3': $device2 = 'iPad Air (3. Generation)'; break;
    case '10-wifi': $device2 = 'iPad Wi-Fi (10. Generation)'; break;
    case '10-cell': $device2 = 'iPad Wi-Fi+Cellular (10. Generation)'; break;
    case '9': $device2 = 'iPad (9. Generation)'; break;
    case '8': $device2 = 'iPad (8. Generation)'; break;
    case '7': $device2 = 'iPad (7. Generation)'; break;
    case '6': $device2 = 'iPad (6. Generation)'; break;
    case '5': $device2 = 'iPad (5. Generation)'; break;


    default : header("Location: servicepreise_start.php");

}



?>

<div class="container">

<div class="row mt-4">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="servicepreise_start.php">Servicepreise</a></li>
    <li class="breadcrumb-item"><a href="servicepreise_device.php?device=ipad">Gerät auswählen</a></li>
    <li class="breadcrumb-item active" aria-current="page">iPad</li>
  </ol>
</nav>


    </div>

<div class="row mt-4">
<div class="col-md-4 text-center">
    <h3>Ausgewähltes Gerät:</h3>
</div>
<div class="col-md-4 text-center">
    <h3><?php echo $device2; ?></h3>
</div>

<div class="col-md-2 text-center">
    <a href="servicepreise_device.php?device=ipad"><button type="button" class="btn btn-secondary">Gerät ändern</button></a>
</div>
<div class="col-md-2 text-center">
    <a href="servicepreise_start.php"><button type="button" class="btn btn-dark">Kategorie ändern</button></a>
</div>
</div>

<div class="row mt-4">


<div class="col-md-4">
    </div>

<div class="col-md-4">
</div>

<div class="col-md-4">
        </div>

</div>
<?php


if(!empty($_POST['teil'])) {

    switch($_POST['teil']){
    case 'akku' : $teillang = 'Akku'; break;
    case 'gesamt' : $teillang = 'Gesamtaustausch'; break;
    default: $teillang = 'Kein Ersatzteil ausgewählt';
}


        $echo1 = '


                    <div class="row mt-4">
                <div class="col-md-4 text-center">
                    <h4>Ausgewähltes Ersatzteil:</h4>
                </div>
                <div class="col-md-4 text-center">
                    <h4>';
                    echo $echo1; echo $teillang; $echo2 = '</h4>
                </div>


                <div class="col-md-4 text-center">
                    <a href=""><button type="button" class="btn btn-dark">Ersatzteil zurücksetzen</button></a>
                </div>
                </div>






        ';

        $echo3 = '
            <form method="POST" action="">
            <div class="row text-center">



                </div>

            ';

            $echo5 ='<input type="hidden" name="device" id="device" value="';

            $echo6 ='">
            <input type="hidden" name="hiddenteil" id="hiddenteil" value="';
            $echo7 ='" </div> </div>';




            $echo10 ='</h6>
                                </div>

                            </div>
                        </div>
                        </div>
            </div>

            <div class="col-md-2">
            </div>


        </div>

        </div>
        </form>
        ';





            echo $echo2;

            if ($_POST['teil']!=='gesamt') {

                echo $echo3;

            }

            echo $echo5;
            echo $device;
            echo $echo6;
            echo $_POST['teil'];
            echo $echo7;





    /* ********** PREIS QUERY *********** */

    $tabelle = $_POST['teil'];
    $modell = $device;

    $query = "
        SELECT vk
        FROM $tabelle
        WHERE modell = '".$modell."'
    ";

    $dataset = array();
	$sqlresult = mysqli_query($connect, $query);
	if (!empty($sqlresult))
		while ($row = mysqli_fetch_array($sqlresult)) {
			$dataset[] = $row;
		}

            foreach($dataset as $data):

            $vp = $data['vk'];

            endforeach;


    ?>





        <div class="row mt-4 text-center">
        <div class="col-md-3">
        </div>
        <div class="col-md-6 mt-4 border border-primary border-5 rounded-pill">
            <h2>Verkaufspreis: € <?php echo $vp; ?>,-</h2>
        </div>
        <div class="col-md-3">
        </div>
</div>

<?php






    echo $echo10;





} else {


    $echo = <<<'HTML'

    <form method="POST" action="">

    <div class="row">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">

    <select name="teil" id="teil" class="form-select" aria-label="Default select example">
      <option value="" selected>Ersatzteil wählen</option>
      <option value="akku">Akku</option>
      <option value="gesamt">Gesamtaustausch</option>

    HTML;

    echo $echo;




    ?>





    <?php

    $echo = <<<'HTML'


    </select>
        </div>


        <div class="col-md-4">
        </div>


        </div>

        <div class="row text-center mt-4">

            <div class="col-md-4">
            </div>

            <div class="col-md-4">
            <button type="submit" class="btn btn-success">Berechnen</button>
            </div>

            <div class="col-md-4">
            </div>

        </div>



        </form>


        </div>
    </div>


    HTML;

    echo $echo;
}

?>