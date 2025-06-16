<?php 

$device = $_GET['device'];

switch($device){
    case 'ultra': $device2 = 'Ultra'; break;
    case '8-alu-gps': $device2 = 'Series 8 Aluminium GPS'; break;
    case '8-alu-cell': $device2 = 'Series 8 Aluminium GPS+Cellular'; break;
    case '8-ss': $device2 = 'Series 8 Edelstahl'; break;
    case 'se2-gps': $device2 = 'SE GPS (2. Generation)'; break;
    case 'se2-cell': $device2 = 'SE GPS+Cellular (2. Generation)'; break;
    case '7-alu-gps': $device2 = 'Series 7 Aluminium GPS'; break;
    case '7-alu-cell': $device2 = 'Series 7 Aluminium GPS+Cellular'; break;
    case '7-ss': $device2 = 'Series 7 Edelstahl'; break;
    case 'se-gps': $device2 = 'SE GPS'; break;
    case 'se-cell': $device2 = 'SE GPS+Cellular'; break;
    case '6-alu-gps': $device2 = 'Series 6 Aluminium GPS'; break;
    case '6-alu-cell': $device2 = 'Series 6 Aluminium GPS+Cellular'; break;
    case '6-ss': $device2 = 'Series 6 Edelstahl'; break;
    case '5-alu-gps': $device2 = 'Series 5 Aluminium GPS'; break;
    case '5-alu-cell': $device2 = 'Series 5 Aluminium GPS+Cellular'; break;
    case '5-ss': $device2 = 'Series 5 Edelstahl'; break;
    case '4-alu-gps': $device2 = 'Series 4 Aluminium GPS'; break;
    case '4-alu-cell': $device2 = 'Series 4 Aluminium GPS+Cellular'; break;
    case '4-ss': $device2 = 'Series 4 Edelstahl'; break;
    case '3-alu-gps': $device2 = 'Series 3 Aluminium GPS'; break;
    case '3-alu-cell': $device2 = 'Series 3 Aluminium GPS+Cellular'; break;
    case '2-alu': $device2 = 'Series 2 Aluminium'; break;
    case '2-ss': $device2 = 'Series 2 Edelstahl'; break;
    case '1-alu': $device2 = 'Series 1 Aluminium'; break;
   

    default : header("Location: servicepreise_start.php");
    
}



?>

<div class="container">

<div class="row mt-4">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
   
    <li class="breadcrumb-item"><a href="servicepreise_start.php">Servicepreise</a></li>
    <li class="breadcrumb-item"><a href="servicepreise_device.php?device=watch">Gerät auswählen</a></li>
    <li class="breadcrumb-item active" aria-current="page">Watch</li>
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
    <a href="servicepreise_device.php?device=watch"><button type="button" class="btn btn-secondary">Gerät ändern</button></a>
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