<?php 

$device = $_GET['device'];

switch($device){
    case 'airpod_pro': $device2 = 'AirPod (AirPods Pro 1. Generation)'; break;
    case 'case_pro': $device2 = 'Ladecase (AirPods Pro 1. Generation)'; break;
    case 'case_pro_vl': $device2 = 'Ladecase bei Verlust (AirPods Pro 1. Generation)'; break;
    case 'airpod': $device2 = 'AirPod (AirPods 1. Generation)'; break;
    case 'case': $device2 = 'Ladecase (AirPods 1./2. Generation)'; break;
    case 'case_wireless': $device2 = 'Kabelloses Ladecase (AirPods 1./2. Generation)'; break;
    case 'case_wireless_vl': $device2 = 'Kabelloses Ladecase bei Verlust (AirPods 1./2. Generation)'; break;
    case 'max': $device2 = 'AirPods Max'; break;
    case 'airpod2': $device2 = 'AirPod (AirPods 2. Generation)'; break;
    case 'airpod3': $device2 = 'AirPod (AirPods 3. Generation)'; break;
    case 'case_magsafe_pro': $device2 = 'MagSafe Ladecase (AirPods Pro 1. Generation)'; break;
    case 'case_magsafe_pro_vl': $device2 = 'MagSafe Ladecase bei Verlust (AirPods Pro 1. Generation)'; break;
    case 'case_magsafe': $device2 = 'MagSafe Ladecase (AirPods 3. Generation)'; break;
    case 'case_magsafe_vl': $device2 = 'MagSafe Ladecase bei Verlust (AirPods 3. Generation)'; break;
    case 'airpod_pro2': $device2 = 'AirPod (AirPods Pro 2. Generation)'; break;
    case 'case_pro2': $device2 = 'MagSafe Ladecase (AirPods Pro 2. Generation)'; break;
    case 'case_pro2_vl': $device2 = 'MagSafe Ladecase bei Verlust (AirPods Pro 2. Generation)'; break;
    case 'case_lightning': $device2 = 'Lightning Ladecase (AirPods 3. Generation)'; break;
    case 'case_lightning_vl': $device2 = 'Lightning Ladecase bei Verlust (AirPods 3. Generation)'; break;
    


    default : header("Location: servicepreise_start.php");
    
}



?>

<div class="container">

<div class="row mt-4">

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
 
    <li class="breadcrumb-item"><a href="servicepreise_start.php">Servicepreise</a></li>
    <li class="breadcrumb-item"><a href="servicepreise_device.php?device=airpods">Gerät auswählen</a></li>
    <li class="breadcrumb-item active" aria-current="page">AirPods</li>
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
    <a href="servicepreise_device.php?device=airpods"><button type="button" class="btn btn-secondary">Gerät ändern</button></a>
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
    case 'verlust' : $teillang = 'Ersatz bei Verlust'; break;
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
      <option value="gesamt">Gesamtaustausch</option>
      <option value="verlust">Ersatz bei Verlust</option>
      
      
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