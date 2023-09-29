<?php

    $tabelle = 'akku';
    $modell = 'touchid';

    $query = "
        SELECT *
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
						
			$ek = $data['ek'];
            $lab = $data['lab'];
			

			
			 endforeach;
    
    switch($lab) {
        case 'FR10': $ak = 54.00; break;
        case 'FR20': $ak = 54.00; break;
        case 'FR30': $ak = 55.50; break;
        case 'FR50': $ak = 60.00; break;
        case 'FR65': $ak = 66.50; break;
        case 'FR75': $ak = 69.50; break;
        case 'FR85': $ak = 70.00; break;
        case 'LAB1': $ak = 52.76; break;
        case 'LAB2': $ak = 74.89; break;
        case 'LAB3': $ak = 100.41; break;
        case 'LAB4': $ak = 127.65; break;
        case 'LAB5': $ak = 159.98; break;
        case 'LAB6': $ak = 181.54; break;
        case 'LAB7': $ak = 202.53; break;
        case 'LAB8': $ak = 234.87; break;
        case 'RMB6': $ak = 129.35;
        default: $ak = 0;
    
    }



    $vp = ceil(($ek/0.93+$ak)*1.2/10)*10-1;
    
    if ($vp > 400) {
        $repbon = $vp-200;
    } else $repbon = ceil($vp/2);

    $digi = ($ek/0.93+$ak*0.875)*1.2;

    echo $vp; echo '<br>';
    echo $repbon; echo '<br>';
    echo number_format($digi,2);
?>