<?php 

$device = $_GET['device'];

switch($device){
    case 'se': $device2 = 'iPhone SE'; break;
    case 'se2': $device2 = 'iPhone SE (2. Generation)'; break;
    case 'se3': $device2 = 'iPhone SE (3. Generation)'; break;
    case '6s': $device2 = 'iPhone 6S'; break;
    case '6splus': $device2 = 'iPhone 6S Plus'; break;
    case '7': $device2 = 'iPhone 7'; break;
    case '7plus': $device2 = 'iPhone 7 Plus'; break;
    case '8': $device2 = 'iPhone 8'; break;
    case '8plus': $device2 = 'iPhone 8 Plus'; break;
    case 'xr': $device2 = 'iPhone XR'; break;
    case 'x': $device2 = 'iPhone X'; break;
    case 'xs': $device2 = 'iPhone XS'; break;
    case 'xsmax': $device2 = 'iPhone XS Max'; break;
    case '11': $device2 = 'iPhone 11'; break;
    case '11pro': $device2 = 'iPhone 11 Pro'; break;
    case '11promax': $device2 = 'iPhone 11 Pro Max'; break;
    case '12mini': $device2 = 'iPhone 12 mini'; break;
    case '12': $device2 = 'iPhone 12'; break;
    case '12pro': $device2 = 'iPhone 12 Pro'; break;
    case '12promax': $device2 = 'iPhone 12 Pro Max'; break;
    case '13mini': $device2 = 'iPhone 13 mini'; break;
    case '13': $device2 = 'iPhone 13'; break;
    case '13pro': $device2 = 'iPhone 13 Pro'; break;
    case '13promax': $device2 = 'iPhone 13 Pro Max'; break;
    case '14': $device2 = 'iPhone 14'; break;
    case '14plus': $device2 = 'iPhone 14 Plus'; break;
    case '14pro': $device2 = 'iPhone 14 Pro'; break;
    case '14promax': $device2 = 'iPhone 14 Pro Max'; break;

    default : header("Location: servicepreise_start.php");
    
}



?>

<div class="container">

<div class="row mt-4">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
   
    <li class="breadcrumb-item"><a href="servicepreise_start.php">Servicepreise</a></li>
    <li class="breadcrumb-item"><a href="servicepreise_device.php?device=iphone">Gerät auswählen</a></li>
    <li class="breadcrumb-item active" aria-current="page">iPhone</li>
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
    <a href="servicepreise_device.php?device=iphone"><button type="button" class="btn btn-secondary">Gerät ändern</button></a>
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
    case 'display' : $teillang = 'Display'; break;
    case 'mittelsystem' : $teillang = 'Mittelsystem'; break;
    case 'gesamt' : $teillang = 'Gesamtaustausch'; break;
    case 'kamera' : $teillang = 'Kamera (Rückseite)'; break;
    case 'truedepth' : $teillang = 'TrueDepth-Kamera'; break;
    case 'hoerer' : $teillang = 'Lautsprecher (Hörer)'; break;
    case 'lautsprecher' : $teillang = 'Lautsprecher (Unten)'; break;
    case 'glas' : $teillang = 'Rückglas'; break;
    case 'ruecksystem' : $teillang = 'Rücksystem'; break;
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
            <input type="hidden" name="teil" id="teil" value="';
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
                    
                  
    
             


    


    
    if ($vp > 400) {
        $repbon = $vp-200;
    } else $repbon = ceil($vp/2);

    if (($_POST['teil'])!== 'gesamt'){
        

    

   
    ?>

    <div class="row mt-4 text-center">
        <div class="col-md-1">
        </div>
        <div class="col-md-5 mt-4 border border-primary border-5 rounded-pill">
            <h2>Verkaufspreis: € <?php echo $vp; ?>,-</h2>
        </div>
        <div class="col-md-5 mt-4 border border-success border-5 rounded-pill">
            <h2>Preis mit Reparaturbonus: € <?php echo $repbon; ?>,-</h2>
        </div>
        <div class="col-md-1">
        </div>
</div>
    
    
<?php

    } else {
        
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
    }

?>



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
      
      
    HTML;

    echo $echo;



                    /* AKKU */
                    
                    $query = "
                    SELECT *
                    FROM akku
                    WHERE modell = '$device'
                ";

                $array = array();
                    $sqlresult = mysqli_query($connect, $query);
                    if (!empty($sqlresult))
                        while ($row = mysqli_fetch_array($sqlresult)) {
                            $array[] = $row;
                        }

                        if(!empty($array)) {
                            echo '<option value="akku">Akku</option>';
                        }

    
    
                    /* DISPLAY */
    
        $query = "
        SELECT *
        FROM display
        WHERE modell = '$device'
    ";
    
    $array = array();
        $sqlresult = mysqli_query($connect, $query);
        if (!empty($sqlresult))
            while ($row = mysqli_fetch_array($sqlresult)) {
                $array[] = $row;
            }
    
            if(!empty($array)) {
                echo '<option value="display">Display</option>';
            }
    
    
     /* MITTELSYSTEM */
    
     $query = "
     SELECT *
     FROM mittelsystem
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="mittelsystem">Mittelsystem</option>';
         }
    
    
          /* RUECKSYSTEM */
    
     $query = "
     SELECT *
     FROM ruecksystem
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="ruecksystem">Rücksystem</option>';
         }
    
    
             /* GESAMT */
    
        $query = "
        SELECT *
        FROM gesamt
        WHERE modell = '$device'
    ";
    
    $array = array();
        $sqlresult = mysqli_query($connect, $query);
        if (!empty($sqlresult))
            while ($row = mysqli_fetch_array($sqlresult)) {
                $array[] = $row;
            }
    
            if(!empty($array)) {
                echo '<option value="gesamt">Gesamtaustausch</option>';
            }
    
     /* KAMERA */
    
     $query = "
     SELECT *
     FROM kamera
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="kamera">Kamera (Rückseite)</option>';
         }
    
    
          /* TRUEDEPTH */
    
     $query = "
     SELECT *
     FROM truedepth
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="truedepth">TrueDepth-Kamera</option>';
         }
    
    
     /* HOERER */
    
     $query = "
     SELECT *
     FROM hoerer
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="hoerer">Lautsprecher (Hörer)</option>';
         }
    
    
     /* LAUTSPRECHER */
    
     $query = "
     SELECT *
     FROM lautsprecher
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="lautsprecher">Lautsprecher (Unten)</option>';
         }
        
    
    
          /* RUECKGLAS */
    
     $query = "
     SELECT *
     FROM glas
     WHERE modell = '$device'
    ";
    
    $array = array();
     $sqlresult = mysqli_query($connect, $query);
     if (!empty($sqlresult))
         while ($row = mysqli_fetch_array($sqlresult)) {
             $array[] = $row;
         }
    
         if(!empty($array)) {
             echo '<option value="glas">Rückglas</option>';
         }
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


